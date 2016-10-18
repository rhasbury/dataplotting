<?php
    // This script pulls sets of data from a mysql server and formats them into json objects that inject into these line charts http://nvd3.org/examples/lineWithFocus.html
	
	// Your mysql table should be formatted similar to this:
	// +---------------------+-------------+---------+
	// | tdate               | temperature | zone    |
	// +---------------------+-------------+---------+
	// | 2016-10-18 08:28:20 |     0.00000 | engine  |
	// | 2016-10-18 08:28:19 |    23.90000 | ambient |
	// | 2016-10-18 08:28:18 |    23.90000 | ambient |
	// | 2016-10-18 08:28:18 |     0.00000 | engine  |
	// | 2016-10-18 08:28:17 |    23.90000 | ambient |
	// | 2016-10-18 08:28:17 |     0.00000 | engine  |
	// | 2016-10-18 08:28:16 |    23.90000 | ambient |
	// | 2016-10-18 08:28:16 |     0.00000 | engine  |
	// | 2016-10-18 08:28:15 |    23.90000 | ambient |
	// | 2016-10-18 08:28:15 |     0.00000 | engine  |
	// +---------------------+-------------+---------+


	//	If you run the script on the data above it should return a json string like so:
	// [{"x":"1476793700","y":"0.00000","type":"engine"},
	// {"x":"1476793699","y":"23.90000","type":"ambient"},
	// {"x":"1476793698","y":"23.90000","type":"ambient"},
	// {"x":"1476793698","y":"0.00000","type":"engine"},
	// {"x":"1476793697","y":"23.90000","type":"ambient"},
	// {"x":"1476793697","y":"0.00000","type":"engine"},
	// {"x":"1476793696","y":"23.90000","type":"ambient"},
	// {"x":"1476793696","y":"0.00000","type":"engine"},
	// {"x":"1476793695","y":"23.90000","type":"ambient"},
	// {"x":"1476793695","y":"0.00000","type":"engine"}]
	
	
	// 	Database details
	// This section checks for arguments from the URL, if they don't exist script defaults back to settings that work with my databse in else sections. 
	// Change your the defaults in the else sections here for testing but normally don't edit anything here, change settings via options in the url from the html/js 
	
	// Mysql database username
	if (isset($_GET['username'])) {
		$username = $_GET['username'];
	} else  {
		$username = "monitor"; 
	}
	
	// Mysql database password
	if (isset($_GET['password'])) {
		$password = $_GET['password'];
	}else {
		$password = "password";   
	}
		
	// Host on which the database is located
	if (isset($_GET['databasehost'])) {
		$host = $_GET['databasehost'];
	} else {
		$host = "localhost";
	}
	
	// Name of the database being queried
	if (isset($_GET['database'])) {
		$database = $_GET['database'];
	} else {
		$database = "temps";
	}
		
	// Name of the table to be pulled from
	if (isset($_GET['table'])) {
		$table = $_GET['table'];
	} else {
		$table = "tempdat";
	}
		
	// Data details 	
	// The field that will be pulled and formatted for the chart. Should be a mysql DATETIME() formatted column
	if (isset($_GET['datefield'])) {
		$datefield = $_GET['datefield'];
	} else {
		$datefield = "tdate";
	}
		
	// The field that contains the Y data. Any numeric data type should work here I believe
	if (isset($_GET['datafield'])) {
		$datafield = $_GET['datafield'];
	} else {
		$datafield = "temperature";
	}

	// mapfield is the field that the different data series will be split by. 
	if (isset($_GET['mapfield'])) {
		$mapfield = $_GET['mapfield'];
	} else {
		$mapfield = "zone";
	}

	// Limit the number of rows retrieved
	if (isset($_GET['datalimit'])) {
		$datalimit = $_GET['datalimit'];
	} else {
		$datalimit = "10";
	}
	
	
	// Estabilsh mysql database connection
    $server = mysql_connect($host, $username, $password);
    $connection = mysql_select_db($database, $server);

	// Create mysql query to send based on the options. 
    $myquery = "SELECT  UNIX_TIMESTAMP(`" . $datefield . "`) AS 'x', " . $datafield . " AS 'y', " . $mapfield . " AS type FROM " . $table . " ORDER by " . $datefield . " DESC LIMIT " . $datalimit;

	// This line will write the query to your http servers log file for debugging. Helpful to debug if a query is failing or returning nothing. 
	// Log located at /var/log/lighttpd/error.log if you're running lighthttpd on a raspberry pi. 
	error_log($myquery , 0);
	
	// Here we send the query
    $query = mysql_query($myquery);
    
	// Quit if mysql query fails 	
    if ( ! $query ) {
        echo mysql_error();
        die;
    }
    
	// create an array
    $data = array();  
	// loop through the retrieved rows and place them into the array for processing. 
    for ($x = 0; $x < mysql_num_rows($query); $x++) {
        $data[] = mysql_fetch_assoc($query);
    }
    
	// encode the populated array as a json string and echo it to the browser
    echo json_encode($data);     
    
	
	
    mysql_close($server);
?>
