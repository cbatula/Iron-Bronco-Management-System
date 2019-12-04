<?php
		$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";


		session_name( 'user' );
		session_start();
	
		$date=$_SESSION["date"];

		$stid_get = oci_parse($c, "SELECT swimming, biking, running FROM race_progress WHERE userEmail = :userEmail AND time = TO_DATE('$date','YY-MM-DD')");

		$email=$_SESSION["email"];


		oci_bind_by_name($stid_get, ':userEmail', $email);
		

		$tf=oci_execute($stid_get);
		

		if(!$tf){
			echo "Error in get statement";			
			exit;
		}									//use try catch


		$row = oci_fetch_assoc($stid_get);


      	$running = $row['RUNNING'];
      	$swimming = $row['SWIMMING'];
      	$biking = $row['BIKING'];
?>

<html>
	<center><h1>
		Change Today's Progress
	</h1></center>

	<body>
		<center> Values will <b>perminently</b> change today's total distances.
		<form action="../PHP/changeInput.php", method="post">
		<form action="./changeInput.php", method="post">

			<center><fieldset class="fieldset-auto-width">		
		
				<p>
        	  			<label>Running</label>
          				<input type = "text"
          		      		name = "runningDist"
        	  	      		value =  "<?php echo $running;?>" />
       				</p>
	
				<p>
        	  			<label>Swimming</label>
        		  		<input type = "text"
        		        	name = "swimmingDist"
       			         	value = "<?php echo $swimming;?>" />
       				</p>
		
				<p>
          				<label>Biking</label>
          				<input type = "text"
               		 		name = "bikingDist"
                			value = "<?php echo $biking?>" />
       				</p>
				<p>
        	  			<label></label>
        		  		<input type = "submit"
        		        	name = "submit"
       			         	value = "Submit" />
       				</p>

			</fieldset>
		</form>

<p>
</p>
		

 <a href="../PHP/home.php">
		<button type="button">Go Back</button></a> <br/>

		</center>
	</body>

</html>

<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
    }
</style>
<div>

<?php

		$runningInput = $_POST['runningDist'];
		$swimmingInput = $_POST['swimmingDist'];
		$bikingInput = $_POST['bikingDist'];

      	$stid_post = oci_parse($c, "UPDATE race_progress SET swimming= :swimming,biking= :biking,running= :running WHERE userEmail= :email AND time = TO_DATE('$date','YY-MM-DD')");
      	oci_bind_by_name($stid_post, ':email', $email);
      	oci_bind_by_name($stid_post, ':running', $runningInput);
      	oci_bind_by_name($stid_post, ':swimming', $swimmingInput);
      	oci_bind_by_name($stid_post, ':biking', $bikingInput);

      	$tf1=oci_execute($stid_post);

      	if (!$tf1) {
       	 	echo "Error in preparing the statement";
        	exit;
      	}

      	print "Inputs Recorded.\n";

		oci_commit($c);
		OCILogoff($c);

		header("Location: ../HTML/home.php");
		exit();

  	

} 
else {
	$err = OCIError();
	echo "Connection failed." . $err[text];
}

?>
