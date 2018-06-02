<?php
/*
  Class used to connect to database and execute queries
*/
class Database{
  private $database;
  function __construct(){ //Connects to database
    require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/Configuration/Globals.php');
    $this->database = new PDO(
      'mysql:host='.DATABASE_HOST.
      ';dbname='.DATABASE_NAME,
      DATABASE_USER,
      DATABASE_PASSWORD,
      array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8') //Don't have a clue what it does
    );
  }

  function executeQuery($query,$arguments){ //executes secure query
    require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/Database/SecureInput.class.php');
    $execution = $this->database->prepare($query);
    $secureText = new SecureInput();
    foreach ($arguments as $key => $value) { //binds values
      $value = $secureText->secureText($value);
      if(!$execution->bindValue($key,$value)){
        die('Error 000');
      }
    }
    $execution->debugDumpParams();
    echo('</br>');
    if(!$execution->execute()){
      print_r($execution->errorInfo());
      die('</br>Error 001');
    }else{
      $result = $execution->fetch(PDO::FETCH_ASSOC);
      return $result;
    }
  }

  //Generates random unique id for record
  public function generateUniqueId($lenght = 13) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
  }
}
?>
