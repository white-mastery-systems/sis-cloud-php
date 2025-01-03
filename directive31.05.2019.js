app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});

app.directive("commondatepicker", function () {
    function link(scope, element, attrs) {
        element.datepicker({
            dateFormat: "dd-mm-yy"
        });
    }
    return {
        require: 'ngModel',
        link: link
    };
});

app.directive("datepicker", function () {
    function link(scope, element, attrs) {
        element.datepicker({
            dateFormat: "dd-mm-yy",
            minDate : 0
        });
    }
    return {
        require: 'ngModel',
        link: link
    };
});

app.directive("refdatepicker", function () {
    function link(scope, element, attrs) {
        element.datepicker({
            dateFormat: "dd-mm-yy",
            maxDate : 0
        });
    }
    return {
        require: 'ngModel',
        link: link
    };
});

app.directive('paymentAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.payment_list) {
                        var result = $filter('filter')($scope.payment_list, {payment:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['payment'];
                        });                       
                    }
                    response(result);
                },
            });
        }
    };
});

app.directive('vatAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.vat_list) {
                        var result = $filter('filter')($scope.vat_list, {vat:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['vat'];
                        });                       
                    }
                    response(result);
                },
            });
        }
    };
});
    
app.directive('vendorAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.vendor_details) {
                        var result = $filter('filter')($scope.vendor_details, {company_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['company_name'];
                        });                       
                    }
                    response(result);
                },
                 minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.purchaseForm.comp_address = ui.item.address;
                        $scope.purchaseForm.contact_person = ui.item.contact_person;
                        $scope.purchaseForm.party_gst = ui.item.party_gst;
                        $scope.mailForm.to_mail = ui.item.email;
                    });                       
                },
            });
        }
    };
});
    
app.directive('codeAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {code:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['code']+' - '+item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        ui.item.value = ui.item.code;
                        $scope.tableForm.item = ui.item.product_name;
                        $scope.tableForm.unit = ui.item.unit;
                        $scope.tableForm.price = ui.item.price;
                        $scope.tableForm.quantity = '1';
                        $scope.tableForm.gst = ui.item.gst;
                        $scope.tableForm.details = ui.item.details;
                        $scope.tableForm.make = ui.item.make;
                        $scope.tableForm.width = ui.item.width;
                        $scope.tableForm.height = ui.item.height;
                        $scope.tableForm.upvc_type = ui.item.upvc_type;
                        $scope.tableForm.size = ui.item.size;
                        $scope.tableForm.cft = ui.item.cft;
                        if(ui.item.type=='Door'){
                            $scope.tableForm.amount = ($scope.tableForm.cft * ui.item.price).toFixed(2);
                        }
                        else{
                            $scope.tableForm.amount = ($scope.tableForm.quantity * ui.item.price).toFixed(2);
                        }
                        $scope.tableForm.gst_amount = (($scope.tableForm.gst)/100 * $scope.tableForm.amount).toFixed(2);
                        $scope.tableForm.total = (($scope.tableForm.amount * 1) + ($scope.tableForm.gst_amount * 1)).toFixed(2);
                        $scope.calculateSum();
                    });                       
                },
            });
        }
    };
});

