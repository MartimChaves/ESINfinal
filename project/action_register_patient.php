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

  $patientInfoErrorTranslater = array(
    -1 => "Accepted Date",
    -2 => "Prescriptions",
    -3 => "Allergies",
    -4 => "Special Requisites",
    -5 => "State of Internment"
  );


  $name = $_POST['name'];
  $address = $_POST['address'];
  $phonenumber = $_POST['phonenumber'];
  $gender = $_POST['gender'];
  $sex = $_POST['sex'];
  $birthdate = $_POST['birthdate'];
  $accepteddate = $_POST['accepteddate'];
  $prescriptions = $_POST['prescriptions'];
  $allergies = $_POST['allergies'];
  $specialreqs = $_POST['specialreqs'];
  $internedstate = $_POST['internedstate'];

  $_SESSION['previousRegPat'] = basename($_SERVER['PHP_SELF']);

  $personInfoInSession = array(
    &$_SESSION['patientFormFill']["name"],
    &$_SESSION['patientFormFill']["address"],
    &$_SESSION['patientFormFill']["gender"],
    &$_SESSION['patientFormFill']["sex"],
    &$_SESSION['patientFormFill']["birthdate"],
    &$_SESSION['patientFormFill']["phonenumber"]);

  $personInfoValues = array(
    $name,
    $address,
    $gender,
    $sex,
    $birthdate,
    $phonenumber
  );

  $patientInfoInSession = array(
    &$_SESSION['patientFormFill']["accepteddate"],
    &$_SESSION['patientFormFill']["prescriptions"],
    &$_SESSION['patientFormFill']["allergies"],
    &$_SESSION['patientFormFill']["specialreqs"],
    &$_SESSION['patientFormFill']["internedstate"]
  );
  
  $patientInfoValues = array(
    $accepteddate,
    $prescriptions,
    $allergies,
    $specialreqs,
    $internedstate
  );
  
  $patientToRegister = new patient();
  try {
      $errorCheckPersonInfo = $patientToRegister->addPersonInfo($name,$address,$phonenumber,$gender,$sex,$birthdate);
      if ($errorCheckPersonInfo == 0){
        $errorCheckPatientInfo = $patientToRegister->addPatientInfo($accepteddate,$prescriptions,$allergies,$specialreqs,$internedstate);
        if ($errorCheckPatientInfo == 0){
          $errorCheckPatientDB_Insert = $patientToRegister->insertIntoDatabase();
          if ($errorCheckPatientDB_Insert == 0){
            $_SESSION['message'] = 'Success!';
            header('Location: entrance.php');
          } else {
            for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {  
              $personInfoInSession[$i-1] = $personInfoValues[$i-1];
            }
            for ($i=0; $i < sizeof($patientInfoInSession); $i++) { 
              $patientInfoInSession[$i] = $patientInfoValues[$i];
            }
            $_SESSION['message'] = 'Error with database insertion.';
            header('Location: register_patient.php');
          }
        } else {
          for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {
            $personInfoInSession[$i-1] = $personInfoValues[$i-1];
          }
          $error = $errorCheckPatientInfo * -1;
          for ($i=1; $i <= sizeof($patientInfoInSession); $i++) { 
            if ($error == $i) {
              $patientInfoInSession[$i-1] = "";
              continue;
            }
            $patientInfoInSession[$i-1] = $patientInfoValues[$i-1];
          }
          $_SESSION['message'] = 'Error with patient info. Error: ' . $patientInfoErrorTranslater[$errorCheckPatientInfo];
          header('Location: register_patient.php');
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
        for ($i=0; $i < sizeof($patientInfoInSession); $i++) { 
          $patientInfoInSession[$i] = $patientInfoValues[$i];
        }
        $_SESSION['message'] = 'Error with personal info. Error: ' . $personInfoErrorTranslater[$errorCheckPersonInfo];
        header('Location: register_patient.php');
      }
  } catch(Exception $e){
    $_SESSION['message'] = 'Unkown Error. Error: ' . $e;
    header('Location: register_patient.php');
  }
?>