/*
Author: Miguel Barahona
Date: February 16, 2013
*/

//main vars

var changeOccurred = false;
var thisCat = "";
var tmpColHeaders; 

//vars for deleting records
var records = [];
var instance;

//onload function

$(function(){ 
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
		
			records = []; //empties array and unchecks any checkboxes
			Handsontable.PluginHooks['hooks'].walkontableConfig = []; 
			deleteRecordsFN();
		
			tmpColHeaders = res.colHeaders 
			$("#output").handsontable({
				data: res.data,
				rowHeaders: false, //for the time being
				colHeaders: res.colHeaders,
				minSpareRows: 1,	
				stretchH: 'all',
				onBeforeChange: function(data) //validating data
				{					
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
	loadFiles($(this).attr("title"));	   
});

//misc functions

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

//populate with checkboxes

function deleteRecordsFN()
{
	"use strict";	
	Handsontable.PluginHooks.push('walkontableConfig', function (walkontableConfig) {
																 
			instance = this;				
			instance.rootElement.addClass('htRemoveRow');
			
		
			if(!walkontableConfig.frozenColumns) {
				walkontableConfig.frozenColumns = [];
			}					
			
			walkontableConfig.frozenColumns.push(function(row, elem){
					if(elem.nodeName == 'COL') {
						$(elem).addClass('htRemoveRow');
						return;
					}
				
					//Gotta loop array to see if it is cheked or not
					if(row != null && row < ($("#output").handsontable('countRows') - 1))
					{
						
						var shouldBeChecked = false;
						for(var i = 0; i < records.length; i++)
						{
							if(row == records[i])
							{
								shouldBeChecked = true;
							}
						}				
						var checked = shouldBeChecked ? "checked" : "";
						var $div = $('<input id="record" type="checkbox" value="'+row+'" ' + checked + '/>');
						
					} else {
						//pending
						var $div = $('');
					}
	
					$div.on('mouseup', function(){
						if(!$(this).prop('checked'))
						{
							records.push(parseInt(row)); //adds row to the array
						} else 
						{
							records.splice(records.indexOf(row), 1); //removes row from the array
						}
					});							
			
					var $th = $(elem);
					$th.addClass('htRemoveRow htNoFrame').html($div);
			});
	});
}

//delete records function

$("#deleteFunction").live("click", function(){										  										  									
	if(records.length > 0)
	{
		$('#myModal').modal('show');
	}
});	

$("#deleteNow").live("click", function(){											
	records.sort(function(a,b){return a-b});
	records.reverse();
	for(var i = 0; i < records.length; i++)
	{
		instance.alter("remove_row", records[i]);
	}
	records = []; //empties array		
	$('#myModal').modal('hide');
});