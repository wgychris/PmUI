<?php

$db= new SQLite3("./hello");

$category = $_GET["category"];

$getColor = 'SELECT DISTINCT Color FROM OrderInfo WHERE Category = \''.$category.'\'';
$colors = $db->query($getColor);

$result1 = array();
$putColor = array();
while ($row = $colors->fetchArray()) {
	$color[0] = $row['Color'];

	$sql_countNum = 'SELECT COUNT(Color) AS colorNum FROM OrderInfo WHERE Category = \''.$category.'\' AND Color = \''.$color[0].'\'';

	$result = $db->query($sql_countNum);
	$row1 = $result->fetchArray();
	$color[1] = $row1['colorNum'];
	array_push($result1,$color);
}


echo json_encode($result1);

?>