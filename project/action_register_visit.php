<?php
  require_once('config/init.php');
  require_once('database/person.php');

  $id_patient = $_POST['id_patient'];
  $id_visitor = $_POST['id_visitor'];

  try {

    // check if there isn't a visit with the same (patient,visitor) pair
    if (checkIfVisistPairExists($id_patient,$id_visitor)) {
      $_SESSION['message'] = 'Error: Visit already exists.';
      header('Location: patient_list.php');
    } elseif (empty($id_visitor)) {
      $_SESSION['message'] = 'Error: There are no registered visitors.';
      header('Location: patient_list.php');
    } 
    else {
      $visit = new visit($id_patient,$id_visitor);
      $visit->insertIntoDatabase();

      // If visitor is added using Modal
      // id_patient is "saved" as a session variable
      // ir order to use, but it needs to be unset afterwards
      if (isset($_SESSION["id_patient"])) 
        unset ($_SESSION["id_patient"]);

      $_SESSION['message'] = 'Visit added with success!';
      header('Location: patient_list.php');
    }
    
  } catch (Exception $e) {

    $_SESSION['message'] = 'Adding visit failed.';
    header('Location: patient_list.php');
    
  }

  

?>