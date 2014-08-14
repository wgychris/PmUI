<?php

	
$rows = array();

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$sql_select='SELECT DISTINCT strftime(\'%W-%Y\', Date) as week FROM DailyStatistics';

if($startDate != null && $endDate != null) {
	$sql_select = $sql_select.' WHERE Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
}

$result=$db->query($sql_select);
$num = 1;

while($row = $result->fetchArray()) {
// 	$temp[0] = $row['Date'];
// 	$temp[1] = $row['Revenue'];
// 	array_push($rows, $temp);

	$weekStr =  $row['week'];
// 	echo $weekStr;
	$selectRevenue = 'SELECT sum(Revenue) as sum FROM DailyStatistics WHERE strftime(\'%W-%Y\', Date) = \''.$weekStr.'\'';

	if($startDate != null && $endDate != null) {
		$selectRevenue = $selectRevenue.' AND Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
	}
	
	
	$tempResult = $db->query($selectRevenue);
	$tempRow = $tempResult->fetchArray();
	
	$temp[0] = $weekStr;
	$temp[1] = $tempRow['sum'];
// 	echo $temp[1];
		array_push($rows, $temp);
	
}
echo json_encode($rows);

?>