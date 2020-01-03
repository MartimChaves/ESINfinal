<?php 
  require_once('config/init.php');
  require_once('database/person.php');

  $id_employee = $_POST['id_employee'];

  $pass1 = $_POST['password1'];
  $pass2 = $_POST['password2'];
  $newPass = $_POST['newPassword'];

  try {
    $errorCheck = $_SESSION['loggedIn_Emp']->changePassword($pass1,$pass2,$newPass);
    if ($errorCheck==0){
      $_SESSION['message'] = 'Password Changed with Success!';
      header('Location: entrance.php');
    } else {
      $_SESSION['message'] = 'Password Change failed.';
      header('Location: entrance.php');
    }
  } catch (Exception $e) {
    $_SESSION['message'] = 'Unkown error.';
    header('Location: entrance.php');
  }

?>