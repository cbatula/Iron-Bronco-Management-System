<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Join Team Test</title>
</head>
<body>
<?php
  session_name('user');
  session_start();
  if(!empty($_SESSION) && isset($_SESSION['email'])) {
    $conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');  
    if(empty($_POST) || ( isset($_POST['groupName']) && $_POST['groupName'] == 'SELECT A GROUP')) {
      echo '<h1>Join Team</h1>';
      echo '<form action="./joinTeam.php" method="post">';
      if(isset($_POST['groupName'])) {
        echo '<h3>Please choose a group</h3>';
      }
      if(!$conn) {
        //Connection failed, use input box
        echo '<label for="groupName">Group Name: </label> <br />';
        echo '<input type="text" name="groupName" id="groupName" /> <br />';
      } else {
        $sql = 'SELECT GROUPNAME, COUNT(GROUPNAME) FROM TEAM INNER JOIN MEMBERS ON TEAM.GROUPID = MEMBERS.GROUPID GROUP BY GROUPNAME HAVING COUNT(GROUPNAME) < 3';
        $stid = oci_parse($conn,$sql);
        if(!oci_execute($stid)) {
          //Fail to execute query, use input box
          echo '<label for="groupName">Group Name: </label> <br />';
          echo '<input type="text" name="groupName" id="groupName" /> <br />';
        } else {
          echo '<label for="groupName">Group Name: </label> <br />';
          echo '<select name="groupName" id = "groupName">';
          echo '<option selected="selected">SELECT A GROUP</option>';
          while(($row = oci_fetch_assoc($stid)) != false) {
            echo '<option value="'.$row[GROUPNAME].'">'.$row[GROUPNAME].'</option>';
          }
          echo '</select> <br />';
        }
      }
      // Have userEmail as a form value for testing, this will be a session variable
      // echo '<label for="userEmail">User Email: </label> <br />';
      // echo '<input type="text" name="userEmail" id="userEmail" /> <br />';
      echo '<input type="submit" value="Submit"/>';
      echo '</form>';
    } else {
      $groupName = $_POST['groupName'];
      $userEmail = $_SESSION['email'];
      $sql = "INSERT INTO Team_Requests VALUES (:groupname, :useremail)";
      $stid = oci_parse($conn,$sql);
      oci_bind_by_name($stid,':groupname',$groupName);
      oci_bind_by_name($stid,':useremail',$userEmail);
      if(oci_execute($stid)) {
        header("Location: home.php");
      }
    }
  }
?>
</body>
</html>
