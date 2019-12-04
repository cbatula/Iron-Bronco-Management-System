<?php
include 'sanitize.php';
$c = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if($c) {
	echo "Connected to Oracle\n";
	session_name( 'user' );
  session_start();

	$email=prepareInput($_POST["j_username"]);
	$password=prepareInput($_POST["password"]);	
  $password=hash('sha256',$password);

	$stid_get = oci_parse($c, "SELECT curpassword, name FROM participant WHERE Email = :Email");

 	oci_bind_by_name($stid_get,':Email',$email);
  $tf = oci_execute($stid_get);
  if(!$tf){
		echo "Error in get statement";
    exit;
  }             	
	$row = oci_fetch_assoc($stid_get);

	if(strcmp($row['CURPASSWORD'],$password) == 0) {
		$_SESSION["email"] = $email;
    $_SESSION['name'] = strtoupper($row['NAME']);

    if($_SESSION['name'] == "ADMIN") {
      header("Location: admin.php");
		  exit();
    }

		$stid = oci_parse($c, "SELECT groupId FROM Members WHERE UserEmail = :UserEmail");
		oci_bind_by_name($stid, ':UserEmail', $email);
		oci_execute($stid);

		if (!$stid) {
			echo "Error in preparing the statement";
			exit;
		}
		
    $row = oci_fetch_assoc($stid);
    if($row != false)
      $_SESSION["groupId"] = $row['GROUPID'];
 		oci_commit($c);
		OCILogoff($c);
		header("Location: home.php");
		exit();
	} else {	
		echo "Incorrect Password try again";
		oci_commit($c);
		OCILogoff($c);
		exit();
	}

}
else
	echo "Could not connect to Oracle\n";
