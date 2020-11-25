<link rel="stylesheet" type="text/css" href="table.css">

<table>
  <thead>	
  <tr class="gray">
    <th>ArticleGroupId</th>
    <th>ArticleGroupName</th> 
    <th>ArticleGroupBrand</th> 
    <th>ArticleGroupKind</th> 

    <th>Catalogus Default</th>
    <th>Catalogus Header</th> 
    <th>Catalogus Footer</th> 

    <th>ExactHeader</th> 
    <th>ExactFooter</th>
    <th>ExactSplit1</th>
    <th>ExactSplit2</th>

    <th>ExtrasHeader</th> 
    <th>ExtrasFooter</th> 

    <th>FamilyHeader</th> 
    <th>FamilyFooter</th> 

    <th>PromoHeader</th> 
    <th>PromoFooter</th> 

    <th>Level1</th>
    <th>Level2</th>
    <th>Level3</th>
    <th>Level4</th>    

    <th>ArticleNumber</th>
    <th>TypeNumber</th> 
    <th>ArticleGtin</th>
    <th>ManufacturerArticleGtin</th>
    <th>CbsNumber</th>

  </tr>
  </thead>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'functions.php';


$xml = simplexml_load_file("test_transformed.xml") or die("Error: Cannot create object");

	echo "<tbody>";
	##recordxpath
	$articleRecords = $xml->xpath('/Webshop/ArticleGroups/ArticleGroup/Articles/Article');
    foreach($articleRecords as $record) {



	    		##artikelgroepdata
    			echo "<tr>";
	         	echo "<td>".getArticleGroupData($record, 'id','Att')."</td>";
	         	echo "<td>".getArticleGroupData($record, 'Name','Node')."</td>";
	         	echo "<td>".getArticleGroupData($record, 'Brand','Node')."</td>";
	         	echo "<td>".getArticleGroupData($record, 'Kind','Node')."</td>";


				##DEFAULT Specification teksten
	         	echo "<td class='specificationlevel'>". getDefaultSpecificationText($record, 'Header') ."</td>";


				##EXTRA Specification teksten
				echo "<td class='specificationlevel'>" . getSpecificationText('4 Catalogus', $record, 'Header') . "</td>";
				echo "<td class='specificationlevel'>" . getSpecificationText('4 Catalogus', $record, 'Footer') . "</td>";

				echo "<td class='exact'>" . getSpecificationText('1 Exact', $record, 'Header') . "</td>";
				echo "<td class='exact'>" . getSpecificationText('1 Exact', $record, 'Footer') . "</td>";

				$exactsplit = explode(",", getSpecificationText('1 Exact', $record, 'Footer'));
				$exactsplit0 = isset($exactsplit[0]) ? $exactsplit[0] : null;
				$exactsplit1 = isset($exactsplit[1]) ? $exactsplit[1] : null;
				echo "<td class='exact'>" . $exactsplit0 . "</td>";
				echo "<td class='exact'>" . $exactsplit1 . "</td>";

				echo "<td>" . getSpecificationText('2 Extras', $record, 'Header') . "</td>";
				echo "<td>" . getSpecificationText('2 Extras', $record, 'Footer') . "</td>";

				echo "<td>" . getSpecificationText('3 Familieomschrijving', $record, 'Header') . "</td>";
				echo "<td>" . getSpecificationText('3 Familieomschrijving', $record, 'Footer') . "</td>";

				echo "<td>" . getSpecificationText('5 Promo', $record, 'Header') . "</td>";
				echo "<td>" . getSpecificationText('5 Promo', $record, 'Footer') . "</td>";


				##EzBase Classificatielagen
				echo "<td class='classLevel'>"  .  getClassifications($record, 1, "name") . "</td>";
				echo "<td class='classLevel'>"  .  getClassifications($record, 2, "name") . "</td>";
				echo "<td class='classLevel'>"  .  getClassifications($record, 3, "name") . "</td>";
				echo "<td class='classLevel'>"  .  getClassifications($record, 4, "name") . "</td>";

	##recordxpath		
    $ArticleNumber = $record->ArticleNumber;
    $TypeNumber = $record->TypeNumber;
    $ArticleGtin = $record->ArticleGtin;
    $ManufacturerArticleGtin = $record->ManufacturerArticleGtin;
    $CbsNumber = $record->CbsNumber;



    echo "	<td class='artLevel'>". $ArticleNumber ." </td>
    		<td class='artLevel'> ".$TypeNumber." </td>
    		<td class='artLevel'> ".$ArticleGtin." </td>
    		<td class='artLevel'> ".$ManufacturerArticleGtin." </td>
    		<td class='artLevel'> ".$CbsNumber." </td>";
    }

    echo "</tr>";
    echo "</tbody>";
?>
</table>





