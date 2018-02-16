$(document).ready(function() {
    getproducts();

});

function getproducts() {
    $.get("/products/list")
        .success(function(data, textStatus, jqXHR) {
            setupTable(data);

            $("#items-page").removeClass('hidden');
            $('#loader_image').hide();
        });
};
var setupTable = function(items) {
    var tableData = [];
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        tableData.push([
            item.recovery_item_id,
            item.name,
            item.category,
            item.item_number,
            parseFloat(item.processing_cost).toFixed(2)            
        ]);
    }
    $('#itemTable').dataTable({
        'bSort': false,
        data: tableData,
        iDisplayLength: 25,  
        bProcessing: false,
        bJQueryUI: !1,
        bAutoWidth: !1,       
        createdRow: function(row, rowData, index) {
            $('.processing-cost-field', row).editable({
                type: 'text',
                title: 'Edit Processing Cost',
                deferRender: true,
                params: function(params) {
                    return {
                        id: params.pk,
                        value: params.value,
                        field: 'processing-cost'
                    }
                },
                url: '/products/save',
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
        columns: [{
            title: 'id',
            className: 'hidden',
        }, {
            title: 'Product',
            className: 'dt-body-center',
        }, {
            title: 'Category',
            className: 'dt-body-center',
        }, {
            title: 'Item Number',
            className: 'dt-body-center'            
        }, {
            title: 'Processing Cost',
            className: 'dt-body-center',
            render: function(data, type, row) {            
                return '$<a href="#" class="processing-cost-field" data-pk="' + row[0] + '">'+data+'</a>';
            }
        }]
    })
};