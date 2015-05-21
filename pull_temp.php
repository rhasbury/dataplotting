<?php
$servername = "localhost";
$username = "monitor";
$password = "password";
$dbname = "temps";


class point
{
	public $date;
	public $temp;
	public $zone;
	
	public function __construct($date, $temp, $zone){
		$this->date = $date;
		$this->temp = $temp;
		$this->zone = $zone;
	}
	
}


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT tdate, temperature, zone FROM temps.tempdat ORDER BY tdate DESC LIMIT 200";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {        
			$pdate = date("U", strtotime($row["tdate"]));			
			$ret[] = new point($pdate, $row["temperature"], $row["zone"]);	
		}
	echo json_encode($ret);
	
	} 
	else {
		echo "0 results";
	}
$conn->close();


?>