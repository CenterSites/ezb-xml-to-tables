<?php
//Center van Liempd - CenterSites 2020

header( 'Content-type: text/html; charset=utf-8' );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbcon.php';
include 'functions.php';

global $debug;
$debug = "on";
$rustart = getrusage();


$tablePrefix = $argv[1];
$tableSuffix = $argv[2];
$ezbZipFileName = $argv[3];

//debuggen
echo ($debug == 'on' ? "0.0) TABLE PREFIX = ".$tablePrefix. " TABLE SUFFIX = ".$tableSuffix."<br>\n" : "");  



$productsImport = $tablePrefix."productsImport".$tableSuffix;
$productsHistory = $tablePrefix."productsHistory".$tableSuffix;
$productMutations = $tablePrefix."productMutations".$tableSuffix;
$productsUpsert = $tablePrefix."productsUpsert".$tableSuffix;

//$zipToTR = "mag_nl.zip";
$zipToTR = $ezbZipFileName;

$xmlToDB = str_replace("zip","xml", $zipToTR); //geef getransformeerde xml dezelfde naam als de bron zip file




//kolomnamen tabellen
$dataColumns = array (
"ArticleId", //cID	
"ArticlePos", //cPos	
"ArticleSubGroup", //cSub	
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
"TablePropertiesToSpecTransposed", //c11
"TablePropertiesTransposed", //c12
"ArticleAssetsDocsDesc", //c13 --
"ArticleAssetsDocsType", //c14 --
"ArticleAssetsDocsFile", //c15 --
"RelatedArticles", //c16
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
"ArticleAssetsIconsDesc",//c28
"GroupAssetsImages",//c29
"GroupAssetsLogos", //c30
"GroupAssetsDocs", //c31
"ArticleAssetsIcons",//c32
"ArticleAssetsImages",//c33
"ArticleAssetsLogos", //c34
"ArticleAssetsDocsUrl", //c35 --
"PrdState", //c36
"PrdLifeTime" //c37
); 
$dataColumnsKeys = implode(", ", array_keys($dataColumns));
$dataColumnsHeaders = implode(", ", $dataColumns);


unzipAndTransform($zipToTR, $xmlToDB);


$sqlClear = "DROP TABLE IF EXISTS $db.$productsImport";
	if(mysqli_query($mysqli, $sqlClear)){
	    echo ($debug == 'on' ? "3.1) Vorige tabel $db / $productsImport is verwijderd.<br>\n" : "");
	} else{
	    echo ($debug == 'on' ? "ERROR: Could not able to execute $sqlClear. EXIT <br>\n" . mysqli_error($mysqli) : "");
	    exit();
}

//tabel productsStructure is vaste structuur voor alle andere gecreeerde tabellen
$sqlCreate = "CREATE TABLE $db.$productsImport LIKE $db.productsStructure";
	if(mysqli_query($mysqli, $sqlCreate)){
	    echo ($debug == 'on' ? "3.2) Nieuwe lege tabel $db / $productsImport is gemaakt obv tabel productsStructure <br>\n" : "");
	} else{
	    echo ($debug == 'on' ? "ERROR: Could not able to execute $sqlCreate. EXIT <br>\n" . mysqli_error($mysqli) : "");
	    exit();
}




//$xmlFileEZB = str_replace("Result-","", $xmlToDB); // xmlFileEZB temp var to test without transforming XML

