/**
 * Dashboard
 */
$(document).ready(function(){
	
	if($("#browser-statistics-chart").length){
		var $_data = $("#browser-statistics-chart").attr('data-chart');
		var $data = [];
		
		if(!$_data || $_data == undefined){
			console.log("Data Not Defined To Build Chart");
			return;
		}

		$options = {
			yaxis: {
				zoomRange: [0.1, 10],
				panRange: [0, 10]
			},
			xaxis: {
				mode: "categories",
				tickLength: 0,
				zoomRange: [0.1, 10],
				panRange: [0, 10]
			}, 
			series: {
				bars: { 
					show: true,
					barWidth: 0.6,
					align: "center"
				}
		    }, 
		    zoom: {
				interactive: true
			},
			pan: {
				interactive: true
			},
			grid: {
				hoverable: true,
				clickable: true
			},
		};
		
		$_data = jQuery.parseJSON($_data);
		
		var browserGraph = $.plot("#browser-statistics-chart", [ $_data ], $options);
		

		$("#browser-statistics-chart").bind("plothover", function (event, pos, item) {
			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;

					$("#browser-count-chart-tooltip").remove();

					var timestamp = item.datapoint[0];
					var count = item.datapoint[1];
					
					$("<div id='browser-count-chart-tooltip'>"+item.datapoint[0] + " - " + item.datapoint[1]).css({
						position: "absolute",
						display: "none",
						top: item.pageY + 5,
						left: item.pageX + 5,
						border: "1px solid #fdd",
						padding: "2px",
						"background-color": "#fee",
						opacity: 0.80
					}).appendTo("body").fadeIn(200);
				}
			} else {
				$("#browser-count-chart-tooltip").remove();
				previousPoint = null;            
			}
		});
	}
	
	if($("#weeks-orders-chart").length){
		
		var $_data = $("#weeks-orders-chart").attr('data-chart');
		var $data = [];
		
		if(!$_data || $_data == undefined){
			console.log("Data Not Defined To Build Chart");
			return;
		}

		$options = {
			yaxis: {
				
			},
			xaxis: {
				mode: "time",
				timeformat: "%m/%d/%Y %h:%s %a"
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			series: {
		        lines: { show: true },
		        points: { show: true }
		    }
		};
		
		$_data = jQuery.parseJSON($_data);
		
		$.each($_data, function(key, val) {
			$data.push($_data[key]);
		});
		
		var graph = $.plot("#weeks-orders-chart", $data, $options);
		var previousPoint = null;
		
		$("#weeks-orders-chart").bind("plothover", function (event, pos, item) {
			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;

					$("#weeks-orders-chart-tooltip").remove();

					var timestamp = item.datapoint[0];
					var count = item.datapoint[1];
					
					$("<div id='weeks-orders-chart-tooltip'>"+count+ " " + item.series.label + " on " + new Date(timestamp).toDateString() + "</div>").css({
						position: "absolute",
						display: "none",
						top: item.pageY + 5,
						left: item.pageX + 5,
						border: "1px solid #fdd",
						padding: "2px",
						"background-color": "#fee",
						opacity: 0.80
					}).appendTo("body").fadeIn(200);
				}
			} else {
				$("#weeks-orders-chart-tooltip").remove();
				previousPoint = null;            
			}
		});
	}
	
	
	if($("#todays-visitors-chart").length){
		
		var $_data = $("#todays-visitors-chart").attr('data-chart');
		var $data = [];
		
		if(!$_data || $_data == undefined){
			console.log("Data Not Defined To Build Chart");
			return;
		}

		$options = {
			yaxis: {
				
			},
			xaxis: {
				mode: "time",
				timeformat: "%m/%d/%Y %h:%s %a"
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			series: {
		        lines: { show: true },
		        points: { show: true }
		    }
		};
		
		$_data = jQuery.parseJSON($_data);
		
		$.each($_data, function(key, val) {
			$data.push($_data[key]);
		});
		
		var graph = $.plot("#todays-visitors-chart", $data, $options);
		
		/*
		var previousPoint = null;
		$("#todays-visitors-chart").bind("plothover", function (event, pos, item) {
			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;

					$("#todays-visitors-chart-tooltip").remove();

					var timestamp = item.datapoint[0];
					var count = item.datapoint[1];
					
					$("<div id='todays-visitors-chart-tooltip'>"+count+ " " + item.series.label + " on " + new Date(timestamp).toDateString() + "</div>").css({
						position: "absolute",
						display: "none",
						top: item.pageY + 5,
						left: item.pageX + 5,
						border: "1px solid #fdd",
						padding: "2px",
						"background-color": "#fee",
						opacity: 0.80
					}).appendTo("body").fadeIn(200);
				}
			} else {
				$("#todays-visitors-chart-tooltip").remove();
				previousPoint = null;            
			}
		});*/
	}
	
});