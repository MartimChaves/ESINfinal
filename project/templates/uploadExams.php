<?php 
if (isset($_SESSION['loggedIn_Emp']) && $_SESSION['loggedIn_Emp']->returnType() == 'technician') {
  ?>
  <table>
      <tr>
      <th>Clinical Record ID</th>
      <th>Patient Name</th>
      <th>Exam Specialty</th>
      <th>Upload</th>
      </tr>
      <?php 
      require_once('database/person.php');
      require_once('database/medicalRecords.php');
      require_once('database/physicalSpaces.php');
      $currentDate = date('Y-m-d');
      $id_employee_technician = $_SESSION['loggedIn_Emp']->returnID();
      $examsToUpload = returnExamsToUpload($id_employee_technician,$currentDate);
      //print_r($hc);
      foreach ($examsToUpload as $upload){
          $id_cr = get_id_cr_from_id_ACLMR($upload['id_aclMR']);
          $clinicalRecord = returnClinicalRecords($id_cr);
          //print_r($upload);
          ?>
            <tr>
                <form action="action_upload_clinicalRecord.php" method="post" enctype="multipart/form-data">                    
                  <input type="hidden" name="id_examSchedule" value=<?php echo $upload['id_examSchedule'] ?>>
                  <input type="hidden" name="id_aclMR" value=<?php echo $upload['id_aclMR'] ?>>
                  <input type="hidden" name="id_cr" value=<?php echo $id_cr ?>>

                  <td><?php echo $id_cr ?></td>
                  <td><?php echo $clinicalRecord['id_patient'] ?></td>
                  <td><?php echo $clinicalRecord['id_specialty'] ?></td>
                  
                  <td>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <input type="submit" id="uploadbutton" value="UploadFile">
                  </td>
                </form>
            </tr>                
          <?php
      }
      ?>

  </table>
  
  <?php 
}
?>