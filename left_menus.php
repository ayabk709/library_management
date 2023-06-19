<div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
<?php if($user->isAdmin()) { ?>
<div id="wrapper">  
   <div class="overlay"></div>  
<nav class="navbar navbar-inverse fixed-top" id="sidebar-wrapper" role="navigation">  
     <ul class="nav sidebar-nav">  
       <div class="sidebar-header">  
       <div class="sidebar-brand">  
         <a href="#"> LIBRARY </a> </div>
     </div>  
     <li ><a  href="dashboard.php"><strong>Dashboard</strong></a></li>
       <li><a href="#home">Books</a></li>  
       <li><a href="books.php">Manage Books</a></li>  
       <li><a href="category.php"> Category </a> </li>  
       <li><a href="author.php"> Author </a> </li>  
    
      <li><a href="publisher.php">Publisher</a></li>  
      <li><a href="rack.php">Rack</a></li>  
      <li><a href="issue_books.php">Issued Books</a></li> 
      <li><a href="user.php"> User </a> </li>  
     
      <li><a href="logout.php">LogOut</a></li>  
      </ul>  
</nav>  
<?php } else { ?>

<?php } ?>  
        <div id="page-content-wrapper">  
            <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">  
                <span class="hamb-top"></span>  
                <span class="hamb-middle"></span>  
                <span class="hamb-bottom"></span>  
            </button>  
            
        </div>    
    </div>   
</div>




