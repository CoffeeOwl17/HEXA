@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Profile_Style.css') }}" rel="stylesheet">
@endsection

@section('script')
	
@endsection

@section('app_content')
<div class="container-fluid">
	<div>
		<h3>User Profile <small>{!! $user_name !!}</small></h3>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-5">
			<img src="{!! $bigPic !!}">
		</div>
		<div class="col-md-7">
			<label class="col-sm-3" align="right">ID:</label> {!! $user_id !!}
			<hr/>
			<label class="col-sm-3" align="right">Name:</label> {!! $user_name !!}
			<hr/>
			<label class="col-sm-3" align="right">Email:</label> {!! $user_email !!}
			<hr/>
		</div>
	</div>
	<hr/>
</div>
@endsection
