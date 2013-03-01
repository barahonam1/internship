<!DOCTYPE html>
<html>
  <head>
    <title>Manage My Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    
    <!-- Plugin -->
    <link rel="stylesheet" media="screen" href="css/jquery.handsontable.full.css">
    
    <!-- Extra CSS -->
    <link rel="stylesheet" href="css/extra.css">
  </head>
  <body>
  
    <!-- Nav Bar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="#">Manage My Data</a>
                <div class="nav-collapse collapse">
                    <p class="navbar-text pull-right">
                        Logged in as <a href="#" class="navbar-link">Username</a>
                    </p>
                    <ul class="nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
	
    
    <!-- Content -->
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
			<div class="well sidebar-nav">
                <ul class="nav nav-tabs nav-stacked">
                  <li class="active"><a href="#home" data-toggle="tab" class="xml" title="guitars">Guitars</a></li>
                  <li><a href="#profile" data-toggle="tab" class="xml" title="albums">Albums</a></li>
                </ul>
            </div>
        </div>
        <div class="span10">
			<div class="hero-unit" style="position:relative">
            
                <div id="loading"><img src="img/ajax-loader.gif" /></div>
                <div id="overlay"></div>
            
            	<h1 id="title"></h1>
                <div id="output"></div>
                
                <span id="saveChanges" class="btn">Save Changes</span>
                <span id="deleteFunction" class="btn btn-danger">Delete</span>                
                
			</div>
        </div>
      </div>
    </div>
    
    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Delete Record</h3>
      </div>
      <div class="modal-body">
        <p align="center"><i class="icon-question-sign"></i> Are you sure you want to delete these records?</p>
      </div>
      <div class="modal-footer">
        <button id="deleteNow" class="btn btn-primary">OK</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button> <!-- !@ Should uncheck? -->
      </div>
    </div>
    
    <!-- Bootstrap -->
    <script src="js/jquery.js"></script> <!--@! bt & pl-->
    <script src="js/bootstrap.min.js"></script>
    
    <!-- Plugin -->    
    <script src="js/jquery.handsontable.full.js"></script>    
    <script src="js/settings.js"></script>
    
  </body>
</html>