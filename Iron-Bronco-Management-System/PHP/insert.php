<?php
include 'sanitize.php';
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";



	if(isset($_POST['submit'])){
		$first = prepareInput($_POST['FirstName']); //preparing to insert participant into database
		$last = prepareInput($_POST['LastName']);
		$choice = prepareInput($_POST['choice']);

		$email = prepareInput($_POST['email']);
		$name = $first." ".$last;
    $password = prepareInput($_POST['password']);
    $password = hash('sha256',$password); //hash password
		//$passcheck = $_POST['passw2'];
		//$groupName = $_POST['group'];

		/*if( $Password != $passcheck ){
			echo "Error passwords do not match, please try again\n";
		}*/

		$stid = oci_parse($c, "INSERT INTO Participant VALUES (:Email,:Name, :Password, NULL)"); //insert values into database
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

		//SET swimming = $swimToday, biking = $bikeToday, running = $runToday

		oci_commit($c);
		OCILogoff($c);

		if( $choice == 'join' ){
			header("Location: ./joinTeam.php");
			exit;

		}else if( $choice == 'create' ){
			header("Location: ./createTeam.php");
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
