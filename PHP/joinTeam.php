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
    $sql = "BEGIN jointeam(:groupname,:useremail); END;";
    $query = oci_parse($conn,$sql);
    oci_bind_by_name($query, ':groupname',$groupName);
    oci_bind_by_name($query, ':useremail',$userEmail);
    if(oci_execute($query)) {
      echo 'Function called successfully.';
      //Don't know if actually inserted or not (either constraint of 3 not satisfied or successfully joined)
    }
  }
?>
