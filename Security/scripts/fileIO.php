<?php
	// The Salt for the filename
	$salt = "FileHash";

	/* 
		Getting the name of the folder
		Setting the path for creation
		Create folder if it doesn't exist
	*/
	$folder = $_POST['key'];
	$path = "../users/".$folder;
	if (!is_dir($path))
		$mkdir = mkdir($path);
	
	/* 
		Generating the encrypted key for the filename
		Setting the filename and its path
	*/
	$encryptedKey = md5($folder.$salt);
	$myFile = '../users/'.$folder.'/'.$encryptedKey.'.xml';
	
	// If file exists, read the contents, otherwise, create it
	if (file_exists($myFile)) {
		// File exists -- reading the file
		$fh = fopen($myFile, "r");
		$content = "File Contents:\n";
		
		while (!feof($fh))
		   $content .= fgets($fh);
		
		echo $content;
	} else {
		// File does not exist -- creating the file
		$fh = fopen($myFile, 'w') or die("Can't open file");
		fwrite($fh, "Secret File Contents");
		fclose($fh);
		
		echo "Folder & File Generated";
	}
?>