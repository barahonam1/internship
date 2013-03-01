<?php
/*
Author: Miguel Barahona
Date: February 16, 2013
*/
require_once("class/xmlparser.php");
$records = new XMLParser($_POST["cat"]);
$records->loadProperties();
echo "{\"data\": ".$records->createData().", \"colHeaders\": ".$records->obtainColHeaders().", \"colWidths\": ".$records->defineColWidths().", \"title\": \"".$records->obtainTitle()."\"}";
?>