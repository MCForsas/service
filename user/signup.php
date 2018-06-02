<?php
  session_start();
  if(isset($_SESSION['email'])){
    header('Location: home.php');
  }
  $error['username'] = '';
  $error['userEmail'] = '';
  $error['userPassword'] = '';
  $error['userPasswordConfirmation'] = '';
  if(!isset($_POST['submit'])){
    $formUserName = '';
    $formUserEmail = '';
    $formUserPassword = '';
    $formUserPasswordConfirmation = '';
    $isError = true;
  }else{
    $isError = false;
    require_once($_SERVER['DOCUMENT_ROOT'].'/e/Classes/User/AddUser.class.php');

    $formUserName = $_POST['username'];
    $formUserEmail = $_POST['userEmail'];
    $formUserPassword = $_POST['userPassword'];
    $formUserPasswordConfirmation = $_POST['userPasswordConfirmation'];
    //User name
    if(!empty($formUserName)){
      if((strlen($formUserName) < 4) || (strlen($formUserName) > 64)){
        $isError = true;
        $error['username'] = 'User name is too short or too long';
      }
    }else{
      $isError = true;
      $error['username'] = 'Enter username';
    }
    //User email:
    if(!empty($formUserEmail)){
      if((strlen($formUserEmail) < 4) || (strlen($formUserEmail) > 256)){
        $isError = true;
        $error['userEmail'] = 'Email is too short or too long';
      }else{
        if(!filter_var($formUserEmail, FILTER_VALIDATE_EMAIL)){
          $isError = true;
          $error['userEmail'] = 'Email is invalid';
        }
      }
    }else{
      $isError = true;
      $error['userEmail'] = 'Enter email';
    }
    //User password
    if(!empty($formUserPassword)){
      if((strlen($formUserPassword) < 6) || (strlen($formUserPassword) > 256)){
        $isError = true;
        $error['userPassword'] = 'Password is too short or too long';
      }
    }else{
      $isError = true;
      $error['userPassword'] = 'Enter password';
    }

    //User password Confirmation
    if(!empty($formUserPasswordConfirmation)){
      if($formUserPasswordConfirmation != $formUserPassword){
        $isError = true;
        $error['userPasswordConfirmation'] = 'Passwords do not match';
      }
    }else{
      $isError = true;
      $error['userPasswordConfirmation'] = 'Confirm password';
    }

    if(!$isError){
      $user = new User();
      if($user->checkIfUserNameExists($formUserName)){
        $isError = true;
        $error['username'] = 'This username already exist';
      }
      if($user->checkIfUserEmailExists($formUserEmail)){
        $isError = true;
        $error['userEmail'] = 'This email already exist';
      }
      if(!$isError){
        if($user->AddNewUser($formUserName,$formUserEmail,$formUserPassword)){
          unset($_POST);
          die('All good');
        }else{
          die('All bad');
        }
      }
    }
  }
?>
<html>
  <head>
    <title>Singup</title>
  </head>
  <div class="Signup-form">
    <h>Welcome. Sign-up here!</h>
    <form name="sign-up" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">
      <p><?php echo($error['username'])?></p>
      <input type="text" value="<?php echo($formUserName); ?>" name="username" placeholder="Username" maxlength="64"></input>
      <p><?php echo($error['userEmail'])?></p>
      <input type="text" value="<?php echo($formUserEmail); ?>" name="userEmail" placeholder="Email" maxlength="256"></input>
      <p><?php echo($error['userPassword'])?></p>
      <input type="password" value="<?php echo($formUserPassword); ?>" name="userPassword" placeholder="Password" maxlength="256"></input>
      <p><?php echo($error['userPasswordConfirmation'])?></p>
      <input type="password" value="<?php echo($formUserPasswordConfirmation); ?>" name="userPasswordConfirmation" placeholder="Confirm password" maxlength="256"></input>
      <input type="submit" name="submit" value="Sign-up"></input>
    </form>
  </div>
</html>
