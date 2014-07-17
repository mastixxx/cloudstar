'use strict';


myControllers.controller( 'addNewAccountCtrl', function ($scope, $modal, $http, $log) {

  
  
  $scope.isCustomerSelected = false;

  $scope.isSelectedCustomerNew = false;

  $scope.isDataForCustomerReady = false;

   $scope.selectedIndex = -1;
  $scope.devices = [{name:"",password:"",index:0}];
  
  $http.get("ajax/getCustomers.php").success(function(data){

        $scope.actualCustomers = data;
        $scope.selectedCustomer = data[0].name;
        getDevicesForCustomer();
        
    });

  $scope.modifiedDevices = [];

  $scope.addDevice = function (){
    var i=0;
    i = $scope.devices.length;
    $http.get("ajax/addNewDeviceForCustomer.php?customerId="+ getCustomerIdWithCustomerName($scope.selectedCustomer)).success(function(data){
      $http.get("ajax/getDevicesForCustomer.php?customerName="+$scope.selectedCustomer).success(function(data){

        $scope.isCustomerSelected = true;
        $scope.devices = data;
        
      });
    });
  };

  $scope.deviceModified = function(id){

    var url = "ajax/modifyDevice.php";
    var dev;
    for (var i=0; i < $scope.devices.length; i++) {
      if ($scope.devices[i].id ==id) {
        addModifiedDeviceOnList($scope.devices[i]);

      }

    }
    
  };

  function addModifiedDeviceOnList(device){
    
    if ($scope.modifiedDevices.length>0) {

      for (var i=0; i < $scope.modifiedDevices.length; i++) {
        if ($scope.modifiedDevices[i].id==device.id) {
          //skip
        }
        else{
          $scope.modifiedDevices.push(device);    
        }
      }
    }
    //SEZNAM JE PRAZDNY ULOZ DO SEZNAMU
    else{
          $scope.modifiedDevices.push(device);    
    }
  }

  function getCustomerIdWithCustomerName(myName){

    for (var i = 0; i < $scope.actualCustomers.length; i++) {
      $log.info($scope.actualCustomers[i].name);
      $log.info(myName);
      if ($scope.actualCustomers[i].name === myName) {
        return $scope.actualCustomers[i].id;
      }
    }
  }



//SELECT CUSTOMER
  $scope.selectCustomer = function (customerName){

    $scope.selectedCustomer = customerName;
    getDevicesForCustomer();
    
  };

  $scope.removeDevice = function(id,name){
     $http.get("ajax/removeDevice.php?id=" + id + "&name=" + name).success(function(data){

     getDevicesForCustomer();
  });
};

  function getDevicesForCustomer(){
     $http.get("ajax/getDevicesForCustomer.php?customerName="+$scope.selectedCustomer).success(function(data){

      if (data) {
        $scope.isCustomerSelected = true;
        $scope.devices = data;
      }
      else{
        $scope.devices = [{name:"",password:"",index:0}];
      }
        
        
    });
  };




$scope.createNewCustomer = function(){
  $scope.newCustomer= {};
  var modalInstance = $modal.open({
      templateUrl: 'partials/createNewCustomerModal.html?u='+(new Date()).getTime(),
      controller: createNewCustomerModalInstanceCtrl,
      size: 'lg',
      resolve:{
         newCustomer: function () {
          return $scope.newCustomer;
        }
      }
  
    });

   modalInstance.result.then(function (newCustomer) {
      $log.info(newCustomer.name);

      var url = "ajax/addCustomer.php";
      var jsonData = {customerName:newCustomer.name,contact:newCustomer.contact};
      var saveChangesRequest = $http.post(url,jsonData);
     
    }, function () {
      
      
    });
  
}

var createNewCustomerModalInstanceCtrl = function ($scope, $modalInstance, newCustomer) {

  $scope.newCustomer = newCustomer;

  $scope.ok = function () {
    $modalInstance.close($scope.newCustomer);
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
};



$scope.saveChanges = function (data) {
  var url = "ajax/saveChanges.php";
  var jsonData = {devices:"",customerName:""};
  jsonData.devices = ($scope.modifiedDevices);
  jsonData.customerName = $scope.selectedCustomer;
  var saveChangesRequest = $http.post(url,jsonData);
  
  saveChangesRequest.success(function(response){
    
    $scope.modifiedDevices.length=0;

    getDevicesForCustomer();//reload page

    $scope.items = response;
    var modalInstance = $modal.open({
      templateUrl: 'partials/myModalContent.html?u='+(new Date()).getTime(),
      controller: ModalInstanceCtrl,
      size: 'lg',
      resolve: {
        items: function () {
          return $scope.items;
        }
       
      }
    });
  
  });

};
   

//MODAL PO UPDATE
var ModalInstanceCtrl = function ($scope, $modalInstance, items) {

  $scope.items = items;
  $scope.selected = {
    item: $scope.items[0]
  };

  $scope.ok = function () {
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
};

//SUBMIT DATA    
$scope.addButtonClicked = function (data) {

  //var data = $scope.form;  
  var url = "ajax/addNewAccount.php";
  var jsonData = {devices:"",customerName:$scope.customerName};
  jsonData.devices = ($scope.devices);
  var ajaxRequest = $http.post(url,jsonData);
  
  
  ajaxRequest.success(function(response){
  //var response = { createdStations: ["sdf-"], wrongStations: ["asdasd","asdasda sdas"]};
    $scope.items = response;
    var modalInstance = $modal.open({
      templateUrl: 'partials/myModalContent.html?u='+(new Date()).getTime(),
      controller: ModalInstanceCtrl,
      size: 'lg',
      resolve: {
        items: function () {
          return $scope.items;
        }
       
      }
    });
  
  });

};

});