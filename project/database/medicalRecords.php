<?php 

  function returnExamRequests($id_specialty){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM examRequests
      WHERE status = "pendent" AND id_specialty = ?'); 
    $stmt->execute(array($id_specialty));
    $examRequests = $stmt->fetchAll();
    return $examRequests;
  }

  function returnClinicalRecords($id_cr){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM clinicalRecord WHERE id_cr = ?');
    $stmt->execute(array($id_cr));
    $clinicalRecord = $stmt->fetchAll();
    return $clinicalRecord[0];
  }

  function returnAvailableExams_toTtechnician($id_employee){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM aclMedicalRec JOIN clinicalRecord 
      USING(id_cr) 
      WHERE id_employee_technician=? AND completed=?;');
    $stmt->execute(array($id_employee,1));
    $availableExams = $stmt->fetchAll();
    return $availableExams;
  }

  function returnExamsUsingPatientID($id_patient){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM clinicalRecord JOIN aclMedicalRec 
      USING(id_cr)
      WHERE id_patient=? AND completed=?;');
    $stmt->execute(array($id_patient,1));
    $exams = $stmt->fetchAll();
    return $exams;
  }

  function startExamProcess($id_specialty,$id_patient,$id_employee_requester){
    global $dbh;

    addClinicalRecordEntry($id_specialty,$id_patient);
    $lastClinicalRecord = getLastClinicalRecordAdded();

    addClinicalRecord_ACL_Entry($lastClinicalRecord,$id_employee_requester);

    $stmt = $dbh->prepare('INSERT INTO examRequests (status,id_specialty,id_cr) 
      VALUES ("pendent",?,?);'); 
    $stmt->execute(array($id_specialty,$lastClinicalRecord));
  }

  function getLastClinicalRecordAdded(){
    global $dbh;
    $stmt = $dbh->prepare('SELECT MAX(id_cr) FROM clinicalRecord');
    $stmt->execute();
    $lastCR_id = $stmt->fetch();
    return $lastCR_id['MAX(id_cr)'];
  }

  function addClinicalRecordEntry($id_specialty,$id_patient) {
    global $dbh;
    $stmt = $dbh->prepare('INSERT INTO clinicalRecord (id_specialty,id_patient) VALUES (?,?);'); 
    $stmt->execute(array($id_specialty,$id_patient));
  }

  function addClinicalRecord_ACL_Entry($id_cr,$id_employee) {
    global $dbh;
    $stmt = $dbh->prepare('INSERT INTO ACLMedicalRec (id_cr,id_employee_requester,completed) 
      VALUES (?,?,?);'); 
    $stmt->execute(array($id_cr,$id_employee,0));
  }

  function get_id_ACLMR_from_id_cr($id_cr) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT id_aclMR 
      FROM aclMedicalRec WHERE id_cr = ?;');
    $stmt->execute(array($id_cr));
    $id_aclMR = $stmt->fetch();
    return $id_aclMR['id_aclMR'];
  }

  function get_id_cr_from_id_ACLMR($id_aclMR) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT id_cr 
      FROM aclMedicalRec 
      WHERE id_aclMR = ?;');
    $stmt->execute(array($id_aclMR));
    $id_cr = $stmt->fetch();
    return $id_cr['id_cr'];
  }

  function updateClinicalRecord($id_cr,$examDate,$id_healthCentre) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE clinicalRecord 
      SET dateExam = ?, id_hc = ? 
      WHERE id_cr = ?;');
    $stmt->execute(array($examDate,$id_healthCentre,$id_cr));
  }

  function finalUpdateClinicalRecord($id_cr,$target_file) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE clinicalRecord 
      SET path_cr = ?
      WHERE id_cr = ?;');
    $stmt->execute(array($target_file,$id_cr));
  }

  function updateACL_medicalRecord($id_employee_technician,$id_aclMR) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE aclMedicalRec 
      SET id_employee_technician = ? WHERE id_aclMR = ?;');
    $stmt->execute(array($id_employee_technician,$id_aclMR));
  }

  function finalUpdateACL_medicalRecord($id_aclMR) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE aclMedicalRec 
      SET completed = ? WHERE id_aclMR = ?;');
    $stmt->execute(array(1,$id_aclMR));
  }

  function finalUpdateExamRequests($id_examRequest) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE examRequests 
      SET status = "executed" 
      WHERE id_examRequests = ?;');
    $stmt->execute(array($id_examRequest));    
  }

  function addExamSchedule($id_employee_technician,$examDate,$id_aclMR){
    global $dbh;
    $stmt = $dbh->prepare('INSERT INTO examSchedule (id_employee_technician,dateExam,uploadStatus,id_aclMR) 
      VALUES (?,?,?,?);'); 
    $stmt->execute(array($id_employee_technician,$examDate,0,$id_aclMR));
  }

  function finalUpdateExamSchedule($id_examSchedule) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE examSchedule 
      SET uploadStatus = ? 
      WHERE id_examSchedule = ?;');
    $stmt->execute(array(1,$id_examSchedule));  
  }

  function returnExamsToUpload($id_employee_technician,$currentDate){
    // return exams where date <= currentDate and id_emp = id_emp_tech and upload_status=0
    global $dbh;
    $currentDate_ts = strtotime($currentDate);
    $stmt = $dbh->prepare('SELECT 
      id_examSchedule,
      id_employee_technician, 
      CAST(strftime("%s", dateExam) AS INT) AS date, 
      uploadStatus,
      id_aclMR 
      FROM examSchedule 
      WHERE id_employee_technician=? AND date<=? AND uploadStatus=?;');
    $stmt->execute(array($id_employee_technician,$currentDate_ts,0));
    $examsToUpload = $stmt->fetchAll();
    return $examsToUpload;
  }

  function checkIfExamRequested($id_patient,$id_specialty){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM examRequests JOIN clinicalRecord 
      USING(id_cr) 
      WHERE clinicalRecord.id_specialty=? AND id_patient=? AND status="pendent";');
    $stmt->execute(array($id_specialty,$id_patient));
    return $stmt->fetch() !== false;
  }

  function checkIfExamScheduled($id_patient,$id_specialty){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM 
      (SELECT * 
      FROM examSchedule JOIN aclMedicalRec USING(id_aclMR)) 
      JOIN clinicalRecord 
      USING(id_cr) 
      WHERE id_patient=? AND id_specialty=? AND completed=?;');
    $stmt->execute(array($id_patient,$id_specialty,0));
    return $stmt->fetch() !== false;
  }

  


?>