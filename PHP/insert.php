<?php
$first = $_POST['FirstName']
$last = $_POST['LastName']

$Email = $_POST['email'];
$Name = $first." ".$last;
$Password = $_POST['password'];
$GroupName = $_POST['group'];

if(!empty($first) || !empty($last) || !empty($Email) || !empty($Password) || !empty($GroupName) ) {

} else {
	echo "All fields are required";
	die();
}
?>
