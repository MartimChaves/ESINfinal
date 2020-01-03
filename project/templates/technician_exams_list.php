<?php 
  require_once('database/person.php');
  require_once('database/medicalRecords.php');


if (isset($_SESSION['loggedIn_Emp'])) {
  if ($_SESSION['loggedIn_Emp']->returnType()=='technician'){
    $id_employee = $_SESSION['loggedIn_Emp']->returnID();
    $availableExams = returnAvailableExams_toTtechnician($id_employee);
    ?>
    <table>
      <tr>
        <th>Patient's ID</th>
        <th>Patient's name</th>
        <th>Specialty</th>
        <th>Date</th>
        <th>File</th>
      </tr>
      <?php
      foreach ($availableExams as $exam) {
        $patientInfo = returnPatientInfo_usingID($exam['id_patient']);
      ?>
        <tr>
          <td><?php echo $exam['id_patient'] ?></td>
          <td><?php echo $patientInfo['name'] ?></td>
          <td><?php echo $exam['id_specialty'] ?></td>
          <td><?php echo $exam['dateExam'] ?></td>
          <td><a href="<?php echo $exam['path_cr'] ?>" target="_blank">Open exam</a></td>
        </tr>
      <?php
      }
    ?>
    </table>

  <?php
  }
}

?>