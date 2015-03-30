<script type="text/javascript" src="_assets/js/libs/jquery-2.1.3.min.js"></script>
<script>
	
	$(window).on('load',function() {
		// $('#search').after('<div class="results"></div>');
		// alert('wibble');
		$('#member').on('keyup',function() {
			var query = $(this).val();
			if (query.length >= 3) {
				$.ajax({
				  url: "inc/member.php",
				  type: "get", //send it through get method
				  data:{member:query},
				  success: function(response) {
				    //Do Something
				    // alert('yay!');
				    $('.results').html(response);
				  },
				  error: function(xhr) {
				    //Do Something to handle error
				    // alert('boo!');
				  }
				});

			}			
		});
		$('.results').on('click','a',function(e) {
			e.preventDefault();
			var qid = $(this).attr('data-mid');
			$.ajax({
			  url: "inc/data.php",
			  type: "get", //send it through get method
			  data:{ mid:qid },
			  success: function(response) {
			    //Do Something
			    // alert('yay!');
			    window.history.pushState(null, null, '/?mid=' + qid);
			    $('svg').remove();
			    $('.results').html(response);
			  },
			  error: function(xhr) {
			    //Do Something to handle error
			    // alert('boo!');
			  }
			});
			$('.results')
		})

	});
</script>
</body>
</html>