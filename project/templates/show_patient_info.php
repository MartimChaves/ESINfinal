<?php
  require_once('config/init.php');
  require_once('database/person.php');
  require_once('database/medicalRecords.php');

  $keysPatientInfo = array(
    "id_patient" => "Patient ID",
    "acceptedDate" => "Acceptance Date",
    "allergies" => "Allergies",
    "specialReqs" => "Special Requirements",
    "internedState" => "Interned State",
    "prescriptions" => "prescriptions",
    "allergies" => "Allergies",
    "name" => "Name",
    "address" => "Address",
    "phoneNumber" => "Phone Number",
    "gender" => "Gender",
    "sex" => "Sex",
    "birthDate" => "Birth Date"
    );

  if (isset($_SESSION['loggedIn_Emp'])){

    $id_patient = $_POST['id_patient'];
    $id_employee = $_POST['id_employee'];

    $infoPatient = returnPatientInfo_usingID($id_patient); // @ person
    $availableExams = returnExamsUsingPatientID($id_patient); //@ medicalRec
    
    ?>
    <div id="patientInfoDisplay">
      <?php
      foreach ($infoPatient as $key => $value) {
        if ($key=='id_person') {
          continue;  
        }
        if ($_SESSION['loggedIn_Emp']->returnType()!='doctor' && ($key=='prescriptions' || $key=='allergies' || $key=='specialReqs'))
          continue;
        echo $keysPatientInfo[$key] . ': ' . $value . '<br>';
      }
      ?>
    </div>

    <?php 
    if ($_SESSION['loggedIn_Emp']->returnType()=='HR') {
      $activeVisits = returnVisits($id_patient);
      ?>
      <p>Current Active Visits:</p>
      <table>
          <tr>
            <th>Visitor ID</th>
            <th>Visitor Name</th>
          </tr>
          <?php
          foreach ($activeVisits as $visit) {
            $visitorInfo = returnVisitorInfo_usingID($visit['id_visitor']);
          ?>
            <tr>
              <td><?php echo $visit['id_visitor'] ?></td>
              <td><?php echo $visitorInfo['name'] ?></td>
            </tr>
          <?php
          }
        ?>
      </table>
      <?php
    }
    ?>
    
    <?php

    if ($_SESSION['loggedIn_Emp']->returnType()=='doctor'){
      
    ?>
      <table>
        <tr>
          <th>Specialty</th>
          <th>Date</th>
          <th>File</th>
        </tr>
        <?php
        $specialtyInfo = returnSpecialties();
        foreach ($availableExams as $exam) {
        ?>
          <tr>
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