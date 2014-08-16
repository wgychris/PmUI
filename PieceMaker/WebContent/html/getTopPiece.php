<?php
$dateArr = array ();
$data = array ();

$db = new SQLite3 ( "./hello" );

$selectDate = 'SELECT DISTINCT Date from PopularityOfPiece ';

$startDate = $_GET ["startDate"];
$endDate = $_GET ["endDate"];

$dateRangeStr = '';

if ($startDate != null && $endDate != null) {
	$dateRangeStr = $dateRangeStr . ' WHERE Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}
	
$selectDate = $selectDate . $dateRangeStr;

$selectDate = $selectDate . ' ORDER BY Date ASC';

$result = $db->query ( $selectDate );

while ( $row = $result->fetchArray () ) {
	array_push ( $dateArr, $row ['Date'] );
}
$selectQuery ='SELECT DISTINCT ActionType FROM PopularityOfPiece' ;
$result=$db->query($selectQuery);
$piece = $_GET['piece'];

while($row = $result->fetchArray()) {
	$type = $row['ActionType'];
	$pieceData = array();
	foreach ($dateArr as $date){
		$selectPiece = 'SELECT count(*) as count FROM PopularityOfPiece WHERE PieceName=\''.$piece.'\' AND ActionType= \''.$type.'\' AND Date=\''.$date.'\'';
		error_log($selectPiece);
		$tempResult = $db->query($selectPiece);
		if($tempRow = $tempResult->fetchArray()){
			if($tempRow['count']!=null){
				error_log("enter not null");
				array_push($pieceData, $tempRow['count']);
			}else {
				array_push($pieceData, 0);
			}
		}
		
	}
array_push($data, array('name'=>$type, 'data'=>$pieceData));
}
echo json_encode(array('dateArr'=>$dateArr, 'data'=>$data));

