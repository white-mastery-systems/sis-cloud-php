


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
		'<td class="datagrid-body">&nbsp;</td>',
         '<td class="datagrid-body"><a id="deleteRow"><img src="images/icon-minus.png" id="image2" name="image2" alt="Remove Item" title="Remove Item"></a> &nbsp; <input name="itemDesc[]" value="" id="itemDesc" class="textbox2" /></td>',
 '<td class="datagrid-body"><input name="itemQty[]" value="" class="quantity textbox" id="itemQty" tabindex="1" type="number"/></td>',
            '<td class="datagrid-body"><input name="itemPrice[]" value="" class="textbox rate" id="itemPrice" tabindex="2" type="number" /></td>',
     		'<td class="datagrid-body"><input name="itemVat[]" class="textbox itemVat" id="itemVat" type="number" tabindex="3"> </td>',
			 '<td class="datagrid-body"><input name="itemLineTotal[]" class="textbox totalLinePrice" id="itemLineTotal" readonly="readonly" type="number" tabindex="4"/></td>',
			 '  <td class="datagrid-body"></td>',
        '</tr>'
    ].join('');

    // Add row to list and allow user to use autocomplete to find items.
    $("#itemDesc").live('change',function(){

        var $itemRow = $(rowTemp);

        // save reference to inputs within row
        var $itemDesc 	        = $itemRow.find('#itemDesc');
        var $itemPrice	        = $itemRow.find('#itemPrice');
        var $itemQty	        = $itemRow.find('#itemQty');
    	var $itemVat	        = $itemRow.find('#itemVat');
	    var $itemLineTotal	        = $itemRow.find('#itemLineTotal');
  // alert($(this).val());
	
	
        if ( $('#itemDesc:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $itemRow.find('#itemDesc').autocomplete({
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
		
	
            $('.item-row:last', $itemsTable).after($itemRow);
			
            $($itemDesc).focus();
	calculateTotal();
        } // End if last itemCode input is empty
	
        return false;
    });




  

}); // End DOM


	// Remove row when clicked
$("#deleteRow").live('click',function(){
										  if($(".item-row").length == 1)
										  {
											document.getElementById("itemDesc").value ="";
											document.getElementById("itemPrice").value ="";
											document.getElementById("itemVat").value ="";
											document.getElementById("itemLineTotal").value ="";
											document.getElementById("itemQty").value ="";
											
									 }
									  
										
								
									
										  else
										  {
		$(this).parents('.item-row').remove();
        // Hide delete Icon if we only have one row in the list.
      //  if ($(".item-row").length < 2) $("#deleteRow").hide()	
	  	
	//if ($(".item-row:last")) $(".item-row:last #deleteRow").hide();
		var total = 0;
     $("#itemsTable .item-row").each(function() {
											  
        var qty = parseInt($(this).find(".quantity").val());
         var rate = parseInt($(this).find(".rate").val());
		 var vat = parseInt($(this).find(".itemVat").val());
		  var total1 = document.getElementById("subTotal").value;
		  var subtotal1 = qty * rate;
		  var vatamount =  (subtotal1 /100)*vat;
        var subtotal = subtotal1+vatamount;
         $(this).find(".totalLinePrice").val(subtotal);
         if(!isNaN(subtotal))
             total+=subtotal;		
			
     });
	 
	   document.getElementById("subTotal").value = total;
									
										  }
	});

$(".item-row:last #deleteRow").live('click',function(){
		if ($(".item-row:last") && $(this).find(".quantity").val(' ') && $(this).find(".rate").val(' ') && $(this).find(".itemVat").val(' ') ){
			$(".item-row:last #deleteRow").hide();
			
														 
													 }
	});


function roundNumber(number,decimals) {
  var newString;// The new rounded number
  decimals = Number(decimals);
  if (decimals < 1) {
    newString = (Math.round(number)).toString();
  } else {
    var numString = number.toString();
    if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
      numString += ".";// give it one at the end
    }
    var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
    var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
    var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
    if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
      if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
        while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
          if (d1 != ".") {
            cutoff -= 1;
            d1 = Number(numString.substring(cutoff,cutoff+1));
          } else {
            cutoff -= 1;
          }
        }
      }
      d1 += 1;
    } 
    if (d1 == 10) {
      numString = numString.substring(0, numString.lastIndexOf("."));
      var roundedNum = Number(numString) + 1;
      newString = roundedNum.toString() + '.';
    } else {
      newString = numString.substring(0,cutoff) + d1.toString();
    }
  }
  if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
    newString += ".";
  }
  var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
  for(var i=0;i<decimals-decs;i++) newString += "0";
  //var newNumber = Number(newString);// make it a number if you like
  return newString; // Output the result to the form field (change for your purposes)
}


$(function() {

    $("#itemsTable").keyup(function(event) {
     var total = 0;
	 var vat = 0;
     $("#itemsTable .item-row").each(function() {
         var qty = parseInt($(this).find(".quantity").val());
         var rate = parseInt($(this).find(".rate").val());
		 var vat = parseInt($(this).find(".itemVat").val());
		  var subtotal1 = qty * rate;
		  var vatamount =  (subtotal1 /100)*vat;
        var subtotal = subtotal1+vatamount;
         $(this).find(".totalLinePrice").val(subtotal);
         if(!isNaN(subtotal))
             total+=subtotal;
		
			
     });
	   document.getElementById("subTotal").value = total;

    });

})

