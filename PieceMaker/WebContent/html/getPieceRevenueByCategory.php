<?php 

$dateArr = array ();
$data = array ();

$db = new SQLite3 ( "./hello" );


$selectDate = 'SELECT DISTINCT Date from OrderInfo ';

$startDate = $_GET ["startDate"];
$endDate = $_GET ["endDate"];

$dateRangeStr = '';

if ($startDate != null && $endDate != null) {
	$dateRangeStr = $dateRangeStr . ' WHERE Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}
	
$selectDate = $selectDate . $dateRangeStr;

$selectDate = $selectDate . ' ORDER BY Date ASC';

// error_log($selectDate);

$result = $db->query ( $selectDate );

while($row = $result->fetchArray()) {
	array_push($dateArr, $row['Date']);
}


$category = $_GET['category'];
// $category = 'Lithophanes';
if ($startDate != null && $endDate != null) {
	$selectQuery = 'SELECT DISTINCT Piece FROM OrderInfo WHERE Category = \''.$category.'\''.' AND Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
} else {
	$selectQuery = 'SELECT DISTINCT Piece FROM OrderInfo WHERE Category = \''.$category.'\'';
}
// error_log($selectQuery);
$result=$db->query($selectQuery);
while($row = $result->fetchArray()) {
	$piece = $row['Piece'];
	$pieceData = array();
	
	foreach ($dateArr as $date){
	
	$selectPieceRevenue = 'SELECT sum(Price) as revenue FROM OrderInfo WHERE Piece=\''.$piece.'\' AND Date=\''.$date.'\'';
	
	$tempResult = $db->query($selectPieceRevenue);
	if($tempRow = $tempResult->fetchArray()){
		if($tempRow['revenue']!=null){
//  			echo $piece.' '.$date.' '.$tempRow['revenue'];
			array_push($pieceData, $tempRow['revenue']);
		}else {
// 			echo $piece.' '.$date.' 0';
			array_push($pieceData, 0);
		}
	}

	}
 	array_push($data, array('name'=>$piece, 'data'=>$pieceData));
}

echo json_encode(array('dateArr'=>$dateArr, 'data'=>$data));

?>