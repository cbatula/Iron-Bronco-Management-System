<html>
    <head>
        <title> Team Page </title>
    </head>
<link rel="stylesheet" type="text/css" href="../css/teamTemplate.css">
	<body>


<?php
        // Connect to Oracle database
        // Data base need to have relation
        //   FormTest(fullname varchar, age integer)
        // for form to work
        $conn=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');
        //Show error and don't load the page if unsuccessful connection
        if(!$conn) {
            $e = oci_error;
            print "<br> connection failed:";
            print htmlentities($e['message']);
            exit;
        }

		session_name( 'user' );
		session_start();

        /**Fetch group name**/
        $sql = "SELECT GroupName FROM Team WHERE groupid = :GroupId";
        $query = oci_parse($conn,$sql);				//Parse query
		oci_bind_by_name($query, ':GroupId', $_SESSION['groupId']);		//Bind variables
        $result = oci_execute($query);        		//Execute query

		if (!$result) {
			$e = oci_error($query);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$row = oci_fetch_assoc($query);
        $groupName = $row['GROUPNAME'];







		/**Check for any accept/reject requests**/
		if(isset($_POST['option'])){
			if( $_POST['option'] == "Accept" ){ 
				$sql = "INSERT INTO Members VALUES (:GroupId, :userEmail)";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':GroupId', $_SESSION['groupId']);
				oci_bind_by_name($query, ':userEmail', $_POST['email']);

				if(oci_execute($query)){ 
				    echo 'Success. Request was accepted.';
					$sql = "DELETE FROM Team_Requests where UserEmail = :UserEmail";
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':UserEmail', $_POST['email']);

					$result = oci_execute($query);

					if (!$result) {
						$e = oci_error($query);
						trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					}

				}else
					echo "Error. Could not add member. Please check if team is full and try again.";
			
			}else if( $_POST['option'] == "Reject" ){

					$sql = "DELETE FROM Team_Requests where UserEmail = :UserEmail";
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':UserEmail', $_POST['email']);

					$result = oci_execute($query);

					if (!$result) {
						$e = oci_error($query);
						trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					} 
					if( oci_num_rows($query) )
						echo "Success. ".$_POST['email']."'s request was rejected. \nIf you made a mistake, please have the individual resubmit a request.";
					else
						echo "Error, request could not be found. Request not rejected.";file:///usr/share/doc/HTML/index.html


			}else if($_POST['option'] == "Submit"){
				if(isset($_POST['newName'])){
					$sql = "INSERT INTO Group_Requests VALUES (:GroupId,:GroupName)";    
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':GroupId', $_SESSION['groupId']);
					oci_bind_by_name($query, ':GroupName', $_POST['newName']);

					if(oci_execute($query) ) 
							echo 'Success. Request was accepted.';
					else
						echo "Error. Could not add member with email ".$_POST['email'].". Please check if team is full and try again.";
				}else
					echo "Error. Please input your new group name.";


			}else if($_POST['option'] == "Delete"){
				$sql = "SELECT COUNT(*) FROM Members WHERE groupId = :gId";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':gId', $_SESSION['groupId']);
				$result = oci_execute($query);

				if (!$result) {
					$e = oci_error($query);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				$row = oci_fetch_assoc($query);

				if( $row['COUNT(*)'] > 1 ){
					$sql = "DELETE FROM Members where UserEmail = :UserEmail";
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':UserEmail', $_POST['email']);

					$result = oci_execute($query);

					if (!$result) {
						$e = oci_error($query);
						trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					} else
						echo "Success. ".$_POST['email']."'s account was removed from group. \nIf you made a mistake, please have the individual resubmit a request.";
				}else
					echo "Error, you cannot delete the last member in a group.\n";
			}else{
				echo "error unidentfied submit button result: ".$_POST['option']. "\nPlease return and try again";
				exit;
			}
		}







		echo "<center><h1> Team: $groupName </h1>";
		echo "<h3> Team Members: </h3>";

        $sql = "SELECT UserEmail FROM Members WHERE groupid = :GroupId";

        //Parse query
        $query = oci_parse($conn,$sql);

		//Bind variables
		oci_bind_by_name($query, ':GroupId', $_SESSION['groupId']);

        //Execute query
        $result = oci_execute($query);
		if (!$result) {
			$e = oci_error($query);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

	  echo '<form action="" method="post">';

      echo '<table border=0';
      echo '<tr><th> Email </th> <th> Name </th><th> Delete </th</tr></center>';

		while($row = oci_fetch_assoc($query)){
			echo "<tr>\n";
			echo '<td>'.$row["USEREMAIL"]."</td>\n";


        	$sql = "SELECT Name FROM Participant WHERE Email = :Email";
		    //Parse query
		    $q2 = oci_parse($conn,$sql);

			//Bind variables
			oci_bind_by_name($q2, ':Email', $row["USEREMAIL"]);

		    //Execute query
		    $result = oci_execute($q2);
			if (!$result) {
				$e = oci_error($q2);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$nameArr = oci_fetch_assoc($q2);
			echo '<center>';
			echo '<td>'.$nameArr["NAME"]."</td>\n";
			echo '<input type="hidden" name="email" id="hiddenField" value="'.$row["USEREMAIL"].'" />';
			echo '<td> <input type="submit" name="option" value="Delete"></td>';
			echo "</tr>\n";
			echo '</center>';
		}

        echo "</table>";


        //Define table


		echo "<center><h3> Pending Team Membership Requests </h3></center>";

        echo "<table border=0>";
        //echo "<tr><th>Email</th> <th>Accept</th> <th>Reject</th></tr>";


        $sql = "SELECT UserEmail FROM Team_Requests WHERE groupName = :groupName";

        //Parse query
        $query = oci_parse($conn,$sql);

		//Bind variables
		oci_bind_by_name($query, ':groupName', $groupName);

        //Execute query
        $result = oci_execute($query);
		if (!$result) {
			$e = oci_error($query);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		while($row = oci_fetch_assoc($query)){
			echo "<tr>\n";
			foreach ($row as $item) {
				echo '<center>';
				echo "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
				echo '<input type="hidden" name="email" id="hiddenField" value="'.$item.'" />';

				echo '<td> <input type="submit" name="option" value="Accept"></td>';
				echo '<td> <input type="submit" name="option" value="Reject"></td>';
				echo '</center>';
			}
			echo "</tr>\n";
		}
		
        echo "</table>";

		echo '<center>Send a request to change the group name to:<br><br> <input type="text" name="newName" value="New Group Name"> <br><br>   <input type="submit" name="option" value="Submit"></center>
';

		echo "</form>";
		


        OCIFreeStatement($query);
        OCILogOFF($conn);        
?>
    </body>
</html>
