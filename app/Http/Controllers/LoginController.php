<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Config;

session_start();

class LoginController extends Controller
{
    public function index(){
      FacebookSession::setDefaultApplication(Config::get('facebook.appid'), Config::get('facebook.secret'));
    	$helper = new FacebookRedirectLoginHelper('http://localhost:8000/home');
    	// $helper = new FacebookRedirectLoginHelper('http://hexanlyzer.azurewebsites.net/home');
      return redirect($helper->getLoginUrl());
    }
}
