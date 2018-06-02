<?php
  $id = $_GET['id'];
  if(empty($id)){
    die('Malformed URL');
  }else {
    if(strlen($id) != 8){
      die('Malformed URL');
    }
  }
  require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/Service/Service.class.php');
  $service = new Service();
  $service->getData($id);
  $service->incrementView();
  echo($service->getTitle());
?>
