<?php
  if(!empty($_POST)) {
    $groupName = $_POST['groupName'];
    //userEmail will be $_SESSION['userEmail'] after login system is done
    $userEmail = $_POST['userEmail'];
    require '/webpages/lshen/dbInfo.php';
    $conn=oci_connect($uname,$pword,$db);
    if(!$conn) {
      $e = oci_error;
      print "<br> connection failed";
      print htmlentities($e['message']);
      exit;
    }

     $sql = " BEGIN :v := joinTeamStatus(:groupname,:useremail); END;";
     $query = oci_parse($conn,$sql);
     oci_bind_by_name($query,':groupname',$groupName);
     oci_bind_by_name($query,':useremail',$userEmail);
     oci_bind_by_name($query,':v',$returnVal);
     if(oci_execute($query)) {
       echo 'Function called successfully <br/>';
       if($returnVal < 3) {
         echo 'User inserted into team <br />';
       }
     }
  }
    
    session_name( 'user' );
    session_start();
    $stid = oci_parse($conn, "SELECT groupId FROM team WHERE groupName = :groupName");
    oci_bind_by_name($stid, ':groupName', $groupName);
    oci_execute($stid);
    if (!$stid) {
        echo "Error in preparing the statement";
        exit;
    }
    $row = oci_fetch_assoc($stid); 
    $_SESSION['groupId'] = $row['groupId'];
    
?>
