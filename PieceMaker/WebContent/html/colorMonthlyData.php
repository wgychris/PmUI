<?php
$db = new SQLite3 ( "./hello" );
$startDate = $_GET ["startDate"];
$endDate = $_GET ["endDate"];

$getColor = 'SELECT DISTINCT OptionValue FROM CustomizableOption WHERE OptionType = "Color"';

if ($startDate != null && $endDate != null) {
	$getColor = $getColor . ' AND Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}

$colors = $db->query ( $getColor );

$sqlSelectWeekStr = 'SELECT DISTINCT strftime(\'%m-%Y\', Date) as week FROM DailyStatistics ';
if ($startDate != null && $endDate != null) {
	$sqlSelectWeekStr = $sqlSelectWeekStr . ' WHERE Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
}

error_log($sqlSelectWeekStr);
$weekArr = $db->query($sqlSelectWeekStr);


$dateArray = array ();

date_default_timezone_set ( 'UTC' );
$begin;
$end;
if ($startDate != null) {
	$begin = new DateTime ( $startDate );
} else {
	$begin = new DateTime ( '2014-07-30' );
}
if ($endDate != null) {
	$end = new DateTime ( $endDate );
} else {
	$tempStr = date ( 'Y-m-d' ) . '';
	$end = new DateTime ( $tempStr );
}
$end = $end->modify ( '+1 day' );

$interval = new DateInterval ( 'P1D' );
$daterange = new DatePeriod ( $begin, $interval, $end );
$Black = array ();
$Orange = array ();
$Red = array ();
$Blue = array ();
$Purple = array ();
$Yellow = array ();

$colorNames = array ();
$colorData = array ();
while ( $temp = $colors->fetchArray () ) {
	$name = $temp ['OptionValue'];
	array_push ( $colorNames, $name );
	$colorData [$name] = array ();
}

while ( $weekRow = $weekArr->fetchArray() ) {
	
	$week = $weekRow['week'];
	error_log('query for week '.$week);
	
	while ( $row = $colors->fetchArray () ) {
		$colorName = $row ['OptionValue'];
		
		$sql_countNum = "SELECT COUNT(OptionValue) AS colorNum FROM CustomizableOption WHERE OptionValue = '" . $colorName . "' AND strftime('%m-%Y', Date) = '" . $week . "'";
		
		if ($startDate != null && $endDate != null) {
			$sql_countNum = $sql_countNum . ' AND Date >= \'' . $startDate . '\' AND Date <=\'' . $endDate . '\'';
		}
		
		error_log($sql_countNum);
		
		$result = $db->query ( $sql_countNum );
		$row1 = $result->fetchArray ();
		
		$colorNum = $row1 ['colorNum'];
		
		array_push ( $colorData [$colorName], $colorNum );
	}
	array_push ( $dateArray, $week );
}
$colorArr = array ();
$i = 0;
foreach ( $colorData as $count ) {
	
	array_push ( $colorArr, array (
			'name' => $colorNames [$i],
			'data' => $count,
			'color' => $colorNames [$i] 
	) );
	$i ++;
}
echo json_encode ( array (
		'dateArr' => $dateArray,
		'colors' => $colorArr 
) );

?>