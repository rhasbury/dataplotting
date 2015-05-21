<?php 
// Set the JSON header
header("Content-type: text/json");

class point
{
	public $date;
	public $temp;
	
	public function __construct($date, $temp){
		$this->date = $date;
		$this->temp = $temp;
	}
	
}

// The x value is the current JavaScript time, which is the Unix time multiplied by 1000.
$x = time() * 1000;
// The y value is a random number
$y = rand(0, 100);

// Create a PHP array and echo it as JSON
//$ret = array($x, $y);
$ret[] = new point($x, $y);
$ret[] = new point($x, $y);
$ret[] = new point($x, $y);

echo json_encode($ret);
?>