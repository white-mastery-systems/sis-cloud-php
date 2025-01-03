// prepare the form when the DOM is ready 
$(document).ready(function() { 
		$('#vendor_name').keyup(function() {
			$('span.error-keyup-1').remove();
			var inputVal = $(this).val();
		
			if(inputVal == '') {	
				$(this).after('<span class="error error-keyup-1">Please select Vendor Name</span>');
		}
		});
		$('.itemDesc').keyup(function() {
			$('span.error-keyup-1').remove();
			var inputVal = $(this).val();
			var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
			if(!numericReg.test(inputVal)) {	
				$(this).after('<span class="error error-keyup-1">Numeric characters only</span>');
		}
		});
		$('.rate').keyup(function() {
			$('span.error-keyup-1').remove();
			var inputVal = $(this).val();
			var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
			if(!numericReg.test(inputVal)) {	
				$(this).after('<span class="error error-keyup-1">Numeric characters only</span>');
		}
		});
		$('.qty').keyup(function() {
			$('span.error-keyup-1').remove();
			var inputVal = $(this).val();
			var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
			if(!numericReg.test(inputVal)) {	
				$(this).after('<span class="error error-keyup-1">Numeric characters only</span>');
		}
		});
			$('.itemVat').keyup(function() {
			$('span.error-keyup-1').remove();
			var inputVal = $(this).val();
			var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
			if(!numericReg.test(inputVal)) {	
				$(this).after('<span class="error error-keyup-1">Numeric characters only</span>');
		}
		});
		$('#btn-submit').click(function() {
			if($('span.error').length > 0){
				alert('Errors!');
				return false;
			} else {
				$("#btn-submit").after('<span class="error">Form Accepted</span>');
				return false;
			}
	});	
});
