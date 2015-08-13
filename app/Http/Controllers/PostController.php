<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRequest;
use Config;
use App;

session_start();

class PostController extends Controller
{
  private function getSession(){
    $session = "";
    FacebookSession::setDefaultApplication(Config::get('facebook.appid'), Config::get('facebook.secret'));
    if( isset($_SESSION['token']))
    {
        // We have a token, is it valid?
        $session = new FacebookSession($_SESSION['token']);
        try
        {
            $session->Validate(Config::get('facebook.appid'), Config::get('facebook.secret'));
        }
        catch( FacebookAuthorizationException $ex)
        {
            // Session is not valid any more, get a new one.
            $session ='';
        }
    }
    return $session;
  }

  private function getUserInfo(){
    $userID     = "";
    $userEmail 	= "";
    $userName 	= "";
    $userPicUrl = "";

    $session = $this->getSession();

    if(isset($session))
    {
        $userID     = $_SESSION['usrID'];
        $userName   = $_SESSION['usrName'];
        $userEmail  = $_SESSION['usrEmail'];
        $userPicUrl = $_SESSION['usrPicUrl'];
    }
    $data = array(
      "user_id"         => $userID,
      "user_name"       => $userName,
      "user_email"      => $userEmail,
      "user_profilePic" => $userPicUrl
    );
    return $data;
  }

  public function index(){
    $data = $this->getUserInfo();
    return view('post.posts', $data);
  }

  public function searchPage(){
    $pageID = $_POST['PageID'];

    $qty	= $_POST['Qty'];

    $since	= $_POST['Since'];
    $part	= explode('/', $since);
    $since 	= $part[2].'-'.$part[0].'-'.$part[1];

    $until	= $_POST['Until'];
    $part	= explode('/', $until);
    $until 	= $part[2].'-'.$part[0].'-'.$part[1];

    $session = $this->getSession();

    if(isset($session))
    {
    	$arrayPost	 	= array();
    	$count			= 0;
    	$checkpoint		= '';
    	$done			= False;

    	$_SESSION['token'] = $session->getToken();
    	$request = new FacebookRequest(
    		$session,
    		'GET',
    		'/'.$pageID.'/posts?limit='.$qty.'&since='.$since.'&until='.$until
    	);
    	try{
    		do {
    			$response = $request->execute();
    			$graphObject = $response->getGraphObject();
    			$result = $graphObject->asArray();

    			$posts = $result['data'];

    			for($i=0; $i<sizeof($posts); ++$i){

    				if($count == 0){
    					$checkpoint = $posts[$i]->id;
    				}
    				if($count == $qty || ($posts[$i]->id == $checkpoint && $count != 0)){
    					$done = True;
    					break;
    				}
    				else{
    					$arrayPost[] = array(
    						'message'		=> $posts[$i]->message,
    						'created_time' 	=> $posts[$i]->created_time,
    						'id'			=> $posts[$i]->id
    					);
    				}
    				$count ++;
    			}
    			if($done){
    				break;
    			}
    		} while ($request = $response->getRequestForNextPage());
    		echo json_encode($arrayPost);
    	}
    	catch (Exception $e){
    		echo "Error: Page not found, make sure the page id entered is valid.";
    	}
    }
  }

