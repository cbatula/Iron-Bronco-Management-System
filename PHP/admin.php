<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Iron Bronco Race Admin</title>
</head>
<body>
<?php
  session_name('user');
  session_start();
  if($_SESSION['name'] == 'Admin') {
    $conn = OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');
    if(!$conn) {
      echo 'Failed to log into database';
    } else {
      $sql = 'SELECT * FROM TEAM_REQUESTS';
      $query = oci_parse($conn,$sql);
      oci_execute($query);
      $num_col = oci_num_fields($query);
      echo '<table border=1>';
      echo '<tr><th>Group Name</th> <th>User Email</th> <th>Decline</th> <th>Accept</th></tr>';
      while(oci_fetch($query)) {
        echo '<tr>';
        for ($i=1;$i <= $num_col; $i++) {
          $col_value = oci_result($query,$i);
          echo '<td>$col_value</td>';
        }
        echo '<td><button>Decline</button></td> <td><button>Accept</button></td>';
        echo '</tr>';
      }
      echo '</table>';
      OCIFreeStatement($query);
    }
  }
?>
</body>
</html>