app.directive('productAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {product_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.tableForm.code = ui.item.code;
                        $scope.tableForm.item = ui.item.product_name;
                        $scope.tableForm.unit = ui.item.unit;
                        $scope.tableForm.price = ui.item.price;
                        $scope.tableForm.quantity = '1';
                        $scope.tableForm.gst = ui.item.gst;
                        $scope.tableForm.details = ui.item.details;
                        $scope.tableForm.make = ui.item.make;
                        $scope.tableForm.width = ui.item.width;
                        $scope.tableForm.height = ui.item.height;
                        $scope.tableForm.upvc_type = ui.item.upvc_type;
                        $scope.tableForm.size = ui.item.size;
                        $scope.tableForm.cft = ui.item.cft;
                        if(ui.item.type=='Door'){
                            $scope.tableForm.amount = ($scope.tableForm.cft * ui.item.price).toFixed(2);
                        }
                        else{
                            $scope.tableForm.amount = ($scope.tableForm.quantity * ui.item.price).toFixed(2);
                        }
                        $scope.tableForm.gst_amount = (($scope.tableForm.gst)/100 * $scope.tableForm.amount).toFixed(2);
                        $scope.tableForm.total = (($scope.tableForm.amount * 1) + ($scope.tableForm.gst_amount * 1)).toFixed(2);
                        $scope.calculateSum();
                    });                       
                },
                change:function (event, ui) {
                    if (ui.item === null) {
                        $scope.tableForm.item = '';
                    }
                }
            });
        }
    };
});
    
app.directive('quotcodeAutoComplete', function($filter, $http) {
    return {      
        link: function ($scope, element, attr) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {code:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['code']+' - '+item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $http({
                            url: './v1/getitemstock',
                            method: "POST",
                            data: { 'project_name': $scope.indentForm.sis_project_name, 'block_name': $scope.indentForm.sis_project_block, 'product_name': ui.item.value }
                        })
                        .then(function (response){
                            $scope.tableForm.available_stock = response.data.stock_details.block_available;
                            $scope.tableForm.stock_details = response.data.stock_details;
                        });
                        ui.item.value = ui.item.code;
                        $scope.tableForm.item = ui.item.product_name;
                        $scope.tableForm.unit = ui.item.unit;
                        $scope.tableForm.price = ui.item.price;
                        $scope.tableForm.quantity = '1';
                        $scope.tableForm.gst = ui.item.gst;
                        $scope.tableForm.details = ui.item.details;
                        $scope.tableForm.make = ui.item.make;
                        $scope.tableForm.width = ui.item.width;
                        $scope.tableForm.height = ui.item.height;
                        $scope.tableForm.upvc_type = ui.item.upvc_type;
                        $scope.tableForm.size = ui.item.size;
                        $scope.tableForm.cft = ui.item.cft;
                        if(ui.item.type=='Door'){
                            $scope.tableForm.amount = ($scope.tableForm.cft * ui.item.price).toFixed(2);
                        }
                        else{
                            $scope.tableForm.amount = ($scope.tableForm.quantity * ui.item.price).toFixed(2);
                        }
                        $scope.tableForm.gst_amount = (($scope.tableForm.gst)/100 * $scope.tableForm.amount).toFixed(2);
                        $scope.tableForm.total = (($scope.tableForm.amount * 1) + ($scope.tableForm.gst_amount * 1)).toFixed(2);
                        if(attr.quotIndex) { $scope.calculateSum(attr.quotIndex, attr.vendorIndex); }
                        else { $scope.calculateSum(attr.vendorIndex); }
                    });                       
                },
            });
        }
    };
});

