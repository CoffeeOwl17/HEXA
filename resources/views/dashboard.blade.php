@extends('app')

@section('include')
<link href="{{ URL::asset('css/Dashboard_Style.css') }}" rel="stylesheet">
@yield('additional_include')
@endsection

@section('content')
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
          <a href="../home/home.php" class="navbar-brand">HEXA</a>
          <p class="navbar-text">Human Emotional Expression Analyzer</p>

          </div>
          <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                <li><p class="navbar-text">Welcome, {!! $user_name !!}</li>
                <li class="active"><a href="/logout">Logout</a></li>
              </ul>

          </div>
      </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 sidebar">
        <div class="profile container-fluid">
          <div class="pic">
            <a href="#"><img class="img-circle" src="{!! $user_profilePic !!}"></a>
          </div>
          <div class="description">
            <div class="Name">{!! $user_name !!}</div>
            <div class="Email">{!! $user_email !!}</div>
          </div>
        </div>
        <ul class="nav nav-sidebar">
          <li class="dashboard-opt"><a href="/home"><i class="fa fa-home fa-fw"></i> Dashboard</a></li>
          <li class="profile-opt"><a href="/profle"><i class="fa fa-user fa-fw"></i> Profile</a></li>
          <li class="post-opt"><a href="/post"><i class="fa fa-flask fa-fw"></i> Analyze Post</a></li>
          <li class="history-opt"><a href="/history"><i class="fa fa-history fa-fw"></i> History</a></li>
          <li class="about-opt"><a href="/about"><i class="fa fa-question-circle fa-fw"></i> What is HEXA</a></li>
        </ul>
      </div>
      <div class="col-md-9 main-content">
      	@yield('app_content')
      </div>
    </div>
  </div>
@endsection
