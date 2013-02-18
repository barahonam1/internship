<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display Records</title>

<!--Loading Handsontable-->
<script src="lib/jquery.js"></script> 
<script src="dist/jquery.handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="dist/jquery.handsontable.full.css">

<!-- Table Extensions (Optional) - @! API has not been updated yet!
<script src="ext/jquery.handsontable.removeRow.js"></script>
<link rel="stylesheet" media="screen" href="ext/jquery.handsontable.removeRow.css" />
-->

<!--jQuery UI-->
<link rel="stylesheet" href="dist/css/custom-theme/jquery-ui-1.9.2.custom.min.css" />
<script src="lib/js/jquery-ui-1.9.2.custom.min.js"></script>
<link rel="stylesheet" href="dist/css/custom-theme/extra.css" />

</head>

<body>

<div id="main">	
    <br/>
    <div id="tabs" style="font-size:80%;">    
    
        <ul>
            <li><a class="xml" title="guitars" href="#tabs-1">Guitars</a></li>
            <li><a class="xml" title="albums" href="#tabs-1">Albums</a></li>
        </ul>
        <div id="tabs-1">
			<div id="loading"><img src="dist/css/custom-theme/images/ajax-loader.gif" /></div>
		    <div id="overlay"></div>		    
            
			<h1 id="title"></h1>
        	<div id="output" style="height: 300px; overflow: scroll"> <!--@! to relative-->            	             
			</div>
        </div>
    </div>
	<br/>
   <div id="toolbar">
   <!--Keep Below as Image for IE-->
   <img id="saveChanges" style="cursor:pointer" src="dist/css/custom-theme/images/savebtn.png" />      
   </div>
</div>

<script src="lib/extra.js"></script>

</body>
</html>