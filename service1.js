app.service('orderService', function() {
    this.doorcalculation = function (x) {
        if(x != null){
            var res = x.split("x");
            if(res[2]) {
                var total = 1;
                for(var i=0; i<3; i++)
                {
                    if(res[i].includes("'"))    //if feet exist
                    {
                        var num = res[i].split("'");
                        if(num[1]) {
                            number = num[1].replace('"', "");
                            inch = number/12; 
                        }
                        else{
                            inch = 0;
                        }
                        feet = parseFloat(num[0]) + inch;
                    }
                    else if(res[i].includes('"'))
                    {
                        number = res[i].replace('"', "");
                        inch = number/12;
                        feet = inch;
                    }
                    else{
                        feet = 0;
                    }
                    total = total * feet;
                    total = total.toFixed(3);
                }
                var cft = total * 1;
            }
        }
        else{
            var cft = '';
        }   
        return cft;
    }
});