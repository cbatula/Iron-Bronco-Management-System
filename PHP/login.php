<?php
$c = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if($c)
{
	echo "Connected to Oracle\n";
	
	session_name( 'user' );
        session_start();

	$email=$_POST["email"];
	$password=$_POST["password"];	

	$stid_get = oci_parse($c, "SELECT curpassword FROM participants WHERE email = :userEmail");

 	oci_bind_by_name($stid_get,':userEmail',$email);
	

        $tf = oci_execute($stid_get);

        if(!$tf)
	{
		echo "Error in get statement";
                exit;
        }
               	
	$row = oci_fetch_assoc($stid_get);

//	if(!$row){}
	
	if(strcmp($row['CURPASSWORD'],$_POST['password']) == 0)
	{
		$_SESSION["email"] = $email;
		
		$stid = oci_parse($c, "SELECT groupId FROM Members WHERE email = :UserEmail");
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
	}
	else
	{	
		echo "Incorrect Password try again";
		oci_commit($c);
		OCILogoff($c);
		exit();
	}

}
else
	echo "Could not connect to Oracle\n";
