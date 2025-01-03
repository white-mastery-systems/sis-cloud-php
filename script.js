var app = angular.module('plunker', ['ngMaterial']);

app.controller('MainCtrl', function($scope) {
    var self = this;
    self.readonly = false;
    self.selectedItem = null;
    self.searchText = null;
    self.querySearch = querySearch;
    self.vegetables = loadVegetables();
    self.selectedVegetables = [];
    self.selectedVegetables2 = [];
    /**
     * Search for vegetables.
     */
    function querySearch (query) {
      var results = query ? self.vegetables.filter(createFilterFor(query)) : [];
      return results;
    }
    /**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);
      return function filterFn(vegetable) {
        return (vegetable._lowername.indexOf(lowercaseQuery) === 0);
      };
    }
    function loadVegetables() {
      var veggies = [
        {
          'name': 'A1'
        },{
          'name': 'A2'
        },{
          'name': 'A3'
        },{
          'name': 'A4'
        },{
          'name': 'A5'
        },{
          'name': 'A6'
        },{
          'name': 'A7'
        },{
          'name': 'A8'
        }
      ];
      return veggies.map(function (veg) {
        veg._lowername = veg.name.toLowerCase();
        return veg;
      });
    }
});
