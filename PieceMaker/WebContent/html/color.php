<?php

	
$db= new SQLite3("./hello");
$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];
// $startDate = '2014-08-05';
// $endDate = '2014-08-13';
$getColor = 'SELECT DISTINCT OptionValue FROM CustomizableOption WHERE OptionType = "Color"';
$colors = $db->query($getColor);
$sql_select='SELECT * FROM CustomizableOption ';
$dateArray = array();



date_default_timezone_set('UTC');

$begin = new DateTime($startDate);
$end = new DateTime( $endDate);
$end = $end->modify( '+1 day' );

$interval = new DateInterval('P1D');
$daterange = new DatePeriod($begin, $interval ,$end);
$Black = array();
$Orange = array();
$Red = array();
$Blue = array();
$Purple = array();
$Yellow = array();

$colorNames = array();
$colorData = array();
while ($temp = $colors->fetchArray()) {
	$name = $temp['OptionValue'];
	array_push($colorNames,$name);
	$colorData[$name] = array();
}

foreach ($daterange as $date) {

while ($row = $colors->fetchArray()) {
	$colorName = $row['OptionValue'];

	$sql_countNum = "SELECT COUNT(OptionValue) AS colorNum FROM CustomizableOption WHERE OptionValue = '".$colorName."' AND Date = '".$date->format("Y-m-d")."'";
	
	$result = $db->query($sql_countNum);
	$row1 = $result->fetchArray();

	$colorNum = $row1['colorNum'];
	
	array_push ($colorData[$colorName],$colorNum);

}
array_push($dateArray,$date->format("Y-m-d"));

}
$colorArr = array();
$i = 0;
foreach($colorData as $count) {
	
	array_push ($colorArr,array('name' => $colorNames[$i], 'data' => $count,'color' =>$colorNames[$i]));
	$i ++;
}
echo json_encode(array('dateArr' => $dateArray,'colors' => $colorArr));



?>