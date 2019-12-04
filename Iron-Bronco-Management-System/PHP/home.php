<!DOCTYPE html>

<html lang="en">
<link rel="stylesheet" type="text/css" href="../CSS/template.css">
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

		//get sums from database for Google chart visualization

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

/*styling for home page*/
	
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
  background-color: #f1f1f1;
  padding: 30px;
  text-align: left;
  font-size: 35px;
  color: DarkRed;
}


ul {
  font-size: 18px;
  list-style-type: none;
  float: left;
  margin: 0;
  padding: 0;
  width: 300px;
  background-color: #555;
  position: absolute;
  height: calc(100% - 150px); 
  overflow: auto;
}

li a {
  display: block;
  color: #DDD;
  padding: 8px 16px;
  text-decoration: none;
}

li a.active {
  background-color: #EEE;
  color: white;
}

li a:hover:not(.active) {
  background-color: #888;
  color: white;
}

article {
  float: right;
  padding: 20px;
  width: calc(100% - 300px);
  background-color: #FFF;
  height: calc(100% - 150px); 
  position: absolute;
  right: 0px;
}

chart-div {
  padding: 70px;
  background-color: #FFF;
  /*border: 2px solid #c9c9c9;
*/
  width: auto;
  position: absolute;
  left: 50%;
  margin-left: -400px;
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
  position : fixed;
  bottom : 0;
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
    <script type="text/javascript"> //display progress with Google Chart
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Swimming', 'Biking', 'Running'],

		<?php if($row['SUM(SWIMMING)'] >= 0 && $row['SUM(BIKING)'] >= 0 && $row['SUM(RUNNING)']>= 0 )

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
<?php if( isset( $_SESSION['email'] ) ){ ?>
<body>
<header>
<img src="https://www.scu.edu/media/offices/umc/scu-brand-guidelines/visual-identity-amp-photography/visual-identity-toolkit/logos-amp-seals/Mission-Horizontal-PMS201.png" alt="Trulli">
</header>

<section>

  <nav>
    <ul>
		<br>
		<br>

      <li><a href="../HTML/input.html">Update progress</font></a></li>
	<?php if( isset( $_SESSION['groupId'] )) { ?>
      <li><a href="./team.php">View team</a></li>
	<?php }else {?>
      <li><a href="./joinTeam.php">Join a team</a></li>
      <li><a href="./createTeam.php">Create a team</a></li>
	<?php } ?>
      <li><a href="./logout.php">Log out</a></li>
    </ul>
  </nav>
  
  <article>

    <h1>Current Progress</h1>

	<?php if( isset($_SESSION['groupId']) ) {?>
	<chart-div>
        <div id="columnchart_material" style="width: 800px; height: 500px;">
	</chart-div>
	<?php }?>

  </article>

</script> 

</section>
<!--
<footer>
  <p>Footer</p>
</footer>
-->
</body>
<?php } else{
			header("Location: ../HTML/login.html");
			exit;
		} ?>
</html>