app.directive('quotproductAutoComplete', function($filter, $http) {
    return {      
        link: function ($scope, element, attr) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {product_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $http({
                            url: './v1/getitemstock',
                            method: "POST",
                            data: { 'project_name': $scope.indentForm.sis_project_name, 'block_name': $scope.indentForm.sis_project_block, 'product_name': ui.item.value }
                        })
                        .then(function (response){
                            $scope.tableForm.available_stock = response.data.stock_details.block_available;
                            $scope.tableForm.stock_details = response.data.stock_details;
                        });
                        $scope.tableForm.code = ui.item.code;
                        $scope.tableForm.unit = ui.item.unit;
                        $scope.tableForm.quantity = '1';
                        $scope.tableForm.price = ui.item.price;
                        $scope.tableForm.gst = ui.item.gst;
                        $scope.tableForm.details = ui.item.details;
                        $scope.tableForm.make = ui.item.make;
                        $scope.tableForm.width = ui.item.width;
                        $scope.tableForm.height = ui.item.height;
                        $scope.tableForm.upvc_type = ui.item.upvc_type;
                        $scope.tableForm.size = ui.item.size;
                        $scope.tableForm.cft = ui.item.cft;
                        if(ui.item.type=='Door'){
                            $scope.tableForm.amount = ($scope.tableForm.cft * ui.item.price).toFixed(2);
                        }
                        else{
                            $scope.tableForm.amount = ($scope.tableForm.quantity * ui.item.price).toFixed(2);
                        }
                        $scope.tableForm.gst_amount = (($scope.tableForm.gst)/100 * $scope.tableForm.amount).toFixed(2);
                        $scope.tableForm.total = (($scope.tableForm.amount * 1) + ($scope.tableForm.gst_amount * 1)).toFixed(2);
                        if(attr.quotIndex) { $scope.calculateSum(attr.quotIndex, attr.vendorIndex); }
                        else { $scope.calculateSum(attr.vendorIndex); }
                    });                       
                },
                change:function (event, ui) {
                    if (ui.item === null) {
                        $scope.tableForm.item = '';
                    }
                }
            });
        }
    };
});

app.directive('personAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.person_list) {
                        var result = $filter('filter')($scope.person_list, {name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.purchaseForm.mobile = ui.item.mobile;
                    });                       
                },
            });
        }
    };
});

app.directive('quotAutoComplete', function($filter, $http) {
    return {
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.quotation_list) {
                        var result = $filter('filter')($scope.quotation_list, {quot_number:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['quot_number'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.poFromQuotation(ui.item.value);
                    });                       
                },
            });
        }
    };
});

app.directive('mailAutoComplete', function($filter, $http) {
    return {
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.mail_list) {
                        var result = $filter('filter')($scope.mail_list, {mail_id:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['mail_id'];
                        });                       
                    }
                    response(result);
                },
            });
        }
    };
});
        
app.directive('supplierAutoComplete', function($filter, $http) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.vendor_list) {
                        var result = $filter('filter')($scope.vendor_list, {company_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['company_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.supplierForm.comp_address = ui.item.address;
                        $scope.supplierForm.contact_person = ui.item.contact_person;
                        $scope.supplierForm.mobile = ui.item.mobile;
                    });                       
                },
            });
        }
    };
});

app.directive('quotSupplierAutoComplete', function($filter, $http) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.vendor_list) {
                        var result = $filter('filter')($scope.vendor_list, {company_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['company_name'];
                        });                       
                    }
                    response(result);
                }
            });
        }
    };
});
   
app.directive('useflatAutoComplete', function($filter, $http) {
    return {    
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.flat_details) {
                        var result = $filter('filter')($scope.flat_details, {flat_no:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['flat_no'];
                        });                       
                    }
                    response(result);
                },
                /*minLength: 1,
                response: function(event, ui) {
                    if (ui.content.length < 1)
                    {
                        $scope.stockForm.use_flat_no = '';
                    }
                },                  
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.stockForm.use_flat_no = ui.item.value;
                        var arr=[];
                        var newDataList=[];
                        for(var i=0;i<$scope.use_array.length;i++) {
                            if(arr.indexOf($scope.use_array[i]['use_flat_no']) == -1) {
                                arr.push($scope.use_array[i]['use_flat_no']);
                                newDataList.push($scope.use_array[i]);
                            }
                        }
                        $scope.useRepeatFn(newDataList);
                    });                       
                },*/
            });
        }
    };
});
      
