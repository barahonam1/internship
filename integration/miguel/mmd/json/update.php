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

if(!isset($_POST["hd"]) && !isset($_POST["dt"]) && !isset($_POST["tb"]) && !isset($_POST["tt"])) {
	header('Location: ../index.php');
	exit;
}

//main methods
require_once("class/update_files.php");
$headers = explode(",", $_POST["hd"]); //creates an array
$data = explode("<a>", $_POST["dt"]); //creates an array 
$table = $_POST["tb"];
$title = $_POST["tt"];

$updateXMLFiles = new updateXMLFiles($headers, $data, $table, $title);
$updateXMLFiles->updateXML();

?>