<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['email'])){
  echo($_SESSION['email']);
}
?>
<html>
  <a href="user/signin.php">Signin</a></br>
  <a href="user/signup.php">Signup</a>
</html>
