@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/About_Style.css') }}" rel="stylesheet">
@endsection

@section('script')
	
@endsection

@section('app_content')
<div class="container-fluid">
	<div class="page-header">
		<h2>About HEXA <small>Human Emotion Expression Analyzer</small></h2>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">What is Human Emotion Expression Analyzer (HEXA)?</h3>
		</div>
		<div class="panel-body">
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
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">How to use it?</h3>
		</div>
		<div class="panel-body">
			<h4><span class="label label-default">1. Go to menu "Analyze Post"</span></h4>
			<h4><span class="label label-default">2. Enter page ID, Result (number of result you would like to get), and range of the date</span></h4>
			<p>You can get the page ID from the URL of the Facebook Page. For example: </p>
			<p>The URL of the Google Facebook Page is <a href="https://www.facebook.com/Google?fref=ts" target="_blank">https://www.facebook.com/Google?fref=ts</a>, thus its page ID will be Google.</p>
			<h4><span class="label label-default">3. Click on search, the result will be display at below.</span></h4>
			<h4><span class="label label-default">4. Click on post ID.</span></h4>
			<h4><span class="label label-default">5. Doughnut Chart and all of the comments will be display.</span></h4>
			<h4><span class="label label-default">6. To update the comments, click on "Update Comments" button.</span></h4>
		</div>
	</div>
</div>
@endsection
