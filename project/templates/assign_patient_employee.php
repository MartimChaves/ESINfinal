<?php 
  require_once('database/person.php');

  if (isset($_SESSION['loggedIn_Emp'])) {

    if ($_SESSION['loggedIn_Emp']->isAdmin()) {
    
      $patientsList = returnPatients();
      $employeesList = returnEmployees();

      ?>
      <table>
        <tr>
          <th>Patient</th>
          <th>Doctor</th>
          <th>Pair</th>
        </tr>

        <form action="action_pairEmployeePatient.php" method="post">
          <tr>
            <td>
              <select name="id_patient">
                <?php 
                foreach ($patientsList as $patient) {
                  ?>
                  <option value=<?php echo $patient['id_patient']?>> <?php echo $patient['name']?> </option>
                  <?php
                }
                ?>
              </select>
            </td>
            <td>
              <select name="id_employee">
                <?php 
                foreach ($employeesList as $employee) {
                  if ($employee['type']=='doctor'){
                    ?>
                      <option value=<?php echo $employee['id_employee']?>> <?php echo $employee['name']?> </option>
                    <?php
                  }
                }
                ?>
              </select>
            </td>
            <td><input type="submit" value="Pair"></td>
          </tr>
        </form>
      </table>
      <?php
      
    }
  }
?>