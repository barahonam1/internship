/*
Author: Miguel Barahona
Date: February 16, 2013
*/

var changeOccurred = false;
var thisCat = "";
var tmpColHeaders; //@! temporary while api is being updated
var colHeaderActivated = false;

//onload function

$(function(){ 
	$("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
	$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );		
	loadFiles("guitars");	   
})

//loadFiles function

function loadFiles(category)
{
	thisCat = category; //initialise	
	loadingLayer("on"); //activate loading
	dynamicStyle();
	
	$.ajax({
		url: "json/data.php",
		data: "cat=" + category,
		dataType: 'json',
		type: 'POST',
		success: function (res) {			
			tmpColHeaders = res.colHeaders //@! temporary while api is being updated
			$("#output").handsontable({
				data: res.data,
				columnSorting: true,
				rowHeaders: true,
				stretchH: 'all',
				colHeaders: res.colHeaders,
				minSpareRows: 1,								
				onBeforeChange: function(data) //validating data
				{	
					if(colHeaderActivated == true)
					{						
					
						$("#output").handsontable('deselectCell');
						//prevents from navigating back in Chrome and Firefox
						$("#output").keydown(function(e){if(e.keyCode == 8){e.preventDefault();}}); 
						
						//confirmation message
						var r = confirm("You cannot edit the grid when sorting is enabled.\nIf "+
						"you wish to edit the grid click on \"OK\".\nOtherwise \"Cancel\".");
						if(r)
						{
							$("#output").handsontable('destroy'); //to reset table (more efficient instead of patching)
							colHeaderActivated = false;							
							loadFiles(category); //Changes are automatically saved (look at misc fns below) 
						} 

						$("#output").handsontable.loadData(res.data); //patch to prevent the scrolls from breaking
						return false;	
					}
				
					for (var i = data.length - 1; i >= 0; i--) {
						if(/(<([^>]+)>)/ig.test(data[i][3]) == true)
						{
							data[i][3] = data[i][3].replace(/(<([^>]+)>)/ig,"");							
						}
					}						
				},
				onChange: function(changes, source){
					if(source === 'loadData')
					{
						return;
					} else {
						changeOccurred = true;
					}
				}
			});									
			$("#title").html(res.title);
			loadingLayer("off"); //deactivate loading
		}
	});		
}

//executed when "Save Changes" is clicked

$("#saveChanges").live("click", function(){												 
	saveChanges();
});

//executed when changing tabs

$(".xml").live("click", function(){
	$("#output").handsontable('destroy'); //to reset table (more efficient instead of patching)
	colHeaderActivated = false;
	loadFiles($(this).attr("title"));	   
});

//misc functions

$("thead .colHeader").live("click", function(){
	saveChanges();
	colHeaderActivated = true;
});

$(window).bind('beforeunload', function(){
	if(changeOccurred == true)
	{
	  return 'You may have unsaved changes.';
	}
});

function loadingLayer(onOff)
{
	if(onOff == "on")
	{$("#loading").show();$("#overlay").show();} 
	else {$("#loading").hide();$("#overlay").hide();}	
}


function saveChanges()
{
	$("#output").handsontable("deselectCell"); //on editing, it deselects and saves		
	loadingLayer("on"); //activate loading
	
	$.ajax({
		url: "json/update.php",
		data: "hd=" + tmpColHeaders + "&dt=" + 
			$("#output").data('handsontable').getData() + "&tb=" + 
			thisCat + "&tt=" + $("#title").html(),
		type: 'POST',
		success: function () {
			changeOccurred = false; //normalize
			loadingLayer("off");
		}
	});	
}

//the dynamic nature of the tables requires the belowf fn

function dynamicStyle() {
	$("#output").css("height","300px");
	$("#output").css("overflow","scroll");
}