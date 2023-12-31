$(document).ready(function(){	

	var issuedBookRecords = $('#issuedBookListing').DataTable({
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
			url:"issue_books_action.php",
			type:"POST",
			data:{action:'listIssuedBook'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 8, 9],
				"orderable":false,
			},
		],
		"pageLength": 10


		
	});	


	new $.fn.dataTable.Buttons( issuedBookRecords, {
		buttons: [
			'copy', 'excel', 'pdf',
			
		]
	} );
	issuedBookRecords.buttons().container()
    .appendTo( $('.col-sm-6:eq(0)', issuedBookRecords.table().container() ) );
	

	
	$('#issueBook').click(function(){
		$('#issuedBookModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#issuedBookModal").on("shown.bs.modal", function () {
			$('#issuedBookForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Issue book");					
			$('#action').val('issueBook');
			$('#save').val('Save');
		});
	});		
	
	$("#issuedBookListing").on('click', '.update', function(){
		var issuebookid = $(this).attr("id");
		var action = 'getIssueBookDetails';
		$.ajax({
			url:'issue_books_action.php',
			method:"POST",
			data:{issuebookid:issuebookid, action:action},
			dataType:"json",
			success:function(respData){				
				$("#issuedBookModal").on("shown.bs.modal", function () { 
					$('#issuedBookForm')[0].reset();
					respData.data.forEach(function(item){
						$('#issuebookid').val(item['issuebookid']);							
						$('#book').val(item['bookid']);						
						$('#users').val(item['userid']);
						$('#expected_return_date').val(item['expected_return_date']);
						$('#return_date').val(item['return_date_time']);						
						$('#status').val(item['status']);						
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit issued book");
					$('#action').val('updateIssueBook');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#issuedBookModal").on('submit','#issuedBookForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"issue_books_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#issuedBookForm')[0].reset();
				$('#issuedBookModal').modal('hide');				
				$('#save').attr('disabled', false);
				issuedBookRecords.ajax.reload();
			}
		})
	});		

	$("#issuedBookListing").on('click', '.delete', function(){
		var issuebookid = $(this).attr("id");		
		var action = "deleteIssueBook";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"issue_books_action.php",
				method:"POST",
				data:{issuebookid:issuebookid, action:action},
				success:function(data) {					
					issuedBookRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
//	getReturnReminders();
	
});