app.directive('breakflatAutoComplete', function($filter, $http) {
    return {
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.flat_details) {
                        var result = $filter('filter')($scope.flat_details, {flat_no:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['flat_no'];
                        });                       
                    }
                    response(result);
                },
                /*minLength: 1,
                response: function(event, ui) {
                    if (ui.content.length < 1)
                    {
                        $scope.stockForm.break_flat_no = '';
                    }
                },                  
                select: function (event, ui) {
                    $scope.$apply(function(){
                        $scope.stockForm.break_flat_no = ui.item.value;
                        var arr=[];
                        var newDataList=[];
                        for(var i=0;i<$scope.break_array.length;i++) {
                            if(arr.indexOf($scope.break_array[i]['break_flat_no']) == -1) {
                                arr.push($scope.break_array[i]['break_flat_no']);
                                newDataList.push($scope.break_array[i]);
                            }
                        }
                        $scope.breakRepeatFn(newDataList);
                    });                       
                },*/
            });
        }
    };
 });

app.directive('userAutoComplete', function($filter, $http) {
    return {
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.user_list) {
                        var result = $filter('filter')($scope.user_list, {name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['name'];
                        });                       
                    }
                    response(result);
                },
            });
        }
    };
});

app.directive('addcodeAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {code:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['code']+' - '+item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        ui.item.value = ui.item.code;
                        $scope.addProductForm.product_name = ui.item.product_name;
                        $scope.addProductForm.unit = ui.item.unit;
                        $scope.addProductForm.price = ui.item.price;
                        $scope.addProductForm.gst = ui.item.gst;
                        $scope.addProductForm.details = ui.item.details;
                        $scope.addProductForm.make = ui.item.make;
                        $scope.addProductForm.width = ui.item.width;
                        $scope.addProductForm.height = ui.item.height;
                        $scope.addProductForm.upvc_type = ui.item.upvc_type;
                        $scope.addProductForm.size = ui.item.size;
                        $scope.addProductForm.cft = ui.item.cft;
                    });                       
                },
            });
        }
    };
});

app.directive('addproductAutoComplete', function($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.product_list) {
                        var result = $filter('filter')($scope.product_list, {product_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['product_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.$apply(function(){
                        ui.item.value = ui.item.product_name;
                        $scope.addProductForm.code = ui.item.code;
                        $scope.addProductForm.unit = ui.item.unit;
                        $scope.addProductForm.price = ui.item.price;
                        $scope.addProductForm.gst = ui.item.gst;
                        $scope.addProductForm.details = ui.item.details;
                        $scope.addProductForm.make = ui.item.make;
                        $scope.addProductForm.width = ui.item.width;
                        $scope.addProductForm.height = ui.item.height;
                        $scope.addProductForm.upvc_type = ui.item.upvc_type;
                        $scope.addProductForm.size = ui.item.size;
                        $scope.addProductForm.cft = ui.item.cft;
                    });                       
                },
            });
        }
    };
});

app.directive('modeAutoComplete', function ($filter) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if($scope.bank_list) {
                        var result = $filter('filter')($scope.bank_list, {name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['name'];
                        });                       
                    }
                    response(result);
                },
            });
        }
    };
});

app.directive("fileInput", function ($parse) {
    return {
        link: function ($scope, element, attrs) {
            element.on("change", function (event) {
                var files = event.target.files;
                $parse(attrs.fileInput).assign($scope, element[0].files);
                $scope.$apply();
            });
        }
    }
});

app.directive('billSupplierAutoComplete', function($filter, $http) {
    return {      
        link: function ($scope, element) {
            element.autocomplete({
                source: function (request, response) {
                    if ($scope.vendor_list) {
                        var result = $filter('filter')($scope.vendor_list, {company_name:request.term});
                        angular.forEach(result, function (item) {
                            item['value'] = item['company_name'];
                        });                       
                    }
                    response(result);
                },
                minLength: 1,                       
                select: function (event, ui) {
                    $scope.search = '';
                    $scope.item_list = [];
                    $scope.$apply(function(){
                        $http({
                            url: './v1/getVendorPoList',
                            method: "POST",
                            data: { 'company': ui.item.value, 'project_short': $scope.billForm.project_short }
                        })
                        .then(function (response){
                            $scope.po_list = response.data.po_list;
                        });
                    });                       
                }
            });
        }
    };
});