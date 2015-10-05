<script>
$(function(){
	var postID 			= [];
	var pageID 			= [];
	var postSentiment	= [];
	var data 			= <?php echo json_encode($statistic) ?>;

	$.each(data, function(index, value){
		postID.push(value['id']);
		pageID.push(value['page_id']);
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
					label: pageID[count]+"/"+postID[count],
		            fillColor: "rgba(220,220,220,0.2)",
		            strokeColor: color[count],
		            pointColor: color[count],
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(220,220,220,1)",
		            data: [postSentiment[count]['joy'], postSentiment[count]['sadness'], postSentiment[count]['trust'], postSentiment[count]['disgust'], postSentiment[count]['fear'], postSentiment[count]['anger'], postSentiment[count]['surprise'], postSentiment[count]['anticipation']]
		        }; 
		        ++count;
			});
			var data = {
				labels: ["Joy", "Sadness", "Trust", "Disgust", "Fear", "Anger", "Surprise", "Anticipation"],
				datasets: mydataset
			}

			var option = {
				scaleShowVerticalLines: true,
				legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><a href=\"post/<%=datasets[i].label%>\"><%=datasets[i].label%></a><%}%></li><%}%></ul>"

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