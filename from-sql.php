<?php
/**
 * This file loads content from four different data tables depending on the required time range.
 * The stockquotes table containts 1.7 million data points. Since we are loading OHLC data and
 * MySQL has no concept of first and last in a data group, we have extracted groups by hours, days
 * and months into separate tables. If we were to load a line series with average data, we wouldn't
 * have to do this.
 * 
 * @param callback {String} The name of the JSONP callback to pad the JSON within
 * @param start {Integer} The starting point in JS time
 * @param end {Integer} The ending point in JS time
 */

// get the parameters

$callback = $_GET['callback'];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback)) {
	die('Invalid callback name');
}

$start = @$_GET['start'];
if ($start && !preg_match('/^[0-9]+$/', $start)) {
	die("Invalid start parameter: $start");
}

$end = @$_GET['end'];
if ($end && !preg_match('/^[0-9]+$/', $end)) {
	die("Invalid end parameter: $end");
}
if (!$end) $end = time() * 1000;



// connect to MySQL
//require_once('../../joomla/configuration.php');
//$conf = new JConfig();
//@mysql_connect($conf->host, $conf->user, $conf->password) or die(mysql_error());
//mysql_select_db($conf->db) or die(mysql_error());


$servername = "localhost";
$username = "monitor";
$password = "password";
$dbname = "temps";
$con = mysql_connect($servername, $username, $password, $dbname);
if (!$con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db("temps", $con);



// set UTC time
mysql_query("SET time_zone = '+00:00'");

// set some utility variables
$range = $end - $start;
$startTime = gmstrftime('%Y-%m-%d %H:%M:%S', $start / 1000);
$endTime = gmstrftime('%Y-%m-%d %H:%M:%S', $end / 1000);

// find the right table
// two days range loads minute data
/*
if ($range < 2 * 24 * 3600 * 1000) {
	$table = 'stockquotes';
	
// one month range loads hourly data
} elseif ($range < 31 * 24 * 3600 * 1000) {
	$table = 'stockquotes_hour';
	
// one year range loads daily data
} elseif ($range < 15 * 31 * 24 * 3600 * 1000) {
	$table = 'stockquotes_day';

// greater range loads monthly data
} else {
	$table = 'stockquotes_month';
} 
*/

$table = 'ltempdat';

$sql = "
	select 
		unix_timestamp(tdate) * 1000 as datetime,
		temperature1,
		temperature2,
		temperature3,
		temperature4
	from $table 
	where tdate between '$startTime' and '$endTime'
	order by tdate
	limit 0, 5000
";

$result = mysql_query($sql) or die(mysql_error());


$rows = array();
while ($row = mysql_fetch_assoc($result)) {
	extract($row);
	
	$rows[] = "[$datetime,$temperature1]";  //",$temperature2,$temperature3,$temperature4]";
}

// print it
header('Content-Type: text/javascript');

//echo "/* console.log(' start = $start, end = $end, startTime = $startTime, endTime = $endTime '); */";
//echo $callback ."([\n" . join(",\n", $rows) ."\n]);";
//echo "?(/* MSFT historical OHLC data from the Google Finance API */ \n";
echo "[\n" . join(",\n", $rows) ."\n];";


?>