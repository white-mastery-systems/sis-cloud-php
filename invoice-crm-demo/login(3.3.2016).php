<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Process</title>
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
     <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/login.css" type="text/css" rel="stylesheet" media="screen">
      <link href="css/style.css" type="text/css" rel="stylesheet" media="screen">
   <link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
     <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
<!-- viewport meta to reset iPhone inital scale -->
<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" charset="utf-8" />
</head>


<body class="cyan loaded">
  <!-- Start Page Loading -->
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
  <!-- End Page Loading -->


<?php 

                                $errors = array(
                                    1=>"Invalid user name or password, Try again",
                                    2=>"Please login to access this area"
                                  );

                                $error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

                               
                               ?>
  <div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
      <form class="login-form" action="logincode.php" method="post">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="images/loginlogo.png" alt="S.I.S" class="responsive-img valign profile-image-login">
            <p class="center login-form-text">South India Shelters Pvt.Ltd</p>
            <p>&nbsp;</p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="material-icons prefix">person</i>
        
           	<input type="text" name="username" id="username" required placeholder="username"/>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="material-icons prefix">lock</i>
            <input type="password" name="password" id="password" required  placeholder="Password" />
          
            <span  id="err"><?php  if ($error_id == 1) {
                                        echo '<p class="text-danger">'.$errors[$error_id].'</p>';
                                    }elseif ($error_id == 2) {
                                        echo '<p class="text-danger">'.$errors[$error_id].'</p>';
                                    }?> </span>
          </div>
        </div>
        <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me">
              <label for="remember-me">Remember me</label>
          </div>
           <p>&nbsp;</p>
        </div>
        <div class="row">
          <div class="input-field col s12">          
           <button type="submit" class='inkMe btn waves-effect waves-light col s12' ink-color='bgWhite'>Login</button>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6 l6">
            <p class="margin medium-small">&nbsp;</p>
          </div>
          <div class="input-field col s6 m6 l6">
              <p class="margin right-align medium-small"><a href="#">Forgot password ?</a></p>
          </div>          
        </div>

      </form>
    </div>
  </div>



  <!-- ================================================
    Scripts
    ================================================ -->

  <!-- jQuery Library -->
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript" src='js/ripple.js'></script>

<div class="hiddendiv common"></div></body>
</body>
</html>