<?php

	
$rows = array();

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$sql_select='SELECT * FROM DailyStatistics ';

if($startDate != null && $endDate != null) {
	$sql_select = $sql_select.'WHERE Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
}

$sql_select = $sql_select.' ORDER BY Date ASC';

$result=$db->query($sql_select);
$num = 1;

while($row = $result->fetchArray()) {
	$temp[0] = $row['Date'];
	$temp[1] = $row['Revenue'];
	array_push($rows, $temp);
}
echo json_encode($rows);

?>