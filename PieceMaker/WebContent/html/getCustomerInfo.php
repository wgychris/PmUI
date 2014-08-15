<?php 
$rows = array();

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$sql_select='SELECT OrderNum, CustomerName, CustomerEmail, Date, Price FROM OrderInfo LIMIT 1000 ';

error_log($sql_select);

if($startDate != null && $endDate != null) {
	$sql_select = $sql_select.'WHERE Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
}


$result=$db->query($sql_select);

while($row = $result->fetchArray()) {
	$temp = array();
	array_push($temp,$row['OrderNum']);
	array_push($temp,$row['CustomerName']);
	array_push($temp,$row['CustomerEmail']);
	array_push($temp,$row['Price'].'');
	array_push($temp,$row['Date']);
// 	$temp[0] = $row['OrderNum'];
// 	$temp[1] = $row['CustomerName'];
// 	$temp[2] = $row['CustomerEmail'];
// 	$temp[4] = $row['Price'];
// 	$temp[5] = $row['Date'];
	array_push($rows, $temp);
}
echo json_encode( $rows);

?>