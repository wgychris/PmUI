<?php
$rows = array ();
$others = 1;
$db = new SQLite3 ( "./hello" );

$startDate = $_GET ["startDate"];
$endDate = $_GET ["endDate"];

if ($startDate != null && $endDate != null) {
	$sql_select = $sql_select . 'WHERE Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}

$sql_select = 'SELECT PieceName, count(*) as OrderNumbers FROM "PopularityOfPiece" WHERE ActionType="VIEWED" GROUP BY PieceName Order by OrderNumbers DESC LIMIT 5;
';

$result = $db->query ( $sql_select );

$sql_total = 'SELECT count(*) as total FROM "PopularityOfPiece" WHERE ActionType="VIEWED";';
$result2 = $db->query ( $sql_total );
$row2 = $result2->fetchArray ();
$total = $row2 [0];

while ( $row = $result->fetchArray () ) {
	$temp [0] = $row ['PieceName'];
	$temp[1] = $row['OrderNumbers']/$total;
	$temp[1] = $temp[1] * 100;
	array_push ( $rows, $temp );
	$others = $others - $row ['OrderNumbers'] / $total;
}
$temp [0] = 'Others';
$temp [1] = $others * 100;
array_push ( $rows, $temp );
echo json_encode ( $rows );

?>