<html>
    <head>
        <title> Team Page </title>
    </head>
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

		//$_SESSION['groupId'] = 0;

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
				$sql = "BEGIN jointeam(:groupname,:useremail); END;";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':groupname',$groupName);
				oci_bind_by_name($query, ':useremail',$_POST['email']);

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
					} else
						echo "Success. Request was rejected. \nIf you made a mistake, please have the individual resubmit a request.";

			}else{
				echo "error unidentfied submit button result: ".$_POST['option']. "\nPlease return and try again";
				exit;
			}
		}







		echo "<h1> Team $groupName </h1>";
		echo "<h3> Team Members: </h3>";

        $sql = "SELECT * FROM Members WHERE groupid = :GroupId";

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

		while($row = oci_fetch_assoc($query)){
			foreach ($row as $item) {
				echo ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;");
			}
		}
        //Define table


		echo "<h3> Pending Team Membership Requests </h3>";

		echo '<form action="" method="post">';

        echo "<table border=1>";
        //echo "<tr><th>Email</th> <th>Accept</th> <th>Reject</th></tr>";

		echo "<td>fake email</td>\n";
		echo '<td> <input type="submit" name="option" value="Accept"></td>';
		echo '<td> <input type="submit" name="option" value="Reject"></td>';

		while($row = oci_fetch_assoc($query)){
			echo "<tr>\n";
			foreach ($row as $item) {
				echo "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
				echo '<input type="hidden" name="email" id="hiddenField" value="'.$item.'" />';

				echo '<td> <input type="submit" name="option" value="Accept"></td>';
				echo '<td> <input type="submit" name="option" value="Reject"></td>';
			}
			echo "</tr>\n";
		}
		
        echo "</table>";

		echo "</form>";
		


        OCIFreeStatement($query);
        OCILogOFF($conn);        
?>
    </body>
</html>
