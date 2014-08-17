<?php
$rows = array ();
$others = 1;
$db = new SQLite3 ( "./hello" );

$startDate = $_GET ["startDate"];
$endDate = $_GET ["endDate"];

$sql_select = 'SELECT PieceName, count(*) as OrderNumbers FROM "PopularityOfPiece" WHERE ActionType="VIEWED" ';


if ($startDate != null && $endDate != null) {
	$sql_select = $sql_select . ' AND Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}

$sql_select = $sql_select . ' GROUP BY PieceName Order by OrderNumbers DESC LIMIT 5;';

error_log($sql_select);

$result = $db->query ( $sql_select );

$sql_total = 'SELECT count(*) as total FROM "PopularityOfPiece" WHERE ActionType="VIEWED" ';
if ($startDate != null && $endDate != null) {
	$sql_total = $sql_select . 'AND Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}

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