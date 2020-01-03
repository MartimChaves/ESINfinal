<?php 
  require_once('database/person.php');

  $keysEmployeeInfo = array(
    "id_employee" => "Your Employee ID",
    "username" => "Your username",
    "adminPrivilege" => "Admin Privileges",
    "type" => "Your Employee type",
    "joiningDate" => "Joining Date",
    "internedState" => "Interned State",
    "education" => "Place of Education",
    "certification" => "Degree of Education",
    "languages" => "Languages",
    "id_superior" => "Superior ID",
    "id_specialty" => "Specialty ID",
    "name" => "Name",
    "address" => "Address",
    "phoneNumber" => "Phone Number",
    "gender" => "Gender",
    "sex" => "Sex",
    "birthDate" => "Birth Date"
    );


  $infoLogdEmp = returnEmployeesInfoUsingID($_SESSION['loggedIn_Emp']->returnID());
  ?>
  <div id="patientInfoDisplay">
    <?php
    foreach ($infoLogdEmp as $key => $value) {
      if ($key=='id_person' || $key=='password') {
        continue;  
      }
      echo $keysEmployeeInfo[$key] . ': ' . $value . '<br>';
    }
    ?>
  </div>



  <div id="passwordChangeForm">
    <a href="change_employeePass.php">Change Password</a>
  </div>
  <?php

?>