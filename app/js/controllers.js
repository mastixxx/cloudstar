'use strict';

/* Controllers */


var myControllers = angular.module('myApp.controllers', []);

myControllers.controller('sipStationCtrl', function($scope, $http) {
 
   // Load all available tasks 
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
 
