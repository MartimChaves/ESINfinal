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

  $employeeInfoErrorTranslater = array(
    -1 => "Username: Unique & Alphanumeric",
    -2 => "Type",
    -3 => "Joining Date",
    -4 => "Place of Education",
    -5 => "Degree of Education",
    -6 => "Languages",
    -7 => "Password",
    -8 => "Admin Privileges"
  );

  $name = $_POST['name'];
  $address = $_POST['address'];
  $phonenumber = $_POST['phonenumber'];
  $gender = $_POST['gender'];
  $sex = $_POST['sex'];
  $birthdate = $_POST['birthdate'];

  $adminprivilege = $_POST['adminprivilege'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $type = $_POST['type'];
  $joiningdate = $_POST['joiningdate'];
  $education = $_POST['education'];
  $certification = $_POST['certification'];
  $languages = $_POST['languages'];
  $id_superior = $_POST['id_superior'];
  $id_specialty = $_POST['id_specialty'];


  $_SESSION['previousRegEmp'] = basename($_SERVER['PHP_SELF']);

  $personInfoInSession = array(
    &$_SESSION['employeeFormFill']["name"],
    &$_SESSION['employeeFormFill']["address"],
    &$_SESSION['employeeFormFill']["gender"],
    &$_SESSION['employeeFormFill']["sex"],
    &$_SESSION['employeeFormFill']["birthdate"],
    &$_SESSION['employeeFormFill']["phonenumber"]);

  $personInfoValues = array(
    $name,
    $address,
    $gender,
    $sex,
    $birthdate,
    $phonenumber
  );

  $employeeInfoInSession = array(
    &$_SESSION['employeeFormFill']["username"],
    &$_SESSION['employeeFormFill']["type"],
    &$_SESSION['employeeFormFill']["joiningdate"],
    &$_SESSION['employeeFormFill']["education"],
    &$_SESSION['employeeFormFill']["certification"],
    &$_SESSION['employeeFormFill']["languages"],
    &$_SESSION['employeeFormFill']["id_superior"],
    &$_SESSION['employeeFormFill']["id_specialty"]
    );

  $employeeInfoValues = array(
    $username,
    $type,
    $joiningdate,
    $education,
    $certification,
    $languages,
    $id_superior,
    $id_specialty
  );

  $employeeToRegister = new employee();

  try {
    $errorCheckPersonInfo = $employeeToRegister->addPersonInfo($name,$address,$phonenumber,$gender,$sex,$birthdate);

    if ($errorCheckPersonInfo==0){
      $errorCheckEmployeeInfo = $employeeToRegister->addEmployeeInfo($adminprivilege,$username,$password,$type,$joiningdate,$education,$certification,$languages,$id_superior,$id_specialty);

      if ($errorCheckEmployeeInfo==0){
        $errorCheckEmployeeDB_Insert = $employeeToRegister->insertIntoDatabase();
        if ($errorCheckEmployeeDB_Insert===0){
          $_SESSION['message'] = 'Success!';
          header('Location: entrance.php');
        } else {

          for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {  
            $personInfoInSession[$i-1] = $personInfoValues[$i-1];
          }

          for ($i=0; $i < sizeof($employeeInfoInSession); $i++) { 
            $employeeInfoInSession[$i] = $employeeInfoValues[$i];
          }

          $_SESSION['message'] = 'Error with database insertion.';
          header('Location: register_employee.php');
        }
      } else {

        for ($i=1; $i<=sizeof($personInfoInSession) ; $i++) {  
          $personInfoInSession[$i-1] = $personInfoValues[$i-1];
        }

        $error = $errorCheckEmployeeInfo * -1;
        for ($i=1; $i <= sizeof($employeeInfoInSession); $i++) { 
          if ($error == $i) {
            $employeeInfoInSession[$i-1] = "";
            continue;
          }
          $employeeInfoInSession[$i-1] = $employeeInfoValues[$i-1];
        }
        
        $_SESSION['message'] = 'Error with employee info. Error: ' . $employeeInfoErrorTranslater[$errorCheckEmployeeInfo];
        header('Location: register_employee.php');
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

      for ($i=0; $i < sizeof($employeeInfoInSession); $i++) { 
        $employeeInfoInSession[$i] = $employeeInfoValues[$i];
      }

      $_SESSION['message'] = 'Error with personal info. Error: ' . $personInfoErrorTranslater[$errorCheckPersonInfo];
      header('Location: register_employee.php');
    }
    
  } catch (Exception $e) {
    $_SESSION['message'] = 'Unkown Error. Error: ' . $e;
    header('Location: register_employee.php');
  }

  
  
  

  

?>