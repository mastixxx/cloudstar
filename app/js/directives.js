'use strict';

/* Directives */


var myDirectives = angular.module('myApp.directives', []);

myDirectives.directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]);

