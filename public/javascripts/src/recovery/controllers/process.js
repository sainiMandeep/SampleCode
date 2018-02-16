recoveryModule.controller('processCtrl', ['$scope','$rootScope','$http', function ($scope,$rootScope,$http) {
        $scope.quantity = 0;
    
        $scope.reset = function() {
                $scope.custom = {
                        name: '',
                        package: '',
                        category: null
                }        
        }

        $scope.init = function () {
                $rootScope.ndc = [];
                $scope.reset();
                $scope.medications = [];
                $scope.waste_types = waste_types;
                $scope.bins = bins;
                if (medications != '') {
                        $scope.medications = JSON.parse(medications);
                        update_quantity();
                        update_bins();
                        // medications = JSON.parse(medications);
                        // angular.forEach( medications, function(medication) {
                        //         alert(medication);
                        //         $scope.add(medication);
                        // });
                }
                $scope.medications_favorite = medications_favorite;
        };

        function update_bins() {
            angular.forEach( $scope.medications, function(medication) {
                var bin = findBin(medication.category);
                if (bin)
                    medication.bin = bin;
            });
        }

        $scope.search = function() {
                var $param;
                if ($rootScope.searchNdc)
                        $param = '/search/'+$rootScope.searchNdc;
                else
                        $param = '';

                $http({method: 'GET', url: '/ndc/medications'+$param}).
                  success(function(data, status, headers, config) {
                        $rootScope.ndc = data;
                  }).
                  error(function(data, status, headers, config) {
                  });
        }

        $scope.add = function (medication) {
                var bin = findBin(medication.category);
                if (!bin) 
                        return;
                $scope.quantity++;
                var medicationAdded = angular.copy(medication);
                medicationAdded.quantity = 1;
                medicationAdded.rejected = false;
                medicationAdded.bin = bin;
                $scope.medications.unshift(medicationAdded);
                // $scope.medications.push(medicationAdded);
        }

        $scope.remove = function (index) {
                $scope.quantity = $scope.quantity - $scope.medications[index].quantity;
                $scope.medications.splice(index, 1);
        }

        function findBin(category) {
                var binFound = false;
                angular.forEach( $scope.bins, function(bin) {
                        if (category == bin.category) {
                                binFound = bin;
                        }
                });
                return binFound;
        }

        function update_quantity() {
                $scope.quantity = 0;
                angular.forEach( $scope.medications, function(medication) {
                        $scope.quantity = $scope.quantity + medication.quantity;
                });
        }

        $scope.rejectAll = function(medication) {
                angular.forEach( $scope.medications, function(medication) {
                        medication.rejected = true;
                });       
        }

        $scope.toggleReject = function(medication) {
                medication.rejected = !medication.rejected;
        }

        $scope.validate_number = function(medication) {
                if (!(typeof medication.quantity === 'number' && medication.quantity % 1 == 0)) {
                        medication.quantity = 1;                        
                }
                update_quantity();
        }

        $scope.submit = function() {
                $('#medications').val(angular.toJson($scope.medications));
        }

        // $scope.unFavorite = function(medication) {
        //     $http.post('/process/unfavorite', {ndc_number: medication.ndc_number}).success(function(data) {
        //             if (data.status == 'success') {
        //                     $scope.search();
        //                     var i = 0;
        //                     medication.favorite = false;
        //                     angular.forEach( medications_favorite, function(current) {
        //                             if (current.ndc_number == medication.ndc_number) {
        //                                     $scope.medications_favorite.splice(i, 1);
        //                             }
        //                             i++;
        //                     });
        //             }
        //     });
        // }


        $scope.unFavorite = function(medication) {
            $http.post('/process/unfavorite', {id: medication.medication_favorite_id}).success(function(data) {
                    if (data.status == 'success') {
                            $scope.search();
                            var i = 0;
                            medication.favorite = false;
                            angular.forEach( medications_favorite, function(current) {
                                    if (current.medication_favorite_id == medication.medication_favorite_id) {
                                            $scope.medications_favorite.splice(i, 1);
                                    }
                                    i++;
                            });
                    }
            });
        }
}]);

recoveryModule.controller('itemsCtrl', ['$rootScope','$scope','$http', function ($rootScope, $scope,$http) {
        $scope.active = 'favorite';
        $rootScope.searchNdc = '';
        $rootScope.ndc = [];
        $scope.favorite = {};

        $scope.add = function (medication) {
                $scope.$parent.add(medication);
        }



        $scope.$watch('searchNdc', function() {
                $rootScope.searchNdc = $scope.searchNdc;
                $scope.search();
        });         

      

        $scope.addNDC = function (medication) {
                $scope.addDestination = 'item';
                $scope.favorite = medication;
                $scope.category = null;
        }

        $scope.addFavorite = function(medication) {
                $scope.addDestination = 'favorite';
                $scope.favorite = medication;
                $scope.category = null;
        }
}]);
recoveryModule.controller('addFavoriteItemCtrl', ['$scope','$http','addFavoriteService', function ($scope,$http,addFavoriteService) {
        $scope.add = function () {
                if ($scope.favorite.category) {
                        if ($scope.addDestination == 'item') {
                                $scope.$parent.add(angular.copy($scope.favorite));
                        }
                        else {
                                addFavoriteService.add($scope.favorite,function(data) {
                                        if (data.status == 'success' && data.id) {
                                                $scope.favorite.favorite = true;
                                                $scope.favorite.medication_favorite_id = data.id;
                                                $scope.medications_favorite.push(angular.copy($scope.favorite));
                                        }
                                });
                        }
                }
        }
}]);

recoveryModule.service('addFavoriteService', ['$http', function($http) {
    return {
      add: function(favorite , callback) {
          $http.post('/process/addfavorite', favorite).success(function(data) {
                  callback(data);
          });
      }
  }
}]);


recoveryModule.controller('addCustomItemCtrl', ['$scope','$rootScope','addFavoriteService', function ($scope,$rootScope,addFavoriteService) {
        $scope.add = function () {
                var valid = true;
                if ((!$scope.custom.name) || ($scope.custom.name.length > 50) || ($scope.custom.name.length == 0))
                        valid = false;
                if ((!$scope.custom.category) || ($scope.custom.category.length > 50) || ($scope.custom.category.length == 0))
                        valid = false;
                if (($scope.custom.package) && ($scope.custom.package.length > 50))
                        valid = false;
                if (valid) {
                        if ($scope.custom.favorite) {
                            addFavoriteService.add($scope.custom,function(data) {
                                    if (data.status == 'success' && data.id) {
                                            $scope.custom.favorite = true;
                                            $scope.custom.medication_favorite_id = data.id;
                                            $scope.medications_favorite.push(angular.copy($scope.custom));
                                    }
                            });
                        }
                        else
                            $scope.$parent.add($scope.custom);
                }
        }
}]);