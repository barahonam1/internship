<?php
	$errorString = "<h4>Password Requirements:</h4><p>6 or more characters</p><p>Atleast 1 uppercase letter</p><p>Atleast 1 lowercase letter</p><p>Atleast one number</p>";
	$type = "alert";
	$ipaddress = $_SERVER['REMOTE_ADDR'];	// Getting ip address of user
	
	// If there is an error, set it
	if (isset($_GET['errorString'])) {
		$errorString = $_GET['errorString'];
		$type = "warning";
	}
?>

<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Sign in · Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
       body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }
      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      #register {
      	  height: 40px;
	      width: 300px;
      }
    </style>
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/m-styles.min.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <form class="form-signin" method="POST" id="form" action="scripts/register.php">
        <h2 class="form-signin-heading" align="center">Register</h2>
        <hr />
        <input type="text" class="input-block-level" name="fname" id="fname" placeholder="First Name" required="required">
        <input type="text" class="input-block-level" name="lname" id="lname" placeholder="Last Name" required="required">
        <input type="text" class="input-block-level" name="phone" id="phone" placeholder="Phone Number" required="required">
        <input type="text" class="input-block-level" name="email" id="email" placeholder="Email Address" required="required">
        <input type="password" class="input-block-level" id="password" name="password" placeholder="Password" required="required" pattern="^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{6,}$">
        <input type="password" class="input-block-level" id="password2" name="password2" placeholder="Confirm Password" required="required" pattern="^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{6,}$">
       <button class="m-btn red" id="register" type="submit">Register</button>
       <a href="index.php" class="pull-right">Login instead</a>
      </form>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="js/noty/noty.js"></script>
    <script type="text/javascript" src="js/noty/layouts/top.js"></script>
    <script type="text/javascript" src="js/noty/layouts/bottom.js"></script>
    <script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="js/noty/themes/default.js"></script>    
    
    <script type="text/javascript">
    
    // On document ready
    $(function() {
 		
 		// Show notification message   
    	notify();
	   
    	// Validates everytime the input is changed
    	$('input').change(function() {
			validate(); 
		});
	   
		// Checks to make sure the passwords match
		$('#password2').keyup(function() {
			checkPass(); 
		});
	   
    });
    
    // Make sure all fields are filled out
    function validate() {
	    if ($('#fname').val() != "" && $('#lname').val() != "" && $('#phone').val() != "" && $('#email').val() != "" && $('#password').val() != "" && $('#password2').val() != "" ) {
	    	checkPass();
	    } else {
		    markBad();
	    }
    }
    
    // Make sure the passwords match
    function checkPass() {
    	var pass1 = $('#password').val();
    	var pass2 = $('#password2').val();
    	
	    if (pass1 == pass2) {
		    markGood();
	    } else {
		    markBad();
	    }
    }
    
    // Mark the register button all good
    function markGood() {
    	$('#register').removeClass("red").addClass("green");
    }

    // Mark the register button bad
    function markBad() {
    	$('#register').removeClass("green").addClass("red");
    }    
    
    // Notify the requirements or the errors
    function notify() {
    	$.noty.closeAll();
	   	noty({
	   		layout: 'bottom', text: '<?php echo $errorString; ?>', type: '<?php echo $type; ?>'
	   	});
    }
    	
    </script>
</body></html>