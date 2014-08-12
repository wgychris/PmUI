<?php

require("CatalogReader.php");

//var_dump($carouselCatalog=read_carousel_catalog("catalog.txt"));

//  var_dump($catalogGridRows=read_catalog_grid("catalog.grid.txt"));

$catalogGridRows=read_catalog_grid("catalog.grid.txt");
$db= new SQLite3("./hello");

for ($i = 0; $i < 26; $i++) {
	//  	echo $catalogGridRows[$i]["title"];
	//  	echo "<br>";
	//  	echo $catalogGridRows[$i]["items"];
	//  	echo "<br>";
	
	$title = $catalogGridRows[$i]["title"];
	$tempPattern = '/:/';
	$tempStrArray = preg_split($tempPattern, $title);
	$category = $tempStrArray[0];
	echo $category."\r\n";
	$sub = trim($tempStrArray[1]);
	echo $sub."\r\n";
	$tempPattern = '/ /';
	$tempStrArray = preg_split($tempPattern, $catalogGridRows[$i]["items"]);
	echo $tempStrArray[0]."\r\n";
	for ($j = 0; $j < count($tempStrArray); $j++) {
		$insertQuery = "INSERT INTO Category (title, category, subCategory, pieceId) VALUES
							(:title, :category, :subCategory, :pieceId)";


		$stmt = $db->prepare($insertQuery);
		$stmt->bindParam(":title", $title);
		$stmt->bindParam(":category", $category);
		$stmt->bindParam(":pieceId", $tempStrArray[$j]);
		$stmt->bindParam(":subCategory", $sub);


		$stmt->execute();


	}
}
?>