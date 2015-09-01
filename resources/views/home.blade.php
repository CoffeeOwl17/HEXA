@extends('dashboard')

@section('additional_include')
<link href="{{ URL::asset('css/Home_Style.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/Chart.js-master/Chart.js') }}"></script>
@endsection

@section('script')
<script>
	$(function(){
		var postID 			= [];
		var postSentiment	= [];
		var data 			= <?php echo json_encode($statistic) ?>;

		$.each(data, function(index, value){
			postID.push(value['id']);
			postSentiment.push(value['sentiment']);
			// alert(postSentiment[1][0]['joy']);
		});

		drawGraph();

		function drawGraph(){
			if(postID.length > 0)
			{
				var color = ['#0066FF', '#6666FF', '#009933', '#FF3300', '#FF9900'];
				var mydataset = [];
				var count = 0;
				$.each(postID, function(index, value){
					mydataset[count] = 
					{
						label: postID[count],
			            fillColor: "rgba(220,220,220,0.2)",
			            strokeColor: color[count],
			            pointColor: color[count],
			            pointStrokeColor: "#fff",
			            pointHighlightFill: "#fff",
			            pointHighlightStroke: "rgba(220,220,220,1)",
			            data: [postSentiment[count][0]['joy'], postSentiment[count][0]['sadness'], postSentiment[count][0]['trust'], postSentiment[count][0]['disgust'], postSentiment[count][0]['fear'], postSentiment[count][0]['anger'], postSentiment[count][0]['surprise'], postSentiment[count][0]['anticipation']]
			        }; 
			        ++count;
				});
				var data = {
					labels: ["Joy", "Sadness", "Trust", "Disgust", "Fear", "Anger", "Surprise", "Anticipation"],
					datasets: mydataset
				}

				var option = {
					legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

				}
				$( ".statistic-chart" ).html( "<div class='row'><div class='col-md-6' align='right'><canvas id='myChart' width='400' height='400'></canvas></div><div class='col-md-6' align='left'><div class='chart-legend'></div></div></div>" );				
				var ctx = $("#myChart").get(0).getContext("2d");
				var myLineChart = new Chart(ctx).Line(data, option);
				$( ".chart-legend" ).html(myLineChart.generateLegend());
			}
			else{
				$( ".statistic-chart" ).html( "No statistic result available" );
			}
		}
	});

</script>
@endsection

@section('app_content')
<div class="container-fluid">
	<h2>Dashboard <small>Statistics Overview</small></h2>
	<hr/>
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
