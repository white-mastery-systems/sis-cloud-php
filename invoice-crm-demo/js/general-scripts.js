

/*
 Create an Array to for adding row in table. You'll want to make sure you have the same number of columns
 You will also want to make sure that your inputs match what's in the html current row.
 Also, be sure you have the correct id's for each input.
 */

  

$(document).ready(function(){

    // Use the .autocomplete() method to compile the list based on input from user
    $('#itemDesc').autocomplete({
        source: 'ajax2.php',
        minLength: 1,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#itemDesc').val(ui.item.itemDesc);
                    $itemrow.find('#itemPrice').val(ui.item.itemPrice);

                    // Give focus to the next input field to recieve input from user
                    $('#itemQty').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.itemDesc + "</a>" )
            .appendTo( ul );
    };

    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

    // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var rowTemp = [
        '<tr class="item-row">',
        '<td class="datagrid-body"> </td>',
    '<td class="datagrid-body"><input id="itemDesc" name="itemDesc[]" type="text" class="form-control textbox" value="" /></td>',
    '<td class="datagrid-body"><input id="itemQty" name="itemQty[]" type="text" class="form-control textbox" value="" /></td>',
    '<td class="datagrid-body"><div class="input-group"><input id="itemPrice" name="itemPrice[]" class="form-control textbox" type="text"></div></td>',
		 '<td class="datagrid-body"><div class="input-group"><input id="itemVat" name="itemVat[]" class="form-control textbox" type="text"></div></td>',

    '<td class="datagrid-body"><div class="input-group"><input id="itemLineTotal" name="itemLineTotal[]" class="form-control textbox" type="text" readonly="readonly" style="border:none"></div></td>',
	'<td class="datagrid-body"><a id="deleteRow"><img src="images/delete.png" border="0" /></a></td>',
        '</tr>'
    ].join('');

    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){

        var $row = $(rowTemp);

        // save reference to inputs within row
        var $itemDesc 	        = $row.find('#itemDesc');
        var $itemPrice	        = $row.find('#itemPrice');
        var $itemQty	        = $row.find('#itemQty');
        var $itemPrice	        = $row.find('#iitemVat');
        var $itemQty	        = $row.find('#itemLineTotal');

        if ( $('#itemDesc:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#itemDesc').autocomplete({
                source: 'ajax2.php',
                minLength: 1,
                select: function(event, ui) {
                    $itemDesc.val(ui.item.itemDesc);
                    $itemPrice.val(ui.item.itemPrice);

                    // Give focus to the next input field to recieve input from user
                    $itemQty.focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.itemDesc + "</a>" )
                    .appendTo( ul );
            };
            // Add row after the first row in table
            $('.item-row:last', $itemsTable).after($row);
            $($itemDesc).focus();

        } // End if last itemCode input is empty
        return false;
    });

    $('#itemDesc').focus(function(){
        window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });

}); // End DOM

	// Remove row when clicked
	$("#deleteRow").live('click',function(){
		$(this).parents('.item-row').remove();
        // Hide delete Icon if we only have one row in the list.
        if ($(".item-row").length < 2) $("#deleteRow").hide();
		calculateTotal();
	});




//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#quantity_'+id[1]).val();
	price = $('#price_'+id[1]).val();
	if( quantity!='' && price !='' ) $('#total_'+id[1]).val( (parseFloat(price)*parseFloat(quantity)).toFixed(2) );	
	calculateTotal();
});

$(document).on('change keyup blur','#tax',function(){
	calculateTotal();
});

//total price calculation 
function calculateTotal(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(2) );
	tax = $('#tax').val();
	if(tax != '' && typeof(tax) != "undefined" ){
		taxAmount = subTotal * ( parseFloat(tax) /100 );
		$('#taxAmount').val(taxAmount.toFixed(2));
		total = subTotal + taxAmount;
	}else{
		$('#taxAmount').val(0);
		total = subTotal;
	}
	$('#totalAftertax').val( total.toFixed(2) );
	calculateAmountDue();
}

$(document).on('change keyup blur','#amountPaid',function(){
	calculateAmountDue();
});

//due amount calculation
function calculateAmountDue(){
	amountPaid = $('#amountPaid').val();
	total = $('#totalAftertax').val();
	if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
		amountDue = parseFloat(total) - parseFloat( amountPaid );
		$('.amountDue').val( amountDue.toFixed(2) );
	}else{
		total = parseFloat(total).toFixed(2);
		$('.amountDue').val( total );
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
