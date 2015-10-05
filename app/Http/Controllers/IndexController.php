<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

session_start();

class IndexController extends Controller
{
    public function index(){
    	if(isset($_SESSION['token'])){
    		return redirect('/home');
    	}
    	return view('index');
    }
}
