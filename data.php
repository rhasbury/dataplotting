<?php
$servername = "localhost";
$username = "monitor";
$password = "password";
$dbname = "temps";

//$con = new mysqli($servername, $username, $password, $dbname);

$con = mysql_connect($servername, $username, $password, $dbname);

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("temps", $con);

//$sql = "Select Month(`tdate`), Day(`tdate`), avg(`temperature`) AS 'avgtemp' from tempdat where `tdate` between '2015-04-01' and '2015-05-31' group by Month(`tdate`), Day(`tdate`)";
//$sql = "SELECT tdate, temperature1, temperature2, temperature3 FROM ltempdat ORDER BY tdate DESC LIMIT 2000";
//$sql = "SELECT DATE_FORMAT(timestamp_value, '%Y-%m-%d') as timestamp_value, SUM(traffic_count) as traffic_count FROM foot_traffic WHERE timestamp_value LIKE '2013-02%' GROUP BY DATE_FORMAT(timestamp_value, '%Y-%m-%d')";



if (isset($_GET["dateParam"])) {
	$date = ($_GET["dateParam"]) . '%';	
	//$sql = "SELECT tdate, temperature FROM tempdat WHERE zone LIKE 'Living Room' AND tdate LIKE " . "'" . $date . "'";
	$sql = "SELECT tdate, temperature FROM tempdat WHERE zone LIKE 'Living Room'AND tdate LIKE " . "'" . $date . "'";
	error_log($sql, 0);
	$query = mysql_query($sql);
	
	$category = array();
	$category['name'] = 'Day';
	$series1 = array();
	$series1['name'] = 'Living Room';
	$series2 = array();
	$series2['name'] = 'Basement';
	$series3 = array();
	$series3['name'] = 'Bedroom';
	$series4 = array();
	$series4['name'] = 'Outside';
	
	while($r = mysql_fetch_array($query)) {
		$category['data'][] = $r['tdate'];
		$series1['data'][] = $r['temperature'];
	}

	$sql = "SELECT tdate, temperature FROM tempdat WHERE zone LIKE 'Basement' AND tdate LIKE " . "'" . $date . "'";
	error_log($sql, 0);
	$query = mysql_query($sql);
	
	while($r = mysql_fetch_array($query)) {
		$series2['data'][] = $r['temperature'];
	}
	
	$sql = "SELECT tdate, temperature FROM tempdat WHERE zone LIKE 'Bedroom' AND tdate LIKE " . "'" . $date . "'";
	error_log($sql, 0);
	$query = mysql_query($sql);
	while($r = mysql_fetch_array($query)) {
		$series3['data'][] = $r['temperature'];
	}

	$sql = "SELECT tdate, temperature FROM tempdat WHERE zone LIKE 'Outside' AND tdate LIKE " . "'" . $date . "'";
	error_log($sql, 0);
	$query = mysql_query($sql);
	while($r = mysql_fetch_array($query)) {
		$series4['data'][] = $r['temperature'];
	}
	

	$result = array();
	array_push($result,$category);
	array_push($result,$series1);
	array_push($result,$series2);
	array_push($result,$series3);
	array_push($result,$series4);

	
    //$query = mysql_query("SELECT tdate, temperature FROM tempdat WHERE tdate LIKE " . " '".$_GET["dateParam"]."%'");
} 

elseif (isset($_GET["daysParam"])) {
	
	$date = explode("-", $_GET["daysParam"]);
	//$sql = "Select Date(`tdate`), avg(`temperature`) AS 'temperature' from tempdat group by Month(`tdate`), Day(`tdate`)";
	$sql = "Select Date(`tdate`), avg(`temperature`) AS 'temperature' from tempdat where tdate >=  FROM_UNIXTIME(" . $date[0] . ") and tdate <= FROM_UNIXTIME(" . $date[1] . ") group by Month(`tdate`), Day(`tdate`)";
	error_log($date, 0);
	error_log($sql, 0);
	$query = mysql_query($sql);

	$category = array();
	$category['name'] = 'Day';
	$series1 = array();
	$series1['name'] = 'temperature';

	while($r = mysql_fetch_array($query)) {
		$category['data'][] = $r['Date(`tdate`)'];
		$series1['data'][] = $r['temperature'];

	}
	$result = array();
	array_push($result,$category);
	array_push($result,$series1);


} else {
    //$sql = "Select Date(`tdate`), avg(`temperature`) AS 'temperature' from tempdat where zone LIKE 'Bedroom' AND `tdate` between '2015-04-01' and '2015-06-31' group by Month(`tdate`), Day(`tdate`)";
	//$sql = "Select Date(`tdate`), avg(`temperature`) AS 'temperature' from tempdat where zone LIKE 'Living Room' group by Month(`tdate`), Day(`tdate`)";
	$sql = "Select Date(`tdate`), avg(`temperature`) AS 'temperature' from tempdat group by Month(`tdate`), Day(`tdate`)";
	error_log($sql, 0);
	$query = mysql_query($sql);

	$category = array();
	$category['name'] = 'Day';
	$series1 = array();
	$series1['name'] = 'temperature';

	while($r = mysql_fetch_array($query)) {
		$category['data'][] = $r['Date(`tdate`)'];
		$series1['data'][] = $r['temperature'];

	}
	$result = array();
	array_push($result,$category);
	array_push($result,$series1);
}



//array_push($result,$series2);
//array_push($result,$series3);

print json_encode($result, JSON_NUMERIC_CHECK);

mysql_close($con);
?>