<?php
/*
  Class used to connect to send emails
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/service/Classes/Configuration/Globals.php');
  class SendEmail{
    public function sendSignupConfirmationEmail($userEmail,$hash,$userName){
      error_reporting( E_ALL );
      $message = "Hello and thank you {$userName} for singing up at our site. Activate email with:\r\n";
      $message .= DOMAIN."/user/confirm_email.php?email={$userEmail}&hash={$hash}";
      $message = wordwrap($message,70);
      return(mail($userEmail,'Account activation',$message));
    }

  }
?>
