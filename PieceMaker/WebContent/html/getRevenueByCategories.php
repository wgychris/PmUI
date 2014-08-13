<?php
$categoryArr = array();
$drilldownArr = array();

$db= new SQLite3("./hello");

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$selectQuery = "SELECT DISTINCT Category FROM OrderInfo";

$result=$db->query($selectQuery);

while($row = $result->fetchArray()) {
	$category = $row['Category'];
	
	$categoryItem['name'] = $category;
	$categoryItem['drilldown'] = $category;
	
	$selectCategoryRevenue = 'SELECT sum(Price) as revenue FROM OrderInfo WHERE Category=\''.$category.'\'';
	$revenueResult = $db->query($selectCategoryRevenue);
	$tempRow = $revenueResult->fetchArray();
	$categoryItem['y'] = $tempRow['revenue'];
	array_push($categoryArr, $categoryItem);
	
	$selectPiece = 'SELECT DISTINCT Piece FROM OrderInfo WHERE Category=\''.$category.'\'';
	$pieceResult = $db->query($selectPiece);
	$pieceData = array();
	
	while($tempRow = $pieceResult->fetchArray()){
		$piece = $tempRow['Piece'];
		$selectPieceRevenue = 'SELECT sum(Price) as revenue FROM OrderInfo WHERE Piece = \''.$piece.'\'';
		
		$pieceRevResult = $db->query($selectPieceRevenue);
		$pieceRevRow = $pieceRevResult->fetchArray();
		$pieceRevenue = $pieceRevRow['revenue'];
		
		$pieceItem[0] = $piece;
		$pieceItem[1] = $pieceRevenue;
		
		array_push($pieceData, $pieceItem);
	}
	
	array_push($drilldownArr, array('id'=>$category, 'data'=>$pieceData, 'name'=>$category));
	
	
}

echo json_encode(array('categoryArr'=>$categoryArr, 'drilldownArr'=>$drilldownArr));


?>