  public function comment($page_id, $post_id){

    $data           = array();
    $user_data      = $this->getUserInfo();
    $post_data      = array();
    $comment_data   = array();
    $sentiment_data = array();
    $num_sent_data  = array();

    $user_model = App\user::where('user_id', $user_data['user_id'])->first();
    $page_model = App\page::where('page_id', $page_id)->first();

    if(is_null($page_model)){ // if page data is not found in database
      $page_model = new App\page;

  		$graphObject = $this->getPage($page_id);
  		$pageName 	 = $graphObject->getProperty('name');

      $page_model->page_id = $page_id;
      $page_model->page_name = $pageName;
      $page_model->save();

      $post_model = new App\post;

  		$graphObject = $this->getPost($post_id);
  		$postMsg 	= $graphObject->getProperty('message');
  		$postTime 	= $graphObject->getProperty('created_time');

      $post_model->post_id        = $post_id;
      $post_model->content        = $postMsg;
      $post_model->created_time   = $postTime;

      $page_model = App\page::where('page_id', $page_id)->first();
      $page_model->posts()->save($post_model);

      $comment_data = $this->getComment($post_id);
      $sentiment_data = $this->sentiment($comment_data);
      $post_model = App\post::where('post_id', $post_id)->first();
      $count = 0;
      foreach($comment_data as $comment){
        $comment_model = new App\comment;
        $comment_model->comment = $comment['comment'];
        $comment_model->commenter = $comment['commenter'];
        $comment_model->commenter_id = $comment['commenter_id'];
        $post_model->comments()->save($comment_model);

        $sentiment_model                = new App\commentSentiment;
        $sentiment_model->joy           = $sentiment_data[$count]['joy'];
        $sentiment_model->sadness       = $sentiment_data[$count]['sadness'];
        $sentiment_model->trust         = $sentiment_data[$count]['trust'];
        $sentiment_model->disgust       = $sentiment_data[$count]['disgust'];
        $sentiment_model->fear          = $sentiment_data[$count]['fear'];
        $sentiment_model->anger         = $sentiment_data[$count]['anger'];
        $sentiment_model->surprise      = $sentiment_data[$count]['surprise'];
        $sentiment_model->anticipation  = $sentiment_data[$count]['anticipation'];
        $sentiment_model->result        = $sentiment_data[$count]['result'];
        $comment_model->sentiment()->save($sentiment_model);

        $count++;
      }
    }
    else{ //if page data is already been saved in database
      $post_model = $page_model->posts()->where('post_id', $post_id)->first();
      if(is_null($post_model)){
        $post_model = new App\post;

        $graphObject = $this->getPost($post_id);
    		$postMsg 	= $graphObject->getProperty('message');
    		$postTime 	= $graphObject->getProperty('created_time');

        $post_model->post_id        = $post_id;
        $post_model->content        = $postMsg;
        $post_model->created_time   = $postTime;
        $page_model->posts()->save($post_model);

        $post_model = $page_model->posts()->where('post_id', $post_id)->first();
        $comment_data = $this->getComment($post_id);
        $sentiment_data = $this->sentiment($comment_data);
        $count = 0;
        foreach($comment_data as $comment){
          $comment_model = new App\comment;
          $comment_model->comment = $comment['comment'];
          $comment_model->commenter = $comment['commenter'];
          $comment_model->commenter_id = $comment['commenter_id'];
          $post_model->comments()->save($comment_model);

          $sentiment_model                = new App\commentSentiment;
          $sentiment_model->joy           = $sentiment_data[$count]['joy'];
          $sentiment_model->sadness       = $sentiment_data[$count]['sadness'];
          $sentiment_model->trust         = $sentiment_data[$count]['trust'];
          $sentiment_model->disgust       = $sentiment_data[$count]['disgust'];
          $sentiment_model->fear          = $sentiment_data[$count]['fear'];
          $sentiment_model->anger         = $sentiment_data[$count]['anger'];
          $sentiment_model->surprise      = $sentiment_data[$count]['surprise'];
          $sentiment_model->anticipation  = $sentiment_data[$count]['anticipation'];
          $sentiment_model->result        = $sentiment_data[$count]['result'];
          $comment_model->sentiment()->save($sentiment_model);

          $count++;
        }
      }
      else{
        $comment_data = $post_model->comments->toArray();
        foreach($comment_data as $comment){
          $sentiment_data[] = App\comment::where('comment_id', $comment['comment_id'])->first()->sentiment->toArray();
        }
      }
    }

    $post_data['post_id'] = $post_model->post_id;
    $post_data['post_content'] = $post_model->content;
    $post_data['post_time'] = $post_model->created_time;

    $pivot_model = App\post_user::where(['user_id' => $user_data['user_id'], 'post_id' => $post_id])->first();
    if(is_null($pivot_model)){
      $user_model = App\user::where('user_id', $user_data['user_id'])->first();
      $user_model->posts()->attach($post_id);
    }

    $data = $user_data;


    $data['post']           = $post_data;
    $data['comments']       = $comment_data;
    $data['sentiment']      = $sentiment_data;
    $num_sent_data = $this->count_sentiment($sentiment_data);
    $data['num_sentiment']  = $num_sent_data;
    return view('post.comments', $data);
  }

  private function getPage($obj){
    $session = $this->getSession();
    $request = new FacebookRequest(
      $session,
      'GET',
      '/'.$obj
    );
    $response = $request->execute();
    return ($response->getGraphObject());
  }

  private function getPost($obj){
    $session = $this->getSession();
    $request = new FacebookRequest(
      $session,
      'GET',
      '/'.$obj
    );
    $response = $request->execute();
    return ($response->getGraphObject());
  }

