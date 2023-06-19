<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(!$user->loggedIn()) {
	header("Location: index.php");
}
include('inc/header4.php');
?>
<title>User Page</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>" />
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">


<!-- CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">



<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="js/user.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
	.form-control{
  margin-top: 10px;
 margin-bottom: 2px;
 
}

</style>
</head>
<body>
<?php include('top_menus.php'); ?>
  <div class="container-fluid" id="main">
  <div class="row">
    <div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
     
    <?php include('left_menus.php'); ?>
      </div>     
      <div class="col-md-9 col-lg-10 main"> 
			<h2>Users</h2>
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-10">
						<h3 class="panel-title"></h3>
					</div>
					<div class="col-md-2" align="right">
						<button type="button" id="addUser" class="btn btn-info" title="Add user"><span class="glyphicon glyphicon-plus">Add</span></button>
					</div>
				</div>
			</div>			
			<table id="userListing" class="table table-striped table-bordered">
				<thead>
					<tr>						
						<th>Sn.</th>					
						<th>Name</th>					
						<th>Email</th>
						<th>Role</th>						
						<th></th>
						<th></th>					
					</tr>
				</thead>
			</table>				
			</div>
		</div>		
		<div id="userModal" class="modal fade">
			<div class="modal-dialog">
				<form method="post" id="userForm">
					<div class="modal-content">
						<div class="modal-header">
							<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
							<h4 class="modal-title"><i class="fa fa-plus"></i> Edit User</h4>
						</div>
						<div class="modal-body">						
							
							<div class="form-group">
								<label for="country" class="control-label">Role</label>							
								<select class="form-control" id="role" name="role"/>
									<option value="">Select Role</option>							
									<option value="admin">Admin</option>
									<option value="user">User</option>								
								</select>							
							</div>
							
							<div class="form-group">							
								<label for="Income" class="control-label">First Name</label>							
								<input type="text" name="first_name" id="first_name" autocomplete="off" class="form-control" placeholder="first name"/>
												
							</div>
							
							<div class="form-group"
								<label for="project" class="control-label">Last Name</label>
								<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" >			
							</div>	

							<div class="form-group"
								<label for="project" class="control-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" >			
							</div>
							
							<div class="form-group"
								<label for="project" class="control-label">New Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="password" >			
							</div>
											
						</div>
						<div class="modal-footer">
							<input type="hidden" name="id" id="id" />						
							<input type="hidden" name="action" id="action" value="" />
							<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</form>
			</div>
		</div>	
	</div>
	<!-- <script>   
    $(document).ready(function () {  
  var trigger = $('.hamburger'),  
      overlay = $('.overlay'),  
     isClosed = false;  
    trigger.click(function () {  
      hamburger_cross();        
    });  
    function hamburger_cross() {  
      if (isClosed == true) {            
        overlay.hide();  
        trigger.removeClass('is-open');  
        trigger.addClass('is-closed');  
        isClosed = false;  
      } else {     
        overlay.show();  
        trigger.removeClass('is-closed');  
        trigger.addClass('is-open');  
        isClosed = true;  
      }  
  }  
  $('[data-toggle="offcanvas"]').click(function () {  
        $('#wrapper').toggleClass('toggled');  
  });    
 });  
    </script>   -->
	<script>
      $(document).ready(function() {
    var trigger = $('.hamburger'),
        overlay = $('.overlay'),
        isClosed = false;

    // Add the following line to automatically open the menu on page load
    $('#wrapper').addClass('toggled');

    trigger.click(function() {
        hamburger_cross();
    });

    function hamburger_cross() {
        if (isClosed == true) {
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').click(function() {
        $('#wrapper').toggleClass('toggled');
    });
});

    </script>
</body>
</html>

