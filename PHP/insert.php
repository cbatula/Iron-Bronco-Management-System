<?php
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";

	if(isset($_POST['submit'])){
		$first = $_POST['FirstName'];
		$last = $_POST['LastName'];
		$choice = $_POST['choice'];

		$email = $_POST['email'];
		$name = $first." ".$last;
		$password = $_POST['password'];
		//$passcheck = $_POST['passw2'];
		//$groupName = $_POST['group'];

		/*if( $Password != $passcheck ){
			echo "Error passwords do not match, please try again\n";
		}*/

		$stid = oci_parse($c, "INSERT INTO Participant VALUES (:Email,:Name, :Password, NULL)");
		oci_bind_by_name($stid, ':Email', $email);
		oci_bind_by_name($stid, ':Name', $name);
		oci_bind_by_name($stid, ':Password', $password);

		oci_execute($stid);

		if (!$stid) {
			echo "Error in preparing the statement";
			exit;
		}

		print "Record Inserted";
		
		
		session_name( 'user' );
		session_start();
		
		$_SESSION['email'] = $email;
		$_SESSION['name'] = $name;
		$_SESSION['password'] = $password;


		//SET swimming = $swimToday, biking = $bikeToday, running = $runToday

		oci_commit($c);
		OCILogoff($c);

		if( $choice == 'join' ){
			header("Location: ../HTML/joinTeam.html");
			exit;

		}else if( $choice == 'create' ){
			header("Location: ../HTML/createTeam.html");
			exit;

		}else{
			echo "Error, please go back and choose a group option";
			exit;
		}
  	}

	else{
		$err = OCIError();
		echo "No Input.";
	}
} else {
	$err = OCIError();
	echo "Connection failed." . $err[text];
}

?>
