<?php

 require("CatalogReader.php");

//  var_dump($carouselCatalog=read_carousel_catalog("catalog.txt"));

//  var_dump($catalogGridRows=read_catalog_grid("catalog.grid.txt"));
 	$carouselCatalog=read_carousel_catalog("catalog.txt");
 	$db= new SQLite3("./hello");
 	for ($i = 0; $i < count($carouselCatalog); $i++) {
 		$id = $carouselCatalog[$i]["id"];
 		$filename = $carouselCatalog[$i]["filename"];
 		$title = $carouselCatalog[$i]["title"];
 		$category = $carouselCatalog[$i]["category"];
 		$price = $carouselCatalog[$i]["price"];
 		$desc = $carouselCatalog[$i]["desc"];
 		
 		$insertQuery = "INSERT INTO Piece (id, filename, title, category, price, desc) VALUES
							(:id, :filename, :title, :category, :price, :desc)";
 		
 		
 		$stmt = $db->prepare($insertQuery);
 		$stmt->bindParam(":id", $id);
 		$stmt->bindParam(":title", $title);
 		$stmt->bindParam(":filename", $filename);
 		$stmt->bindParam(":category", $category);
 		$stmt->bindParam(":price", $price);
 		$stmt->bindParam(":desc", $desc);
 		
 		
 		$stmt->execute();
 	}

 ?>