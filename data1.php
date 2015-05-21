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
$sql = "SELECT tdate, temperature1, temperature2 FROM ltempdat ORDER BY tdate DESC LIMIT 2000";
$query = mysql_query($sql);

//$sql = "SELECT tdate, temperature1, temperature2, temperature3 FROM ltempdat";
//$query = $con->query($sql);


$category = array();
$category['name'] = 'tdate';

$series1 = array();
$series1['name'] = 'temperature1';

$series2 = array();
$series2['name'] = 'temperature2';

//$series3 = array();
//$series3['name'] = 'temperature3';

while($r = mysql_fetch_array($query)) {
    $category['data'][] = $r['tdate'];
    $series1['data'][] = $r['temperature1'];
    $series2['data'][] = $r['temperature2'];
//    $series3['data'][] = $r['temperature3'];   
}

$result = array();
array_push($result,$category);
array_push($result,$series1);
array_push($result,$series2);
//array_push($result,$series3);

print json_encode($result, JSON_NUMERIC_CHECK);

mysql_close($con);
?>