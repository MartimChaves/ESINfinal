<?php 
  require_once('config/init.php');
  require_once('database/person.php');

  $id_patient = $_POST['id_patient'];
  $id_employee = $_POST['id_employee'];

  //check if pairing already exists
  if (checkIfPairAlreadyExists($id_patient,$id_employee)){
    $_SESSION['message'] = 'Pairing already exists.';
    header('Location: assign_patient_employee.php');
  } else {
    try {
        pairPatientEmployee($id_patient,$id_employee);
        $_SESSION['message'] = 'Success!';
        header('Location: assign_patient_employee.php');
      } catch (Exception $e) {
        $_SESSION['message'] = 'Error: ' . $e;
        header('Location: assign_patient_employee.php');
      }
  }

?>