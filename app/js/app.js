'use strict';


// Declare app level module which depends on filters, and services
var myApp = angular.module('myApp', [
  'ngRoute',
  'ui.bootstrap',
  'myApp.filters',
  'myApp.services',
  'myApp.directives',
  'myApp.controllers'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/view1', {templateUrl: 'partials/sipStations.html', controller: 'sipStationCtrl'});
  $routeProvider.when('/view2', {templateUrl: 'partials/addNewAccount.html', controller: 'addNewAccountCtrl'});
  $routeProvider.otherwise({redirectTo: '/view1'});
}]);


