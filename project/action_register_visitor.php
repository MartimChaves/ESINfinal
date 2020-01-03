<?php
  require_once('config/init.php');
  require_once('database/person.php');

  $personInfoErrorTranslater = array(
    -1 => "Name",
    -2 => "Address",
    -3 => "Gender",
    -4 => "Sex",
    -5 => "Birth date",
    -6 => "Phone number - 9 integers"
  );

  $name = $_POST['name'];
  $address = $_POST['address'];
  $phonenumber = $_POST['phonenumber'];
  $gender = $_POST['gender'];
  $sex = $_POST['sex'];
  $birthdate = $_POST['birthdate'];

  $_SESSION['previousRegVis'] = basename($_SERVER['PHP_SELF']);

  $personInfoInSession = array(
    &$_SESSION['visitorFormFill']["name"],
    &$_SESSION['visitorFormFill']["address"],
    &$_SESSION['visitorFormFill']["gender"],
    &$_SESSION['visitorFormFill']["sex"],
    &$_SESSION['visitorFormFill']["birthdate"],
    &$_SESSION['visitorFormFill']["phonenumber"]);

  $personInfoValues = array(
    $name,
    $address,
    $gender,
    $sex,
    $birthdate,
    $phonenumber
  );
  
  $visitorToRegister = new visitor();
  try{
      $errorCheckPersonInfo = $visitorToRegister->addPersonInfo($name,$address,$phonenumber,$gender,$sex,$birthdate);
    if ($errorCheckPersonInfo==0){
      $errorCheckVisitorDB_Insert = $visitorToRegister->insertIntoDatabase();
      if ($errorCheckVisitorDB_Insert==0){
        $_SESSION['message'] = 'Success!';
        header('Location: entrance.php');
      } else {
        for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {  
          $personInfoInSession[$i-1] = $personInfoValues[$i-1];
        }
        $_SESSION['message'] = 'Error with database insertion.';
        header('Location: register_visitor.php');
      }
    } else {
      $error = $errorCheckPersonInfo * -1;
      for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {  
        if ($error == $i) {
          $personInfoInSession[$i-1] = "";
          continue;
        }
        $personInfoInSession[$i-1] = $personInfoValues[$i-1];
      }
      $_SESSION['message'] = 'Error with personal info. Error: ' . $personInfoErrorTranslater[$errorCheckPersonInfo];
      header('Location: register_visitor.php');
    }
    
    if (isset($_POST['inModal'])) {
      $_SESSION['id_patient'] = $_POST['id_patient'];
      header('Location: register_visit.php');
    }
  } catch (Exception $e) {
    $_SESSION['message'] = 'Unkown Error. Error: ' . $e;
    header('Location: register_visitor.php');
  }
  
?>