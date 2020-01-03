<?php 

if(isset($_SESSION['loggedIn_Emp'])){

  ?>
  <section id="register">
    <form action="action_change_employeePass.php" method="post">
      <input type="hidden" name="id_employee" value=<?php echo $_SESSION['loggedIn_Emp']->returnID() ?>>

      <label for="epassword">Your current password</label> <br>
      <input type="password" id="epassword" name="password1"><br>

      <label for="tpassword">Your current password (duplicate)</label> <br>
      <input type="password" id="tpassword" name="password2"><br>

      <label for="rpassword">New password</label> <br>
      <input type="password" id="rpassword" name="newPassword"> <br>

      <input type="submit" value="Change Password">
    </form>
  </section>

  <?php
}

?>


