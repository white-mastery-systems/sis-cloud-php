$('#countryname_1').autocomplete({
					
			      	source: function( request, response ) {
							
			      		$.ajax({
							   
			      			url : 'get_vendorlist.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : 1
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[0],
										value: code[0],
										data : item
									}
									alert(data);
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");
						console.log(names[1], names[2], names[3]);						
						$('#country_no_1').val(names[1]);
						$('#phone_code_1').val(names[2]);
						$('#country_code_1').val(names[3]);
					}		      	
			      });