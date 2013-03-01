<?php 
	session_start();
	require_once("connect.php");
	
	// Init vars
	$id = "";
	$name = "";
	$phone = "";
	$ipaddress = "";
	$FolderName = "";
	$FileName = "";
	$ipaddress = $_SERVER['REMOTE_ADDR'];	// Getting ip address of user
	
	
	// The secret salts
	$FolderSalt = "FolderHash";
	$FileSalt = "FileHash";
	
	// Getting the inputted username and password
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	// Calling the query
	$result = mysql_query("SELECT * FROM users WHERE email = '$email' AND password = MD5('$password')");
	$num = mysql_num_rows($result);
	
	if ($num == 0) {
		// If no user exists with this, send them back to index page with the error string
		header('Location: ../index.php?errorString=<h3 class="text-error">Incorrect Email/Password Combination</h3>');
	} else {
		// Otherwise, get all the info on the user
		$row = mysql_fetch_array($result);
		
		$id = $row['id'];							// The id of the user in the DB
		$name = $row['fname'].' '.$row['lname'];	// Combination of firstname and lastname
		$phone = $row['phone'];
		$email = $row['email'];						// The users email
		$ipaddress = $_SERVER['REMOTE_ADDR'];		// Getting ip address of user
		
		// Creating the salt on the fly
		$FolderName = md5($email.$FolderSalt.$password);	// The encrypted foldername
		$FileName = md5($FolderName.$FileSalt);				// The encrypted filename
	}
?>


<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>My Last List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Account page for user">
    <meta name="author" content="Zill Christian">

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }
      .container {
        width: auto;
        max-width: 680px;
      }
      .container .credit {
        margin: 20px 0;
      }


    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>
    <div id="wrap">

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>User Information</h1>
          <h4>Database Interaction for Admin <a href="logout.php" class="pull-right">Log out</a></h4>

        </div>
        
        <br />
        
        <p class="lead">Stored Inside Database</p>
        <p>ID: <em class="muted"><?php echo $id; ?></em></p>
        <p>Name: <em class="muted"><?php echo $name; ?></em></p>
        <p>Email: <em class="muted"><?php echo $email; ?></em></p>
        <p>Phone: <em class="muted"><?php echo $phone; ?></em></p>

        <br />
        
        <p class="lead">Script Creation</p>
        <p>Foldername: <em class="muted"><?php echo $FolderName; ?></em></p>
        <p>Filename: <em class="muted"><?php echo $FileName; ?></em></p>
        
        <br />
        <p class="lead">Security</p>
        <p>IP Address: <em class="muted"><?php echo $ipaddress; ?></em></p>    
      </div>

      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">My Last List | <em class="text-info">Database Interaction</em></p>
      </div>
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
</body></html>