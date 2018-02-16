var currentEmployee;
function updateEventEmployee(){
	$(document).on("click", ".remove", function() {		
		currentEmployee = $(this).parents('tr');
		$("#modal-delete").modal('show');
	});	
	$("#delete-modal-save").click(function() {
		var token = currentEmployee.attr('id');	
		$.ajax({
			url : '/employee/remove/token/' + token,
			success : function(data) {
				currentEmployee.fadeOut();
			}
		});
		$("#modal-delete").modal('hide');
	});
	$("#dTableEmployee").dataTable({
		bJQueryUI: !1,
		bAutoWidth: !1,
		sPaginationType: "full_numbers",
		sDom: '<"table-header"fl>t<"table-footer"ip>'
	});
}

function notification(message,status,time){
	if(typeof status == 'undefined' || status == '' )
	status = 'info';
	if(typeof message == 'undefined' && message != '')
		return;
	$("#notification").html("<div class='alert alert-" + status + "'><button type='button' class='close' data-dismiss='alert'>&times;</button><h8 class='center'>" + message +"</h8></div>");
	if(typeof time != 'undefined' && time != 0 ){
		setTimeout(function(){ 
			$("#notification .alert").fadeOut('slow'); 
	  }, time ); 
	}	
}
$(document).ready(function() {
	$("#add_employee").click(function() {
		$.get("/employee/create", function(data) {
			$("#modal-form").html(data);
			$("#modal-form").modal("show");
		});
	});
	$("#add_bin").click(function(){
		$.get("/bin/create",function (data){
			$("#modal-form").html(data);
			$("#modal-form").modal("show");
		})
	});	
	// $(document).on('click','.edit_location',function() {
	// 	var currentBin = $(this).parents('tr').attr("id");
	// 	var currentlocation = $(this).parent('td').attr('id');		
	// 		$.get("/bin/editlocation/token/"+currentBin + "/num/"+currentlocation, function(data){
	// 		$("#modal-simple").html(data);
	// 		$("#modal-simple").modal('show');
	// 	});
	// });
});  

