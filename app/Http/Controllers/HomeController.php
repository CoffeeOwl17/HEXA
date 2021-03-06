<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRedirectLoginHelper;
use Config;
use App;
use DB;

session_start();

class HomeController extends Controller
{
  private function getUserInfo(){
      FacebookSession::setDefaultApplication(Config::get('facebook.appid'), Config::get('facebook.secret'));
      $helper = new FacebookRedirectLoginHelper('http://localhost:8000/home');
      $userID     = "";
      $userEmail  = "";
      $userName   = "";
      $userPicUrl = "";
      try {
        $session = $helper->getSessionFromRedirect();
      } catch (FacebookRequestException $ex) {
          // When Facebook returns an error
      } catch (\Exception $ex) {
          // When validation fails or other local issues
      }
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
      if(isset($session))
      {
        $_SESSION['token'] = $session->getToken();
        $request = new FacebookRequest(
          $session,
          'GET',
          '/me?fields=id,name,email,picture'
        );
        $response = $request->execute();
        $graphObject = $response->getGraphObject();
        $userID   = $graphObject->getProperty('id');
        $userName   = $graphObject->getProperty('name');
        $userEmail  = $graphObject->getProperty('email');
        $userPicObj = $graphObject->getProperty('picture')->asArray();
        $userPicUrl = $userPicObj['url'];
        $_SESSION['usrID']    = $userID;
        $_SESSION['usrName']  = $userName;
        $_SESSION['usrEmail'] = $userEmail;
        $_SESSION['usrPicUrl']  = $userPicUrl;
        $user_model = App\user::where('user_id', $userID)->first();
        if(is_null($user_model)){
          $user_model                   = new App\user;
          $user_model->user_id          = $userID;
          $user_model->user_name        = $userName;
          $user_model->user_email       = $userEmail;
          $user_model->user_profilePic  = $userPicUrl;
          $user_model->save();
        }
        else{
          $user_model->user_name        = $userName;
          $user_model->user_email       = $userEmail;
          $user_model->user_profilePic  = $userPicUrl;
          $user_model->save();
        }
      }
      $data = array(
        "user_id"         => $userID,
        "user_name"       => $userName,
        "user_email"      => $userEmail,
        "user_profilePic" => $userPicUrl
      );
    $data = array(
      "user_id"         => $userID,
      "user_name"       => $userName,
      "user_email"      => $userEmail,
      "user_profilePic" => $userPicUrl
    );
    return $data;
  }

  private function getTotalPost($user_id){
    $total_post = DB::table('post_user')
                  ->where('user_id', $user_id)
                  ->count('post_id');
    return $total_post;
  }

  private function getTotalComment($user_id){
    $user           = App\user::where('user_id', $user_id)->first();
    $posts          = $user->posts;
    $total_comment  = 0;

    foreach($posts as $post){
      $total_comment += DB::table('comment')
                        ->where('post_id', $post->post_id)
                        ->count('comment_id');
    } 
    return $total_comment;
  }

  private function getStatistic($user_id){
    $post_data = array();
    $posts   = DB::table('post_user')
              ->where('user_id', $user_id)
              ->take(5)
              ->orderBy('updated_at', 'decs')
              ->get();
    $count = 0;
    foreach($posts as $post){
      $post_model = App\post::where('post_id', $post->post_id)->first();
      $post_data[$count]['page_id'] = $post_model->page_id;
      $post_data[$count]['id'] = $post->post_id;
      $num_joy = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'joy')
                  ->count('commentSentiment.id');

      $num_sadness = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'sadness')
                  ->count('commentSentiment.id');

      $num_trust = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'trust')
                  ->count('commentSentiment.id');

      $num_disgust = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'disgust')
                  ->count('commentSentiment.id');

      $num_fear = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'fear')
                  ->count('commentSentiment.id');

      $num_anger = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'anger')
                  ->count('commentSentiment.id');

      $num_surprise = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'surprise')
                  ->count('commentSentiment.id');

      $num_anticipation = DB::table('commentSentiment')
                  ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                  ->where('comment.post_id', $post->post_id)
                  ->where('result', 'anticipation')
                  ->count('commentSentiment.id');

      $post_data[$count]['sentiment'] = array(
                                          'joy'           => $num_joy, 
                                          'sadness'       => $num_sadness, 
                                          'trust'         => $num_trust, 
                                          'disgust'       => $num_disgust, 
                                          'fear'          => $num_fear, 
                                          'anger'         => $num_anger, 
                                          'surprise'      => $num_surprise, 
                                          'anticipation'  => $num_anticipation
                                        );
                                        // DB::table('commentSentiment')
                                        // ->join('comment', 'comment.comment_id', '=', 'commentSentiment.comment_id')
                                        // ->where('comment.post_id', $post->post_id)
                                        // ->get(
                                        //   array(
                                        //     DB::raw('COUNT(id) AS joy'),
                                        //     DB::raw('COUNT(id) AS sadness'),
                                        //     DB::raw('COUNT(id) AS trust'),
                                        //     DB::raw('COUNT(id) AS disgust'),
                                        //     DB::raw('COUNT(id) AS fear'),
                                        //     DB::raw('COUNT(id) AS anger'),
                                        //     DB::raw('COUNT(id) AS surprise'),
                                        //     DB::raw('COUNT(id) AS anticipation'),
                                        //     )
                                        //   );
      $count++;
    }
    return $post_data;
  }

  public function index(){
    $user_data            = $this->getUserInfo();
    $total_post           = $this->getTotalPost($user_data['user_id']);
    $total_comment        = $this->getTotalComment($user_data['user_id']);
    $statistic_data       = $this->getStatistic($user_data['user_id']);
    $data                 = $user_data;
    $data['total_post']   = $total_post;
    $data['total_comment']= $total_comment;
    $data['statistic']    = $statistic_data;
    return view('home', $data);
  }
}
