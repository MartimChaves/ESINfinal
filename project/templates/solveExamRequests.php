<?php 
if (isset($_SESSION['loggedIn_Emp']) && $_SESSION['loggedIn_Emp']->returnType() == 'technician') {
    ?>
    <table>
        <tr>
        <th>Exam request ID</th>
        <th>Date</th>
        <th>Health Center</th>
        <th>Solve Exam Request</th>
        </tr>
        <?php 
        require_once('database/person.php');
				require_once('database/medicalRecords.php');
				require_once('database/physicalSpaces.php');
        $id_specialty = $_SESSION['loggedIn_Emp']->returnSpecialty();
        $examRequests = returnExamRequests($id_specialty);
				$hc = returnHealthCentres();
        //print_r($hc);
        foreach ($examRequests as $request){
            ?>
                <tr>
                    <form action="action_solve_examRequest.php" method="post">
											<input type="hidden" name="id_examRequest" value=<?php echo $request['id_examRequests'] ?>>
											<input type="hidden" name="id_cr" value=<?php echo $request['id_cr'] ?>>
											<input type="hidden" name="id_employee_technician" value=<?php echo $_SESSION['loggedIn_Emp']->returnID() ?>>
											<td><?php echo $request['id_examRequests'] ?></td>
											<td><input type="date" name="examDate"> <br></td>
											<td>
												<select name="id_healthCentre"> 
													<?php 
													$healthCentres = returnHealthCentres();
													foreach ($healthCentres as $centre) {
														?>
														<option value=<?php echo $centre['id_hc'] ?>><?php echo $centre['id_hc'] . "-" . $centre['name'] ?></option>
														<?php
													}
													?>
												</select>
											</td>
											<td><input type="submit" value="Solve Exam Request"></td>
                    </form>
                </tr>                
            <?php
        }
        ?>

    </table>
    
    <?php 
}
?>

