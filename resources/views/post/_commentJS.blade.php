	<script>
	$(function(){
		var commentDate		= [];
		var sentiments		= [];
		var comment_sent	= [];
		var comment_data 	= <?php echo json_encode($comments) ?>;
		var sentimnt_data 	= <?php echo json_encode($sentiment) ?>;

		$.each(comment_data, function(index, value){
			commentDate.push(value['comment_datetime']);
		});

		$.each(sentimnt_data, function(index, value){
			sentiments.push(value['result']);
		});

		for(i=0; i<commentDate.length; ++i){
			comment_sent.push({date:commentDate[i], sentiment:sentiments[i]});
		}

		var table = $('#comment_table').DataTable({
			"scrollY"		: "500px",
			"scrollX"		: true,
	        "scrollCollapse": true,
	        "paging"		: false,
	        "aoColumns": [
	        	{"bSearchable": false}, 
	        	{"bSearchable": false}, 
	        	{"bSearchable": false},
	        	{"bSearchable": false},
	        	{"bSearchable": true}
	        ]
		});

		drawDoughnutGraph({!! $num_sentiment['num_neutral'] !!},
              {!! $num_sentiment['num_joy'] !!},
              {!! $num_sentiment['num_sadness'] !!},
              {!! $num_sentiment['num_trust'] !!},
              {!! $num_sentiment['num_disgust'] !!},
              {!! $num_sentiment['num_fear'] !!},
              {!! $num_sentiment['num_anger'] !!},
              {!! $num_sentiment['num_surprise'] !!},
              {!! $num_sentiment['num_anticipation'] !!});

		function drawDoughnutGraph(n, j, s, t, d, f, a, su, an){
			var neutral 		= n;
			var joy 			= j;
			var sadness 		= s;
			var trust 			= t;
			var disgust 		= d;
			var fear 			= f;
			var anger 			= a;
			var surprise 		= su;
			var anticipation 	= an;

			var data = [
			    {
			        value: neutral,
			        color:"#4E4E4E",
			        highlight: "#606060",
			        label: "Neutral"
			    },
			    {
			        value: joy,
			        color: "#E62E00",
			        highlight: "#E84319",
			        label: "Joy"
			    },
			    {
			        value: sadness,
			        color: "#FF6600",
			        highlight: "#FF8533",
			        label: "Sadness"
			    },
			    {
			        value: trust,
			        color: "#FFCC00",
			        highlight: "#FFD633",
			        label: "Trust"
			    },
			    {
			        value: disgust,
			        color: "#19A319",
			        highlight: "#47B547",
			        label: "Disgust"
			    },
			    {
			        value: fear,
			        color: "#0099FF",
			        highlight: "#33ADFF",
			        label: "Fear"
			    },
			    {
			        value: anger,
			        color: "#3333CC",
			        highlight: "#5C5CD6",
			        label: "Anger"
			    },
			    {
			        value: surprise,
			        color: "#5C5CE6",
			        highlight: "#7D7DEB",
			        label: "Surprise"
			    },
			    {
			        value: anticipation,
			        color: "#9933FF",
			        highlight: "#AD5CFF",
			        label: "Anticipation"
			    }
			]

	    	var option = {
	    		legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span class=\"label-color\" style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><span class=\"label-name\"><%=segments[i].label%></span><%=segments[i].value%><%}%></li><%}%></ul>"
	    	};

			$( "div" ).remove( "#loading" );
			$( ".statistical-result" ).html( "<div class='row'><div class='col-md-6' align='right'><canvas id='myChart' width='300' height='300'></canvas></div><div class='col-md-6 chart-legend' align='left'></div></div>" );

			var ctx = $("#myChart").get(0).getContext("2d");
			var myDoughnutChart = new Chart(ctx).Doughnut(data, option);
	  		$( ".chart-legend" ).html(myDoughnutChart.generateLegend());
		}
	});

</script>