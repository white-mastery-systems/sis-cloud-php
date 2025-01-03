app.service('orderService', function() {
    this.doorcalculation = function (x) {
        if(x != null) {
			if(x.indexOf('x') !== -1)
			   {
			 var res = x.split("x");			
			 if(res[2] != " " && typeof res[2] != "undefined" ){
			 var total_i = 1;
			 var val = res[0]*res[1]*res[2];				
			 total1 = parseFloat(val/144);			
//			 total_c=String(total);			
//			if(total_c.indexOf('.') !== -1) {			
//				 str = total_c.split('.');
//				 var res1 = str[1].slice(0, 3);
//				 var str1 = str[0]+'.'+res1;
//				 total1 = parseFloat(str1);
//			}
//			else {
//				total1 = parseFloat(total);
//			}
				
			var total_f = parseFloat(total_i * total1);
		    var cft = parseFloat(total_f * 1); 
			 }
			else {
				var cft = 0;				
			}
			   }
			else
				{
				var cft = 0;
		
				}
      
	}
        else{
            var cft = 0;
        }
        return cft;
    }
});		
			
			
			
//            if(res[2]) {
//                var total = 1;
//                for(var i=0; i<3; i++)
//                {
//                    if(res[i].includes("'"))    //if feet exist
//                    {
//                        var num = res[i].split("'");
//                        if(num[1]) {
//                            number = num[1].replace('"', "");
//                            inch = number/12; 
//                        }
//                        else{
//                            inch = 0;
//                        }
//                        feet = parseFloat(num[0]) + inch;
//                    }
//                    else if(res[i].includes('"'))
//                    {
//                        number = res[i].replace('"', "");
//                        inch = number/12;
//                        feet = inch;
//                    }
//                    else{
//                        feet = 0;
//                    }
//                    total = total * feet;
//                    total = total.toFixed(3);
//                }
//                var cft = total * 1;
//            }
        