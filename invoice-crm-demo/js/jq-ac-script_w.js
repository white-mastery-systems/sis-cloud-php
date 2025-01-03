


$(document).ready(function(){

    // Use the .autocomplete() method to compile the list based on input from user
    $('#itemCode').autocomplete({
        source: 'ajax2_w.php',
        minLength: 1,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
 $itemrow.find('#itemCode').val(ui.item.itemCode);
 $itemrow.find('#itemWidth').val(ui.item.itemWidth);
 $itemrow.find('#itemHeight').val(ui.item.itemHeight);
  $itemrow.find('#itemDesc').val(ui.item.itemDesc);
  $itemrow.find('#itemHeight').val(ui.item.itemHeight);
  $itemrow.find('#itemWidth').val(ui.item.itemWidth);
  $itemrow.find('#itemBasic_m').val(ui.item.itemBasic_m);
   $itemrow.find('#itemBasic_i').val(ui.item.itemBasic_i);

   $itemrow.find('#itemQty').val(ui.item.itemQty);
  
                    // Give focus to the next input field to recieve input from user
                    $('#itemQty').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append("<a>" + item.itemCode + " - " + item.itemDesc + "</a>")
            .appendTo( ul );
    };

    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

    // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var rowTemp = [
				   '<tr class="item-row"><td></td>',
'<td><div class="input-field col s8 "><div class="input-field col s1 "><a id="deleteRow"><span id="img"></span></a> &nbsp;</div><div class="input-field col s8 "><input name="itemCode[]" value="" id="itemCode" type="text" class="resetThis"  /></td>',
'<td><div class="input-field col s8 "> <input  data-type="itemWidth" name="itemWidth[]" class="autocomplete_txt itemDesc" id="itemWidth"  type="text"  required /></div></td>',
'<td><div class="input-field col s8 "><input name="itemHeight[]"  class="rate" id="itemHeight" type="number" pattern="[0-9]*" required /></div></td>',
'<td><div class="input-field col s8 "><input name="itemQty[]"  class="rate" id="itemQty" type="number" pattern="[0-9]*" required /></div></td>',
'<td><div class="input-field col s8 "><input name="itemDesc[]" class="itemDesc" id="itemDesc" type="text"   required  ></div></td>',
'<td><div class="input-field col s8 "><input name="itemBasic_m[]" class="itemBasic_m" id="itemBasic_m" type="number"/></div></td>',
'<td><div class="input-field col s8 "><input name="itemTotal_m[]" class="itemTotal_m" id="itemTotal_m" readonly="readonly" type="number"/></div></td>',
'<td><div class="input-field col s8 "><input name="itemBasic_i[]" class="itemBasic_i" id="itemBasic_i" type="number"/></div></td>',
'<td><div class="input-field col s8 "><input name="itemTotal_i[]" class="itemTotal_i" id="itemTotal_i" readonly="readonly" type="number"/></div></td>',
'<td><div class="input-field col s8 "><input name="itemLineTotal[]" class="totalLinePrice resetThis" id="itemLineTotal" readonly="readonly" type="number" placeholder="Total"/></div></td>',

'</tr>'
				   
    ].join('');

    // Add row to list and allow user to use autocomplete to find items.
   // Add row to list and allow user to use autocomplete to find items.
    $("#itemCode").live('change',function(){

        var $itemRow = $(rowTemp);

        // save reference to inputs within row
        var $itemCode 	        = $itemRow.find('#itemCode');
        var $itemWidth	        = $itemRow.find('#itemWidth');
		var $itemHeight	        = $itemRow.find('#itemHeight');
        var $itemQty	        = $itemRow.find('#itemQty');
    	var $itemDesc	        = $itemRow.find('#itemDesc');
		var $itemBasic_m	    = $itemRow.find('#itemBasic_m');
		var $itemTotal_m	    = $itemRow.find('#itemTotal_m');
		var $itemBasic_i	    = $itemRow.find('#itemBasic_i');
		var $itemTotal_i	    = $itemRow.find('#itemTotal_i');
	    var $itemLineTotal	    = $itemRow.find('#itemLineTotal');
		
  // alert($(this).val());
	
	
        if ( $('#itemCode:last').val() !== '' ) {

         // apply autocomplete widget to newly created row
            $itemRow.find('#itemCode').autocomplete({
                source: 'ajax2_w.php',
                minLength: 1,
                select: function(event, ui) {
					$itemCode.val(ui.item.itemCode);
                    $itemDesc.val(ui.item.itemDesc);
                    $itemWidth.val(ui.item.itemWidth);
					$itemHeight.val(ui.item.itemHeight);
					$itemBasic_m.val(ui.item.itemBasic_m);
					$itemBasic_i.val(ui.item.itemBasic_i);
					
					

                    // Give focus to the next input field to recieve input from user
                    $itemQty.focus();

                    return false;
                }
				
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.itemCode + " - " + item.itemDesc + "</a>" )
                    .appendTo( ul );
            };
            // Add row after the first row in table
		
	
            $('.item-row:last', $itemsTable).after($itemRow);
			
			
		//$("tr:last").prev().css({backgroundColor: 'yellow', fontWeight: 'bolder'});
		//$('#itemsTable tr:last ').prev().append("<img src='images/icon-minus.png'/>");
		$('#itemsTable tr:last').prev().find('#img').append("<img src='images/icon-minus.png'/>");
		
	//	$("tr:last").prev().find('#itemDesc').rules('add','required');
		$('#itemsTable tr:last').find('input').rules("add", "required");
            $($itemCode).focus();
		
        } // End if last itemCode input is empty
	
    });



        return false;

  

}); // End DOM


	// Remove row when clicked
$("#deleteRow").live('click',function(){
								
										  if($(".item-row").length == 1)
										  {
											document.getElementById("itemCode").value ="";
											document.getElementById("itemDesc").value ="";
											document.getElementById("itemBasic_m").value ="";
											document.getElementById("itemTotal_m").value ="";
											document.getElementById("itemBasic_i").value ="";
											document.getElementById("itemTotal_i").value ="";
											document.getElementById("itemQty").value ="";
											document.getElementById("itemLineTotal").value ="";
											document.getElementById("itemQty").value ="";
											document.getElementById("itemHeight").value ="";
											document.getElementById("itemWidth").value ="";

							}
                                      else
										  {
		$(this).parents('.item-row').remove();
        // Hide delete Icon if we only have one row in the list.
      //  if ($(".item-row").length < 2) $("#deleteRow").hide()	
	  
	
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

