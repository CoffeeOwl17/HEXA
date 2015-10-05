@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Home_Style.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/Chart.js-master/Chart.js') }}"></script>
@endsection

@section('script')
	@include('_homeJS')
@endsection

@section('app_content')
<div class="container-fluid">
	<div class="page-header">
		<h2>Dashboard <small>Statistics Overview</small></h2>
	</div>
	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-post">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-facebook-official fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{!! $total_post !!}</div>
							<div>Total Facebook Posts Registered!</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-comment">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-comments fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{!! $total_comment !!}</div>
							<div>Total Comments Collected!</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-line-chart fa-1x"></i> Recent Post Emotional Analysis
				</div>
				<div class="panel-body statistic-chart">
					<!-- <div class="row">
						<div class="col-md-6" align="right">
							<canvas id="myChart" width="400" height="400"></canvas>
						</div>
						<div class="col-md-6" align="left">
							<div class="chart-legend"></div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
