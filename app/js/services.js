'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
  value('version', '0.1');

angular.module('myApp.services', [])
          .service('AuthService', [function(){
        var userIsAuthenticated = false;

        this.setUserAuthenticated = function(value) {
            userIsAuthenticated = value;
        };

        this.getUserAuthenticated = function() {
            return userIsAuthenticated;
        };

        return this;
    }])

  ;