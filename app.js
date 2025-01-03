'use strict';

var app = angular.module('app', ['ui.router','ngMaterial', 'ui.directives', 'ui.filters', 'ngSanitize', 'bootstrapLightbox']);

app.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {

    $locationProvider.hashPrefix('');
    $urlRouterProvider.otherwise('/login');
    $stateProvider
        
    /***** Login *****/
    .state('login', {
        url: '/login',
        templateUrl: 'modules/login/view/login.html',
        controller: 'loginCtrl',
        onEnter: function($window){ $window.document.title = "Login"; }
    })
		
    /***** Header *****/
    .state('home', {
        url: '/home',
        templateUrl: 'modules/common/view/index.html',
        controller: 'commonHeaderCtrl'
    })
        
    /***** Dashboard *****/
    .state('home.dashboard', {
        url: '/dashboard',
        templateUrl: 'modules/dashboard/view/index.html',
        //controller: 'dashboardCtrl',
        onEnter: function($window){ $window.document.title = "dashboard"; }
    })
    
    /***** Projects *****/
    .state('home.projects', {
        url: '/projects',
        templateUrl: 'modules/projects/view/projects.html',
        controller: 'sisProjectCtrl',
        onEnter: function($window){ $window.document.title = "SIS Projects"; }
    })
    
    /***** Materials -> Dashboard *****/
    .state('home.materials', {
        url: '/materials',
        templateUrl: 'modules/materials/dashboard/view/dashboard.html',
        //controller: 'materialDashboardCtrl',
        onEnter: function($window){ $window.document.title = "Dashboard"; }
    })
    
    /***** Materials -> Indent *****/
    .state('home.indent', {
        url: '/indent/{filter}',
        templateUrl: 'modules/materials/indent/view/indent.html',
        controller: 'indentCtrl',
        onEnter: function($window){ $window.document.title = "Indent"; }
    })
    
    .state('home.new_indent', {
        url: '/new_indent/{filter}',
        templateUrl: 'modules/materials/indent/view/new_indent.html',
        controller: 'newIndentCtrl',
        onEnter: function($window){ $window.document.title = "New Indent"; }
    })
    
    .state('home.indent_update', {
        url: '/indent_update/{filter}',
        templateUrl: 'modules/materials/indent/view/indent_update.html',
        controller: 'indentUpdateCtrl',
        onEnter: function($window){ $window.document.title = "Indent"; }
    })
    
    .state('home.indent_duplicate', {
        url: '/indent_duplicate/{filter}',
        templateUrl: 'modules/materials/indent/view/indent_duplicate.html',
        controller: 'indentDuplicateCtrl',
        onEnter: function($window){ $window.document.title = "Indent"; }
    })
    
    .state('home.indent_view', {
        url: '/indent_view/{filter}',
        templateUrl: 'modules/materials/indent/view/indent_view.html',
        controller: 'indentViewCtrl',
        onEnter: function($window){ $window.document.title = "Indent"; }
    })
    
    .state('indent_mail', {
        url: '/indent_mail/{filter}',
        controller: 'indentMailCtrl',
        onEnter: function($window){ $window.document.title = "Indent"; }
    })
    
    /***** Materials -> Quotation *****/
    .state('home.quotation', {
        url: '/quotation/{filter}',
        templateUrl: 'modules/materials/quotation/view/quotation.html',
        controller: 'quotationCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
    
    .state('home.new_quotation', {
        url: '/new_quotation/{filter}',
        templateUrl: 'modules/materials/quotation/view/new_quotation.html',
        controller: 'quotationNewCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
    
    .state('home.quotation_update', {
        url: '/quotation_update/{filter}',
        templateUrl: 'modules/materials/quotation/view/quotation_update.html',
        controller: 'quotationUpdateCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
    
    .state('home.quotation_update_admin', {
        url: '/quotation_update_admin/{filter}',
        templateUrl: 'modules/materials/quotation/view/quotation_update_admin.html',
        controller: 'quotationUpdateAdminCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
   
    .state('home.quotation_view', {
        url: '/quotation_view/{filter}',
        templateUrl: 'modules/materials/quotation/view/quotation_view.html',
        controller: 'quotationViewCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
    
    .state('quotation_approve', {
        url: '/quotation_approve/{filter}',
        templateUrl: 'modules/materials/quotation/view/approved.html',
        controller: 'quotationApprovedCtrl',
        onEnter: function($window){ $window.document.title = "Quotation"; }
    })
    
    /***** Materials -> Purchase *****/
    .state('home.orders', {
        url: '/orders/{filter}',
        templateUrl: 'modules/materials/purchase/view/order.html',
        controller: 'orderCtrl',
        onEnter: function($window){ $window.document.title = "Purchase Order"; }
    })
		
    .state('home.new_purchase', {
        url: '/new_purchase/{filter}',
        templateUrl: 'modules/materials/purchase/view/new_purchase.html',
        controller: 'neworderCtrl',
        onEnter: function($window){ $window.document.title = "New Purchase"; }
    })
		
    .state('home.purchase_duplicate', {
        url: '/purchase_duplicate/{filter}',
        templateUrl: 'modules/materials/purchase/view/purchase_duplicate.html',
        controller: 'orderDupCtrl',
        onEnter: function($window){ $window.document.title = "Purchase Order"; }
    })
		
    .state('home.purchase_update', {
        url: '/purchase_update/{filter}',
        templateUrl: 'modules/materials/purchase/view/purchase_update.html',
        controller: 'orderUpdateCtrl',
        onEnter: function($window){ $window.document.title = "Purchase Order"; }
    })
		
    .state('home.purchase_view', {
        url: '/purchase_view/{filter}',
        templateUrl: 'modules/materials/purchase/view/purchase_view.html',
        controller: 'orderViewCtrl',
        onEnter: function($window){ $window.document.title = "Purchase Order"; }
    })
    
    .state('home.vendors', {
        url: '/vendors',
        templateUrl: 'modules/materials/purchase/view/vendors.html',
        controller: 'vendorCtrl',
        onEnter: function($window){ $window.document.title = "Vendors"; }
    })
    
    .state('home.products', {
        url: '/products',
        templateUrl: 'modules/materials/purchase/view/products.html',
        controller: 'productCtrl',
        onEnter: function($window){ $window.document.title = "Products"; }
    })
    
    /***** Materials -> Inventory *****/
    .state('home.inventory', {
        url: '/inventory',
        templateUrl: 'modules/materials/inventory/view/inventory.html',
        controller: 'inventoryCtrl',
        onEnter: function($window){ $window.document.title = "Inventory"; }
    })
    
    .state('home.inventory_use', {
        url: '/inventory_use/{filter}',
        templateUrl: 'modules/materials/inventory/view/inventory_use.html',
        controller: 'inventoryUseCtrl',
        onEnter: function($window){ $window.document.title = "Inventory Usage"; }
    })
    
    .state('home.category', {
        url: '/category/{filter}',
        templateUrl: 'modules/materials/inventory/view/category.html',
        controller: 'categoryCtrl',
        onEnter: function($window){ $window.document.title = "Category"; }
    })
    
    /***** User List *****/
    
    .state('home.userlist', {
        url: '/userlist/{filter}',
        templateUrl: 'modules/user/view/userlist.html',
        controller: 'userListCtrl',
        onEnter: function($window){ $window.document.title = "User List"; }
    })
    
    .state('home.user_view', {
        url: '/user_view/{filter}',
        templateUrl: 'modules/user/view/editprofile.html',
        controller: 'userEditCtrl',
        onEnter: function($window){ $window.document.title = "User Details"; }
    })
    
    .state('home.add_user', {
        url: '/add_user/{filter}',
        templateUrl: 'modules/user/view/adduser.html',
        controller: 'addUserCtrl',
        onEnter: function($window){ $window.document.title = "Add User"; }
    })
    
    .state('home.permission', {
        url: '/permission/{filter}',
        templateUrl: 'modules/user/view/user_permission.html',
        controller: 'userPermissionCtrl',
        onEnter: function($window){ $window.document.title = "User Permissions"; }
    })
    
    /***** Accounts *****/
    
    .state('home.accounts', {
        url: '/accounts',
        templateUrl: 'modules/accounts/view/accounts.html',
        controller: 'accountsCtrl',
        onEnter: function($window){ $window.document.title = "Accounts"; }
    })

    /***** Billing *****/
    .state('home.bill_list', {
        url: '/bill_list/{filter}',
        templateUrl: 'modules/billing/view/bill_list.html',
        controller: 'billListCtrl',
        onEnter: function($window){ $window.document.title = "Bill List"; }
    })
        
    .state('home.new_bill', {
        url: '/new_bill/{filter}',
        templateUrl: 'modules/billing/view/new_bill.html',
        controller: 'newBillCtrl',
        onEnter: function($window){ $window.document.title = "New Bill"; }
    })
        
    .state('home.bill_view', {
        url: '/bill_view/{filter}',
        templateUrl: 'modules/billing/view/bill_view.html',
        controller: 'billViewCtrl',
        onEnter: function($window){ $window.document.title = "Bill"; }
    });
});