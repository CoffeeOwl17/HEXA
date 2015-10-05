<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>HEXA</title>
		<meta name="_token" content="{{ csrf_token() }}"/>
		<script type="text/javascript" src="{{ URL::asset('js/jquery-2.1.4.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('css/Bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js') }}"></script>
		<link href="{{ URL::asset('css/Bootstrap/bootstrap-3.3.5-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    	<link href="{{ URL::asset('css/font-awesome-4.3.0/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="{{ URL::asset('Images/favicon.ico') }}">
    @yield('include')

	</head>

	<body>
		@yield('script')
		@yield('content')
	</body>
</html>
