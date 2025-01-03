

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//autocomplete script
function convertNumberToWords(amount) {
    var words = new Array();
    words[0] = '';
    words[1] = 'One';
    words[2] = 'Two';
    words[3] = 'Three';
    words[4] = 'Four';
    words[5] = 'Five';
    words[6] = 'Six';
    words[7] = 'Seven';
    words[8] = 'Eight';
    words[9] = 'Nine';
    words[10] = 'Ten';
    words[11] = 'Eleven';
    words[12] = 'Twelve';
    words[13] = 'Thirteen';
    words[14] = 'Fourteen';
    words[15] = 'Fifteen';
    words[16] = 'Sixteen';
    words[17] = 'Seventeen';
    words[18] = 'Eighteen';
    words[19] = 'Nineteen';
    words[20] = 'Twenty';
    words[30] = 'Thirty';
    words[40] = 'Forty';
    words[50] = 'Fifty';
    words[60] = 'Sixty';
    words[70] = 'Seventy';
    words[80] = 'Eighty';
    words[90] = 'Ninety';
    amount = amount.toString();
    var atemp = amount.split(".");
    var number = atemp[0].split(",").join("");
    var n_length = number.length;
    var words_string = "";
    if (n_length <= 9) {
        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        var received_n_array = new Array();
        for (var i = 0; i < n_length; i++) {
            received_n_array[i] = number.substr(i, 1);
        }
        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
            n_array[i] = received_n_array[j];
        }
        for (var i = 0, j = 1; i < 9; i++, j++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                if (n_array[i] == 1) {
                    n_array[j] = 10 + parseInt(n_array[j]);
                    n_array[i] = 0;
                }
            }
        }
        value = "";
        for (var i = 0; i < 9; i++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                value = n_array[i] * 10;
            } else {
                value = n_array[i];
            }
            if (value != 0) {
                words_string += words[value] + " ";
            }
            if ((i == 1 && value != 0 && value != 1) || (i == 0 && value != 0 && n_array[i + 1] == 0 && value != 1)) {
                words_string += "Crores ";
            }
            if ((i == 1 && value == 1) || (i == 0 && value == 1 && n_array[i + 1] == 0)) {
               
                words_string += "Crore ";
            }
            if ((i == 3 && value != 0 && value != 1) || (i == 2 && value != 0 && n_array[i + 1] == 0 && value != 1)) {
               
                words_string += "Lakhs ";
            }
             if ((i == 3 && value == 1) || (i == 2 && value == 1 && n_array[i + 1] == 0)) {
               
                words_string += "Lakh ";
            }
            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Thousand ";
            }
            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                words_string += "Hundred and ";
            } else if (i == 6 && value != 0) {
                words_string += "Hundred ";
            }
        }
        words_string = words_string.split("  ").join(" ");
    }
    return words_string;
}

