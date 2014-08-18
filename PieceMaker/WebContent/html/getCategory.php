<?php
$rows = array();

$db= new SQLite3("./hello");

$sql_select='SELECT DISTINCT title FROM Category ';

$result=$db->query($sql_select);

while($row = $result->fetchArray()) {
	$temp[0] = $row['title'];
	array_push($rows, $row['title']);
}
echo json_encode($rows);

?>