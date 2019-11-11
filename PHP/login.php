<?php
$c = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if($c)
{
	echo "Connected to Oracle\n";
	
	session_name( 'user' );
        session_start();

	$_SESSION["email"] = $_POST["email"];
	$email=$_POST["email"];
	$password=$_POST["password"];	

	$stid_get = oci_parse($c, "SELECT password FROM participants WHERE userEmail = :userEmail");

 	oci_bind_by_name($stid_get,':userEmail',$email);
	

        $tf = oci_execute($stid_get);

        if(!$tf)
	{
		echo "Error in get statement";
                exit;
        }
               	
	$row = oci_fetch_assoc($stid_get);

//	if(!$row){}
	
	if(strcmp($row['PASSWORD'],$_POST['password']) == 0)
	{
 		oci_commit($c);
		OCILogoff($c);
		header("Location: home.php");
		exit();
	}
	
	echo "Incorrect Password try again";



}









