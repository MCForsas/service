<?php
/*
ob_start();

  Class used to add new service to the website!

require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/Service/Service.class.php');
class AddService extends Service{
  //protected $database;
  function __construct(){
     parent::__construct();
   }

   private function getSubstringBetween($string,$start,$end){
     $r = explode($start, $string);
     if (isset($r[1])){
      $r = explode($end, $r[1]);
      return $r[0];
    }
    return false;
  }

}
?>
