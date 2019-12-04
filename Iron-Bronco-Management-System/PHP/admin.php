<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Iron Bronco Race Admin</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
function sendStuff( id, name ){
$.post("./admin_data.php", { gid: id, gname: name } ) };

</script>
</head>

<body>
<?php
  include 'sanitize.php';
  session_name('user');
  session_start();	// load session

  // check if session variable is set, and equal to ADMIN
  if(isset($_SESSION['name']) && $_SESSION['name'] == 'ADMIN') {
	  
    // connect to oracle database for user 'lshen' with password 'password'
    $conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');
    if(!$conn) {
      echo 'Failed to log into database';
    } else {


		/**Upon a post submit, page refreshes; check if any post requests have been set**/
		// Approve new group
		if( isset($_POST['newGr'])){ 
			if( $_POST['newGr'] == "Accept" ){ 
				// attempt to create new team
				$sql = "BEGIN createteam(:groupname,:useremail); END;";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':groupName',$_POST['gname']);
				oci_bind_by_name($query, ':useremail',$_POST['memEmail']);

				if(oci_execute($query)){ 
					echo 'Success. Request was accepted.';
					$sql = "DELETE FROM Team_Requests where UserEmail = :UserEmail";	// remove request
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':UserEmail', $_POST['memEmail']);

					$result = oci_execute($query);
					
					//error checking
					if (!$result) {
						$e = oci_error($query);
						trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					}

				}else
					echo "Error. Could not add member. Please check if team is full and try again.";
				
			// Reject new group
			}else if( $_POST['newGr'] == "Reject" ){
				// Delete request
				$sql = "DELETE FROM Team_Requests where UserEmail = :UserEmail";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':UserEmail', $_POST['memEmail']);

				$result = oci_execute($query);

				if (!$result) {
					$e = oci_error($query);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				} 

				if( oci_num_rows($query) )
					echo "Success. ".$_POST['memEmail']."'s request was rejected. \nIf you made a mistake, please have the individual resubmit a request.";
				else
					echo "Error, request could not be found. Request not rejected.";
			}

		// Approve name change
		}else if(isset($_POST['nchange'])){
			// Update group name
			if( $_POST['nchange'] == "Accept" ) {
				$sql = "UPDATE Team SET GroupName = :GroupName WHERE GroupID = :GroupID";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':GroupName',$_POST['gname']);
				oci_bind_by_name($query, ':GroupID',$_POST['gid']);

				if(oci_execute($query)){ 
					echo 'Success. Name was updated.';
					$sql = "DELETE FROM Group_Requests where GroupID = :GroupID";
					$query = oci_parse($conn,$sql);
					oci_bind_by_name($query, ':GroupID', $_POST['gid']);

					$result = oci_execute($query);

					if (!$result) {
						$e = oci_error($query);
						trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					}

					if( oci_num_rows($query) )
						echo "The request has been removed.";
					else
						echo "Error, request could not be removed.";


				}else
					echo "Error. Could not add member. Please check if team is full and try again.";

			// Reject name change
			}else if( $_POST['nchange'] == "Reject" ) {
				$sql = "DELETE FROM Group_Requests where GroupID = :GroupID";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':GroupID', $_POST['gid']);

				$result = oci_execute($query);

				if (!$result) {
					$e = oci_error($query);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}


				if( oci_num_rows($query) )
					echo "Success. Request to change group ".$_POST['gid']."'s name to '".$_POST['gname']."' has been rejected. \nIf you made a mistake, please have the team resubmit a request.";
				else
					echo "Error, request could not be found. Request not rejected.";
			}

		// Update group data
		}else if(isset($_POST['updateGr'])){
			// Delete group (only works for empty group
			if( $_POST['updateGr'] == "Delete Group" ) {
				$sql = "DELETE FROM Team WHERE GroupID = :GroupID";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':GroupID',$_POST['gid']);

				if(oci_execute($query)){ 
					echo 'Success. The group "'.$_POST['gname'].'" was deleted. '.$_POST['gid'];
					$sql = "DELETE FROM Group_Requests where GroupID = :GroupID";

				}else
					echo "Error. Could not delete team. Please check if team is empty and try again.";

			// Add manually inputted member
			}else if( $_POST['updateGr'] == "Add" ) {
				if(isset($_POST['email'])){
					$sql = "INSERT INTO Members VALUES (:GroupID,:userEmail)";    
					$query = oci_parse($conn,$sql);
					$email = prepareInput($_POST['email']);
					oci_bind_by_name($query, ':GroupID', $_POST['gid']);
					oci_bind_by_name($query, ':useremail', $email);

					if(oci_execute($query) ) 
							echo 'Success. Request was accepted.';
					else
						echo "Error. Could not add member with email ".$_POST['email'].". Please check if team is full and try again.";
				}else
					echo "Error. Please input the email of the member you wish to add.";
				
			// Delete group member
			}elseif($_POST['updateGr'] == "Delete") {
				$sql = "DELETE FROM Members where UserEmail = :UserEmail";
				$query = oci_parse($conn,$sql);
				oci_bind_by_name($query, ':UserEmail', $_POST['memEmail']);

				$result = oci_execute($query);

				if (!$result) {
					$e = oci_error($query);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				} else
					echo "Success. ".$_POST['memEmail']."'s account was removed from group. \nIf you made a mistake, please have the individual resubmit a request.";

			}else {
				echo "error unidentfied submit button result: ".$_POST['updateGr']."\nPlease return and try again";
				exit;
			}


			}/

	// echo HTML CODE
	// display all create team requests
	  echo "<h3> New Team Requests: </h3>";

      // select and display team request table information
      $sql = 'SELECT * FROM Team_Requests WHERE groupName NOT IN (SELECT groupName FROM Team)';
      $query = oci_parse($conn,$sql);
      oci_execute($query);
      $num_col = oci_num_fields($query);

      echo '<form action="" method="post">';

      echo '<table border=1>';
      echo '<tr><th>Group ID</th> <th>Email</th><th>Edit</th><th>Delete</th></tr>';
      while(oci_fetch($query)) {
        echo '<tr>';

        $gname = oci_result($query,1);
        $email = oci_result($query,2); 

        echo '<td>'.$gname.'</td>';
        echo '<td>'.$email.'</td>';
        	// if a button is clicked, pass the relevant variables through post
		echo '<input type="hidden" name="memEmail" id="hiddenField" value="'.$email.'" />';
		echo '<input type="hidden" name="gname" id="hiddenField" value="'.$gname.'" />';
		echo '<td> <input type="submit" name="newGr" value="Accept"></td>';
		echo '<td> <input type="submit" name="newGr" value="Reject"></td>';

        echo '</tr>';
      }
      echo '</table>';
	  echo "</form>";

	// display all name change requests
	  echo "<h3> Team Name Changes: </h3>";

      $sql = 'SELECT * FROM Group_Requests';
      $query = oci_parse($conn,$sql);
      oci_execute($query);
      $num_col = oci_num_fields($query);

	  echo '<form action="" method="post">';

      echo '<table border=1>';
      echo '<tr><th>Group ID</th> <th>Group Name</th><th>Edit</th><th>Delete</th></tr>';
      while(oci_fetch($query)) {
        echo '<tr>';

        $id = oci_result($query,1);
        $gname = oci_result($query,2);

        echo '<td>'.$id.'</td>';
        echo '<td>'.$gname.'</td>';
        	// if a button is clicked, pass the relevant variables through post
		echo '<input type="hidden" name="gid" id="hiddenField" value="'.$id.'" />';
		echo '<input type="hidden" name="gname" id="hiddenField" value="'.$gname.'" />';
		echo '<td> <input type="submit" name="nchange" value="Accept"></td>';
		echo '<td> <input type="submit" name="nchange" value="Reject"></td>';

        echo '</tr>';
      }
      echo '</table>';
	  echo "</form>";






	// display all information in the currently existing team tables
	  echo "<h3> Manage current teams: </h3>";

      $sql = 'SELECT * FROM Team';
      $query = oci_parse($conn,$sql);
      oci_execute($query);
      $num_col = oci_num_fields($query);



	  $count = 0;

      echo '<table border=1>';
      echo '<tr><th>ID</th> <th>Group Name</th><th></th><th></th><th></th></tr>';
      while(oci_fetch($query)) {
        echo '<tr>';


        $id = oci_result($query,1);
        $gname = oci_result($query,2);
		$_SESSION['gid'] = $id;
		$_SESSION['gname'] = $gname;

        echo '<td>'.$id.'</td>';
        echo '<td>'.$gname.'</td>';

        $sql = "SELECT UserEmail FROM Members WHERE groupid = :GroupId";

        //Parse query
        $q2 = oci_parse($conn,$sql);

		//Bind variables
		oci_bind_by_name($q2, ':GroupId', $id);

        //Execute query
        $result = oci_execute($q2);
		if (!$result) {
			$e = oci_error($q2);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

	  echo '<form action="" method="post">';        
        	// if a button is clicked, pass the relevant variables through post
		echo '<input type="hidden" name="gid" id="hiddenField" value="'.$id.'" />';
		echo '<input type="hidden" name="gname" id="hiddenField" value="'.$gname.'" />';
		echo '<td> Add a member (by email): <input type="text" name="email" placeholder="email"> </td>';
		echo '<td> <input type="submit" name="updateGr" value="Add" style="width:100%">    </td>';
		echo '<td> <input type="submit" name="updateGr" value="Delete Group"></td>';



		while($row = oci_fetch_assoc($q2)){

			echo "<tr>\n";
			echo "<td></td>\n";
			echo "<td></td>\n";
			echo '<td>'.$row["USEREMAIL"]."</td>\n";
			echo '<input type="hidden" name="memEmail" id="hiddenField" value="'.$row["USEREMAIL"].'" />'; // form information for manually inputed new member
			
			echo '<td> <input type="submit" name="updateGr" value="Delete"></td>';

			
			echo "</tr>\n";
		}
	  echo "</form>";

        echo '</tr>';
		$count++;
      }
      echo '</table>';





      OCIFreeStatement($query);
    }

	}else {
		echo "Error; you do not have permission to access this page\n";
  }
?>
</body>
</html>
