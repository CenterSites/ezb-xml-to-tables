<?php

global $debug;
$debug = "on";

function getDefaultSpecificationText($record, $headerFooter){
	$artGroups = $record->xpath('ancestor::ArticleGroup');   
	foreach($artGroups as $artGroup){
       		if(isset($artGroup->Specifications->Specification->$headerFooter)){
       			$articleGroupDefaultText = (string) $artGroup->Specifications->Specification->$headerFooter;
       		}else{
       			$articleGroupDefaultText = "NULL";}
		return $articleGroupDefaultText;
	}	         		
}  

function getSpecificationText($type, $record, $headerFooter){
	$artGroupSpecifications = $record->xpath('ancestor::ArticleGroup/Specifications/Specification[@type="'.$type.'"]');   
	foreach($artGroupSpecifications as $aGrS){
			$aGrText = (string) $aGrS->$headerFooter;
		return $aGrText;
	}
}   

function getClassifications($record, $depth, $attname){
	$classificationLevels = $record->xpath('ancestor::ArticleGroup/Classifications/Classification/group['.$depth.']');   
	foreach($classificationLevels as $level){
			$classLevel = (string) $level->attributes()->$attname;
		return $classLevel;

	} 
} 

function getArticleGroupData($record, $element, $Node_Att){
				$artGroups = $record->xpath('ancestor::ArticleGroup');   
	         	foreach($artGroups as $artGroup){

	         	if($Node_Att == 'Att'){		
	         		$articleGroupData = $artGroup->attributes()->$element;
				}elseif($Node_Att == 'Node'){
	         		$articleGroupData = $artGroup->$element;
	         	}

		return $articleGroupData;
	}	         		
} 

function getGroupAssets1($record, $element, $Node_Att){
				$artGroups = $record->xpath('ancestor::ArticleGroup/Assets/Asset');   
	         	foreach($artGroups as $artGroup){

	         	if($Node_Att == 'Att'){		
	         		$articleGroupData = $artGroup->attributes()->$element;
	    
				}elseif($Node_Att == 'Node'){
	         		$articleGroupData = $artGroup->$element;
	         	}
	//print_r($articleGroupData);

		return $articleGroupData;
	}	         		
} 

function getAssets($record, $element, $type, $depth){
	$asset_array = array();

			if($depth == 'GRP'){
				$assets = $record->xpath('ancestor::ArticleGroup/Assets/Asset[@type="'.$type.'"]');
			}
			if($depth == 'PRD'){
				$assets = $record->xpath('Assets/Asset[@type="'.$type.'"]');  
			}


		foreach($assets as $asset){
			$rowAssets = $asset->$element;
			$asset_array[] = $rowAssets  ;
		}

	$Assets = implode(",\n", $asset_array);

	//groter formaat type=icon
 	$AssetsCat = str_replace("GalleryPreviewIcon","CatalogueIcon",$Assets);


	return $AssetsCat;
}


function getAssetsFilename($record, $element, $type, $depth){
	$asset_array = array();

			if($depth == 'GRP'){
				$assets = $record->xpath('ancestor::ArticleGroup/Assets/Asset[@type="'.$type.'"]');
			}
			if($depth == 'PRD'){
				$assets = $record->xpath('Assets/Asset[@type="'.$type.'"]');  
			}


		foreach($assets as $asset){
			$rowAssets = $asset->$element.$asset->OriginalExtension;
			$asset_array[] = $rowAssets  ;
		}

	$Assets = implode(",\n", $asset_array);




	return $Assets;
}




function getAssetsLarge($record, $element, $type, $depth){
	$asset_array = array();
	$assetExt_array = array();

			if($depth == 'GRP'){
				$assets = $record->xpath('ancestor::ArticleGroup/Assets/Asset[@type="'.$type.'"]');
			}
			if($depth == 'PRD'){
				$assets = $record->xpath('Assets/Asset[@type="'.$type.'"]');  
			}

				foreach($assets as $asset){
					$rowAssets = $asset->$element;	

					//haal de ezb .extenstie uit de url en vervang met OriginalExtension 
					$rowAssets = preg_replace('/\\.[^.\\s]{3,4}$/', '', $asset->$element).$asset->OriginalExtension;
					$asset_array[] = $rowAssets;
				}

	$Assets = implode(",\n", $asset_array);

		if($type == "Icon"){
	//groter formaat type=icon
 	$AssetsCat = str_replace("GalleryPreviewIcon","CatalogueIcon",$Assets);
 		}
 		if($type == "Logo"){
	//groter formaat type=Logo
 	$AssetsCat = str_replace("Logo","CatalogueLogo",$Assets);
 		}

	return $AssetsCat;
}







