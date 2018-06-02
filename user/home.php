<?php
if(session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(!isset($_SESSION['email'])){
  header('Location: signin.php');
}
?>
<html>
  <head>
    <title>Home</title>
  </head>
  <div class="home">
    <h1>Welcome back <?php echo($_SESSION['email'])?></h1>
    <a href="../services/create_service.php">Create a new service</a>
  </br>
    <a href="signout.php">Signout</a>
  </div>
</html>
