<section id="register">
  <?php if (isset($_SESSION['username'])){ ?> 
    <?php if ($_SESSION['loggedIn_Emp']->isAdmin()) {?> 
    <h2>Register Employee</h2>
    <?php 
      require_once('database/person.php');
      global $dbh;

      if (isset($_SESSION['previousRegEmp'])){
        if ($_SESSION['previousRegEmp'] != 'action_register_employee.php') {
          unset($_SESSION['employeeFormFill']);
        }
      }

      if (!isset($_SESSION['employeeFormFill'])) {
        $_SESSION['employeeFormFill'] = array(
          "name" => "",
          "address" => "",
          "phonenumber" => "",
          "gender" => "",
          "sex" => "",
          "birthdate" => "",
          "type" => "",
          "joiningdate" => "",
          "education" => "",
          "certification" => "",
          "languages" => "",
          "id_superior" => "",
          "id_specialty" => "",
          "username" => ""
        );
      } 

      $_SESSION['previousRegEmp'] = basename($_SERVER['PHP_SELF']);   

    ?>

    
    <form action="action_register_employee.php" method="post">
      
      <label for="ename">Name</label> <br>
      <input type="text" id="ename" name="name" placeholder="Employee's name" value=<?php echo $_SESSION['employeeFormFill']["name"]?> > <br>
      
      <label for="eadress">Adress</label> <br>
      <input type="text" id="eadress" name="address" placeholder="Employee's address" value=<?php echo $_SESSION['employeeFormFill']["address"]?>> <br>
      
      <label for="egender">Gender</label> <br>
      <input type="text" id="egender" name="gender" placeholder="Employee's gender" value=<?php echo $_SESSION['employeeFormFill']["gender"]?>> <br>
      
      <label for="esex">Sex</label> <br>
      <input type="text" id="esex" name="sex" placeholder="Employee's sex" value=<?php echo $_SESSION['employeeFormFill']["sex"]?>> <br>
      
      <label for="ebdate">Birthdate</label> <br>
      <input type="date" id="ebdate" name="birthdate" placeholder="Employee's birth date" value=<?php echo $_SESSION['employeeFormFill']["birthdate"]?>> <br>
      
      <label for="enumber">Phone Number</label> <br>
      <input type="number" id="enumber" name="phonenumber" placeholder="xxxxxxxxx" value=<?php echo $_SESSION['employeeFormFill']["phonenumber"]?>> <br>
      
      <label> Admin privileges:</label> <br>
      <input type="radio" name="adminprivilege" value="NotAdmin" checked>Non Admin<br>
      <input type="radio" name="adminprivilege" value="Admin">Admin<br>
      
      <label for="etype">Type of Employee</label> <br>
      <select name="type" id="etype"> 
        <?php 
        $possibleTypes = array("doctor","technician","HR");
        $valuesTypes = array("Doctor","Health Technician","Human Resources");
        for ($i = 0; $i < sizeof($possibleTypes); $i++) {
          if ($possibleTypes[$i] == $_SESSION['employeeFormFill']["type"]){
            ?>
            <option value=<?php echo $possibleTypes[$i] ?> selected><?php echo $valuesTypes[$i] ?></option>
            <?php
          } else {
            ?>
            <option value=<?php echo $possibleTypes[$i] ?> ><?php echo $valuesTypes[$i] ?></option>
          <?php
          }
        }
        ?>
      </select> <br>

      <label for="ejdate">Joining Date</label> <br>
      <input type="date" id="ejdate" name="joiningdate" placeholder="Employee's joining date" value=<?php echo $_SESSION['employeeFormFill']["joiningdate"]?>> <br>
      
      <label for="eeducation">Place of Education</label> <br>
      <input type="text" id="eeducation" name="education" placeholder="Place of education" value=<?php echo $_SESSION['employeeFormFill']["education"]?>> <br>
      
      <label for="ecertification">Degree of Education</label> <br>
      <input type="text" id="ecertification" name="certification" placeholder="Degree of education" value=<?php echo $_SESSION['employeeFormFill']["certification"]?>> <br>
      
      <label for="elanguages">Languages</label> <br>
      <input type="text" id="elanguages" name="languages" placeholder="Employee's languages" value=<?php echo $_SESSION['employeeFormFill']["languages"]?>> <br>

      <!-- Superior's List -->
      <br>
      <label for="esuperior">Superior</label> <br>
      <select name="id_superior" id="esuperior">
        <option value="">--</option>
        <?php 
          require_once('database/person.php');
          $employeesInfo = returnEmployees();
          foreach ($employeesInfo as $employee) {
            if ($employee['id_employee'] == $_SESSION['employeeFormFill']["id_superior"]){
              ?>
              <option value=<?php echo $employee['id_employee'] ?> selected><?php echo $employee['id_employee'] . "-" . $employee['name'] ?></option>
              <?php
            } else {
              ?>
              <option value=<?php echo $employee['id_employee'] ?>><?php echo $employee['id_employee'] . "-" . $employee['name'] ?></option>
            <?php
            }
          }
          ?>
      </select>

      <!-- Specialty List -->
      <br>
      <label for="especialty">Specialty</label> <br>
      <select name="id_specialty" id="especialty"> 
        <?php 
        require_once('database/person.php');
        $specialtyInfo = returnSpecialties();
        foreach ($specialtyInfo as $specialty) {
          if ($specialty['id_specialty'] == $_SESSION['employeeFormFill']["id_specialty"]){
            ?>
            <option value=<?php echo $specialty['id_specialty'] ?> selected><?php echo $specialty['id_specialty'] . "-" . $specialty['name'] ?></option>
            <?php
          } else {
            ?>
            <option value=<?php echo $specialty['id_specialty'] ?>><?php echo $specialty['id_specialty'] . "-" . $specialty['name'] ?></option>
          <?php
          }
        }
        ?>
      </select> <br>

      <label for="eusername">Username</label> <br>
      <input type="text" id="eusername" name="username" placeholder="username" value=<?php echo $_SESSION['employeeFormFill']["username"]?>> <br>
      
      <label for="epassword">Password</label> <br>
      <input type="password" id="epassword" name="password" placeholder="password"> <br>
      <br>
      <input type="submit" value="Register">
    </form>
    <?php } else { ?>
      <h3>You do not have permission to register users.</h3>
    <?php } ?>
  <?php } else { ?>
    <h3>You do not have permission to register users.</h3>
  <?php } ?>
  
</section>