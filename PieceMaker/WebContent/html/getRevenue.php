<?php

	
$rows = array();

$db= new SQLite3("./hello");

$sql_select='SELECT * FROM DailyStatistics ORDER BY Date ASC';

$result=$db->query($sql_select);
$num = 1;

while($row = $result->fetchArray()) {
	$temp[0] = $row['Date'];
	$temp[1] = $row['Revenue'];
	array_push($rows, $temp);
}
echo json_encode($rows);

?>