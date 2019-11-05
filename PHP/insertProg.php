<?php
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";


		session_name( 'user' );
		session_start();
	
		$date=$_POST["date"];

		$stid_get = oci_parse($c, "SELECT swimming, biking, running FROM race_progress WHERE userEmail = :userEmail AND time = TO_DATE('$date','YY-MM-DD')");

		$email=$_POST["email"];


		oci_bind_by_name($stid_get, ':userEmail', $email);
		

		$tf=oci_execute($stid_get);
		

		if(!$tf){
			echo "Error in get statement";			
			exit;
		}									//use try catch


		$row = oci_fetch_assoc($stid_get);

//		$_SESSION["running"] = $running;
//		$_SESSION['swimming'] = $swimming;
//		$_SESSION['biking'] = $biking;
		

		$running = $_POST['runningDist'];
		$swimming = $_POST['swimmingDist'];
		$biking = $_POST['bikingDist'];

    if($row) {
      $running += $row['RUNNING'];
      $swimming += $row['SWIMMING'];
      $biking += $row['BIKING'];
      $stid_post = oci_parse($c, "UPDATE race_progress SET swimming= :swimming,biking= :biking,running= :running WHERE userEmail= :email AND time = TO_DATE('$date','YY-MM-DD')");
      oci_bind_by_name($stid_post, ':email', $email);
      oci_bind_by_name($stid_post, ':running', $running);
      oci_bind_by_name($stid_post, ':swimming', $swimming);
      oci_bind_by_name($stid_post, ':biking', $biking);

      $tf1=oci_execute($stid_post);

      if (!$tf1) {
        echo "Error in preparing the statement";
        exit;
      }

      print "Inputs Recorded.\n";

    } else {
      $sql = "INSERT INTO race_progress VALUES (:email,TO_DATE('$date','YY-MM-DD'),:swimming,:biking,:running)";
      $stid = oci_parse($c,$sql);
      oci_bind_by_name($stid_post, ':email', $email);
      oci_bind_by_name($stid_post, ':running', $running);
      oci_bind_by_name($stid_post, ':swimming', $swimming);
      oci_bind_by_name($stid_post, ':biking', $biking);
      $tf1 = oci_execute($stid);
      if(!$tf1) {
        echo "Error in preparing the statement";
        exit;
      }
      print "Inputs Recorded for insert.\n";
    }
		//SET swimming = $swimToday, biking = $bikeToday, running = $runToday

		oci_commit($c);
		OCILogoff($c);

  	

} else {
	$err = OCIError();
	echo "Connection failed." . $err[text];
}

?>
