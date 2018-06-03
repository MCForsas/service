<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/Service/Service.class.php');

  {
  //
  $ip = '78.58.186.99';
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.ipdata.co/".$ip);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Accept: application/json"
  ));

  $response = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($response,true);
  //print_r($data);
  $lat = $data['latitude'];
  $lng = $data['longitude'];
  }

  //Create new array of $services
  $listSize = 10;
  $services = new Service();
  $location = $services->searchServiceByLocation(/*"({$lat},{$lng})"*/"(45,45)",100000,10);
  //$services->getData($location);

  /*for($i = 0; $i < $listSize; $i++) {
    $services[$i] = new Service();
    $location = $services[$i]->searchServiceByLocation("({$lat},{$lng})",10,1);
    $services[$i]->getData($location);
    echo($services[$i]->getTitle().'</br>');
  }*/


  //echo($services[0]->getTitle());
?>
