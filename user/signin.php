<?php
  session_start();
  if(isset($_SESSION['email'])){
    header('Location: home.php');
  }
  $error['userEmail'] = '';
  $error['userPassword'] = '';
  if(!isset($_POST['submit'])){
    $formUserEmail = '';
    $formUserPassword = '';
    $isError = true;
  }else{
    $isError = false;
    require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/User/User.class.php');
    $formUserEmail = $_POST['userEmail'];
    $formUserPassword = $_POST['userPassword'];
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

    if(!$isError){
      $user = new User();
      if(!$user->checkIfUserEmailExists($formUserEmail)){
        $isError = true;
        $error['userEmail'] = 'This email does not exist';
      }
      if(!$isError){
        if($user->userLogin($formUserEmail,$formUserPassword)){
          unset($_POST);
          if(isset($_SESSION['email'])){
            header('Location: home.php');
          }
        }else{
          $isError = true;
          $error['userPassword'] = 'Wrong password';
        }
      }
    }
  }
?>
<html>
  <head>
    <title>Signup</title>
  </head>
  <div class="Signin-form">
    <h>Welcome. Sign-in here!</h>
    <form name="sign-in" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">
      <p><?php echo($error['userEmail'])?></p>
      <input type="text" value="<?php echo(/*$formUserEmail-*/'test@mail.com'); ?>" name="userEmail" placeholder="Email" maxlength="256"></input>
      <p><?php echo($error['userPassword'])?></p>
      <input type="password" value="<?php echo(/*$formUserPassword*/'123456'); ?>" name="userPassword" placeholder="Password" maxlength="256"></input>
      <input type="submit" name="submit" value="Sign-in"></input>
    </form>
  </div>
</html>
