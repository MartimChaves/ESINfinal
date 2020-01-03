<?php 
	require_once('config/init.php');
	require_once('database/person.php');
	require_once('database/medicalRecords.php');
	require_once('database/physicalSpaces.php');

	$id_cr = $_POST['id_cr'];
	$examDate = $_POST['examDate'];
	$id_healthCentre = $_POST['id_healthCentre'];
	$id_examRequest = $_POST['id_examRequest'];
	$id_employee_technician = $_POST['id_employee_technician'];
	
  try {
    $id_aclMR = get_id_ACLMR_from_id_cr($id_cr);
    // update clinical record
    updateClinicalRecord($id_cr,$examDate,$id_healthCentre);
    
    // update acl
    updateACL_medicalRecord($id_employee_technician,$id_aclMR);

    // update exam requests
    finalUpdateExamRequests($id_examRequest);

    // schedule exam
    addExamSchedule($id_employee_technician,$examDate,$id_aclMR);
    
    $_SESSION['message'] = 'Exam Request Solved Successfully!';
    header('Location: solveExamRequests.php');
  } catch (Exception $e) {
    $_SESSION['message'] = 'Solving Exam Request Failed. Error: ' .  $e;
    header('Location: solveExamRequests.php');
  }
 
?>