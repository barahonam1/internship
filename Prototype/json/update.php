<?php
//main methods
require_once("class/update_files.php");
$headers = explode(",", $_POST["hd"]); //creates an array
$data = explode(",", $_POST["dt"]); //creates an array 
$table = $_POST["tb"];
$title = $_POST["tt"];

$updateXMLFiles = new updateXMLFiles($headers, $data, $table, $title);
$updateXMLFiles->updateXML();

?>