//price change
$(document).on('change keyup blur','.changesNop',function(){
	id_arr = $(this).attr('id');
	//id = id_arr.split("_");
	subvalue = $('#subvalue').val();
    gstvalue = $('#gstvalue').val();
    larea = $('#larea').val();
    gvalue = $('#gvalue').val();
    regcharge = $('#regcharge').val();
    plotcost = $('#plotcost').val();
    invoicetotal = $('#invoicetotal').val();
    developmentcharge = $('#developmentcharge').val();
    landpayment = 0;
    landpayment = $('#landvalue').val(landpayment);
 
   
    projectname = $('#project_name').val();
      block = $('#blockname').val();
 
    if(projectname == 'S.I.S Capetown')
       {
console.log("S.I.S Capetown"); 
cal1 = parseFloat(plotcost);
 payment = parseFloat(subvalue);
    $('#lcvalue').val(subvalue);           
    cal2 = parseFloat(gvalue) * parseFloat(larea);  
    console.log("cal2...............",cal2) 
   // taxvalue = cal2;
    landcostcal = Math.round(cal2);
    $('#landcost').val(landcostcal); 
    regcal = (landcostcal * (11/100 ))+parseFloat(7500);
    $('#regcharge').val(regcal); 
    cal4 = parseFloat(landcostcal) + parseFloat(regcal);
          
   $('#landtotal').val(cal4); 
     //$('#regcharge').val(calextamount);        
  // landremval = cal4 - invoicetotal;
  console.log("cal4.............",cal4);
  invoicetotalcal  =  parseFloat(invoicetotal) +  parseFloat(payment);
           console.log("invoicetotal.............",invoicetotal,"Payment..........",invoicetotal)
           console.log("invoicetotalcal.............",invoicetotalcal,"plotcost..........",plotcost)
        if(invoicetotalcal <= plotcost)
            {
             $('#err').html("");   
           if(cal4 < invoicetotal) 
           {
    //$('#regcharge').val(calextamount);            
    developmentchargecal = Math.round(parseFloat(payment) * (100 /118 ));
    $('#developmentcharge').val(developmentchargecal);
          
    gstcal =(parseFloat(developmentchargecal) * (parseFloat(gstvalue) /100 ));            
    gst = Math.round(gstcal/2);
    $('#taxvalue').val(gst);
    // gst = cal3/2;
    //  gsttotal = Math.round(cal3)
     // gsttotal = cal3;        
  // landpayment = parseFloat(subvalue) - parseFloat(developmentchargecal);
  // $('#landvalue').val(landpayment);
   grandtotal =  Math.round(parseFloat(developmentchargecal) + parseFloat(gstcal)) ; 
   $('#cgst').val(gst);
   $('#sgst').val(gst);
   $('#roundvalue').val(payment);   
    $('#grandtotal').val(payment);
    grandtotalwords = convertNumberToWords(payment);
    $('#grandtotalwords').html(grandtotalwords +" Only");
    $('#totalword').val(grandtotalwords +" Only");    
           }
           else
               {
               landbalance  = parseFloat(cal4) - parseFloat(invoicetotal);
                   
                   if(payment <= landbalance )
                       {
                           
                        // developmentchargecal = Math.round(parseFloat(calextamount) * (100 /118 ));            
    $('#developmentcharge').val(0);
    $('#landvalue').val(payment);
    $('#taxvalue').val(0);    
     $('#cgst').val(0);
     $('#sgst').val(0);
        //   $('#roundvalue').val(subvalue);   
     $('#grandtotal').val(payment);
    grandtotalwords = convertNumberToWords(payment);
         $('#roundvalue').val(payment); 
    $('#grandtotalwords').html(grandtotalwords +" Only");
      $('#totalword').val(grandtotalwords +" Only"); 
 }
                   
                   else
                       {
     calextamount =  parseFloat(payment) - parseFloat(landbalance);
    developmentchargecal = Math.round(parseFloat(calextamount) * (100 /118 ));
    $('#developmentcharge').val(developmentchargecal);          
    gstcal =( parseFloat(developmentchargecal) * (parseFloat(gstvalue) /100 ));
      gstcalf =  Math.round(gstcal)                 
    gst = Math.round(gstcal/2);
    $('#taxvalue').val(gst);   
   $('#landvalue').val(landbalance);
   grandtotal =  Math.round(parseFloat(developmentchargecal)+parseFloat(gstcalf)+parseFloat(landbalance)) ;
                           
   $('#cgst').val(gst);
   $('#sgst').val(gst);
   $('#roundvalue').val(payment);   
    $('#grandtotal').val(payment);
    grandtotalwords = convertNumberToWords(payment);
    $('#grandtotalwords').html(grandtotalwords +" Only");
    $('#totalword').val(grandtotalwords +" Only");                            
                           
                       }                   
                   
}  
                
}
           
else{
console.log("Kindly Dont cross the Plotcost");
$('#subvalue').val("")
$('#err').html("Kindly Dont cross the Plotcost")
}        
}

  
       else
       {
           
           if(block == 'commercial')
               {
console.log("other Project commercial");
     if(gstvalue == 0)
    {
 
     // gsttotal = cal3;
     $('#cgst').val(0);
     $('#sgst').val(0);
    
    $('#roundvalue').val(subvalue);    
    
     $('#grandtotal').val(subvalue);
    grandtotalwords = convertNumberToWords(subvalue);
    $('#grandtotalwords').html(grandtotalwords +" Only");
      $('#totalword').val(grandtotalwords +" Only");  
    }
    else
        {
                  
     cal3 = subvalue * ( parseFloat(gstvalue) /100 );  
 gst = Math.round(cal3/2);
   // gst = cal3/2;
   gsttotal = Math.round(cal3)
     // gsttotal = cal3;
     $('#cgst').val(gst);
     $('#sgst').val(gst);
 final = parseFloat(subvalue)+gsttotal;
    $('#roundvalue').val(final);    
//    roundamount = cal4-parseFloat(subvalue);
       //final = cal4-roundamount;
     $('#grandtotal').val(final);
    grandtotalwords = convertNumberToWords(final);
    $('#grandtotalwords').html(grandtotalwords +" Only");
      $('#totalword').val(grandtotalwords +" Only");   
}    
      
}

else
    {
              console.log("other Project without commercial");
     if(gstvalue == 0)
    {
    //lcvalue = cal1;
    $('#lcvalue').val(subvalue);
     
   
    //taxvalue = cal2;
    $('#taxvalue').val(0); 

     // gsttotal = cal3;
     $('#cgst').val(0);
     $('#sgst').val(0);
    
    $('#roundvalue').val(subvalue);    
    
     $('#grandtotal').val(subvalue);
    grandtotalwords = convertNumberToWords(subvalue);
    $('#grandtotalwords').html(grandtotalwords +" Only");
      $('#totalword').val(grandtotalwords +" Only");  
    }
    else
        {
                  cal1 = (((parseFloat(subvalue)/ 112.0006))*100);
	//cal= (((parseFloat(price)*parseFloat(quantity)))*(parseFloat(itemvat) /100));
	//alert(cal);
    lcvalue = Math.round(cal1);
    
    //lcvalue = cal1;
    $('#lcvalue').val(lcvalue);
    cal2 = lcvalue * ( 66.67 /100 );    
    taxvalue = cal2;
    taxvalue = Math.round(cal2);
    //taxvalue = cal2;
    $('#taxvalue').val(taxvalue);
     cal3 = taxvalue * ( parseFloat(gstvalue) /100 );  
 gst = Math.round(cal3/2);
   // gst = cal3/2;
   gsttotal = Math.round(cal3)
     // gsttotal = cal3;
     $('#cgst').val(gst);
     $('#sgst').val(gst);
    cal4 = lcvalue+gsttotal;
    $('#roundvalue').val(cal4);    
    roundamount = cal4-parseFloat(subvalue);
    final = cal4-roundamount;
     $('#grandtotal').val(final);
    grandtotalwords = convertNumberToWords(subvalue);
    $('#grandtotalwords').html(grandtotalwords +" Only");
      $('#totalword').val(grandtotalwords +" Only");   
}
    }
     
}
    
   
    
  // $('#itemVatamount_'+id[1]).val(cal);
    //$('#itemtotalamount_'+id[1]).val((parseFloat(price)*parseFloat(quantity)));
	//totalamt = ((parseFloat(price)*parseFloat(quantity))) + cal;
	//if( quantity!='' && price !='' && itemvat !='' ) $('#itemLineTotal_'+id[1]).val(totalamt.toFixed(2));	
	//calculateTotal1();
   
});




$(document).on('change keyup blur','#tp2',function(){
 calculateTotal1();
   
});

//total price calculation 




$(document).on('change keyup blur','#tp2',function(){
	calculateTotal1();
});

//total price calculation 
function calculateTotal1(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice_s').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});

    		$('#subTotal').val( subTotal.toFixed(2) );
	tp2 = $('#tp2').val();
	if(tp2 != '' && typeof(tp2) != "undefined" ){
		tpamount = subTotal + parseFloat(tp2);
		$('#transportAmount_s').val(tpamount.toFixed(2));
		total = tpamount;
	}else{
		$('#transportAmount_s').val(0);
		total = subTotal;
	}
	$('#grandTotal').val( total.toFixed(2) );
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

