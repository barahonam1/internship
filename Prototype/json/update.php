<?php
/*
Author: Miguel Barahona
Date: February 16, 2013
*/

//main methods
require_once("class/update_files.php");
$headers = explode(",", $_POST["hd"]); //creates an array
$data = explode("<a>", $_POST["dt"]); //creates an array 
$table = $_POST["tb"];
$title = $_POST["tt"];

$updateXMLFiles = new updateXMLFiles($headers, $data, $table, $title);
$updateXMLFiles->updateXML();

?>