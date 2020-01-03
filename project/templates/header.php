<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>ClinicalHub</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https%3A%2F%2Ffonts.googleapis.com%2Fcss%3Ffamily%3DLibre%2BFranklin%7CMerriweather" rel="stylesheet"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="icon" href="images/red-heart-favicon.ico">
  </head>
  <body>
    <header> 
      <a href="entrance.php"> <h1>ClinicalHub</h1> <img src="images/red-heart.jpg" alt="Red Heart" class=logo> </a>
      <?php if (isset($_SESSION['username'])) {
        if ($_SESSION['loggedIn_Emp']->isAdmin()) {
            ?>
            <ul>
              <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Register</a>
                <div class="dropdown-content">
                  <a href="register_employee.php">Register Employee</a>
                  <a href="register_patient.php">Register Patient</a>
                  <a href="register_visitor.php">Register Visitor</a>
                </div>
              </li>
              <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">People Lists</a>
                <div class="dropdown-content">
                  <a href="patient_list.php">Patients List</a>
                  <a href="employee_list.php">Employees List</a>
                </div>
              </li>
              <li><a href="assign_patient_employee.php">Assign Patient to Doctor</a></li>
            </ul>
            <?php
          } elseif ($_SESSION['loggedIn_Emp']->returnType() == 'doctor') {
            ?>
            <ul><li><a href="patient_list.php">My patients</a></li></ul>
            <?php
          } elseif ($_SESSION['loggedIn_Emp']->returnType() == 'technician') {
            $id_specialty = $_SESSION['loggedIn_Emp']->returnSpecialty();
            $id_employee_technician = $_SESSION['loggedIn_Emp']->returnID();
            $currentDate = date('Y-m-d');
            $examsToUpload = returnExamsToUpload($id_employee_technician,$currentDate);
            $examRequests = returnExamRequests($id_specialty);
            ?>
            <ul>
              <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Exams</a>
                <div class="dropdown-content">
                  <?php
                  if (!empty($examRequests)) {
                    ?>
                    <a href="solveExamRequests.php"><?php echo sizeof($examRequests)?> Requested Exam(s)</a>
                    <?php
                  } else {
                    ?>
                    <a>No Requested Exam(s)</a>
                    <?php
                  }
                  
                  if (!empty($examsToUpload)) {
                    ?>
                    <a href="uploadExams.php"><?php echo sizeof($examsToUpload)?> Exam(s) to upload</a>
                    <?php
                  } else {
                    ?>
                    <a>No Exam(s) to upload</a>
                    <?php
                  } ?>
                </div>
              </li>
              <li><a href="technician_exams_list.php">My exams</a></li>
            </ul>
            <?php
          }?>
          <form class="logout" action="action_logout.php">
            <span><a href="loggedIn_Emp_info.php"><?=$_SESSION['username']?></a></span>
            <input type="submit" value="Logout">
          </form>
          <?php
      } else {
        ?>
        <form class="login" action="action_login.php" method="post">
          <input type="text" placeholder="username" name="username">
          <input type="password" placeholder="password" name="password">
          <input type="submit" value="Login">
        </form>
        <?php
      } ?>

      <?php if (isset($_MESSAGE)) { ?>
        <div class="message">
          <?=$_MESSAGE?>
        </div>
      <?php } ?>
    </header>



