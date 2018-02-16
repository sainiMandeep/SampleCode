recoveryModule.controller('mwsCtrl', ['$scope','$rootScope','$http', function ($scope, $rootScope, $http) {

    $scope.actions = [{ 'id': 'process', 'text': 'Processing'}, {'id': 'checkin', 'text': 'Checking In' }];
    $scope.action = $scope.actions[1];
    
    $scope.scan = {
        'serialNumber': '',
        'weight': '',
        'actionType' : '',
        'note': ''        
    }
    $scope.submitScan = function() {        
        if(!$scope.scan.serialNumber) {
            $scope.class="status-error";
            $scope.message ="Please filled out missing information.";
            return;
        }
        
        if($scope.action.id == 'process' && !$scope.scan.weight) {             
            $scope.class="status-error";
            $scope.message ="Weight is required to process the recovery.";
            return;
        }
        
        var postData = {
        serialNumber: $scope.scan.serialNumber,
        weight: $scope.scan.weight,
        actionType: $scope.action.id,
        note: $scope.scan.note
        };
        
        $http({
            method: 'POST',
            url: '/mws/process',
            data: postData,
            headers: { 'Content-Type': 'application/json'}
        }).success(
            function(data, status,
                headers, config) {
                if (data) { 
                    $scope.message = data.message;
                    $scope.class = 'status-success';
                    $scope.resetForm($scope.scan);
                }
            }).error(
            function(data, status,
                headers, config) {
                $scope.message = data.message;                
                $scope.class = 'status-error';
            });             
    }
    
    $scope.resetForm = function() {
        $scope.scan = {};
    }

 }]);
