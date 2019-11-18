<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Team Request</title>
  <link rel="stylesheet" type="text/css" href="../CSS/template.css" />
<style>
  fieldset {
    margin-top: 15px;
  }
</style>
</head>
<body>
<img class="logo" src="https://www.scu.edu/media/offices/umc/scu-brand-guidelines/visual-identity-amp-photography/visual-identity-toolkit/logos-amp-seals/Mission-Horizontal-PMS201.png" alt="Trulli">
<?php
session_name( 'user' );
session_start();
$conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');
if(!empty($_SESSION) && isset($_SESSION['email'])) {
  if(empty($_POST)) {
    echo '<div class="header"> <h1> New Team Request</h1> </div>';
    echo '<div class="center">';
    echo '<form action="./createTeam.php" method="post">';
    echo '<fieldset class="fieldset-auto-width">';
    echo '<label for="groupName">Please input the desired group name </label> <br /> <br />';
    echo '<input type="text" name="groupName" id="groupName" /> <br /> <br />';
    echo '<input type="submit" value="Submit"/>';
    echo '</fieldset>';
    echo '</form>';
    echo '</div>';
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
