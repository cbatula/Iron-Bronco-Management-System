<?php
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";

	$first = $_POST['FirstName'];
	$last = $_POST['LastName'];

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

	oci_commit($c);
	OCILogoff($c);

} else {
	$err = OCIError();
	echo "Connection failed." . $err[text];
}

?>
