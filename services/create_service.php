<?php
ob_start();
if(session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(!isset($_SESSION['email'])){
  header('Location: ../user/signin.php');
}
$categories = array('Food','Accomodation','Transport','Guide','Luggage');

$error['title'] = '';
$error['description'] = '';
$error['price'] = '';
$error['category'] = '';
$error['coordinates'] = '';

if(!isset($_POST['submit'])){
  $formTitle = '';
  $formDescription = '';
  $formPrice = 0;
  $formCategory = '';
  $isError = true;
}else{
  $isError = false;
  $formTitle = $_POST['title'];
  $formDescription = $_POST['description'];
  $formPrice = $_POST['price'];
  $formCategory = $_POST['category'];
  $formCoordinates = $_POST['coordinates'];

  //Title
  if(!empty($formTitle)){
    if((strlen($formTitle) < 7) || (strlen($formTitle) > 64)){
      $isError = true;
      $error['title'] = 'Title is too short or too long';
    }
  }else{
    $isError = true;
    $error['title'] = 'Enter title';
  }
  //Desc
  if(!empty($formDescription)){
    if((strlen($formDescription) < 24) || (strlen($formDescription) > 1024)){
      $isError = true;
      $error['description'] = 'Description is too short or too long';
    }
  }else{
    $isError = true;
    $error['description'] = 'Enter description';
  }
  //price
  if(!empty($formPrice)){
    if((strlen($formPrice) < 1) || (strlen($formPrice) > 32)){
      $isError = true;
      $error['price'] = 'Price is too big or too little';
    }else{
      $decimalSelector = array(
        '.',
        ',',
        '-',
        ':'
      );
      $formPrice = str_replace($decimalSelector,'',$formPrice);
    }
  }else{
    $isError = true;
    $error['price'] = 'Enter price';
  }
  //category
  if(!empty($formCategory)){
    if((strlen($formCategory) < 1) || (strlen($formCategory) > 32)){
      $isError = true;
      $error['category'] = 'Category is too small or too big';
    }else{
      if(!array_key_exists($formCategory,$categories)){
        $isError = true;
        $error['category'] = 'Unknown category';
      }
    }
  }else{
    $isError = true;
    $error['category'] = 'Select category';
  }
  if(!empty($formCoordinates)){
    /*$formCoordinates = str_replace('(','{lat:',$formCoordinates);
    $formCoordinates = str_replace(',',',lng:',$formCoordinates);
    $formCoordinates = str_replace(')','}',$formCoordinates);*/
    //die($formCoordinates);
  }else{
    $isError = true;
    $error['coordinates'] = 'Select location';
  }
  if(!$isError){

    require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/Service/AddService.class.php');
    $service = new Service();
    $serviceResult = $service->addNewService($_SESSION['email'],$formTitle,$formDescription,$formPrice,$formCategory,$formCoordinates);
    if($serviceResult != false){
      unset($_POST);
      header('Location: service.php?id='.$serviceResult);
    }else{
      die('Error 007');
    }
  }
}
?>
<html>
  <head>
    <style>
      #map{
        height:400px;
        width:50%;
      }
    </style>
    <title>New service</title>
  </head>
  <div class="Signup-form">
    <h1>Welcome. Create new service here!</h1>
    <h>Position marker on place where you want your client to arrive</h>
    <div id="map"></div>
    <script>
      function initMap(){
        function getLocation() {
          var errorField = document.getElementById("noPermissionError");
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setMarkerPosition);
          } else {
            errorField.innerHTML = "Geolocation is not supported.";
          }
        }
        getLocation();

        var mapOptions = {
          zoom:10,
          center:{lat:54.9,lng:23.9}
        }
        var map = new google.maps.Map(document.getElementById('map'),mapOptions);
        //Add Marker
        var marker = {};
        function addMarker(coordinates,content){
          var markerOptions= {
            position:coordinates,
            //icon:'https://cdn0.iconfinder.com/data/icons/tiny-icons-1/100/tiny-08-512.png'
            map:map
          }
          marker = new google.maps.Marker(markerOptions);
          var infoWindowOptions = {
            content:content
          }
          var infoWindow = new google.maps.InfoWindow(infoWindowOptions);
          marker.addListener('click',function(){
            infoWindow.open(map,marker);
          });
        }
        google.maps.event.addListener(map, 'click',
          function(event){
            document.getElementById('location').value = event.latLng;
            marker.setPosition(event.latLng);
          }
        );
        addMarker(mapOptions.center,"<h1>Location</h1><p>Service location</p>");

        function setMarkerPosition(position){
          var coordinates = {
            lat:position.coords.latitude,
            lng:position.coords.longitude
          }
          document.getElementById('location').value = "("+coordinates.lat+","+coordinates.lng+")";
          marker.setPosition(coordinates);
        }
      }
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTJO1HF2OZS-060yGn6tM6d6HHJIEtwJA&callback=initMap">
    </script>
    <form name="create-service" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">
      <p id="noPermissionError"><?php echo($error['coordinates'])?></p>
      <input name="coordinates" type="hidden" id="location"></input>
      <p><?php echo($error['title'])?></p>
      <input type="text" value="<?php echo($formTitle); ?>" name="title" placeholder="Title" maxlength="256"></input>
      <p><?php echo($error['description'])?></p>
      <textarea rows="16" cols="64" name="description" maxlength="1024"><?php echo($formDescription); ?></textarea>
      <p><?php echo($error['price'])?></p>
      <input type="number" placeholder="5.00" required name="price" min="0" value="<?php echo($formPrice); ?>" step="0.1" maxlength="32">
      <p><?php echo($error['category'])?></p>
      <select name="category" maxlength="32">
        <option></option>
        <?php
          foreach ($categories as $key => $value) {
            echo('<option value="'.$key.'">'.$value.'</option>');
          }
        ?>
      </select>
      <input type="submit" name="submit" value="Create a new service">

    </form>

  </div>
</html>
