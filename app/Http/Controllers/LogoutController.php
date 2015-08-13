<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

session_start();

class LogoutController extends Controller
{
    public function index(){
      session_destroy();
      return redirect('/');
    }
}
