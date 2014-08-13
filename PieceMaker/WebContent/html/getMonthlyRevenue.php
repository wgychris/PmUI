<?php

	
$rows = array();

$db= new SQLite3("./hello");

$sql_select='SELECT DISTINCT strftime(\'%m-%Y\', Date) as week FROM DailyStatistics';

$result=$db->query($sql_select);
$num = 1;

while($row = $result->fetchArray()) {
// 	$temp[0] = $row['Date'];
// 	$temp[1] = $row['Revenue'];
// 	array_push($rows, $temp);

	$monthStr =  $row['week'];
// 	echo $weekStr;
	$selectRevenue = 'SELECT sum(Revenue) as sum FROM DailyStatistics WHERE strftime(\'%m-%Y\', Date) = \''.$monthStr.'\'';

	$tempResult = $db->query($selectRevenue);
	$tempRow = $tempResult->fetchArray();
	
	$temp[0] = $monthStr;
	$temp[1] = $tempRow['sum'];
// 	echo $temp[1];
		array_push($rows, $temp);
	
}
echo json_encode($rows);

?>