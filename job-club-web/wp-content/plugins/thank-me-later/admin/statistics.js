jQuery(document).ready(function() {
	var Statistics = {},
		$ = jQuery,
		$sends_graph = $("#bbpp-thankmelater-sends-graph"),
		$opens_graph = $("#bbpp-thankmelater-opens-graph");
	
	Statistics.plotData = function () {
		var max_sends = 1,
			max_opens = 1,
			i,
			opts,
			sends_data = bbpp_thankmelater_sends_data,
			opens_data = bbpp_thankmelater_opens_data;
			
		for (i = 0; i < sends_data.data.length; i++) {
			max_sends = Math.max(max_sends, sends_data.data[i][1]);
		}
		
		for (i = 0; i < opens_data.data.length; i++) {
			max_opens = Math.max(max_opens, opens_data.data[i][1]);
		}
		
		opts = {
			xaxis: {
				tickLength: 0,
				ticks: sends_data.labels
			},
			yaxis: {
				tickLength: 0,
				minTickSize: 1,
				tickDecimals: 0,
				max: max_sends
			},
			grid: {
				borderWidth: 0,
				aboveData: true,
				markings: [
					{ 
						xaxis: {
							from: -0.5, 
							to: sends_data.length
						}, 
						yaxis: {
							from: 0,
							to: 0 
						}, 
						color: "#CCCCCC"
					},
					{
						xaxis: {
							from: -0.5,
							to: -0.5
						}, 
						yaxis: {
							from: 0,
							to: max_sends
						}, 
						color: "#E6E6E6" 
					}
				]
			}
		};
		
		$.plot(
			$sends_graph,
			[
				{
					color: "#FFFFFF",
					bars: {
						show: true,
						fillColor: "#21759B",
						lineWidth: 2,
						align: "center"
					},
					data: sends_data.data
				}
			],
			opts
		);

		$.plot(
			$opens_graph,
			[
				{
					color: "#FFFFFF",
					bars: {
						show: true,
						fillColor: "#21759B",
						lineWidth: 2,
						align: "center"
					},
					data: opens_data.data
				}
			],
			opts
		);
	};
	
	if ($sends_graph.length) {
		Statistics.plotData();
	}
});