$( document ).ready(function() {
   $('.start').datepicker({
        endDate:'id',
        autoclose:true
    }).on('changeDate',function(e){
        $('.end').datepicker('setStartDate',e.date)
    });

    $('.end').datepicker({
        startDate:'id',
        autoclose:true
    }).on('changeDate',function(e){
        $('.start').datepicker('setEndDate',e.date)
    });
    $('.report').DataTable({
        "pageLength": 50,        
        bJQueryUI: !1,
        bAutoWidth: !1,
        bPaginate: true,        
        "aaSorting": [[0]],         
        "aoColumns": [
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false }
        ],        
        dom: 'Bfrtip',        
        buttons: [        
            {   
                extend: 'excel',
                text: 'Export Excel',
                filename: 'MWSRecoveryReport',
                title: 'MWS Recovery Report'                
            }
        ]
    });
});