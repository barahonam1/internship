<?php
session_start();
/*
Author: Miguel Barahona
Date: February 16, 2013
*/

if(!isset($_SESSION["fldrname"]) && !isset($_SESSION["flname"])) {
	header('Location: ../../index.php'); 
	exit;
}

if(!isset($_POST["cat"])) {
	header('Location: ../index.php');
	exit;
}

require_once("class/xmlparser.php");
$records = new XMLParser($_POST["cat"]);
$records->loadProperties();
echo "{\"data\": ".$records->createData().", \"colHeaders\": ".$records->obtainColHeaders().", \"title\": \"".$records->obtainTitle()."\", \"cat\": \"".$records->obtainCategory()."\", \"tabs\": ".$records->obtainTabs()."}";
?>