<?php

	$userInitiatedSessionStr 	= "User Initiated Session";
	$categoryViewPreStr 		= "KioskGrid Item ID";
	$pieceViewPreStr 			= "KioskVignette CarouselItem ID";
	$colorPickedStr				= "Color picked:";
	$reviewAndConfirmStr		= "Review and Confirm";
	$confirmPurchaseStr 		= "Confirm Purchase";
	$completeOrderStr			= "Complete My Order";
	$recerptCreatedStr 			= "Receipt Created for";
	$timeoutStr					= "Timed Out";
	$backButtonStr 				= "`Back` Button";
	
	
	$viewStr = "VIEWED";
	$customizedStr = "CUSTOMIZED";
	$finalizedStr = "FINALIZED";
	$puchaseStr = "PURCHASED";
	
	$colorOptStr = "Color";
	$stencilOptStr = "Stencil";
	
	$isSessionInit = false;
	
	$currCategory = null;
	$currPiece = null;
	$currStep = 0;
	$currPrice = 0;
	$currDate = null;
	$currColor = null;
	$currStencil = null;
	
	$isView = false;
	$isCustomized = false;
	$isFinalized = false;
	$isPurchase = false;
	
	
	

	date_default_timezone_set('UTC');
	
	$filename = "daily-".date('j-n-Y').".txt";
	//for debugging
	$filename = "daily-6-8-2014.txt";
	$currDate = date('Y-m-d');
