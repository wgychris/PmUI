<?php

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$dateRange = ' ';

if($startDate != null && $endDate != null) {
	$dateRange = ' Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
	$sqlOrderCount = 'SELECT COUNT(*) as count FROM OrderInfo WHERE IsPrinted=1 AND'.$dateRange;
	$sqlFailureCount = 'SELECT COUNT(*) as count FROM FailureInfo WHERE'.$dateRange;
} else {
	$sqlOrderCount = 'SELECT COUNT(*) as count FROM OrderInfo WHERE IsPrinted=1';
	$sqlFailureCount = 'SELECT COUNT(*) as count FROM FailureInfo';
}

// error_log($sqlOrderCount);
// error_log($sqlFailureCount);

$orderCount = 0;
$FailureCount = 0;

$result=$db->query($sqlOrderCount);

while($row = $result->fetchArray()) {
	$orderCount = $row['count'];
	
}

$result=$db->query($sqlFailureCount);

while($row = $result->fetchArray()) {
	$FailureCount = $row['count'];

}

echo json_encode(array('failureCount'=>$FailureCount, 'printedCount'=>$orderCount));
?>