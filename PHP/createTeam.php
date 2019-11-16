<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Team Test</title>
</head>
<body>
<?php
session_name( 'user' );
session_start();
$conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');
if(!empty($_SESSION) && isset($_SESSION['email'])) {
  print($_SESSION['email']);
  if(empty($_POST)) {
    echo '<h1> Create Team </h1>';
    echo '<form action="./createTeam.php" method="post">';
    echo '<label for="groupName">Group Name: </label> <br />';
    echo '<input type="text" name="groupName" id="groupName" /> <br />';
    echo '<input type="submit" value="Submit"/>';
    echo '</form>';
  } else {
    $groupName = $_POST['groupName'];
    $userEmail = $_SESSION['email'];
    if(!$conn) {
      $e = oci_error;
      print "<br> connection failed";
      print htmlentities($e['message']);
      exit;
    }
    $sql = "INSERT INTO Team_Requests VALUES (:groupname, :useremail)";
    $query = oci_parse($conn,$sql);
    oci_bind_by_name($query, ':groupname',$groupName);
    oci_bind_by_name($query, ':useremail',$userEmail);
    if(oci_execute($query)) {
      header("Location: home.php");
    }
  }
}
?>
</body>
</html>
