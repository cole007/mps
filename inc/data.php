<?php
	include_once('../inc/mysql.php');
	if (isset($_GET['mid'])) $mid = $_GET['mid'];
	$q = "SELECT * FROM `mp` WHERE `mys_id` = 'uk.org.publicwhip/member/".$mid."' LIMIT 1";
	if ($result = $mysqli->query($q)) {
		if ($result->num_rows > 0) {					
			$row = $result->fetch_array();
			echo '<h1>';
			if (isset($row['title'])) echo $row['title'] . ' ';
			if (isset($row['firstname'])) echo $row['firstname'] . ' ';
			if (isset($row['lastname'])) echo $row['lastname'] . ' ';
			echo '</h1>';
			echo '<h2>'.$row['constituency'].' (' . $row['party'] . ')</h2>';
		}
	}
	?>		

	<script>

		var width = 700,   // width of svg
            height = 400,  // height of svg
            padding = 60; // space around the chart, not including labels
		            
        d3.json('/data/mp.php?mid=<?php echo $mid; ?>', function(error, data) {
        	
        	var x_domain = d3.extent(data, function(d) { return d.date; }),
				y_domain = d3.extent(data, function(d) { return d.value; });

			// display date format
	        var  date_format = d3.time.format("%d %b");
	        
	        // create an svg container
	        var vis = d3.select("body")
	        	.attr('class', 'chart')
	        	.append("svg:svg")
                .attr("width", width)
                .attr("height", height);
	                
	        // define the y scale  (vertical)
	        var yScale = d3.scale.linear()
		        .domain([d3.min(data, function(d) {
					return d.value;
				}), d3.max(data, function(d) {
					return d.value;
				})]).nice()   // make axis end in round number
				.range([height - padding, padding]);   // map these to the chart height, less padding.  In this case 300 and 100
	                 //REMEMBER: y axis range has the bigger number first because the y value of zero is at the top of chart and increases as you go down.
			    
	            
            function getDate(d) {
                return new Date(d.date);
            }
            var minDate = getDate(data[0]),
                maxDate = getDate(data[data.length-1]);

                console.log(minDate);
                console.log(maxDate);
                
	        var xScale = d3.time.scale()
	        	.domain([minDate, maxDate]).range([padding, width - padding]);
		        // .domain([new Date(data[0].date), d3.time.day.offset(new Date(data[data.length - 1].date), 1)])
		        // .rangeRound([0, width - padding]);
			    // .range([padding, width - padding]);   // map these sides of the chart, in this case 100 and 600


	        // define the y axis
	        var yAxis = d3.svg.axis()
	            .orient("left")
	            .scale(yScale);
	        
	        // console.log(minDate, maxDate);
	        // define the x axis
	        var xAxis = d3.svg.axis()
	            .orient("bottom")
	            .ticks(d3.time.years)
                .tickFormat(d3.time.format('%Y'))
                .tickSize(4)
                // .tickPadding(8)
	            .scale(xScale);
	            
	        // draw y axis with labels and move in from the size by the amount of padding
	        vis.append("g")
	        	.attr("class", "axis")
	            .attr("transform", "translate("+padding+",0)")
	            .call(yAxis);

	        // draw x axis with labels and move to the bottom of the chart area
	        vis.append("g")
	            .attr("class", "xaxis axis")  // two classes, one for css formatting, one for selection below
	            .attr("transform", "translate(0," + (height - padding) + ")")
	            .call(xAxis);
	            
	        // now rotate text on x axis
	        // solution based on idea here: https://groups.google.com/forum/?fromgroups#!topic/d3-js/heOBPQF3sAY
	        // first move the text left so no longer centered on the tick
	        // then rotate up to get 45 degrees.
	        vis.selectAll(".xaxis text")  // select all the text elements for the xaxis
	          .attr("transform", function(d) {
	             return "translate(" + this.getBBox().height*-2 + "," + this.getBBox().height + ")rotate(-45)";
	         });
	    
	        // now add titles to the axes
	        vis.append("text")
	            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
	            .attr("transform", "translate("+ (padding/2) +","+(height/2)+")rotate(-90)")  // text is drawn off the screen top left, move down and out and rotate
	            .text("£");

	        vis.append("text")
	            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
	            .attr("transform", "translate("+ (width/2) +","+(height-(padding/3))+")")  // centre below axis
	            .text("Date");
 	

	        vis
	        	.selectAll('.chart')
				.data(data)
				.enter().append('rect')
		            .attr('class', 'bar')
		            .attr('x', function(d) { return xScale(new Date(d.date)); })
		            .attr('y', function(d) { return height - padding;  })
		            .attr('width', 14)
		            .attr('title',function(d) { return d.date + ' (' + d.value + ')'; })
		            .attr('height',0)
		            .transition()
		            .delay(250)
		            .duration(500)
		            .attr('y', function(d) { return yScale(d.value);  })
		            .attr('height', function(d) { return height - padding - yScale(d.value); });


        });

	</script>