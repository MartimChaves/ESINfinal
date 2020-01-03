<?php
  require_once('config/init.php');
  require_once('database/person.php');

  $keysEmployeeInfo = array(
    "id_employee" => "Employee's ID",
    "username" => "Employee's username",
    "adminPrivilege" => "Employee's Admin Privileges",
    "type" => "Employee's type",
    "joiningDate" => "Employee's Joining Date",
    "education" => "Employee's Place of Education",
    "certification" => "Employee's Degree of Education",
    "languages" => "Employee's Languages",
    "id_superior" => "Employee's Superior ID",
    "id_specialty" => "Employee's Specialty ID",
    "name" => "Employee's Name",
    "address" => "Employee's Address",
    "phoneNumber" => "Employee's Phone Number",
    "gender" => "Employee's Gender",
    "sex" => "Employee's Sex",
    "birthDate" => "Employee's Birth Date"
    );

  if (isset($_SESSION['loggedIn_Emp']) && $_SESSION['loggedIn_Emp']->isAdmin()){

    $id_employee = $_POST['id_employee'];

    $infoLogdEmp = returnEmployeesInfoUsingID($id_employee);

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
    <?php
  }
?>