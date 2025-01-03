
var i=$('#itemsTable tr').length;
$(".addmore").on('click',function(){
								  alert("hi");
	html = '<tr>';
	html += '<td><input type="checkbox" id="selecttd'+i+'" class="case" /><label for="selecttd'+i+'"></label></td>';
		html += '<td> <input  data-type="product_code"  id="itemCode_'+i+'"  name="itemCode[]" class="autocomplete_txt resetThis" type="text"  required autocomplete="off"  /></td>';
	html += '<td><div class="input-field col s8 "> <input name="itemWidth[]" id="itemWidth_'+i+'"  type="text"  required autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemHeight[]" id="itemHeight_'+i+'" type="number" pattern="[0-9]*" required autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemQty[]"  id="itemQty_'+i+'" type="number" pattern="[0-9]*" required autocomplete="off" class="changesNo" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" /></div></td>';
	html += '<td><div class="input-field col s8 "><input data-type="pro_name" name="itemDesc[]"  id="itemDesc_'+i+'" type="text" class="autocomplete_txt"  required tocomplete="off" ></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemBasic_m[]"  id="itemBasicm_'+i+'" type="number" autocomplete="off" class="changesNo" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemTotal_m[]" id="itemTotalm_'+i+'" readonly="readonly" type="number" autocomplete="off" class="totalLinePrice1"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemBasic_i[]"  id="itemBasici_'+i+'" type="number" autocomplete="off" class="changesNo" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemTotal_i[]" id="itemTotali_'+i+'" readonly="readonly" type="number" autocomplete="off" class="totalLinePrice2" /></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemLineTotal[]"  id="itemLineTotal_'+i+'" readonly="readonly" type="number" placeholder="Total" autocomplete="off" class="totalLinePrice" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"/></div></td>';
	html += '</tr>';
	$('#itemsTable').append(html);
	i++;
});

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal();
});

//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
	type = $(this).data('type');
	alert(type);
	if(type =='product_code' )autoTypeNo=0;
	if(type =='pro_name' )autoTypeNo=1; 	
	
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'purchase_wautocomplete.php',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: type
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
					 	var code = item.split("|");
						return {
							label: code[autoTypeNo],
							value: code[autoTypeNo],
							data : item
						}
					}));
				}
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
			var names = ui.item.data.split("|");						
			id_arr = $(this).attr('id');
	  		id = id_arr.split("_");
			$('#itemCode_'+id[1]).val(names[0]);
			$('#itemDesc_'+id[1]).val(names[1]);
			$('#itemHeight_'+id[1]).val(names[3]);
			$('#itemWidth_'+id[1]).val(names[4]);
			//$('#ed').val(names[8]);
			//$('#st').val(names[9]);
			//$('#vat').val(names[7]);
			$('#itemQty_'+id[1]).val(1);
			$('#itemBasicm_'+id[1]).val(names[5]);
			$('#itemTotalm_'+id[1]).val( 1*names[5] );
			$('#itemBasici_'+id[1]).val(names[6]);
			$('#itemTotali_'+id[1]).val( 1*names[6] );
			$('#itemLineTotal_'+id[1]).val( 1*((+names[5])+ (+names[6])));
			
			calculateTotal();
		}		      	
	});
});

//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#itemQty_'+id[1]).val();
	
	itembasicm = $('#itemBasicm_'+id[1]).val();
	itembasici = $('#itemBasici_'+id[1]).val();
	
	if( quantity!='' && itembasicm !='' ) $('#itemTotalm_'+id[1]).val( (parseFloat(itembasicm)*parseFloat(quantity)).toFixed(2) );
	if( quantity!='' && itembasici !='' ) $('#itemTotali_'+id[1]).val( (parseFloat(itembasici)*parseFloat(quantity)).toFixed(2) );
	itembasicmt =  $('#itemTotalm_'+id[1]).val();
	itembasicit =  $('#itemTotali_'+id[1]).val();
	if( itembasici!='' && itembasicm !='' ) $('#itemLineTotal_'+id[1]).val((parseFloat(itembasicmt)+parseFloat(itembasicit)).toFixed(2) );
	calculateTotal();
});

$(document).on('change keyup blur','#ed',function(){
	calculateTotal();
});
$(document).on('change keyup blur','#vat',function(){
	calculateTotal();
});

//total price calculation 
function calculateTotal(){
	subTotal = 0 ; total = 0; 
	total1 = 0;
	total2 = 0;
	total3 = 0;
	subTotal1 = 0; 
	subTotal2 = 0;taxAmount1 =0;
	
	
	
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('.totalLinePrice1').each(function(){
		if($(this).val() != '' )subTotal1 += parseFloat( $(this).val() );
	});
	$('.totalLinePrice2').each(function(){
		if($(this).val() != '' )subTotal2 += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(2) );
	$('#basictotal').val( subTotal1.toFixed(2) );
	$('#gt').val( subTotal1.toFixed(2) );
	$('#gt1').val( subTotal2.toFixed(2) );
	tax = $('#ed').val();
   tax1 = $('#vat').val();
if(tax != '' || tax1 !=''  ){
	
		taxAmount = subTotal1 * ( parseFloat(tax) /100 );
		taxAmount1 = subTotal1 * ( parseFloat(tax1) /100 );
		//$('#taxAmount').val(taxAmount.toFixed(2));
	
		total1 = subTotal1 + taxAmount+taxAmount1 ;
		total = subTotal + taxAmount+taxAmount1 ;
	}
	

	else{
	
	
		
		total1 = subTotal1;
		total = subTotal;
		
	}
	 
$('#gt').val( total1.toFixed(2) );
	$('#subTotal').val( total.toFixed(2) );
calculateAmountDue();


}

$(document).on('change keyup blur','#tp',function(){
	calculateAmountDue();
});


//due amount calculation
function calculateAmountDue(){
	tp = $('#tp').val();
	total = $('#subTotal').val();
	total1 = $('#gt').val();
	if(tp!= '' && typeof(tp) != "undefined" ){
		tp1 = parseFloat(total) + parseFloat(tp);
		tp2=parseFloat(total1) + parseFloat(tp);
		$('#gt').val(tp2.toFixed(2) );
		$('#subTotal').val(tp1.toFixed(2) );
	}else{
		total = parseFloat(total).toFixed(2);
		total1 = parseFloat(total1).toFixed(2);
		$('#subTotal').val( total );
		$('#gt').val( total1 );
	}
}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

//datepicker
$(function () {
    $('#invoiceDate').datepicker({});
});