<?php
  if(!empty($_POST)) {
    $groupName = $_POST['groupName'];
    require '/webpages/lshen/dbInfo.php';
    $conn=oci_connect($uname,$pword,$db);
    if(!$conn) {
      $e = oci_error;
      print "<br> connection failed";
      print htmlentities($e['message']);
      exit;
    }
    $sql = "BEGIN createteam(:groupname,:useremail); END;";
    $query = oci_parse($conn,$sql);
    oci_bind_by_name($query, ':groupname',$groupName);
    oci_bind_by_name($query, ':useremail',);
    if(oci_execute($query)) {
      echo 'Group successfully created.';
    }
  }
?>
