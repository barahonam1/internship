<?php
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		header('Location: ../index.html?error=Not Authorized');
	}
	
	// A secret salt
	$salt = "FolderHash";
	
	// The Specified Username & Password
	$username = mysql_escape_string(htmlentities($_POST['username']));
	$password = mysql_escape_string(htmlentities($_POST['password']));
	
	// Getting the first character of the username
	$u = substr($username, 0);
	/* 
		Generating the new key by making a md5 of:
			The first character of the given username
			The Salt
			The given password
	*/
	$encryptedKey = md5($u.$salt.$password);
?>

<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Sign in · Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
      }
    </style>
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/m-styles.min.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">

	    <div class="well span4 offset4">
			<?php
				echo '<h4 class="text-info">Username: </h4>'.$username . '<br />';
				echo '<h4 class="text-info">Password: </h4>'.$password.'<br /><br />';
				
				echo '<h4 class="text-error">Encrypted Key: </h4><em id="key">'.$encryptedKey.'</em><br />';
				echo '<button id="generate" class="red-stripe green m-btn">Generate Folder</button>';
			?>
		</div>

   </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
    	$(function() {
	    	$('#generate').click(function() {
	    		key = "<?php echo $encryptedKey; ?>";
		    	
		    	$.post('fileIO.php', {key:key}, function(data) {
			    	alert(data);
		    	});
	    	});
    	});
    </script>
</body></html>