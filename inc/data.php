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
			$p = $row['party'];
		}
	}

	$avg = "SELECT AVG(value) AS mean FROM `interests` LIMIT 1";
	if ($result = $mysqli->query($avg)) {
		if ($result->num_rows > 0) {					
			$mean = $result->fetch_array()['mean'];
		}
	}
	// echo $mean;
	?>		

	<script>
		<?php
		
		echo 'var avg = \'' . $mean . '\';';
		echo 'var party = \'' . $p . '\';';
		
		?>
		var palette = [];
			palette['Con'] = '#0087DC';
			palette['Lab'] = '#F10000';
			palette['LDem'] = '#FFDC00';
			palette['SNP'] = '#F8FF00';
			palette['PC'] = '#008844';
			palette['DUP'] = '#E45F3D';
			palette['SDLP'] = '#99FF66';
			palette['Green'] = '#3FB921';
			palette['SF'] = '#009108';
			palette['SPK'] = '#CCC';
			palette['Ind'] = '#999';
			palette['Res'] = '#FF3300';
			palette['UKIP'] = '#7E007A';		

		function shadeColor(color, percent) {  
		    var num = parseInt(color.slice(1),16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
		    return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
		}

		var width = 700,   // width of svg
            height = 400,  // height of svg
            padding = 60; // space around the chart, not including labels
		            
        d3.json('/data/mp.php?mid=<?php echo $mid; ?>', function(error, data) {
        	
        	var x_domain = d3.extent(data, function(d) { return d.date; }),
				y_domain = d3.extent(data, function(d) { return d.value; });

			// display date format
	        var date_format = d3.time.format("%d %b");
	        // var 
	        var meanVal = d3.mean(data, function(d) {
				return d.value;
			});
			var medianVal = d3.median(data, function(d) {
				return d.value;
			});
			var maxVal = d3.max(data, function(d) {
				return d.value;
			});
			var sumVal = d3.sum(data, function(d) {
				return d.value;
			});


			var tip = d3.tip()
			  .attr('class', 'd3-tip')
			  .offset([-10, 0])
			  .direction('s')
			  .html(function(d) {
			  	var date = new Date( d.date );
			  	var monthNames = ["January", "February", "March", "April", "May", "June",
			  	  "July", "August", "September", "October", "November", "December"
			  	];
			  	return "<strong>&pound;" + d3.format(',')(d.value) + "</strong> " + monthNames[date.getMonth()] + ' ' + date.getFullYear();
			  })

	        // create an svg container
	        var vis = d3.select("body")
	        	.attr('class', 'chart')
	        	.append("svg:svg")
                .attr("width", width)
                .attr("height", height);
	                
			vis.call(tip);
			$('.schpeil').remove();
			$('body').append('<div class="schpeil"></div>');

	        // define the y scale  (vertical)
	        var yScale = d3.scale.linear()
		  		.domain([d3.min(data, function(d) {
					return d.value;
				}), d3.max(data, function(d) {
					return d.value;
				})]).nice()   // make axis end in round number
				// .domain([0, 125000]).nice()   // make axis end in round number
				.range([height - padding, padding]);   // map these to the chart height, less padding.  In this case 300 and 100
	            
	            
            function getDate(d) {
                return new Date(d.date);
            }
            // var minDate = getDate(data[0]),
                // maxDate = getDate(data[data.length-1]);
			var minDate = getDate(data[0]),
                maxDate = getDate(data[data.length-1]);

            console.log(data[0]);
            console.log(minDate);
            console.log(new Date('2010-09'));

            var xScale = d3.time.scale()
	        	// .domain([minDate, maxDate]).range([padding + 20, width - padding]);
	        	.domain([new Date('2010-09'), new Date('2015-04')]).range([padding + 20, width - padding]);
		        

	        // define the y axis
	        var yAxis = d3.svg.axis()
	            .orient("left")
	            .scale(yScale);
	        
	        // define the x axis
	        var xAxis = d3.svg.axis()
	            .orient("bottom")
	            .ticks(d3.time.years)
                .tickFormat(d3.time.format('%Y'))
                .tickSize(4)
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
	        // vis.selectAll(".xaxis text")  // select all the text elements for the xaxis
	        //   .attr("transform", function(d) {
	        //      // return "translate(" + this.getBBox().height*-2 + "," + this.getBBox().height + ")rotate(-45)";
	        //  });
	    
	        // now add titles to the axes
	        vis.append("text")
	            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
	            .attr("transform", "translate(" + 5 + "," + (height/2) + ")")  // text is drawn off the screen top left, move down and out and rotate
	            .attr('class','label')
	            .text("£");

	        vis.append("text")
	            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
	            .attr("transform", "translate("+ (width/2) +","+(height-(padding/3))+")")  // centre below axis
	            .attr('class','label')
	            .text("Date");
 			
 			// do something with mouse hover
 			function mouseover(p) {
 			    var g = d3.select(this).style('fill-opacity','1');
 			}
 			
 			// do something with mouse blur
 			function mouseout(p) {
 			    var g = d3.select(this).style('fill-opacity','0.75');
 			}

 			var rects = vis
	        	.selectAll('.chart')
				.data(data)
				.enter().append('rect')
		            .attr('class', 'bar')
		            .attr('x', function(d) { return xScale(new Date(d.date)) - 6; })
		            .attr('y', function(d) { return height - padding;  })
		            .attr('width', 12)
		            .attr('title',function(d) { return d.date + ' (' + d.value + ')'; })
		            .attr('height',0)
		            .style('fill',shadeColor(palette[party],25));
		    
		    rects.transition()
	            .delay(250)
	            .duration(500)
	            .attr('y', function(d) { return yScale(d.value);  })
	            .attr('height', function(d) { return height - padding - yScale(d.value); });

		    rects.on('mouseover', function(p) {
		    	var g = d3.select(this).style('fill',palette[party]);
		    	})
		    .on('mouseout', function(p) {
		    	var g = d3.select(this).style('fill',shadeColor(palette[party],25));
		    }).on('mouseover', function(d) {
	            tip.attr('class', 'd3-tip animate').show(d)
	        })
	        .on('mouseout', function(d) {
	            tip.attr('class', 'd3-tip').show(d)
	            tip.hide()
            });
		    
			var myLine = vis.append("svg:line")
	           .attr("class", 'd3-dp-line')
	           .attr("x1", padding)
	           .attr("y1", function(d) { return yScale(avg); })
	           .attr("x2", width - padding + 6)
	           .attr("y2", function(d) { return yScale(avg); })
	           .style("stroke-dasharray", ("3, 3"))
	           .style("stroke-opacity", 0.9)
	           .style("stroke", '#999');

			var myLine = vis.append("svg:line")
	           .attr("class", 'd3-dp-line')
	           .attr("x1", padding)
	           .attr("y1", function(d) { return yScale(medianVal); })
	           .attr("x2", width - padding + 6)
	           .attr("y2", function(d) { return yScale(medianVal); })
	           .style("stroke-dasharray", ("3, 3"))
	           .style("stroke-opacity", 0.9)
	           // .style("stroke", shadeColor(palette[party],-25));
	           .style("stroke", shadeColor(palette[party],-12.5));
	        // alert(shadeColor(palette[party],-25));
			
	        $('.schpeil').empty();
	        $('.schpeil').append('<p>Highest: £' + d3.format(',')(maxVal) + '</p>');
	        $('.schpeil').append('<p>Total: £' + d3.format(',')(sumVal) + '</p>');
	        $('.schpeil').append('<p>Average: £' + d3.format(',')(medianVal) + '</p>');
        });
		
	</script>
