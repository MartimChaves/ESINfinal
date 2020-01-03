<?php 
require_once('database/person.php');

if (isset($_SESSION['loggedIn_Emp'])) {

  if ($_SESSION['loggedIn_Emp']->isAdmin()) {
    $patientsList = returnPatients();
    ?>
    <table>
      <tr>
        <?php ?>
        <th>Patient ID</th>
        <th>Patient Name</th>
        <th>More Info</th>
        <th>Add Visit</th>
      </tr>
    <?php
      foreach ($patientsList as $patient) {
        ?>
        <tr>
          <td><?php echo $patient['id_patient'] ?></td>
          <td><?php echo $patient['name'] ?></td>
          <td>
            <form action="show_patient_info.php" method="post">
              <input type="hidden" name="id_patient" value=<?php echo $patient['id_patient'] ?>>
              <input type="hidden" name="id_employee" value=<?php echo $_SESSION['loggedIn_Emp']->returnID() ?>>
              <input type="submit" value="Get more Info">
            </form>
          </td>
          <td>
            <form action="register_visit.php" method="post"> 
              <input type="hidden" name="id_patient" value=<?php echo $patient['id_patient'] ?>>
              <input type="submit" value="Add Visit">
            </form>
          </td>
        </tr>
        <?php
        } 
    ?>
    </table>
    <?php
  } elseif ($_SESSION['loggedIn_Emp']->returnType() == 'doctor') {
    $patientsList = $_SESSION['loggedIn_Emp']->returnMyPatients();
    ?>
    <table>
      <tr>
        <?php ?>
        <th>Patient ID</th>
        <th>Patient Name</th>
        <th>More Info</th>
        <th>Request Exam</th>
      </tr>

      <?php
      foreach ($patientsList as $patient) {
        ?>
        <tr>
          <td><?php echo $patient['id_patient'] ?></td>
          <td><?php echo $patient['name'] ?></td>
          <td>
            <form action="show_patient_info.php" method="post">
              <input type="hidden" name="id_patient" value=<?php echo $patient['id_patient'] ?>>
              <input type="hidden" name="id_employee" value=<?php echo $_SESSION['loggedIn_Emp']->returnID() ?>>
              <input type="submit" value="Get more Info">
            </form>
          </td>
          <td>
            <form action="action_request_exam.php" method="post"> 
              <input type="hidden" name="id_patient" value=<?php echo $patient['id_patient'] ?>>
              <input type="hidden" name="id_employee_requester" value=<?php echo $_SESSION['loggedIn_Emp']->returnID() ?>>
              <select name="id_specialty">
                <?php 
                $specialtyInfo = returnSpecialties();
                foreach ($specialtyInfo as $specialty) {
                  ?>
                    <option value=<?php echo $specialty['id_specialty'] ?>><?php echo $specialty['name'].' Exam' ?></option>
                  <?php
                }
                ?>
              </select>
              <input type="submit" value="Request Exam">
            </form>              
          </td>
        </tr>
      <?php
      }
      ?>
    </table>
    <?php
  }
}
?>