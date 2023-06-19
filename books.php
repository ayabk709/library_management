<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Books.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(!$user->loggedIn()) {
	header("Location: index.php");
}
$book = new Books($db);
include('inc/header4.php');
?>
<title>phpzag.com : Demo Library Management System with PHP & MySQL</title>
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
<script src="js/books.js"></script>
<style>
	body {
font-size:15px;	


	}
	.row{
		margin-top:10px;
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
			<h2>Manage Books</h2> 
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-10">
						<h3 class="panel-title"></h3>
					</div>
					<div class="col-md-2" align="right">
						<button type="button" id="addBook" class="btn btn-info" title="Add book"><span class="glyphicon glyphicon-plus">Add Book</span></button>
					</div>
				</div>
			</div>
			<div class="row" >

</div>


			<table id="bookListing" class="table table-striped table-bordered">
				<thead>
					<tr>						
						<td></td>
						<th>Book</th>
						<th>ISBN</th>
						<th>Author</th>	
						<th>Publisher</th>	
						<th>Category</th>	
						<th>Rack</th>
						<th>No of copy</th>						
						<th>Status</th>	
						<th>Updated On</th>							
						<th></th>
						<th></th>					
					</tr>
				</thead>
			</table>				
			</div>
		</div>		
		<div id="bookModal" class="modal fade">
			<div class="modal-dialog">
				<form method="post" id="bookForm" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title"><i class="fa fa-plus"></i> Edit book</h4>
						</div>
						<div class="modal-body">						
							
							<div class="form-group">							
								<label for="book" class="control-label">Book</label>							
								<input type="text" name="name" id="name" autocomplete="off" class="form-control" placeholder="book name"/>
												
							</div>
							
							<div class="form-group">							
								<label for="book" class="control-label">ISBN No</label>							
								<input type="text" name="isbn" id="isbn" autocomplete="off" class="form-control" placeholder="isbn name"/>
												
							</div>
							
							<div class="form-group">							
								<label for="book" class="control-label">No of copy</label>			
								<input type="number" name="no_of_copy" id="no_of_copy" autocomplete="off" class="form-control" placeholder="No of copy"/>
							</div>
								
							<!-- <div class="form-group">							
		
								<input type="file" name="image" id="image" class="form-control" placeholder="No of copy"/>
							</div> -->
							
							<div class="form-group">							
								<label for="author" class="control-label">Author</label>
								<select name="author" id="author" class="form-control">
									<option value="">Select</option>
									<?php 
									$authorResult = $book->getAuthorList();
									while ($author = $authorResult->fetch_assoc()) { 	
									?>
									<option value="<?php echo $author['authorid']; ?>"><?php echo $author['name']; ?></option>			
									<?php } ?>									
								</select>
							</div>
							
							
							<div class="form-group">							
								<label for="publisher" class="control-label">Publisher</label>
								<select name="publisher" id="publisher" class="form-control">
									<option value="">Select</option>
									<?php 
									$publisherResult = $book->getPublisherList();
									while ($publisher = $publisherResult->fetch_assoc()) { 	
									?>
									<option value="<?php echo $publisher['publisherid']; ?>"><?php echo $publisher['name']; ?></option>			
									<?php } ?>									
								</select>
							</div>

							<div class="form-group">							
								<label for="category" class="control-label">Category</label>
								<select name="category" id="category" class="form-control">
									<option value="">Select</option>
									<?php 
									$categoryResult = $book->getCategoryList();
									while ($category = $categoryResult->fetch_assoc()) { 	
									?>
									<option value="<?php echo $category['categoryid']; ?>"><?php echo $category['name']; ?></option>			
									<?php } ?>									
								</select>
							</div>								
						
							<div class="form-group">							
								<label for="rack" class="control-label">Rack</label>
								<select name="rack" id="rack" class="form-control">
									<option value="">Select</option>
									<?php 
									$rackResult = $book->getRackList();
									while ($rack = $rackResult->fetch_assoc()) { 	
									?>
									<option value="<?php echo $rack['rackid']; ?>"><?php echo $rack['name']; ?></option>			
									<?php } ?>									
								</select>
							</div>	
							<div class="form-group">
        <label for="photo">Book Photo:</label>
        <input type="file" name="photo" id="photo">
    </div>
							
							<div class="form-group">
								<label for="status" class="control-label">Status</label>							
								<select class="form-control" id="status" name="status"/>
									<option value="">Select</option>							
									<option value="Enable">Enable</option>
									<option value="Disable">Disable</option>								
								</select>							
							</div>				
							
											
						</div>
						<div class="modal-footer">
							<input type="hidden" name="bookid" id="bookid" />					
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

