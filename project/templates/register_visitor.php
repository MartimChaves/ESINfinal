<section id="register">
  <?php if (isset($_SESSION['username'])){ ?> 
    <?php if ($_SESSION['loggedIn_Emp']->isAdmin()) {?> 
    <?php 
      require_once('database/person.php');
      global $dbh;
      if (isset($_SESSION['previousRegVis'])){
        if ($_SESSION['previousRegVis'] != 'action_register_visitor.php') {
          unset($_SESSION['visitorFormFill']);
        }
      }
      if (!isset($_SESSION['visitorFormFill'])) {
        $_SESSION['visitorFormFill'] = array(
          "name" => "",
          "address" => "",
          "phonenumber" => "",
          "gender" => "",
          "sex" => "",
          "birthdate" => ""
        );
      } 
      $_SESSION['previousRegVis'] = basename($_SERVER['PHP_SELF']);   
    ?>
    <h2>Register Visitor</h2>
    
    <form action="action_register_visitor.php" method="post">

      <label for="vname">Name</label> <br>
      <input type="text" id="vname" name="name" placeholder="Visitor's name" value=<?php echo $_SESSION['visitorFormFill']["name"]?> > <br>
      
      <label for="vadress">Adress</label> <br>
      <input type="text" id="vadress" name="address" placeholder="Visitor's address" value=<?php echo $_SESSION['visitorFormFill']["address"]?> > <br>
      
      <label for="vgender">Gender</label> <br>
      <input type="text" id="vgender" name="gender" placeholder="Visitor's gender" value=<?php echo $_SESSION['visitorFormFill']["gender"]?> > <br>
      
      <label for="vsex">Sex</label> <br>
      <input type="text" id="vsex" name="sex" placeholder="Visitor's sex" value=<?php echo $_SESSION['visitorFormFill']["sex"]?> > <br>
      
      <label for="vbdate">Birthdate</label> <br>
      <input type="date" id="vbdate" name="birthdate" placeholder="Visitor's birth date" value=<?php echo $_SESSION['visitorFormFill']["birthdate"]?> > <br>
      
      <label for="vnumber">Phone Number</label> <br>
      <input type="number" id="vnumber" name="phonenumber" placeholder="xxxxxxxxx" value=<?php echo $_SESSION['visitorFormFill']["phonenumber"]?> > <br>
      <br>
      <input type="submit" value="Register Visitor">
    </form>
    <?php } else { ?>
      <h3>You do not have permission to register users.</h3>
    <?php } ?>
  <?php } else { ?>
    <h3>You do not have permission to register users.</h3>
  <?php } ?>

</section>