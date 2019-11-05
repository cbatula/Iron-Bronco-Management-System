<?php
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {

echo "Successfully connected to Oracle.\n";

OCILogoff($c);

} else {

$err = OCIError();

echo "Connection failed." . $err[text];

}

?>
