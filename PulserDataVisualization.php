naren.s


Search Drive

Drive
.
Folder Path
My Drive
GSoC McGill - Exercise
NEW 
Folders and views
My Drive
Shared with me
Google Photos
Recent
Starred
Trash
2 GB of 15 GB used
Upgrade storage
Get Drive for PC
.

Javascript
d3.legend.js

Javascript
d3.min.js

Unknown File
pulsar_data_test.json

PHP
PulserDataVisualization.php


<!doctype html>
<html>
	<head> 
			<title> Visulaize Pulsar Data </title>
			<script src = "http://d3js.org/d3.v3.min.js"></script>
			<script src = "d3.legend.js"></script>
	</head>
	<style> /* set the CSS */

			body { font: 12px Arial;}

			path { 
				stroke: steelblue;
				stroke-width: 2;
				fill: none;
			}

			.axis path,
			.axis line {
				fill: none;
				stroke: grey;
				stroke-width: 1;
				shape-rendering: crispEdges;
			}
			
			.legend rect {
			  fill:white;
			  stroke:black;
			  opacity:0.8;
			}
	</style>
	<body>
		<script>
			// Set the dimensions of the canvas / graph
			var margin = {top: 100, right: 20, bottom: 50, left: 300},
				width = 1250 - margin.left - margin.right,
				height = 600 - margin.top - margin.bottom;
			d3.json("pulsar_data_test.json",function(dataArray){
				function compare(a,b) {
				  if (a.Period < b.Period)
					return -1;
				  else if (a.Period > b.Period)
					return 1;
				  else 
					return 0;
				}

				dataArray.sort(compare);
				var canvas = d3.select("body")
							   .append("svg")
								.attr("width", width + margin.left + margin.right)
								.attr("height", height + margin.top + margin.bottom)
								.append("g")
								.attr("transform","translate(" + margin.left + "," + margin.top + ")");
				// Set the ranges
				var x = d3.scale.linear().range([0, width]);
				var y = d3.scale.linear().range([height, 0]);
				
				// Define the axes
				var xAxis = d3.svg.axis().scale(x)
					.orient("bottom").ticks(10);

				var yAxis = d3.svg.axis().scale(y)
					.orient("left").ticks(5);
					
				// Define the line
				var valueline = d3.svg.line()
					.x(function(d) { return x(d.Period*Math.pow(10,4)); })
					.y(function(d) { return y(Math.log(d["Period Derivative"])); });
					
				// Scale the range of the data
				x.domain(d3.extent(dataArray, function(d) { return d.Period*Math.pow(10,4); }));
				y.domain(d3.extent(dataArray, function(d) { return Math.log(d["Period Derivative"]); }));
				
				// Add the valueline path.
					canvas.append("path")
						.attr("class", "line")
						.attr("d", valueline(dataArray));
				// Add the scatterplot
					var points = canvas.selectAll("dot")
						.data(dataArray)
					  .enter().append("circle")
						.attr("r", 3.5)
						.attr("cx", function(d) { return x(d.Period*Math.pow(10,4)); })
						.attr("cy", function(d) { return y(Math.log(d["Period Derivative"])); })
						.append("svg:title").text(function(d) { return "(" + d.Period*Math.pow(10,4) + "," + Math.log(d["Period Derivative"]) + ")";});
					points.onmouseover = function(d){
						display("(" + d.Period*Math.pow(10,4) + "," + Math.log(d["Period Derivative"]) + ")");
					}
				// Add the X Axis
					canvas.append("g")
						.attr("class", "x axis")
						.attr("transform", "translate(0," + height + ")")
						.attr("data-legend","Period E-04")
						.call(xAxis)
						.append("text")
						.attr("x", width-30)
						.attr("y", -6)
						.attr("dx", "1.2em")
						.style("font-size","1.5em")
						.style("text-anchor", "end")
						.text("Period");
				// Add the Y Axis
					canvas.append("g")
						.attr("class", "y axis")
						.attr("data-legend","Period Derivative Log(pd)")
						.call(yAxis)
						.append("text")
						.attr("transform", "rotate(-90)")
						.attr("y", 6)
						.attr("dy", "1.5em")
						.style("text-anchor", "end")
						.style("font-size","1.5em")
						.text("Period Derivative");
					
					
				//Add Legend
				var	legend = canvas.append("g")
								.attr("class","legend")
								.attr("transform","translate(100,30)")
								.style("font-size","12px")
								.call(d3.legend)
				setTimeout(function() { 
					legend
					  .style("font-size","20px")
					  .attr("data-style-padding",10)
					  .call(d3.legend)
				},1000)
			});
		</script>
	</body>
