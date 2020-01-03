<?php 
  require_once('config/init.php');
  require_once('database/person.php');
  require_once('database/medicalRecords.php');
  

  $id_specialty = $_POST['id_specialty'];
  $id_patient  = $_POST['id_patient']; 
  $id_employee_requester = $_POST['id_employee_requester'];

  // check if there is no request for that patient and exam type
  // check if there is no examSchedule for that patient and exam type

  if (checkIfExamRequested($id_patient,$id_specialty)) {
    $_SESSION['message'] = 'Exam request denied: Exam request of that type for that patient has already been done.';
    header('Location: patient_list.php');
  } elseif (checkIfExamScheduled($id_patient,$id_specialty)) {
    $_SESSION['message'] = 'Exam request denied: Exam of that type for that patient is to be uploaded in the future.';
    header('Location: patient_list.php');
  } else {

    try {
        startExamProcess($id_specialty,$id_patient,$id_employee_requester);
        $_SESSION['message'] = 'Exam request successful!';
        header('Location: patient_list.php');
      } catch (Exception $e) {
        $_SESSION['message'] = 'Exam request failed!';
        header('Location: patient_list.php');
      }

  }
  
?>