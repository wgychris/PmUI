<?php

	
$db= new SQLite3("./hello");
// $startDate = $_GET["startDate"];
// $endDate = $_GET["endDate"];
// $piece = $_GET["pieceName"];
$startDate = '2014-08-05';
$endDate = '2014-08-13';
$piece = "Guitar Pick Earrings";

$getColor = "SELECT DISTINCT OptionValue FROM CustomizableOption WHERE pieceName = '".$piece."' AND Date >= '".$startDate."' AND Date <= '".$endDate."'";

$colors = $db->query($getColor);

$sql_select='SELECT * FROM CustomizableOption ';
$dateArray = array();



// date_default_timezone_set('UTC');

// $begin = new DateTime($startDate);
// $end = new DateTime( $endDate);
// $end = $end->modify( '+1 day' );

// $interval = new DateInterval('P1D');
// $daterange = new DatePeriod($begin, $interval ,$end);


$colorNames = array();
$colorData = array();
while ($temp = $colors->fetchArray()) {
	$name = $temp['OptionValue'];

	array_push($colorNames,$name);
	$colorData[$name] = array();
}
// $finalResult = array();
$colorNum = array();

while ($row = $colors->fetchArray()) {
	$colorName = $row['OptionValue'];
	echo $colorName;
	$sql_countNum = "SELECT COUNT(OptionValue) AS colorNum FROM CustomizableOption WHERE OptionValue = '".$colorName."' AND pieceName = '".$piece."' AND Date >= '".$startDate."' AND Date <= '".$endDate."'";
	
	$result = $db->query($sql_countNum);
	$row1 = $result->fetchArray();

	$Num = $row1['colorNum'];

	
	array_push ($colorNum,$Num);

}




echo json_encode(array('colorNames' => $colorNames,'colorNum' => $colorNum));



?>