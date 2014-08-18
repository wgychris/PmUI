<?php

$data = array();

$db= new SQLite3("./hello");


$sql_select='SELECT DISTINCT title FROM Category ';

$result=$db->query($sql_select);


while($row = $result->fetchArray()) {
	$category = $row['title'];
	$pieceData = array();
	
	$selectPieceQuery = 'SELECT DISTINCT title FROM Piece WHERE category = \''.$category.'\'';
	
	$tempResult = $db->query($selectPieceQuery);
	
	while($tempRow = $tempResult->fetchArray()) {
		array_push($pieceData, $tempRow['title']);
	}
	
	array_push($data, array('category'=>$category, 'piece'=>$pieceData));
}
echo json_encode($data);

?>