function getPropertiesTransposed($record, $toSpecIs){


	if ($toSpecIs == "false"){
		$TableProperties = $record->xpath('TableProperties/Property[@toSpecification="false"][@isHidden="false"]');  
		}
	if ($toSpecIs == "true"){
		$TableProperties = $record->xpath('TableProperties/Property[@toSpecification="true"][@isHidden="false"]');
		}  

		if (!empty($TableProperties)){
				$tableRow = array();
				$tableRow[] = '<table>'."\n".'</tr>'."\n";

				foreach($TableProperties as $Property){
					$rowCells = '<th>'.$Property->attributes()->headername.'</th>'."\n";
					$tableRow[] = $rowCells;

				}
					$tableRow[] = '</tr>'."\n".'<tr>'."\n";

				foreach($TableProperties as $Property){
					$rowCells = '<td>'. $Property->Value.'</td>'."\n";
					$tableRow[] = $rowCells;
				}

			$tableRow[] = '</tr>'."\n";
			$tableRow[] = '</table>';	
			$tableRows = implode('', $tableRow);
			return $tableRows;
		}

}




function getProperties($record, $toSpecIs){

	if ($toSpecIs == "false"){
		$TableProperties = $record->xpath('TableProperties/Property[@toSpecification="false"][@isHidden="false"]');  
		}
	if ($toSpecIs == "true"){
		$TableProperties = $record->xpath('TableProperties/Property[@toSpecification="true"][@isHidden="false"]');  
		}

		if (!empty($TableProperties)){
			$tableRow = array();
			$tableRow[] = '<table>'."\n";

				foreach($TableProperties as $Property){
					$rowCells = '<tr><td>'.$Property->attributes()->headername.'</td><td>'. $Property->Value.'</td></tr>'."\n";
					$tableRow[] = $rowCells;
				}

			$tableRow[] = '</table>';	
			$tableRows = implode('', $tableRow);
			return $tableRows;
		}
}





function getRelated($record){
	$related = array();
	//$tableRow[] = '<table>'."\n";
	$RelatedArticles = $record->xpath('RelatedArticles/Article');  
		foreach($RelatedArticles as $Related){
			$row = 'id='.$Related->attributes()->id.', pos='.$Related->attributes()->position.', type='.$Related->attributes()->type."\n";
			$related[] = $row;
		}

	//$tableRow[] = '</table>';	
	$relatedRows = implode('', $related);
	return $relatedRows;
	//print_r($tableRows);
}







function getProductDetailPrices($record, $priceListName, $node){
	//$prices = $record->xpath('ProductDetailPrices/Price[@priceListName="'.$priceListName.'"]');   //[@priceListName='.$priceListName.']
	$prices = $record->xpath('ProductDetailPrices'); 

	foreach($prices as $price){
			$productPrice = (string) $price->Price->GrossPrice;

		
		print_r($productPrice);	
		return $productPrice;

	} 
} 







function unzipAndTransform($zipfile, $xmlToDB){
		// assuming file.zip is in the same directory as the executing script.
		// get the absolute path to $file
		$path = pathinfo(realpath("zip/".$zipfile), PATHINFO_DIRNAME);
		$zip = new ZipArchive;
		$res = $zip->open("zip/".$zipfile);
		if ($res === TRUE) {
		  // extract it to the path we determined above
		  $zip->extractTo($path);
		  $zip->close();

		  //zip deleten
		  unlink($path."/".$zipfile);

		  //debuggen
		  GLOBAL $debug;
		  echo ($debug == 'on' ? "1.0) $path / $zipfile unzipped en deleted<br>\n" : "");

		  
		  	//xml dezelfde filename geven als de zip
			$xmlfilename = str_replace("zip","xml", $zipfile);

			//XLS TRANSFORM TAKE LONG
		 	transformXML($xmlfilename, '14XSLT.xsl', $xmlToDB);

		} else {
		  //debuggen
		  GLOBAL $debug;	
		  //echo ($debug == 'on' ? "kon zipbestand niet openen #8896 ".$zipfile."<br>\n" :"");
		  if($debug == 'on'){ 
		  	echo "zipbestand niet gevonden #8896 ".$zipfile." EXIT<br>\n";
		  	exit();
		  }
		}
}

