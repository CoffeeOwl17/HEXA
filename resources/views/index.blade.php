@extends('app')

@section('include')
<link href="{{ URL::asset('css/Index_Style.css') }}" rel="stylesheet">
@endsection

@section('script')
  @include('_indexJS')
@endsection

@section('content')
  <!-- Navigation bar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">HEXA</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#Login">Login</a></li>
              <li><a href="#About">About</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>

  <section id="Home" data-type="background" data-speed="10" class="pages">
    <div class="container">
          <h1>Welcome to HEXA</h1>
          <p><strong>H</strong> uman  <strong>E</strong> motional  e <strong>X</strong> pression <strong>A</strong> nalyzer</p>
        </div>
    </section>

  <section id="Login" data-type="background" data-speed="10" class="pages">
        <div class="container">
          <p>To enter HEXA, please login as your Facebook account.</p>

          <a href='/login' class='btn btn-outline-inverse btn-lg'>
            <i class='fa fa-facebook-official fa-2x'></i>
            <font> Login as Facebook</font>
          </a>

        </div>
  </section>
  <section id="About" data-type="background" data-speed="10" class="pages">
        <div class="container">
          <h1>About Us</h1>
          <hr />
          <p>
          Human Emotional Expression Analyzer (HEXA) is a web-based system that used to identify and classify a list of comments on a post,
          in order to determine whether the commenter's attitude towards to the post is positive, negative or neutral.
          </p>
          <p>
          HEXA will furthur classify the comments into detail emotional category. For instance, the emotion involved in positive emotion
          category such as happy and surprise while in negative emotion category there will be angry and sad.
          </p>
          <p>
          To use HEXA, users are required to login as their own Facebook account. HEXA will not required users' confidential information such as
          personal information.
          </p>
        </div>
  </section>
@endsection