  private function getComment($obj){
    $comment_data = array();
    $session = $this->getSession();
    $request = new FacebookRequest(
      $session,
      'GET',
      '/'.$obj.'/comments'

    );
    do {
      $response = $request->execute();
      $graphObject = $response->getGraphObject();
      $result = $graphObject->asArray();

      $comments = $result['data'];

      for($i=0; $i<sizeof($comments); ++$i){

        $removeChar = array("'", '"', '(', ')', ';', '>', '<');

        $emoticonSymbol 	= array(':-)', ':)', ':]', '=)', ':>', ':-(', ':(', ':[', '=(', ':-P', ':-p',
          ':P', ':p', '=P', ';-)', ';)', ':-D', ':D', '=P', ':-O', ':O',
          ':-o', ':o', ':\'(', 'T_T', '8-)', '8)', 'B-)', 'B)', '8-|', '8|', 'B-|',
          'B|', '>:(', '>:-(', '>:O', '>:-O', '>:o', '>:-o', ':/', ':-/', ':\\',
          ':-\\', 'o.O', 'O.o', ':-*', ':*', '3:)', '3:-)', 'O:)', 'O:-)', '<3',
          '^_^', '-_-', ':V', ':3', ':|]', '(^^^)', '<(")', '</3');

        $emoticonMeaning 	= array("smile", "smile", "smile", "smile", "smile", "sad", "sad", "sad", "sad", "naughty", "naughty",
          "naughty", "naughty", "naughty", "wink", "wink", "big smile", "big smile", "big smile", "surprised", "surprised",
          "surprised", "surprised", "cry", "cry", "geek", "geek", "geek", "geek", "cool", "cool", "cool",
          "cool", "mad", "mad", "very angry", "very angry", "very angry", "very angry", "thinking", "thinking", "thinking",
          "thinking", "confused", "confused", "kiss", "kiss", "devil", "devil", "angel", "angel", "love",
          "very happy", "moody one", "pacman", "curly lips", "robot", "shark", "penguin", "broken love");

        $comment_obj = $comments[$i]->message;
        if($comment_obj != ""){
          $comment_obj = str_replace($emoticonSymbol, $emoticonMeaning, $comment_obj);
          $comment_obj = str_replace($removeChar, "", $comment_obj);
          $comment_obj = str_replace($emoticonSymbol, $emoticonMeaning, $comment_obj);
          $comment_obj = preg_replace('/!!+/', '!', $comment_obj);
          $comment_obj = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\,\(\)%&-]/s', '', $comment_obj);

          if($comment_obj != "" && trim($comment_obj, ' ') != ''){
            $comment_data[] = array(
              "comment"       => $comment_obj,
              "commenter"     => $comments[$i]->from->name,
              "commenter_id"  => $comments[$i]->from->id
            );
          }
        }
      }
    } while ($request = $response->getRequestForNextPage());
    return $comment_data;
  }
  private function sentiment($comments){
    $script_location = public_path().'/SentimentAnalysis/SA/SA.py';
    $comments_sentiment = array();

    foreach($comments as $comment){
      $comment_content = $comment['comment'];
      exec("python $script_location $comment_content", $output, $ret);

      $emotion = 'neutral';
      $emotion_array = array();
      $emotion_array["joy"] = $output[0];
      $emotion_array["sadness"] = $output[1];
      $emotion_array["trust"] = $output[2];
      $emotion_array["disgust"] = $output[3];
      $emotion_array["fear"] = $output[4];
      $emotion_array["anger"] = $output[5];
      $emotion_array["surprise"] = $output[6];
      $emotion_array["anticipation"] = $output[7];

      if(max($emotion_array) != 0){
        $emotion = array_search(max($emotion_array),$emotion_array);
      }

      $emotion_array['result'] = $emotion;
      $comments_sentiment[] = $emotion_array;
      unset($output);
    }
    return $comments_sentiment;
  }

  private function count_sentiment($sentiments){
    $neutral      = 0;
    $joy          = 0;
    $sadness      = 0;
    $trust 			  = 0;
    $disgust      = 0;
    $fear         = 0;
    $anger 			  = 0;
    $surprise 		= 0;
    $anticipation = 0;

    foreach($sentiments as $sentiment){
      if(strcmp($sentiment['result'], "neutral")==0){
        $neutral++;
      }
      else if(strcmp($sentiment['result'], "joy")==0){
        $joy++;
      }
      else if(strcmp($sentiment['result'], "sadness")==0){
        $sadness++;
      }
      else if(strcmp($sentiment['result'], "trust")==0){
        $trust++;
      }
      else if(strcmp($sentiment['result'], "disgust")==0){
        $disgust++;
      }
      else if(strcmp($sentiment['result'], "fear")==0){
        $fear++;
      }
      else if(strcmp($sentiment['result'], "anger")==0){
        $anger++;
      }
      else if(strcmp($sentiment['result'], "surprise")==0){
        $surprise++;
      }
      else{
        $anticipation++;
      }
    }
    $count_sentiment = array(
      'num_neutral'         =>  $neutral,
      'num_joy'             =>  $joy,
      'num_sadness'         =>  $sadness,
      'num_trust'           =>  $trust,
      'num_disgust'         =>  $disgust,
      'num_fear'            =>  $fear,
      'num_anger'           =>  $anger,
      'num_surprise'        =>  $surprise,
      'num_anticipation'    =>  $anticipation
    );
    return $count_sentiment;
  }
}
