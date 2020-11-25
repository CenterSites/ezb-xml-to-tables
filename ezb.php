<?php
//header('Content-Type: text/xml');
header( 'Content-type: text/html; charset=utf-8' );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbcon.php';
include 'functions.php';

	if (!empty($_GET['xml'])) {
			$xmlToDB = $_GET['xml'];
	}else{  
			$xmlToDB = "mag_nl.xml";
	}
	if (!empty($_GET['zip'])) {
			$zipToTR = $_GET['zip'];
	}else{  
			$zipToTR = "mag_nl.zip";
	}

$database = "ezb_products";
$dbtable = "products";
$dataColumns = array (
"ArticleGroupId", //c0 
"ArticleGroupName", //c1
"ArticleGroupBrand", //c2
"ArticleGroupKind", //c3
"CatalogusDefault", //c4
"CatalogusHeader", //c5
"CatalogusFooter", //c6
"ExactHeader", //c7
"ExactFooter", //c8
"ExactSplit1", //c9
"ExactSplit2", //c10
"ExtrasHeader", //c11
"ExtrasFooter", //c12
"FamilyHeader", //c13
"FamilyFooter", //c14
"PromoHeader", //c15
"PromoFooter", //c16
"Level1", //c17
"Level2", //c18
"Level3", //c19
"Level4", //c20
"ArticleNumber", //c21
"TypeNumber", //c22
"ArticleGtin", //c23
"ManufacturerArticleGtin", //c24 
"CbsNumber", //c25
"TableProperties", //c26 
"TablePropertiesToSpec",//c27
"GroupAssetsIcons",//c28
"GroupAssetsImages",//c29
"GroupAssetsLogos", //c30
"GroupAssetsDocs", //c31
"ArticleAssetsIcons",//c32
"ArticleAssetsImages",//c33
"ArticleAssetsLogos", //c34
"ArticleAssetsDocs", //c35
"PrdState", //c36
"PrdLifeTime" //c37
); 

$dataColumnsKeys = implode(", ", array_keys($dataColumns));
$dataColumnsValues = implode(", ", $dataColumns);

unzipAndTransform($zipToTR, $xmlToDB);
$sqlClear = "TRUNCATE TABLE ezb_products.products";
	if(mysqli_query($mysqli, $sqlClear)){
	    echo "3) Database tabel $database / $dbtable is geleegd.<br>";
	} else{
	    echo "ERROR: Could not able to execute $sqlClear. <br>" . mysqli_error($mysqli);
}

	##recordxpath
	$xml = simplexml_load_file("xml/".$xmlToDB) or die("Error: Cannot create object");
	$articleRecords = $xml->xpath('/Webshop/ArticleGroups/ArticleGroup/Articles/Article');
	$rows = 0;
    foreach($articleRecords as $record) {

    			$rows ++;
				$exactsplit = explode(",", getSpecificationText('1 Exact', $record, 'Footer'));
				$exactsplit0 = isset($exactsplit[0]) ? $exactsplit[0] : null;	
				$exactsplit1 = isset($exactsplit[1]) ? $exactsplit[1] : null;	

				$c0 = getArticleGroupData($record, 'id','Att');
				$c1 = getArticleGroupData($record, 'Name','Node');
				$c2 = getArticleGroupData($record, 'Brand','Node');
				$c3 = getArticleGroupData($record, 'Kind','Node');

				$c4 = getDefaultSpecificationText($record, 'Header');
				$c5 = getSpecificationText('4 Catalogus', $record, 'Header');
				$c6 = getSpecificationText('4 Catalogus', $record, 'Footer');
				$c7 = getSpecificationText('1 Exact', $record, 'Header');
				$c8 = getSpecificationText('1 Exact', $record, 'Footer');
				$c9 = $exactsplit0;
				$c10 = $exactsplit1;
				$c11 = getSpecificationText('2 Extras', $record, 'Header');
				$c12 = getSpecificationText('2 Extras', $record, 'Footer');
				$c13 = getSpecificationText('3 Familieomschrijving', $record, 'Header');
				$c14 = getSpecificationText('3 Familieomschrijving', $record, 'Footer');
				$c15 = getSpecificationText('5 Promo', $record, 'Header');
				$c16 = getSpecificationText('5 Promo', $record, 'Footer');

				$c17 = getClassifications($record, 1, "name");
				$c18 = getClassifications($record, 2, "name");
				$c19 = getClassifications($record, 3, "name");
				$c20 = getClassifications($record, 4, "name");

				$c21 = $record->ArticleNumber;
				$c22 = $record->TypeNumber;
				$c23 = $record->ArticleGtin;
				$c24 = $record->ManufacturerArticleGtin;
				$c25 = $record->CbsNum;
				$c26 = getProperties($record);
				$c27 = getPropertiesToSpec($record);

				$c28 = getAssets($record,'Url','Icon','GRP');
				$c29 = getAssets($record,'Url','Image','GRP');
				$c30 = getAssets($record,'Url','Logo','GRP');
				$c31 = getAssets($record,'Url','Document','GRP');

				$c32 = getAssets($record,'Url','Icon','PRD');
				$c33 = getAssets($record,'Url','Image','PRD');
				$c34 = getAssets($record,'Url','Logo','PRD');
				$c35 = getAssets($record,'Url','Document','PRD');				
				$c36 = $record->States->State;
				$c37 = $record->States->LifeTime;

				//https://phpdelusions.net/mysqli_examples/insert
				$sqlInsert = "INSERT INTO $database.$dbtable ($dataColumnsValues) 
							  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

				$stmt= $mysqli->prepare($sqlInsert);
				$stmt->bind_param("issssssssssssssssssssssiiissssssssssss", 
				$c0, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $c21, $c22, $c23, $c24, $c25, $c26, $c27, $c28, $c29, $c30, $c31, $c32, $c33, $c34, $c35, $c36, $c37);
				$stmt->execute();
    }

//opruimen
echo "4) ".$rows . " records toegevoegd aan ".$db.", table ".$dbtable."<br>";    
mysqli_close($mysqli);
echo "6) Database verbinding gesloten <br><br>";
?>