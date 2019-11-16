<!DOCTYPE html>
<html lang="en">
<head>
<title>Iron Bronco Home</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
* {
  box-sizing: border-box;
}

<?php
$c=OCILogon("lshen", "password",'//dbserver.engr.scu.edu/db11g');

if ($c) {
	echo "Successfully connected to Oracle.\n";

		session_name( 'user' );
		session_start();

		$stid = oci_parse($c, "SELECT SUM(swimming), SUM(biking),SUM(running) FROM race_progress INNER JOIN members ON race_progress.useremail = members.useremail WHERE groupid = :GroupId");

		$id = 0;

		//$_SESSION['groupId'] = 0;

		if (!$stid) {
			$e = oci_error($c);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		//assuming group ID has been saved to session
		oci_bind_by_name($stid, ':GroupId', $_SESSION['groupId']);


		$result = oci_execute($stid);
		if (!$result) {
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$row = oci_fetch_assoc($stid);

		if( $row['SUM(SWIMMING)'] == NULL )
			$row['SUM(SWIMMING)'] = 0;

		if( $row['SUM(BIKING)'] == NULL )
			$row['SUM(BIKING)'] = 0;

		if( $row['SUM(RUNNING)'] == NULL )
			$row['SUM(RUNNING)'] = 0;

		oci_commit($c);
		OCILogoff($c);

} else {
	$err = OCIError();
	echo "Connection failed." . $err[text];
}

?>


body {
  font-family: Arial, Helvetica, sans-serif;
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" />
    margin: 0
}

.container {
    display: flex; 
}

/* Style the header */
header {
  background-color: #f7f7f7;
  padding: 30px;
  text-align: center;
  font-size: 35px;
  color: DarkRed;
}

nav {
  float: left;
  width: 20%;
  height: 100%; /* only for demonstration, should be removed */
  position: absolute;
  background: #ccc;
  padding: 20px;
}

article {
  float: left;
  padding: 20px;
  width: 80%;
  background-color: #FFF;
  height: 100%; 
}



/* Style the list inside the menu */
nav ul {
  list-style-type: none;
  padding: 0;
}


/* Clear floats after the columns */
section:after {
  content: "";
  display: table;
  clear: both;
}

/* Style the footer */
footer {
  position : absolute;
  bottom : 0;$_SESSION['groupId']
  width: 100%;
  background-color: #777;
  padding: 10px;
  text-align: center;
  color: white;
}

/* Responsive layout - makes the two columns/boxes stack on top of each other instead of next to each other, on small screens */
@media (max-width: 600px) {
  nav, article {
    width: 100%;
    height: auto;
  }
}
</style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Swimming', 'Biking', 'Running'],

		<?php if($row['SUM(SWIMMING)'] != 0 && $row['SUM(BIKING)'] != 0 && $row['SUM(RUNNING)'] != 0 )

echo "[2, ".$row['SUM(SWIMMING)'].", ".$row['SUM(BIKING)'].", ".$row['SUM(RUNNING)']."]," ?>
        ]);

        var options = {
          
          chart: {

          }
          
          
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

		$(window).resize(function(){ drawChart();
		});
      }
    </script>

</head>
<body>

<header>
  <h2>Santa Clara University</h2>
  	<h3 style="color:DimGrey;font-size:80%">Jesuit</h3>
</header>

<section>

  <nav>
    <ul>
      <li><a href="../HTML/input.html">Update progress</a></li>
      <li><a href="./team.php">View team</a></li>
      <li><a href="./logout.php">Log out</a></li>
    </ul>
  </nav>
  
  <article>
    <h1>Current Progress</h1>


  </article>


    <div style="position:relative;width:100%">
        <div id="columnchart_material" style="position:absolute;right:0px;top:100px;width: 800px; height: 500px;">
    </div>


</script> 

</section>

<footer>
  <p>Footer</p>
</footer>

</body>
</html>
