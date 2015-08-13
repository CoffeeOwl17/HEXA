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

session_start();

class HomeController extends Controller
{
    public function index(){
      FacebookSession::setDefaultApplication(Config::get('facebook.appid'), Config::get('facebook.secret'));
    	$helper = new FacebookRedirectLoginHelper('http://localhost:8000/home');

      $userID     = "";
      $userEmail 	= "";
      $userName 	= "";
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

      	$userID 	= $graphObject->getProperty('id');
      	$userName 	= $graphObject->getProperty('name');
      	$userEmail 	= $graphObject->getProperty('email');
      	$userPicObj	= $graphObject->getProperty('picture')->asArray();
      	$userPicUrl = $userPicObj['url'];

      	$_SESSION['usrID'] 		= $userID;
      	$_SESSION['usrName']	= $userName;
      	$_SESSION['usrEmail']	= $userEmail;
      	$_SESSION['usrPicUrl']	= $userPicUrl;

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
      return view('home', $data);
    }
}
