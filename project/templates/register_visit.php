
<section id="register">
  <?php 
    //echo $_POST['id_patient'];
  ?>

  <h2> Register Visit </h2>

  <form action="action_register_visit.php" id="rvisit" method="post">
    <label for="rvisit">Select Registered Visitor:</label>
    <?php 
    if (isset($_SESSION['id_patient'])){
      ?>
        <input type="hidden" name="id_patient" value=<?php echo $_SESSION['id_patient'] ?>>
      <?php
    } else {
      ?>
        <input type="hidden" name="id_patient" value=<?php echo $_POST['id_patient'] ?>>
      <?php
    }
    ?>  

    <select name="id_visitor">
        <?php 
          require_once('database/person.php');
          $visitorsInfo = returnVisitors();
          foreach ($visitorsInfo as $visitor) {
            ?>
            <option value=<?php echo $visitor['id_visitor'] ?>><?php echo $visitor['id_visitor'] . "-" . $visitor['name'] ?></option>
            <?php
          }
        ?>
    </select> <br>

    <input type="submit" value="Register Visit"> <br> <br> <br>

  </form>

  <!-- Quickly add a new visitor, if needed -->
  <button id="regiserVisitorBtn">Register New Visitor</button>

  <!-- visitor modal -->
  <div id="addVisitorModal" class="visitorModal">
    <div class="visitorModal-content">
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
      <span class="close">&times;</span>
      <p>Register New Visitor:</p>

      <form action="action_register_visitor.php" method="post">

        <?php 
          if (isset($_SESSION['id_patient'])){
            ?>
              <input type="hidden" name="id_patient" value=<?php echo $_SESSION['id_patient'] ?>>
            <?php
          } else {
            ?>
              <input type="hidden" name="id_patient" value=<?php echo $_POST['id_patient'] ?>>
            <?php
          }

        ?>

        <input type="hidden" name="inModal" value=<?php echo "1" ?>>
        
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

    </div>
  </div>

  <script type="text/javascript" src="js/visitorModal.js"></script>

</section>



