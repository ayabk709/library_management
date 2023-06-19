<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if($user->loggedIn()) {	
	header("Location: dashboard.php");	
  exit();
}

$loginMessage = '';
if(!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {	
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];	
	if($user->login()) {
		header("Location: dashboard.php");	
	} else {
		$loginMessage = 'Invalid login! Please try again.';
	}
}else {
	$loginMessage = 'Fill all fields.';
}

include('inc/header4.php');
?>
<title>index page </title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>" />
<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
	body {
		
    background-color: #0c1022;
	}
</style>
</head>
<body>
  <?php include('top_menus.php'); ?>	
  <div class="container-fluid" id="main">
    <div class="row row-offcanvas row-offcanvas-left">
     
	  <div class="col-md-9 col-lg-10 main"> 
		<div class="row mb-3">		 
		
	

<div class="panel-body" >
  <div class="riri">
  <?php
    if ($loginMessage != '' && isset($_POST["login"]) && (empty($_POST["email"]) || empty($_POST["password"]))) {
      echo '<div id="login-alert" class="alert alert-danger col-sm-12">' . $loginMessage . '</div>';
    }
    ?>
  
</div>
<div class="login_form_container">
  
      <form class="login_form" role="form" method="POST">
        <h2>Login</h2>
        <div class="input_group">
          <i class="fa fa-user"></i>
          <input
          type="text"  id="email" name="email"
            placeholder="Username"
            class="input_text"
			value="<?php if(!empty($_POST["email"])) { echo $_POST["email"]; } ?>"
          />
        </div>
        <div class="input_group">
          <i class="fa fa-unlock-alt"></i>
          <input
         
            placeholder="Password"
            class="input_text"
			type="password" id="password" name="password"
			value="<?php if(!empty($_POST["password"])) { echo $_POST["password"]; } ?>"
          />
        </div>
        <div class="button_group" id="login_button">
          
		  <input type="Submit" name="login" value="Login" >
		  <span></span>
        </div>
</form>
 </div>
	</div>    
		
        <hr>         
       </div>       
      
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="login.js"></script>
 


<!-- <script>
  document.addEventListener("DOMContentLoaded", function() {
    var loginAlert = document.getElementById("login-alert");
    var emailField = document.getElementById("email");
    var passwordField = document.getElementById("password");

    function checkFields() {
      if (emailField.value === "" || passwordField.value === "") {
        loginAlert.style.display = "block";
      } else {
        loginAlert.style.display = "none";
      }
    }

    document.getElementById("login-button").addEventListener("click", checkFields);

    // Check fields on page load
    checkFields();
  });
</script> -->

  </body>
</html>