// 	$currDate = "2014-07-30";
	
	if(!file_exists($filename)) {
		error_log("Today's log file does not exist!", 0);
		exit;
	}
	
	$lineCount = 0;
	
	$file_handle = fopen($filename, "r");
	if($file_handle) {
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			
			$pattern = '/[,]/';
			//echo '<pre>', print_r( preg_split( $pattern, $line ), 1 ), '</pre>';
			$strArray = preg_split( $pattern, $line);
				
			if (count($strArray) <=1 ) {
				continue;
			}
			echo $line;
// 			echo " ".$lineCount++;
			echo "<br>";
// 			echo $strArray[3];
// 			echo "<br>";

			$tempPattern;
			$tempStrArray;

			if (strpos($strArray[3], $userInitiatedSessionStr) !== false) {
				
				initSession();
				
			} else if (strpos($strArray[3], $categoryViewPreStr) !== false) {
				if (!$isSessionInit) {
					initSession();
				}
				
				$tempPattern = '/ /';
				$tempStrArray = preg_split($tempPattern, $strArray[3]);
				$isView = true;
				
				echo "select category ".$tempStrArray[3];
				echo "<br>";
			} else if (strpos($strArray[3], $pieceViewPreStr) !== false) {
				$tempPattern = '/ /';
				$tempStrArray = preg_split($tempPattern, $strArray[3]);
				echo "select piece id ".$tempStrArray[3];
				echo "<br>";
				
				$data = getPieceData($tempStrArray[3]);

				$currPiece = $data["title"];
				$currCategory = $data["category"];
				$currPrice = $data["price"];;
				updatePopularityOfPiece($currDate, $strArray[2], $currPiece, $currPiece, $currCategory, $viewStr);
			} else if (strpos($strArray[3], $colorPickedStr) !== false) {
				$tempPattern = '/ /';
				$tempStrArray = preg_split($tempPattern, $strArray[3]);
				echo "picked color ".$tempStrArray[2];
				echo "<br>";
				$currColor = $tempStrArray[2];
				updateCustomizableOption($currDate, $strArray[2], $colorOptStr, $tempStrArray[2], $viewStr);
			} else if (strpos($strArray[3], $reviewAndConfirmStr) !== false) {
				$isFinalized = true;
				echo "click review and confirm button";
				echo "<br>";
			} else if (strpos($strArray[3], $confirmPurchaseStr) !== false) {
				echo "click confirm purchase button";
				echo "<br>";
			} else if (strpos($strArray[3], $completeOrderStr) !== false) {
				echo "click complete order button";
				echo "<br>";
				
				updateCustomizableOption($currDate, $strArray[2], $colorOptStr, $currColor, $puchaseStr);
				updatePopularityOfPiece($currDate, $strArray[2], $currPiece, $currPiece, $currCategory, $puchaseStr);
				$isPurchase = true;
			} else if (strpos($strArray[3], $recerptCreatedStr) !== false) {
				
				//update order info
				$tempPattern = '/`/';
				$tempStrArray = preg_split($tempPattern, $strArray[3]);
				echo "sent receipt ".$tempStrArray[1];
				echo "<br>";
				updateOrderInfo($tempStrArray[1], $tempStrArray[3], $tempStrArray[5], $strArray[2]);
				
				//update daily
				if (!isRevenueRecordExist($currDate)) {
					createRecordForToday();
				}
				addRevenue($currPrice);
				addOne($puchaseStr);
				addOne($finalizedStr);
				addOne($customizedStr);
				addOne($viewStr);
				
			} else if (strpos($strArray[3], $backButtonStr) !== false) {
				echo "click back button";
				echo "<br>";
			} else if (strpos($strArray[3], $timeoutStr) !== false) {
				echo "time out, reset session";
				echo "<br>";
				if($isSessionInit) {
					if($isFinalized) {
						updatePopularityOfPiece($currDate, $strArray[2], $currPiece, $currPiece, $currCategory, $finalizedStr);
						addOne($finalizedStr);
						addOne($customizedStr);
						addOne($viewStr);
					} else if($isCustomized) {
						updatePopularityOfPiece($currDate, $strArray[2], $currPiece, $currPiece, $currCategory, $customizedStr);
						addOne($customizedStr);
						addOne($viewStr);
					} else if ($isView) {
						addOne($viewStr);
					}
					$isSessionInit = false;
				} else {
					
				}
			} else {
				echo "<br>";
			}
			
		}
	}
	
	
	function initSession() {
		global $isSessionInit;
		
		global $currCategory;
		global $currPiece;
		global $currStep;
		global $currPrice;
		global $currColor;
		global $currStencil;
	
		global $isView;
		global $isCustomized;
		global $isFinalized;
		global $isPurchase;
	
		$currCategory = null;
		$currPiece = null;
		$currStep = 0;
		$currPrice = 0;
		$currColor = null;
		$currStencil = null;
	
		$isView = false;
		$isCustomized = false;
		$isFinalized = false;
		$isPurchase = false;
		
		$isSessionInit = true;
		
		echo "initial session in function";
		echo "<br>";
	}
	
	function updateCustomizableOption($date, $timestamp, $opt, $optValue, $actionType) {
		global $currPiece;
		
		$insertQuery = "INSERT INTO CustomizableOption (OptionType, OptionValue, ActionType, PieceName, Date, Timestamp) VALUES
							(:optionType, :optionValue, :actionType, :pieceName, :date, :timestamp)";
		
		$db= new SQLite3("./hello");
		$stmt = $db->prepare($insertQuery);
		$stmt->bindParam(":optionType", $opt);
		$stmt->bindParam(":optionValue", $optValue);
		$stmt->bindParam(":actionType", $actionType);
		$stmt->bindParam(":pieceName", $currPiece);
		$stmt->bindParam(":date", $date);
		$stmt->bindParam(":timestamp", $timestamp);
		
		$stmt->execute();
		
		
		$db = null;
	}
	
	function updatePopularityOfPiece($date, $timestamp, $pieceId, $pieceName, $category, $actionType) {
		$insertQuery = "INSERT INTO PopularityOfPiece (PieceName, Category, ActionType, Date, Timestamp) VALUES
							(:pieceName, :category, :actionType, :date, :timestamp)";
		$db= new SQLite3("./hello");
		$stmt = $db->prepare($insertQuery);
		$stmt->bindParam(":pieceName", $pieceName);
		$stmt->bindParam(":category", $category);
		$stmt->bindParam(":actionType", $actionType);
		$stmt->bindParam(":date", $date);
		$stmt->bindParam(":timestamp", $timestamp);
		
		$stmt->execute();
		
		
		$db = null;
	}
	
	
	function updateOrderInfo($customerName, $customerEmail, $orderNum, $timestamp) {
		global $currCategory;
		global $currPiece;
		global $currStep;
		global $currPrice;
		global $currColor;
		global $currStencil;
		global $currDate;
		
		$currStencil = "test stencil".$currPiece;
		
		$insertQuery = "INSERT INTO OrderInfo (Piece, Category, Price, Color, Stencil, OrderNum, CustomerName, CustomerEmail, Date, Timestamp, OrderStatus, IsPrinted) VALUES
							(:piece, :category, :price, :color, :stencil, :orderNum, :customerName, :customerEmail, :date, :timestamp, \"PENDING\", 0)";
		
		$db= new SQLite3("./hello");
		$stmt = $db->prepare($insertQuery);
		
		$stmt->bindParam(":piece", $currPiece);
		$stmt->bindParam(":category", $currCategory);
		$stmt->bindParam(":price", $currPrice);
		$stmt->bindParam(":color", $currColor);
		$stmt->bindParam(":stencil", $currStencil);
		$stmt->bindParam(":orderNum", $orderNum);
		$stmt->bindParam(":customerEmail", $customerEmail);
		$stmt->bindParam(":customerName", $customerName);
		$stmt->bindParam(":date", $currDate);
		$stmt->bindParam(":timestamp", $timestamp);
		
		$stmt->execute();
	}
	
	function isRevenueRecordExist($dateStr) {
		$db= new SQLite3("./hello");
		
		$sql_select="SELECT Date FROM DailyStatistics WHERE Date = \"".$dateStr."\";";
		error_log($sql_select);
		
		$result=$db->query($sql_select);
		$row = $result->fetchArray(SQLITE3_NUM);

		$timestamp = $row[0];
		$db = null;
		
		if (strcmp($timestamp, $dateStr) == 0) {
			error_log("found data for ".$dateStr, 0);
			return true;
		} else {
			return false;
		}
	}
	
	function createRecordForToday() {
		global $currDate;
		
		$db= new SQLite3("./hello");
		$insertQuery = "INSERT INTO DailyStatistics (Date, Revenue, NumOfOrder, NumOfView, NumOfCustomization, NumOfFinalize) VALUES
							(:date, 0.0, 0, 0, 0, 0)";
		$stmt = $db->prepare($insertQuery);
		
		$stmt->bindParam(":date", $currDate);
		$stmt->execute();
		
		$db = null;
	

		error_log("try to create record for ".$currDate, 0);
	}
	
	function createRecordForDate($dateStr) {
	
		$db= new SQLite3("./hello");
		$insertQuery = "INSERT INTO DailyStatistics (Date, Revenue, NumOfOrder, NumOfView, NumOfCustomization, NumOfFinalize) VALUES
							(:date, 0.0, 0, 0, 0, 0)";
		$stmt = $db->prepare($insertQuery);
	
		$stmt->bindParam(":date", $dateStr);
		$stmt->execute();
	
		$db = null;
	
		error_log("try to create record for ".$currDate, 0);
	}
	
	function addRevenue($price) {
		$db= new SQLite3("./hello");
		global $currDate;
		
		$updateQuery = "UPDATE DailyStatistics SET Revenue = Revenue + ".$price." ";
		$updateQuery = $updateQuery." WHERE Date = '".$currDate."';";
		$result = $db->exec($updateQuery);
		
		if ($result) {
			error_log("number of rows modified ".$db->changes(), 0);
		}
	}
	
	function addOrderNum() {
		
	}
	
	function addOne($type) {
		global $viewStr;
		global $customizedStr;
		global $finalizedStr;
		global $puchaseStr;
		
		global $currDate;
		
		$db= new SQLite3("./hello");
		
		error_log($type);
		
		$updateQuery = "UPDATE DailyStatistics SET ";
		if (strcmp($type, $puchaseStr) == 0) {
			$updateQuery = $updateQuery."NumOfOrder = NumOfOrder + 1";
		} else if (strcmp($type, $finalizedStr) == 0) {
			$updateQuery = $updateQuery."NumOfFinalize = NumOfFinalize + 1";
		} else if (strcmp($type, $customizedStr) == 0) {
			$updateQuery = $updateQuery."NumOfCustomization = NumOfCustomization + 1";
		} else if (strcmp($type, $viewStr) == 0) {
			$updateQuery = $updateQuery."NumOfView = NumOfView + 1";
		}
		
		$updateQuery = $updateQuery." WHERE Date = '".$currDate."';";
		
		$result = $db->exec($updateQuery);
		if ($result) {
			error_log("number of rows modified ".$type.$db->changes(), 0);
		}
	}
	
	function getPieceData($pieceId) {
		$db= new SQLite3("./hello");

		$selectQuery = "SELECT * FROM Piece WHERE id = :id;";
		$stmt = $db->prepare($selectQuery);

		$stmt->bindParam(":id", $pieceId);
		$result = $stmt->execute();

		$db = null;

		return $result->fetchArray();
	}


?>