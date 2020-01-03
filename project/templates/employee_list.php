<?php 
require_once('database/person.php');

if (isset($_SESSION['loggedIn_Emp'])) {

  if ($_SESSION['loggedIn_Emp']->isAdmin()) {
    $employeesList = returnEmployees();
    ?>
    <table>
      <tr>
        <?php ?>
        <th>Employee ID</th>
        <th>Employee Name</th>
        <th>More Info</th>
      </tr>
    <?php
      foreach ($employeesList as $employee) {
        ?>
        <tr>
          <td><?php echo $employee['id_employee'] ?></td>
          <td><?php echo $employee['name'] ?></td>
          <td>
            <form action="show_employee_info.php" method="post">
              <input type="hidden" name="id_employee" value=<?php echo $employee['id_employee'] ?>>
              <input type="submit" value="Get more Info">
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