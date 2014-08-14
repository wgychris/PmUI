<?php

$rows = array();
$dateArr = array();
$coversionArr = array();
$customizationArr = array();
$finalizationArr = array();

$db= new SQLite3("./hello");

$sql_select='SELECT DISTINCT strftime(\'%W-%Y\', Date) as week FROM DailyStatistics';

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

if($startDate != null && $endDate != null) {
	$sql_select = $sql_select.' WHERE Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
}


$result=$db->query($sql_select);

while($row = $result->fetchArray()) {

	$weekStr =  $row['week'];
	$selectRevenue = 'SELECT sum(NumOfOrder) as NumOfOrder, sum(NumOfCustomization) as NumOfCustomization, 
			sum(NumOfFinalize) as NumOfFinalize, sum(NumOfView) as NumOfView FROM DailyStatistics WHERE strftime(\'%W-%Y\', Date) = \''.$weekStr.'\'';

	if($startDate != null && $endDate != null) {
		$selectRevenue = $selectRevenue.' AND Date >= \''.$startDate.'\' AND Date <=\''.$endDate.'\'';
	}
	
	$tempResult = $db->query($selectRevenue);
	$tempRow = $tempResult->fetchArray();


	array_push($dateArr, $weekStr);
	array_push($coversionArr, $tempRow['NumOfOrder'] * 100 / $tempRow['NumOfView']);
	array_push($customizationArr, $tempRow['NumOfCustomization'] * 100 / $tempRow['NumOfView']);
	array_push($finalizationArr, $tempRow['NumOfFinalize'] * 100 / $tempRow['NumOfView']);

}



echo json_encode(array('dateArr' => $dateArr, 'coversionArr' => $coversionArr, 'customizationArr' => $customizationArr, 'finalizationArr' => $finalizationArr));


?>