<?php
/*
  Class used to get and or modify user account info.
*/
class Service{
  protected $database;
  private $serviceData;
  function __construct(){
    require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/Database/Database.class.php');
    $this->database = new Database();
  }
  //
  public function addNewService($email,$title,$description,$price,$category,$coordinates){
    require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/User/User.class.php');
    $user = new User();
    if(!$user->checkIfUserEmailExists($email)){
      die('Error006');
    }
    $uniqueId = $this->database->generateUniqueId(8);
    $lat = $this->getSubstringBetween($coordinates,'(',',');
    $lng = $this->getSubstringBetween($coordinates,',',')');
    //die($lng.'--'.$lat);
    $query =
      'INSERT INTO services (code,creatorEmail,title,description,category,price,lat,lng)
      VALUES(:code,:creatorEmail,:title,:description,:category,:price,:lat,:lng)';
    $values = array(
      ':code' => $uniqueId,
      ':creatorEmail' => $email,
      ':title' => $title,
      ':description' => $description,
      ':category' => $category,
      ':price' => $price,
      ':lat' => $lat,
      ':lng' => $lng
    );
    $result = $this->database->executeQuery($query,$values);
    if(!$result){
      return $uniqueId;
    }else{
      return false;
    }
  }

  private function getSubstringBetween($string,$start,$end){
    $r = explode($start, $string);
    if (isset($r[1])){
     $r = explode($end, $r[1]);
     return $r[0];
    }
    return false;
  }

  public function getData($id){
    $query = 'SELECT * FROM services WHERE code= :code';
    $values = array(
      ':code' => $id
    );
    $result = $this->database->executeQuery($query,$values);
    if(!empty($result)){
      $this->serviceData = $result;
    }else{
      //$this->serviceData = $result;
      die('error 004');
    }
  }

  public function searchService($search){
    $search = "%{$search}%";
    $query = 'SELECT * FROM services WHERE title LIKE :title';
    $values = array(
      ':title' => $search
    );
    $result = $this->database->executeQuery($query,$values);
    if(!empty($result)){
      return($result);
    }else{
      //return($result);
      die('error 004');
    }
  }

  public function searchServiceByLocation($coordinates,$distance,$amount){
    $query = 'SELECT code, (3959 * acos( cos( radians(:latitude) ) * cos( radians( lat ) )
      * cos( radians( lng ) - radians(:longtitude) ) + sin( radians(:latitude) ) * sin(radians(lat)) ) ) AS distance
      FROM services
      HAVING distance < :desiredDistance
      ORDER BY distance LIMIT '.intval($amount);

      $lat = $this->getSubstringBetween($coordinates,'(',',');
      $lng = $this->getSubstringBetween($coordinates,',',')');

    $values =  array(
      ':latitude' => $lat,
      ':longtitude' => $lng,
      ':desiredDistance' => $distance
    );


    $result = $this->database->executeQuery($query,$values);
    if(!empty($result)){
      return $result;//['code'];
    }else{
      die('error 004');
    }
  }

  public function selectTopServices(){

  }
  public function incrementView(){
    $query = 'UPDATE services SET views = views+1 WHERE id = :id';
    $values = array(
      ':id' => $this->serviceData['id']
    );
    $result = $this->database->executeQuery($query,$values);
    if($result){
      return false;
    }else{
      return true;
    }
  }
  public function getTitle(){
    return $this->serviceData['title'];
  }
  public function getDescription(){
    return $this->serviceData['description'];
  }
  public function getPrice(){
    return $this->serviceData['price'];
  }

}
?>
