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
<title>Dashboard</title>
<title>Dashboard</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<style>
  .col-md-9 h2{
  padding-top: 50px;
  padding-bottom: 50px;
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
		<h2>Dashboard</h2>
        <div class="row mb-3">		
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-success">
              <div class="card-block bg-success">
                <div class="rotate">
                <i class="fas fa-book fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Total Books</h6>
                <h1 class="display-1"><a href="books.php"><?php echo $book->getTotalBooks(); ?></a></h1>
              </div>
            </div> 
          </div>        
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-info">
              <div class="card-block bg-info">
                <div class="rotate">
                <i class="fas fa-check-circle fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Available Books</h6>
                <h1 class="display-1"><a href="books.php"><?php 
                if($book->getTotalBooks() - $book->getTotalIssuedBooks()>0)
                  echo ($book->getTotalBooks() - $book->getTotalIssuedBooks()); 
                  else
                  echo ("0")?></a></h1>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-warning">
              <div class="card-block bg-warning">
                <div class="rotate">
                
                <i class="fas fa-arrow-left fa-5x"></i>

                </div>
                <h6 class="text-uppercase">Returned Books</h6>
                <h1 class="display-1"><a href="books.php"><?php echo $book->getTotalReturnedBooks(); ?></a></h1>
              </div>
            </div>
          </div>
		  <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-danger">
              <div class="card-block bg-danger">
                <div class="rotate">
                  <i class="fa fa-list fa-4x"></i>
                </div>
                <h6 class="text-uppercase">Issued Books</h6>
                <h1 class="display-1"><a href="issue_books.php"><?php echo $book->getTotalIssuedBooks(); ?></a></h1>
              </div>
            </div>
          </div>
        </div>
        <hr>       
        <div class="row mb-3">		
 <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-success">
              <div class="card-block bg-primary">
                <div class="rotate">
                <i class="fas fa-tags fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Total Categories</h6>
                <h1 class="display-1"><a href="category.php" style="color:#87CEEB;"><?php echo $book->getTotalCat(); ?></a></h1>
                  </div>
            </div> 
   </div>       

<div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-info">
              <div class="card-block bg-secondary">
                <div class="rotate">
              
        <i class="fas fa-boxes fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Total racks</h6>
                <h1 class="display-1"><a href="rack.php"><?php echo $book->getTotalRack(); ?></a></h1>
              </div>
            </div>
          </div> 

          <div class="col-xl-3 col-lg-6">
  <div class="card card-inverse card-info">
    <div class="card-block bg-info">
      <div class="rotate">
        <i class="fa fa-user fa-5x"></i>
      </div>
      <h6 class="text-uppercase">Total publishers</h6>
      <h1 class="display-1"><a href="publisher.php"><?php $publishers = $book->getPublisher(); echo count($publishers); ?></a></h1>
    </div>
  </div>
 </div>

 <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-success">
              <div class="card-block bg-success">
                <div class="rotate">
              
        <i class="fas fa-book-reader fa-5x"></i>
                </div>
                              <h6 class="text-uppercase">Registred users</h6>
                <h1 class="display-1"><a href="user.php"><?php echo $book->getTotalusersList(); ?></a></h1>
                  </div>
            </div> 
   </div>  

   </div>
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