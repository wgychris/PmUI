<?php

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];
$category = $_GET["category"];

$getColor = 'SELECT DISTINCT Color FROM OrderInfo WHERE Category = \''.$category.'\'';

if($startDate != null && $endDate != null) {
	$getColor = $getColor.' AND Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
}

$colors = $db->query($getColor);

$result1 = array();
$putColor = array();
$colorArr = array();

while ($row = $colors->fetchArray()) {
	$color[0] = $row['Color'];
	array_push($colorArr, $color[0]);

	$sql_countNum = 'SELECT COUNT(Color) AS colorNum FROM OrderInfo WHERE Category = \''.$category.'\' AND Color = \''.$color[0].'\'';

	if($startDate != null && $endDate != null) {
		$sql_countNum = $sql_countNum.' AND Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
	}
	
	$result = $db->query($sql_countNum);
	$row1 = $result->fetchArray();
	$color[1] = $row1['colorNum'];
	array_push($result1,$color);
}


echo json_encode(array('data'=>$result1, 'color'=>$colorArr));

?>