$(document).ready(function() {
    getCheckedinRecovery();
});
function getCheckedinRecovery() {
	$.get("/mws/bulkprocess")
    .success(function(data, textStatus, jqXHR) {
        setupTable(data);
        $("#checkedin-page").removeClass('hidden');
        $('#loader_image').hide();
	}).error(function(data, status, headers, config) {
        $('#loader_image').hide();
        $scope.message ="An error occured while grabbing data.";
        $scope.class="status-error";
        return;
    });
}

var setupTable = function(checkedinRecovery) {
	var tableData = [];
    for (var i = 0; i < checkedinRecovery.length; i++) {
        var recovery = checkedinRecovery[i];
        tableData.push([
        	recovery.recovery_id,
            recovery.serial_number,                        
            recovery.checkin_date,            
            recovery.weight,
            recovery.recovery_type
        ]);
    }

    $('#checkedinTable').dataTable({
        'bSort': false,
        data: tableData,
        iDisplayLength: 25,  
        bProcessing: false,
        bJQueryUI: !1,
        bAutoWidth: !1,       
        createdRow: function(row, rowData, index) {
            $('.weight-field', row).editable({
                type: 'text',
                title: 'Edit Weight',
                
                deferRender: true,                
                params: function(params) {                	                	
                    return {
                        id: params.pk,
                        value: params.value,
                        field: 'weight',
                    }
                },
                
                url: '/mws/weightupdate',
                success: function(response, newValue) {                    
                    if (!response.success) 
                        return response.msg;
                },
                error: function(response, newValue) {
                    if (response.status === 500) {
                        return 'Service unavailable. Please try later.';
                    } else {
                        return response.responseText;
                    }
                }
            });
        },
        columns: [
        {
        	title: '<input class="sorting_disabled" id="select-all-checkinRecovery" type="checkbox" onclick="selectAll(this)">&nbsp;Select All (on this page)',
            className: 'dt-body-left',
            render: function(data, type, row) {                
                return '<label><input class="process-checkbox" type="checkbox" name="processRecovery[]" value="' + row[0] + '"></label>';
            }
        },{
            title: 'Serial Number',
            className: 'dt-body-left',
        },{
            title: 'Checkedin Date',
            className: 'dt-body-left'            
        },{
            title: 'Weight',
            className: 'dt-body-left',
            render: function(data, type, row) {            
                return '<a href="#" maxlength="5" class="weight-field" data-pk="' + row[0] + '">'+data+'</a>';
            }
        },{
            title: 'Category',
            className: 'dt-body-left',
        }]
    })
}

 