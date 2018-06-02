<?php
  if(session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  if(isset($_SESSION['email'])){
    unset($_SESSION['email']);
    header('Location: ../index.php');
  }else{
    header('Location: signin.php');
  }
  session_destroy();
?>
