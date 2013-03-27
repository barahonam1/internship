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

//var for saving dialog

var tmp = "";
var $this = "";

//onload function

$(function(){ 
	loadFiles();	   
})

//loadFiles function

function loadFiles()
{	
	loadingLayer("on"); //activate loading
	dynamicStyle();
	
	$.ajax({
		url: "json/data.php",
		data: "cat=" + thisCat,
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
				autoWrapRow: true, //experimental
				onBeforeChange: function(data) //validating data
				{					
					if(data[0][2] != data[0][3]) //[3] new value
					{
						changeOccurred = true;
						$("#cancelChanges").removeClass("disabled");
					}
					
					for (var i = data.length - 1; i >= 0; i--) {
						if(/(<([^>]+)>)/ig.test(data[i][3]) == true)
						{
							data[i][3] = data[i][3].replace(/(<([^>]+)>)/ig,"");							
						}
					}						
				}
			});
		
			
			if(thisCat == "")
			{			
				var links = "";
				for(var i = 0; i < res.tabs.length; i++)
				{
					//zero for xml pointer & one for name
					active = i==0 ? "class='active'" : "";
					links += "<li " + active + " style=\"cursor:pointer\"><a class='xml' cat='" + 
					res.tabs[i][1] + "' data-toggle='tooltip' data-placement='right' title='"+res.tabs[i][2]+"'>"+res.tabs[i][0]+"</a></li>";
				}
				
				$("#myTab").append(links);
				thisCat = res.cat; //for initial saving					
			}

			$("#title").html(res.title);
			loadingLayer("off"); //deactivate loading			
		}
	});		
}

//executed when "Save Changes" is clicked

$("#saveChanges").on("click", function(){	
	saveChanges();
});

//executed when changing tabs

$(document).on('click', ".xml", function () {		
										  
	$("#output").handsontable('deselectCell');
	tmp = $(this).attr("cat");
	$this = $(this);

	if(changeOccurred == true)
	{
		$("#myModal2").modal("show"); 
	} else {
		modalOptions("cancel", tmp, $this)
	}				  
});

//not good practice to nest functions jQuery fns

$("#yesbtn").on("click", function(){ 
	modalOptions("yes", tmp, $this);
});

$("#nobtn").on("click", function(){
	modalOptions("no", tmp, $this);
	$("#cancelChanges").addClass("disabled");	
});

function modalOptions(btn, thecat, theattr)
{
	if(btn == "yes" || btn == "no")
	{
		btn == "yes" ? saveChanges() : false;
		$("#myModal2").modal("hide");
		changeOccurred = false;
	}
	
	$("#output").handsontable('destroy');
	
	thisCat = thecat; 
	loadFiles();
	
	$("#myTab li").removeClass("active"); //removes all those li with class active
	theattr.parent().attr("class", "active");
}

//misc functions

$(window).bind('beforeunload', function(){
	if(changeOccurred == true)
	{
	  $('#myModal').modal('hide');
	  $('#myModal2').modal('hide');
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

	var tmp_array = []; //solution for comma bugs

	for(var i = 0; i < $("#output").handsontable('countRows'); i++)
	{
		for(var j = 0; j < $("#output").handsontable('countCols'); j++)
		{
			tmp_array.push($("#output").handsontable('getDataAtCell', i, j));
		}
	}
	
	loadingLayer("on"); //activate loading
	
	$.ajax({
		url: "json/update.php",
		data: "hd=" + tmpColHeaders + "&dt=" + 
			tmp_array.join("<a>") + "&tb=" + 
			thisCat + "&tt=" + $("#title").html(),
		type: 'POST',
		success: function () {
			changeOccurred = false; //normalize
			loadingLayer("off");
		}
	});	
	
	$("#cancelChanges").addClass("disabled");	
}

//the dynamic nature of the tables requires the below fn

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

$("#deleteFunction").on("click", function(){										  										  									
	if(records.length > 0)
	{
		$('#myModal').modal('show');
	}
});	

$("#deleteNow").on("click", function(){											
	records.sort(function(a,b){return a-b});
	records.reverse();
	
	for(var i = 0; i < records.length; i++)
	{
		instance.alter("remove_row", records[i]);
	}
	records = []; //empties array		
	changeOccurred = true; //attention to this!
	$("#cancelChanges").removeClass("disabled");
	$('#myModal').modal('hide');
});

//Tooltip
$(document).on('mouseenter', ".xml", function () {		
	$(this).tooltip("show");
});

//Cancel Changes
$("#cancelChanges").on("click", function(){
	if(changeOccurred == true)
	{
		$("#output").handsontable('destroy');
		$(this).addClass("disabled");
		changeOccurred = false;
		loadFiles();
	}
});

//Ctrl to save (experimental)
$("#output").keydown(function(e) {
	if (e.keyCode == 83 && e.ctrlKey) {
	    e.preventDefault();
		$("#output").handsontable('deselectCell');
		saveChanges();
	}
});