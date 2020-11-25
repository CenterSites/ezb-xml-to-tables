<?php
//https://www.w3schools.com/php/php_file_upload.asp
$target_dir = "zip/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

/*
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
*/
// Check if file already exists
if (file_exists($target_file)) {
    echo "Dit bestand bestaat al. ";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 20000000) {
    echo "Het bestand is te groot, max.2Mb. #9912 ";
    echo '<br><a href="http://huvemanode.hypernode.io/ezbase/">Ga terug</a><br><br>';
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "zip" && $imageFileType != "zip" && $imageFileType != "zip"
&& $imageFileType != "zip" ) {
    echo "Alleen ZIP bestanden zijn toegestaan. #9963 ";
    echo '<br><a href="httphttp://huvemanode.hypernode.io/ezbase/">Ga terug</a><br><br>';

    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Het bestand kon niet worden toegevoegd. #9987 ";
    echo '<br><a href="http://huvemanode.hypernode.io/ezbase/">Ga terug</a><br><br>';

// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<h1>Bestand ". basename( $_FILES["fileToUpload"]["name"]). " is toegevoegd.</h1>";

        $zipFile = basename( $_FILES["fileToUpload"]["name"]);

// form vars doorzetten naar script
echo "
<form action='xpath2.php' method='post'>
  <input type='hidden' id='zip' name='zip' value='".$zipFile."'>
  <input type='hidden' id='tableSuffix' name='tableSuffix' value='".$_POST['tableSuffix']."'>  
  <input type='hidden' id='tablePrefix' name='tablePrefix' value='".$_POST['tablePrefix']."'>  
  <input type='hidden' id='tableSuffixCron' name='tableSuffixCron' value='".$_POST['tableSuffixCron']."'>  
  <input type='submit' value='Transfomeer XML naar CSV en maak database tabbellen met Prefix: ".$_POST['tablePrefix']." | Suffix: ".$_POST['tableSuffix']."'>
</form>";


    } else {
        echo "Er is een fout opgetreden: #9945 ";
    }
}
?>