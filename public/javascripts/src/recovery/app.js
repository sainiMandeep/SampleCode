'use strict';
var recoveryModule = angular.module('recovery',[]).constant('uiDateConfig', {});
recoveryModule.directive('uiDate', function() {
	return {
		restrict : 'A',
		scope : {
			model : '=',
			compare : '='
		},
		link : function(scope, elem, attrs) {
			var element = angular.element(elem);
			element.datepicker({
				endDate : '1d',
				"autoclose" : true
			});
			element.change(function() {
				if (scope.compare && (new Date(scope.compare) > new Date($(this).val())) && $(this).val()!=0) {					
					element.datepicker("setDate", new Date(scope.compare));
					scope.$apply(function() {						
						scope.model = element.val();
					});
				} else {
					scope.$apply(function() {						
						scope.model = element.val();
					});
				}
			})
			scope.$watch('model', function(newVal, oldVal) {
			          if (newVal) 
			        	  element.datepicker("setDate", new Date(newVal));			          
			  });			
		},
	}
});

