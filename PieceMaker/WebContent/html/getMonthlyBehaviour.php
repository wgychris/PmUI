<?php

$rows = array();
$dateArr = array();
$coversionArr = array();
$customizationArr = array();
$finalizationArr = array();

$db= new SQLite3("./hello");

$sql_select='SELECT DISTINCT strftime(\'%m-%Y\', Date) as week FROM DailyStatistics';

$result=$db->query($sql_select);

while($row = $result->fetchArray()) {

	$weekStr =  $row['week'];
	$selectRevenue = 'SELECT sum(NumOfOrder) as NumOfOrder, sum(NumOfCustomization) as NumOfCustomization, 
			sum(NumOfFinalize) as NumOfFinalize, sum(NumOfView) as NumOfView FROM DailyStatistics WHERE strftime(\'%m-%Y\', Date) = \''.$weekStr.'\'';

	$tempResult = $db->query($selectRevenue);
	$tempRow = $tempResult->fetchArray();


	array_push($dateArr, $weekStr);
	array_push($coversionArr, $tempRow['NumOfOrder'] * 100 / $tempRow['NumOfView']);
	array_push($customizationArr, $tempRow['NumOfCustomization'] * 100 / $tempRow['NumOfView']);
	array_push($finalizationArr, $tempRow['NumOfFinalize'] * 100 / $tempRow['NumOfView']);

}



echo json_encode(array('dateArr' => $dateArr, 'coversionArr' => $coversionArr, 'customizationArr' => $customizationArr, 'finalizationArr' => $finalizationArr));


?>