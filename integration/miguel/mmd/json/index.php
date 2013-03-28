<?php
session_start();
if(!isset($_SESSION["fldrname"]) && !isset($_SESSION["flname"])) {
	header('Location: ../../index.php');
	exit;
}

if(!isset($_POST["cat"])) {
	header('Location: ../index.php');
	exit;
}
?>