<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookAuthorizationException;
use Config;
use App;

session_start();


class HistoryController extends Controller
{
    public function index(){
    	$user_data = $this->getUserInfo();
    	$post_data = array();
    	$user_model = App\user::where('user_id', $_SESSION['usrID'])->first();
    	$post_model = $user_model->posts;
    	$count = 0;
    	foreach($post_model as $post){
    		$count++;
    		$post_info = array('post_id' => $post->post_id, 'content' =>$post->content, 'created_time' => $post->created_time, 'page_id' => $post->page_id, 'page_name' => $post->page->page_name);
    		$post_data[] = $post_info;
    	}
    	$data = $user_data;
    	$data['post'] = $post_data;
    	$data['total'] = $count;
    	return view('history.history', $data);
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
}
