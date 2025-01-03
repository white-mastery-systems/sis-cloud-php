$(document).ready(function(){

    // Use the .autocomplete() method to compile the list based on input from user
    $('#itemDesc').autocomplete({
        source: 'data/item-data.php',
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
            '<td><a id="deleteRow"><img src="images/icon-minus.png" alt="Remove Item" title="Remove Item"></a></td>',
 
            '<td><input name="itemDesc[]" class="tInput" value="" id="itemDesc"  readonly="readonly" /></td>',
            '<td><input name="itemQty[]" class="tInput" value="" id="itemQty" /></td>',
            '<td><input name="itemPrice[]" class="tInput" value="" id="itemPrice" /></td>',
        '</tr>'
    ].join('');

    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){

        var $row = $(rowTemp);

        // save reference to inputs within row
        var $itemDesc 	        = $row.find('#itemDesc');
        var $itemPrice	        = $row.find('#itemPrice');
        var $itemQty	        = $row.find('#itemQty');

        if ( $('#itemCode:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#itemCode').autocomplete({
                source: 'data/item-data.php',
                minLength: 1,
                select: function(event, ui) {
                    $itemCode.val(ui.item.itemCode);
                    $itemDesc.val(ui.item.itemDesc);
                    $itemPrice.val(ui.item.itemPrice);

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
            $('.item-row:last', $itemsTable).after($row);
            $($itemCode).focus();

        } // End if last itemCode input is empty
        return false;
    });

    $('#itemCode').focus(function(){
        window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });

}); // End DOM

	// Remove row when clicked
	$("#deleteRow").live('click',function(){
		$(this).parents('.item-row').remove();
        // Hide delete Icon if we only have one row in the list.
        if ($(".item-row").length < 2) $("#deleteRow").hide();
	});
