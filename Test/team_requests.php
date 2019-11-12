<html>
    <head>
        <title> Team Requests Table </title>
    </head>
    <body>
        <h1> Team Requests Table </h1>
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
        //Testing to show what is in FormTest
        $sql = "select * from Team_Requests Order by groupname";
        //Parse query
        $query = oci_parse($conn,$sql);
        //Execute query
        oci_execute($query);
        //Store number of attributes
        $num_col = oci_num_fields($query);
        //Define table
        echo "<table border=1>";
        //Define table header with attribute name
        echo "<tr><th>Group Name</th> <th>User Email</th></tr>";
        //Get each row of query
        while(oci_fetch($query)) {
            echo "<tr>";
            for ($i=1;$i <= $num_col; $i++) {
                //Get each attribute of the row of query fetched
                $col_value = oci_result($query,$i);
                //Put the value in table data
                echo "<td>$col_value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        //Free connection
        OCIFreeStatement($query);
        OCILogOFF($conn);        
?>
    </body>
</html>
