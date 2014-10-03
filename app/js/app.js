'use strict';


// Declare app level module which depends on filters, and services
var myApp = angular.module('myApp', [
  'toggle-switch',
  'ngRoute',
  'ui.bootstrap',
  'myApp.filters',
  'myApp.services',
  'myApp.directives',
  'myApp.controllers'
])

.config(['$compileProvider', '$routeProvider', '$locationProvider', function ($compileProvider, $routeProvider, $locationProvider){
        //$compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|tel):/);

        $routeProvider
            .when('/login', {
                templateUrl: 'partials/login.html', 
                controller: 'LoginCtrl',

                // You do not need to be logged in to go to this route.
                requireLogin: false
            })

            .when('/manageDevices', {
                templateUrl: 'partials/addNewAccount.html',
                controller: 'addNewAccountCtrl',

                // You must be logged into go to this route.
                requireLogin: true
            })

            .when('/listOfStations', {
                templateUrl: 'partials/sipStations.html',
                controller: 'sipStationCtrl',
                requireLogin: true
            })
            .when('/statistics', {
                templateUrl: 'partials/statistics.html',
                controller: 'statisticsCtrl',
                requireLogin: true
            })    
        ;

        // If the url is unrecognized go to login
        $routeProvider.otherwise({
            redirectTo: '/login'
        });
    }])

 .run(['$rootScope', 'AuthService', '$location', function($rootScope, AuthService, $location){
        // Everytime the route in our app changes check auth status
        $rootScope.$on("$routeChangeStart", function(event, next, current) {
            // if you're logged out send to login page.
            if (next.requireLogin && !AuthService.getUserAuthenticated()) {
                $location.path('/login');
                event.preventDefault();
            }
        });
    }])

.controller('MainCtrl', ['$scope', 'AuthService', '$location', '$log', function($scope, AuthService, $location, $log) {
        
        $scope.getClass = function(path) {
            
            if ($location.path().substr(0, path.length) === path) {
                
                return "active";
            } else {
                return "";
            }
        };
                
        $scope.logoutUser = function() {
            // run a logout function to your api
            AuthService.setUserAuthenticated(false);
            $location.path('/login');
        };

        $scope.isLoggedIn = function() {
            return AuthService.getUserAuthenticated();
        };
    }]);


