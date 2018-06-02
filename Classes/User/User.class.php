<?php
/*
  Class used to get and or modify user account info.
*/
class User{
  protected $database;
  function __construct(){
    require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/Database/Database.class.php');
    $this->database = new Database();
  }
  public function addNewUser($userUserName,$userEmail,$userPassword){
    $hashString = $this->generateHash(256);
    /*$options = [
      'cost' => 10,
      'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];*/
    $userPassword = password_hash($userPassword,PASSWORD_ARGON2I);
    $query = 'INSERT INTO users (username,email,password,hash) VALUES(:username,:email,:password,:hash); INSERT INTO profile (email) VALUES(:email)';
    $values = array(
      ':username' => $userUserName,
      ':email' => $userEmail,
      ':password' => $userPassword,
      ':hash' => $hashString
    );
    $result = $this->database->executeQuery($query,$values);
    if($result){
      return false;
    }else{
      require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/Email/SendEmail.class.php');
      $email = new SendEmail;
      if($email->sendSignupConfirmationEmail($values[':email'],$values[':hash'],$values[':username'])){
        return true;
      }else{
        die('Error 005');
      }
    }
  }

  private function generateHash($length){
    $string = '';
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $string .= $characters[$rand];
    }
    $string = hash('sha256',$string);
    return($string);
  }

  public function activateEmail($userEmail,$userHash){
    $query = 'SELECT email_activated, hash FROM users WHERE email = :email';
    $values = array(
      ':email' => $userEmail
    );
    $result = $this->database->executeQuery($query,$values);
    if($result['email_activated'] == 0){
      if($result['hash'] == $userHash){
        $newHash = $this->generateHash(256);
        $updateQuery = 'UPDATE users SET email_activated = 1, hash = :hash WHERE email = :email';
        $updateValues = array(
          ':email' => $userEmail,
          ':hash' => $newHash
        );
        if(!$this->database->executeQuery($updateQuery,$updateValues)){
          return true;
        }else{
          return false;
        }
      }else{
        die('Wrong hash');
      }
    }else{
      die('already updated');
    }
  }

  public function checkIfUserNameExists($userName){
    $query = 'SELECT * FROM users WHERE username = :username';
    $values = array(
      ':username' => $userName
    );
    $result = $this->database->executeQuery($query,$values);
    if(empty($result)){
      return false;
    }else{
      return true;
    }
  }

  public function checkIfUserEmailExists($userEmail){
    $query = 'SELECT * FROM users WHERE email = :email';
    $values = array(
      ':email' => $userEmail
    );
    $result = $this->database->executeQuery($query,$values);
    if(empty($result)){
      return false;
    }else{
      return true;
    }
  }

  public function userLogin($userEmail,$userPassword){
    $query = 'SELECT * FROM users WHERE email = :email';
    $values = array(
      ':email' => $userEmail
    );
    $result = $this->database->executeQuery($query,$values);
    if(!empty($result)){
      if($result['email_activated'] == 0){
        die('Email not activated');
      }else{
        if(password_verify($userPassword,$result['password'])){
          if (session_status() == PHP_SESSION_NONE) {
              session_start();
          }
          $_SESSION['email'] = $userEmail;
          return true;
        }else{
          return false;
        }
      }
    }else{
      die('User doesn\'t exist');
    }
  }
}
?>
