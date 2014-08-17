<?php

$rows = array();

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$sql_select='SELECT DISTINCT Piece, Category FROM OrderInfo WHERE IsPrinted=1';

error_log($sql_select);
$dateRange = '';

if($startDate != null && $endDate != null) {
	$dateRange = ' AND Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
	$sql_select = $sql_select.$dateRange;
}


$result=$db->query($sql_select);

while($row = $result->fetchArray()) {
	$temp = array();
	array_push($temp,$row['Piece']);
	array_push($temp,$row['Category']);
	$count = 1;
	$totalTime = 1;
	$selectCount = 'SELECT COUNT(*) as count FROM OrderInfo WHERE IsPrinted=1 AND Piece = \''.$row['Piece'].'\' AND Category = \''.$row['Category'].$dateRange.'\'';
	$selectTotalTime = 'SELECT SUM(PrintTime) as printTime FROM OrderInfo WHERE IsPrinted=1 AND Piece = \''.$row['Piece'].'\' AND Category = \''.$row['Category'].$dateRange.'\'';
	
	error_log($selectCount);
	error_log($selectTotalTime);
	
	$tempResult = $db->query($selectCount);
	$tempRow = $tempResult->fetchArray();
	$count = $tempRow['count'];
	
	$tempResult = $db->query($selectTotalTime);
	$tempRow = $tempResult->fetchArray();
	$totalTime = $tempRow['printTime'];
	
	array_push($temp,$totalTime / $count);
	
	array_push($rows, $temp);
}
echo json_encode( $rows);
?>