// this takes a lot of time
function transformXML($xmlFile, $xlsFile, $transformedXmlFilename){
			$xmlDoc = new DOMDocument('1.0');
			$xmlDoc->formatOutput = true;

			//remove EZBase 'Result-' from unzipped filename
			$xmlFileEZB = str_replace("Result-","", $xmlFile);

			$xmlDoc->load("zip/".$xmlFileEZB);

			$xslDoc = new DomDocument('1.0');
			$xslDoc->load($xlsFile);

			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xslDoc);

			$strXml= $proc->transformToXML($xmlDoc);

			//debuggen
			//echo ($proc->transformToXML($xmlDoc));

			// Way to parse XML string and save to a file
			$convertedXML = simplexml_load_string($strXml);
			$convertedXML->saveXML("xml/".$transformedXmlFilename);

			//debuggen
			GLOBAL $debug;
			echo ($debug == 'on' ? "2.1) ".$transformedXmlFilename . " getransformeert met ".$xlsFile."<br>\n" : "");

			//return ($proc->transformToXML($xmlDoc));
	

}


//vervallen?
function createNewHistoryTable($host,$db,$user,$pass, $productsHistory, $productsImport){

		$dsn = "mysql:host=$host;dbname=$db";	
		$pdo = new PDO($dsn, $user, $pass);	
		//$currentTable = 'products';
		//$newTableName = $compareTable;

		$pdo->query("DROP TABLE IF EXISTS $productsHistory");
		 
		//Run the CREATE TABLE new_table LIKE current_table
		$pdo->query("CREATE TABLE $productsHistory LIKE $productsImport");
		//Import the data from the old table into the new table.
		$pdo->query("INSERT $productsHistory SELECT * FROM $productsImport");


		//debuggen
		echo ($debug == 'on' ? "0.1) Laatste export gekopieerd naar $productsHistory<br>\n" : "");

}


//TODO CVL REPLACE IS MINDER SNEL DAN 'INSERT ON DUPLICATE KEY UPDATE'
//ECHTER BIJ 'INSERT ON DUPLICATE KEY UPDATE' MOET MEN ALLE KOLOMMEN EN VALUES  DEFINIEREN IN HET UPDATE STATEMENT
function UpsertHistoryTable($host,$db,$user,$pass, $productsUpsert, $productsImport){

		$dsn = "mysql:host=$host;dbname=$db";	
		$pdo = new PDO($dsn, $user, $pass);	

		$pdo->query("CREATE TABLE IF NOT EXISTS $productsUpsert LIKE productsStructure");

		//Import the data from the old table into the new table.
		$pdo->query("REPLACE INTO $productsUpsert SELECT * FROM $productsImport");


		//debuggen
		GLOBAL $debug;
		echo ($debug == 'on' ? "5.1) Laatste export geupsert naar $productsUpsert<br>\n" :"");

}





function CompareAndCreateNewMutations($host,$db,$user,$pass, $dataColumnsHeaders, $productsImport, $productsHistory, $productMutations){
		$dsn = "mysql:host=$host;dbname=$db";	
		$pdoC = new PDO($dsn, $user, $pass);

 		$pdoC->query("DROP TABLE IF EXISTS $productMutations");

		$pdoC->query("CREATE TABLE $productMutations 

			SELECT * FROM $productsImport          
			WHERE ($dataColumnsHeaders) 
			NOT IN (SELECT $dataColumnsHeaders FROM $productsHistory)");

		//debuggen
		GLOBAL $debug;
		echo ($debug == 'on' ? "5.0) Nieuwe mutatietabel aangemaakt: $productMutations<br>\n" :"");


		if($debug == 'on'){
		$nRows = $pdoC->query("select count(*) from ".$productMutations."")->fetchColumn(); 
		echo "5.2) ".$nRows." nieuwe records toegevoegd aan tabel ".$productMutations."<br>\n"; 
		}
}













?>