<section id="register">
  <?php if (isset($_SESSION['username'])){ ?> 
    <?php if ($_SESSION['loggedIn_Emp']->isAdmin()) {?> 
    <h2>Register Patient</h2>
    
    <?php
      require_once('database/person.php');
      global $dbh;
      if (isset($_SESSION['previousRegPat'])){
        if ($_SESSION['previousRegPat'] != 'action_register_patient.php') {
          unset($_SESSION['patientFormFill']);
        }
      }
      if (!isset($_SESSION['patientFormFill'])) {
        $_SESSION['patientFormFill'] = array(
          "name" => "",
          "address" => "",
          "phonenumber" => "",
          "gender" => "",
          "sex" => "",
          "birthdate" => "",
          "accepteddate" => "",
          "prescriptions" => "",
          "allergies" => "",
          "specialreqs" => "",
          "internedstate" => ""
        );
      } 
      $_SESSION['previousRegPat'] = basename($_SERVER['PHP_SELF']);   
    ?>

    <form action="action_register_patient.php" method="post">
      
      <label for="pname">Name</label> <br>
      <input type="text" id="pname" name="name" placeholder="Patient's name" value=<?php echo $_SESSION['patientFormFill']["name"]?> > <br>
      
      <label for="padress">Adress</label> <br>
      <input type="text" id="padress" name="address" placeholder="Patient's address" value=<?php echo $_SESSION['patientFormFill']["address"]?>> <br>
      
      <label for="pgender">Gender</label> <br>
      <input type="text" id="pgender" name="gender" placeholder="Patient's gender" value=<?php echo $_SESSION['patientFormFill']["gender"]?>> <br>
      
      <label for="psex">Sex</label> <br>
      <input type="text" id="psex" name="sex" placeholder="Patient's sex" value=<?php echo $_SESSION['patientFormFill']["sex"]?>> <br>
      
      <label for="pbdate">Birthdate</label> <br>
      <input type="date" id="pbdate" name="birthdate" placeholder="Patient's birth date" value=<?php echo $_SESSION['patientFormFill']["birthdate"]?>> <br>

      <label for="pnumber">Phone Number</label> <br>
      <input type="number" id="pnumber" name="phonenumber" placeholder="xxxxxxxxx" value=<?php echo $_SESSION['patientFormFill']["phonenumber"]?>> <br>

      <label for="pacdate">Date of Acceptance</label> <br>
      <input type="date" id="pacdate" name="accepteddate" placeholder="Patient's accepted date" value=<?php echo $_SESSION['patientFormFill']["accepteddate"]?>> <br>
      
      <label for="pprescriptions">Prescriptions</label> <br>
      <input type="text" id="pprescriptions" name="prescriptions" placeholder="Patient's prescriptions" value=<?php echo $_SESSION['patientFormFill']["prescriptions"]?>> <br>
      
      <label for="pallergies">Allergies</label> <br>
      <input type="text" id="pallergies" name="allergies" placeholder="Patient's allergies" value=<?php echo $_SESSION['patientFormFill']["allergies"]?>> <br>
      
      <label for="pspecialreqs">Special Requisites</label> <br>
      <input type="text" name="specialreqs" placeholder="Patient's special requisites" value=<?php echo $_SESSION['patientFormFill']["specialreqs"]?>> <br>
      
      <label> Internment State</label> <br>
      <input type="radio" name="internedstate" value="Not Interned" ckecked>Not Interned<br>
      <input type="radio" name="internedstate" value="Interned">Interned<br>
      <br>
      
      <input type="submit" value="Register">
    </form>
    <?php } else { ?>
      <h3>You do not have permission to register patients.</h3>
    <?php } ?>
  <?php } else { ?>
    <h3>You do not have permission to register patients.</h3>
  <?php } ?>
  
</section>