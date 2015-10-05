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

class ProfileController extends Controller
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
    	$user_data = $this->getUserInfo();
    	$user_model = App\user::where('user_id', $user_data['user_id'])->first();
    	if($user_model->user_profilePic2==''){
    		$session = $this->getSession();
    		$request = new FacebookRequest(
				$session,
				'GET',
				'/me?fields=picture.height(200)'
	        );
	        $response = $request->execute();
        	$graphObject = $response->getGraphObject();
        	$userPicObj = $graphObject->getProperty('picture')->asArray();
        	$user_model->user_profilePic2   = $userPicObj['url'];
        	$user_model->save();
    	}
		$user_data['bigPic'] = $user_model->user_profilePic2;
    	return view('profile', $user_data);
    }
}
