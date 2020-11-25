<?php
header('Content-Type: text/xml');

$xmlDoc = new DOMDocument('1.0');
$xmlDoc->formatOutput = true;
$xmlDoc->load("test.xml");

$xslDoc = new DomDocument('1.0');
$xslDoc->load("12XSLT.xsl");

$proc = new XSLTProcessor;
$proc->importStyleSheet($xslDoc);

$strXml= $proc->transformToXML($xmlDoc);

echo ($proc->transformToXML($xmlDoc));

// Way to parse XML string and save to a file
$convertedXML = simplexml_load_string($strXml);
$convertedXML->saveXML("test2.xml");




?>