//Pulk de waarden uit de XML
$xml = simplexml_load_file("xml/".$xmlToDB) or die("Error: Cannot create object");
	$rows = 0;
	##recordxpath
	$articleRecords = $xml->xpath('/Webshop/ArticleGroups/ArticleGroup/Articles/Article');
    foreach($articleRecords as $record) {

    			$rows ++;
				$exactsplit = explode(",", getSpecificationText('1 Exact', $record, 'Footer'));
				$exactsplit0 = isset($exactsplit[0]) ? $exactsplit[0] : null;	
				$exactsplit1 = isset($exactsplit[1]) ? $exactsplit[1] : null;	


				$cID= $record->attributes()->id;
				$cPos = $record->attributes()->position;
				$cSub = $record->attributes()->articleSubGroup;

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
				$c11 = getPropertiesTransposed($record, "true");
				$c12 = getPropertiesTransposed($record, "false");

				$c13 = getAssets($record,'Description','Document','PRD');
				$c14 = getAssets($record,'OriginalExtension','Document','PRD');
				$c15 = getAssets($record,'OriginalFile','Document','PRD');

				//$c13 = getSpecificationText('3 Familieomschrijving', $record, 'Header');
				//$c14 = getSpecificationText('3 Familieomschrijving', $record, 'Footer');
				//$c15 = getSpecificationText('5 Promo', $record, 'Header');
				$c16 = getRelated($record, 'id');

				$c17 = getClassifications($record, 1, "name");
				$c18 = getClassifications($record, 2, "name");
				$c19 = getClassifications($record, 3, "name");
				$c20 = getClassifications($record, 4, "name");

				$c21 = $record->ArticleNumber;
				$c22 = $record->TypeNumber;
				$c23 = $record->ArticleGtin;
				$c24 = $record->ManufacturerArticleGtin;
				$c25 = $record->CbsNum;
				$c26 = getProperties($record, "false");
				$c27 = getProperties($record, "true");

				//$c28 = getAssetsFilename($record,'OriginalFile','Icon','GRP');
				$c28 = getAssets($record,'Description','Icon','PRD');

				$c29 = getAssets($record,'Url','Image','GRP');
				$c30 = getAssetsLarge($record,'Url','Logo','GRP');
				$c31 = getAssets($record,'Url','Document','GRP');

				//$c32 = getAssetsFilename($record,'OriginalFile','Icon','PRD');
				$c32 = getAssetsLarge($record,'Url','Icon','PRD');

				$c33 = getAssets($record,'Url','Image','PRD');
				$c34 = getAssetsLarge($record,'Url','Logo','PRD');
				$c35 = getAssets($record,'Url','Document','PRD');				
				$c36 = $record->States->State;
				$c37 = $record->States->LifeTime;

				//https://phpdelusions.net/mysqli_examples/insert how-to
				$sqlInsert = "INSERT INTO $db.$productsImport ($dataColumnsHeaders) 
							  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

				$stmt= $mysqli->prepare($sqlInsert);
				$stmt->bind_param("iiiisssssssssssssssssssssssssssssssssssss", 
				$cID, $cPos, $cSub, $c0, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $c21, $c22, $c23, $c24, $c25, $c26, $c27, $c28, $c29, $c30, $c31, $c32, $c33, $c34, $c35, $c36, $c37);
				$stmt->execute();	


    }




//debuggen
echo ($debug == 'on' ? "4.1) ".$rows . " records toegevoegd aan ".$db.", table ".$productsImport."<br>\n" : "");    



//Vergelijk tabellen $productsImport met $productsUpsert en creeer alleen afwijkenden en nieuwe records in $productMutations
CompareAndCreateNewMutations($host,$db,$user,$pass, $dataColumnsHeaders, $productsImport, $productsUpsert, $productMutations);

//Vervang bestaande records en voeg nieuwe toe.
UpsertHistoryTable ($host,$db,$user,$pass, $productsUpsert, $productMutations);


//opruimen
mysqli_close($mysqli);
echo ($debug == 'on' ? "6.1) Database verbinding gesloten <br>\n" : "");


$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";



//debuggen data
/*
echo ($debug == 'on' ? "dataColumnsHeaders<br>\n" : "");
if ($debug == 'on'){print_r($dataColumnsHeaders);}
echo ($debug == 'on' ? "<br><br>dataColumnsKeys<br>\n" : "");
if ($debug == 'on'){print_r($dataColumnsKeys);}
echo ($debug == 'on' ? "<br><br>dataColumns<br>\n" : "");
if ($debug == 'on'){print_r($dataColumns);}
echo ($debug == 'on' ? "<br><br>\n" : "");
if ($debug == 'on'){print_r($record);}
echo ($debug == 'on' ? "<br><br>\n" : "");
*/



?>







