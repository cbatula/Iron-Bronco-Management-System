<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Join Team Test</title>
</head>
<body>
  <h1>Join Team</h1>
  <form action="./joinTeam_s.php" method='post'>
<?php
  $conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');  
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
?>
    <!-- Have userEmail as a form value for testing, this will be a session variable-->
    <label for="userEmail">User Email: </label> <br />
    <input type="text" name="userEmail" id="userEmail" /> <br />
    <input type="submit" value="Submit"/>
  </form>
</body>
</html>
