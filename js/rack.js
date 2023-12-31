$(document).ready(function(){	

	var rackRecords = $('#rackListing').DataTable({
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-primary' },
            { extend: 'excel', className: 'btn btn-primary' },
            { extend: 'pdf', className: 'btn btn-primary' }
        ],

        "searching": true,
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": false,		
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"rack_action.php",
			type:"POST",
			data:{action:'listRack'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	
	new $.fn.dataTable.Buttons( rackRecords, {
		buttons: [
			'copy', 'excel', 'pdf',
			
		]
	} );
	rackRecords.buttons().container()
    .appendTo( $('.col-sm-6:eq(0)', rackRecords.table().container() ) );
	

	$('#addRack').click(function(){
		$('#rackModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#rackModal").on("shown.bs.modal", function () {
			$('#rackForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add rack");					
			$('#action').val('addRack');
			$('#save').val('Save');
		});
	});		
	
	$("#rackListing").on('click', '.update', function(){
		var rackid = $(this).attr("id");
		var action = 'getRackDetails';
		$.ajax({
			url:'rack_action.php',
			method:"POST",
			data:{rackid:rackid, action:action},
			dataType:"json",
			success:function(respData){				
				$("#rackModal").on("shown.bs.modal", function () { 
					$('#rackForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#rackid').val(item['rackid']);						
						$('#name').val(item['name']);	
						$('#status').val(item['status']);						
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit rack");
					$('#action').val('updateRack');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#rackModal").on('submit','#rackForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"rack_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#rackForm')[0].reset();
				$('#rackModal').modal('hide');				
				$('#save').attr('disabled', false);
				rackRecords.ajax.reload();
			}
		})
	});		

	$("#rackListing").on('click', '.delete', function(){
		var rackid = $(this).attr("id");		
		var action = "deleteRack";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"rack_action.php",
				method:"POST",
				data:{rackid:rackid, action:action},
				success:function(data) {					
					rackRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
	
});