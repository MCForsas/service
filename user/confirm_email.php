<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/User/User.class.php');
  $userEmail = $_GET['email'];
  $userHash = $_GET['hash'];
  if(isset($_SESSION['email'])){
    header('Location: home.php');
  }
  if(empty($userHash) || empty($userEmail)){
    die('malformed url');
  }else{
    $user = new User;
    if($user->activateEmail($userEmail,$userHash)){
        die('All good');
    }else{
      die('All bad');
    }
  }
?>
