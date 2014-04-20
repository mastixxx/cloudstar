'use strict';

/* Controllers */


var myControllers = angular.module('myApp.controllers', []);

myControllers.controller('LoginCtrl', ['$scope', '$http', 'AuthService', '$location','$log', function($scope, $http, AuthService, $location, $log) {
        // If user is logged in send them to home page
        if (AuthService.getUserAuthenticated()) {
            $location.path('/view1');
        }

        // attempt login to your api
        $scope.attemptLogin = function() {
         
            var url = "ajax/login.php";
          
            var data = {name:$scope.name,password:$scope.password};  
  
            var ajaxRequest = $http.post(url,data);
            
            ajaxRequest.success(function(data){
                $log.info(data);
                if (data === '"OK"'){
                         
                    AuthService.setUserAuthenticated(true);
                    $location.path('/view1');
                }
                else{
                    AuthService.setUserAuthenticated(false);
                    $location.path('/login');
                }
                
        });

            
        };
    }]);

myControllers.controller('sipStationCtrl', function($scope, $http, $location) {
 
 
   // Load all devices
  $scope.getSipStations = function (){  
      
    $scope.loading = true;
    $http.get("ajax/getSipStations.php").success(function(data){

        $scope.sipStations = data;
        $scope.loading = false;
    });
 };
 
 $scope.removeDevice = function (id,name){
     $http.get("ajax/removeDevice.php?id=" + id + "&name=" + name).success(function(data){

     $scope.getSipStations();
     
 });
 };
 
$scope.getSipStations();
 
});

myControllers.controller( 'addNewAccountCtrl', function ($scope, $modal, $http) {

  var url = "ajax/addNewAccount.php";
  
  $scope.addButtonClicked = function (data) {


  var data = $scope.form;  
  
  var ajaxRequest = $http.post(url,data);
  
  ajaxRequest.success(function(data){
  
  $scope.status = data; 
      var modalInstance = $modal.open({
      templateUrl: 'myModalContent.html',
      controller: ModalInstanceCtrl,
      resolve: {
      status: function () {
          return $scope.status;
        }
      }
    });
  });
  };
 });
 
 myControllers.controller( 'statisticsCtrl', function ($scope, $log, $http, $interval) {
    $scope.getActiveCalls = function (){  
        
        $http.get("ajax/getActiveCalls.php").success(function(data){
        $log.info(data);
            $scope.activeCalls = data['calls'];
        });
        
 };
 
 $scope.getSipRegistrations = function (){  
        
        $http.get("ajax/getSipRegistrations.php").success(function(data){
        $log.info(data);
            $scope.onlinePeers = data['online'];
        });
        
 };
 $scope.getCpuLoad = function (){  
        
        $http.get("ajax/getCpuLoad.php").success(function(data){
        $log.info(data);
            
            $scope.cpuLoad = 100-data['idle'];
            
        });
        
 };
  $scope.getCpuLoad();
    $scope.getActiveCalls();
    $scope.getSipRegistrations();
 $interval(function() {
     $scope.getCpuLoad();
    $scope.getActiveCalls();
    $scope.getSipRegistrations();
 },10000);
 
    
 });
 
 


// Please note that $modalInstance represents a modal window (instance) dependency.
// It is not the same as the $modal service used above.

var ModalInstanceCtrl = function ($scope, $modalInstance, status) {

 $scope.status = status;

  $scope.ok = function () {
    //$modalInstance.close($scope.selected.item);
    $modalInstance.close();
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
};
 
