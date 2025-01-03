


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
 $itemrow.find('#cat_name').val(ui.item.cat_name);
  $itemrow.find('#sub_catname').val(ui.item.sub_catname);
  $itemrow.find('#size').val(ui.item.size);
  $itemrow.find('#brand').val(ui.item.brand);
  $itemrow.find('#product_code').val(ui.item.product_code);
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
         '<td class="datagrid-body"><div class="input-field col s1 "><a id="deleteRow"><span id="img"></span></a> &nbsp;</div><div class="input-field col s8 "><input name="itemDesc[]" value="" id="itemDesc" type="text" class="resetThis"  />  </div></td>',
 '<td class="datagrid-body"><div class="input-field col s8 "> <input name="cat_name[]" value="" class="resetThis" id="cat_name" tabindex="1" type="hidden"/><input name="sub_catname[]" value="" class="resetThis" id="sub_catname" type="hidden" /><input name="size[]" value="" class="resetThis" id="size" type="hidden"/><input name="brand[]" value="" class="resetThis" id="brand" type="hidden"/><input name="product_code[]" value="" class="resetThis" id="product_code" type="hidden"/><input name="itemQty[]" value="" class="quantity textbox" id="itemQty" tabindex="1" type="number"/></div><select id="units" name="units[]" CLASS="resetThis"><option value="kg">Select</option><option value="nos">Nos</option><option value="cft">CFT</option><option value="sft">SFT</option><option value="tonnage">Tonnage</option><option value="bags">Bags</option><option value="boxes">Boxes</option><option value="rft">RFT</option><option value="meters">meters</option><option value="length">Length</option><option value="feet">Feet</option></select></td>',
            '<td class="datagrid-body"><div class="input-field col s8 "> <input name="itemPrice[]" value="" class="resetThis rate" id="itemPrice" tabindex="2" type="number" /></div></td>',
     		'<td class="datagrid-body"><div class="input-field col s8 "> <input name="itemVat[]" class="resetThis itemVat" id="itemVat" type="number" tabindex="3"></div></td>',
			 '<td class="datagrid-body"><div class="input-field col s8 "> <input name="itemLineTotal[]" class="totalLinePrice resetThis" id="itemLineTotal" readonly="readonly" type="number" tabindex="4"/></div></td>',
			 '<td class="datagrid-body"></td>',
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
		var $sub_catname  = $itemRow.find('#sub_catname');
		var $cat_name  = $itemRow.find('#cat_name');
		var $size  = $itemRow.find('#size');
		var $brand  = $itemRow.find('#brand');
		var $product_code  = $itemRow.find('#product_code');
  // alert($(this).val());
	
	
        if ( $('#itemDesc:last').val() !== '' ) {

         // apply autocomplete widget to newly created row
            $itemRow.find('#itemDesc').autocomplete({
                source: 'ajax2.php',
                minLength: 1,
                select: function(event, ui) {
                    $itemDesc.val(ui.item.itemDesc);
                    $itemPrice.val(ui.item.itemPrice);
					$cat_name.val(ui.item.cat_name);
					$sub_catname.val(ui.item.sub_catname);
					$size.val(ui.item.size);
					$brand.val(ui.item.brand);
					$product_code.val(ui.item.product_code);
					

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
			
			
		//$("tr:last").prev().css({backgroundColor: 'yellow', fontWeight: 'bolder'});
		//$('#itemsTable tr:last ').prev().append("<img src='images/icon-minus.png'/>");
		$('#itemsTable tr:last').prev().find('#img').append("<img src='images/icon-minus.png'/>");
		
	//	$("tr:last").prev().find('#itemDesc').rules('add','required');
		$('#itemsTable tr:last').find('input').rules("add", "required");
            $($itemDesc).focus();
		
        } // End if last itemCode input is empty
	
    });



        return false;

  

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

