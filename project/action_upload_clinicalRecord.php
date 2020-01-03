<?php
    require_once('config/init.php');
    require_once('database/medicalRecords.php');

    $target_dir = "clinicalRecords/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 0;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['message'] = 'File already exists.';
        header('Location: uploadExams.php');
        $uploadOk = -1;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 2000000) {
        $_SESSION['message'] = 'Your file is too large.';
        header('Location: uploadExams.php');
        $uploadOk = -1;
    }
    // Allow certain file formats
    if($imageFileType != "pdf") {
        $_SESSION['message'] = 'Only PDF files are allowed.';
        header('Location: uploadExams.php');
        $uploadOk = -1;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          $_SESSION['message'] = 'There was an error uploading the file.';
          header('Location: uploadExams.php');
          $uploadOk = -1;
      }
    }

    if ($uploadOk == 0){
        $id_cr = $_POST['id_cr'];
        $id_aclMR = $_POST['id_aclMR'];
        $id_examSchedule = $_POST['id_examSchedule'];

        // mark schedule status upload = 1 (meaning it has been uploaded)
        finalUpdateExamSchedule($id_examSchedule);

        // mark acl complete = 1 (meaning there is a file)
        finalUpdateACL_medicalRecord($id_aclMR);

        // update clinical record path 
        finalUpdateClinicalRecord($id_cr,$target_file);

        $_SESSION['message'] = 'Exam Uploaded Successfully!';
        header('Location: uploadExams.php');

    } 

?>