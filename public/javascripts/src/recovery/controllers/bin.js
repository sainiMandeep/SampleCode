recoveryModule.controller('binCtrl', ['$scope','$http', function ($scope,$http) {
	$scope.init = function () {       
		$scope.bins =  bins;
		for (var i = 0; i < $scope.bins.length; i++) {
			if ($scope.bins[i].is_default == 0) {
				$scope.bins[i].is_default = false;
			}
			else if ($scope.bins[i].is_default == 1) {
				$scope.bins[i].is_default = true;
			}
		}
		$scope.initial_bins = angular.copy(bins);
		$scope.updated_bins = [];
		
		$scope.waste_types =  waste_types;
		$scope.bin_types =  bin_types;
		$scope.bins_missing = {
			waste_types_bit: angular.copy($scope.waste_types),
			list: []
		}
		$scope.checkMissingBin();
	}

	$scope.$watch('bins',function(newVal, oldVal) {
		$scope.updated_bins = [];
		for (var i = 0; i < newVal.length; i++) {
			// console.log('*------*');
			// console.log(newVal[i].number_id);
			// console.log($scope.initial_bins[i].number_id);
			// console.log(newVal[i].close_date);
			// console.log($scope.initial_bins[i].close_date);
			// console.log(newVal[i].is_default);
			// console.log($scope.initial_bins[i].is_default);
			if ($scope.binComparator(newVal[i] , $scope.initial_bins[i]) != true) {
				$scope.updated_bins.push(newVal[i]);
				// console.log('diff');
			}
			// else {
			// 	console.log('idem');
				
			// }
		};
		// console.log($scope.updated_bins);
	}, true);

	$scope.binComparator = function(bin1 , bin2) {
		if (bin1.bin_id !== bin2.bin_id) return false;
		if (bin1.bin_type_id !== bin2.bin_type_id) return false;
		if (bin1.bin_type_name !== bin2.bin_type_name) return false;
		if (bin1.close_date !== bin2.close_date) return false;
		if (bin1.destruction_date !== bin2.destruction_date) return false;
		if (bin1.is_default !== bin2.is_default) return false;
		if (bin1.location_name !== bin2.location_name) return false;
		if (bin1.number_id !== bin2.number_id) return false;
		if (bin1.qty !== bin2.qty) return false;
		if (bin1.start_date !== bin2.start_date) return false;
		if (bin1.waste_type_id !== bin2.waste_type_id) return false;
		if (bin1.waste_type_name !== bin2.waste_type_name) return false;
		return true;
	}

	$scope.setDefault= function(bin){
		if (bin.is_default==0 ||bin.is_default==false){
			for (var i=0;i<$scope.bins.length;i++){
				if(
					($scope.bins[i].waste_type_name == bin.waste_type_name) &&
					($scope.bins[i].bin_id != bin.bin_id)
				){
					// console.log('update: '+$scope.bins[i].number_id)
					$scope.bins[i].is_default=false;
				}
				else if (
					($scope.bins[i].waste_type_name == bin.waste_type_name) &&
					($scope.bins[i].bin_id == bin.bin_id)
				) {
					$scope.bins[i].is_default=true;
				}
			}
		}
	}

	$scope.checkMissingBin = function() {
		// reset bins_missing
		$scope.bins_missing = {
			waste_types_bit: angular.copy($scope.waste_types),
			list: []
		}

		var $i,$j;
		for ($i=0;$i<$scope.bins.length;$i++){
		if(!$scope.bins[$i].destruction_date && $scope.bins[$i].is_default == 1) {
				for ($j=0;$j<$scope.bins_missing.waste_types_bit.length;$j++){
					if ($scope.bins_missing.waste_types_bit[$j].name == $scope.bins[$i].waste_type_name) {
						$scope.bins_missing.waste_types_bit[$j].found = true;
					}
				}
			}
		}
		for ($j=0;$j<$scope.bins_missing.waste_types_bit.length;$j++){
			if (!$scope.bins_missing.waste_types_bit[$j].found)
				$scope.bins_missing.list.push($scope.bins_missing.waste_types_bit[$j]);
		}
	}

	$scope.save = function(){
		$.post('/bin/save',{bins:$scope.updated_bins},function(data){
		});	
		var $i;
		for ($i=0;$i<$scope.bins.length;$i++){
			if($scope.bins[$i].destruction_date)
				$scope.bins[$i].isdestroyed = true;
			else
				$scope.bins[$i].isdestroyed = false;
		}

		$scope.checkMissingBin();
		notification('Successfully Saved','success',3000);
	}

	$scope.selectBin = function(bin) {
		$scope.selected_bin_ref = bin;
		$scope.selected_bin = angular.copy(bin);
		$scope.selected_bin.bin_type = _.findWhere($scope.bin_types, {bin_type_id: $scope.selected_bin.bin_type_id});
		$scope.selected_bin.waste_type = _.findWhere($scope.waste_types, {waste_type_id: $scope.selected_bin.waste_type_id});
	}


}]);

recoveryModule.controller('editBinCtrl', ['$scope','$http', function ($scope,$http) {
	$scope.save = function() {
		if ($scope.editBinForm.$valid && $scope.selected_bin.waste_type != null && $scope.selected_bin.bin_type != null) {
			$http.post('/bin/edit', $scope.selected_bin).success(function(data) {
				if (data.status == 'success') {
					$scope.selected_bin_ref.number_id = $scope.selected_bin.number_id;
					$scope.selected_bin_ref.bin_type_id = $scope.selected_bin.bin_type.bin_type_id;
					$scope.selected_bin_ref.bin_type_name = $scope.selected_bin.bin_type.name;
					$scope.selected_bin_ref.waste_type_id = $scope.selected_bin.waste_type.waste_type_id;
					$scope.selected_bin_ref.waste_type_name = $scope.selected_bin.waste_type.name;
					$scope.selected_bin_ref.location_name = $scope.selected_bin.location_name;
					$scope.checkMissingBin();
				}
				else {
					notification(data.message,'danger',12000);
				}
			});	
		}
	}

	$scope.delete = function(){
		var bin = $scope.selected_bin_ref;
		$http.post('/bin/delete',{bin_id:bin.bin_id}).success(function(data){
			if (data.status == 'success') {
				$scope.bins.splice( $scope.bins.indexOf(bin), 1 );
				$scope.checkMissingBin();
				notification('The bin has been deleted','success',3000);
			}
			else {
				notification(data.message,'danger',3000);	
			}
		});	
	}
}]);