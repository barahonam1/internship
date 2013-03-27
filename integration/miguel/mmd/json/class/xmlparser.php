<?php
/*
Author: Miguel Barahona
Date: February 16, 2013
*/

class XMLParser
{	
	private $category;
	private $xml;
	private $common;
	private $colHeaders = "";
	private $records = "";
	private $title = "";
	private $tabs = "";
	
	public function __construct($cat)
	{
		$this->category = $cat;
		$this->xml = "../../users/".$_SESSION["fldrname"]."/".$_SESSION["flname"].".xml";
		$this->common = "../../common/commonschema.xml";		
	}	
	
	public function loadProperties()
	{		
		//update file if common changed
		if(filemtime($this->common) != file_get_contents("../../users/".$_SESSION["fldrname"]."/updatetime.txt"))
		{
			$timefile = fopen("../../users/".$_SESSION["fldrname"]."/updatetime.txt", "w"); 
			
			$this->update_master_file();
			
			if(!$timefile)
			{return "There was an error. Please try again.";}
			
			@fputs($timefile, filemtime($this->common));
			@fclose($timefile);
		} 
		
		//in case server is busy
		$sxml = @simplexml_load_file($this->xml); 				
		if(!$sxml) { 
			$sxml = @simplexml_load_file($this->xml);
			if(!sxml) {
				return "There was a problem with the server. Please try again.";
			}
		}	
		
		//sets category
		$category = $this->category;	
		if($category == "") //when $category is empty select first table
		{
			$tmp = $sxml->tables->children();
			$category = $tmp[0]->getName();
			$this->category = $category;
		} 

		//sets title
		$this->title = $sxml->tables->$category->title;
				
		//forms records
		$this->records .= "[";
		$recordCounter = count($sxml->tables->$category->records->children());
		foreach($sxml->tables->$category->records->record as $eachRecord) //changes made "guitars included"
		{
			$this->records .= "[";
			$itemCounter = count($eachRecord->children());
			foreach($eachRecord->children() as $recordItem)
			{
				$this->records .= $this->testIfNumber($this->stripItem($recordItem));
				$this->records .= $itemCounter != 1 ? "," : false;
				$itemCounter--;
			}
			$this->records .= "]";
			$this->records .= $recordCounter != 1 ? "," : false;
			$recordCounter--;
		}
		$this->records .= "]";
		
		//forms colheaders			
		$this->colHeaders .= "[";
		$headCounter = count($sxml->tables->$category->records->record->children());
		foreach($sxml->tables->$category->records->record->children() as $eachHeader) //changes made "guitars included"
		{
			$this->colHeaders .= "\"".ucfirst($eachHeader->getName())."\"";
			$this->colHeaders .= $headCounter != 1 ? "," : false;
			$headCounter--;			
		}	
		$this->colHeaders .= "]";
		
		//forms tabs
		$this->tabs .= "[";
		$tabsCounter = count($sxml->tables->children());
		foreach($sxml->tables->children() as $eachTab)
		{	
			$this->tabs .= "[";
			$this->tabs .= "\"".$eachTab->tabname."\", ";
			$this->tabs .= "\"".$eachTab->getName()."\", ";
			$this->tabs .= "\"".$eachTab->longdesc."\"";
			$this->tabs .= "]";
			$this->tabs .= $tabsCounter != 1 ? "," : false;
			$tabsCounter--;
		}
		$this->tabs .= "]";
	}
	
	public function obtainColHeaders() {return $this->colHeaders;}		
	
	public function createData() {return $this->records;}
	
	public function obtainTabs() {return $this->tabs;}
	
	public function obtainTitle() {return $this->title;}
	
	public function obtainCategory() {return $this->category;}
	
	private function testIfNumber($theVar)
	{
		if(is_numeric($theVar))
		{
			return $theVar;
		} else {
			return "\"".$theVar."\"";
		}
	}
	
	private function stripItem($str) 
	{
		$str = str_replace("\\", "\\\\", $str); 
		$str = str_replace("\"", "\\\"", $str);
		return $str;
	} 
	
	private function update_master_file()
	{
		//you manipulate common
		$common = new DOMDocument();
		$common->load($this->common);		
		
		//you just read this one
		$master = simplexml_load_file($this->xml);		
		
		$tabs = $common->getElementsByTagName("tables")->item(0)->childNodes;
		foreach($tabs as $tab)
		{
			if($tab->nodeName != "#text")
			{
				$tabName = $tab->nodeName;
				$tabContent = $this->get_value_if_tab_exists($tabName, $master);
				if($tabContent)
				{					
					$deleteFirst = 1;
					for($i=0; $i<count($master->tables->$tabName->records->children()); $i++)
					{
						//creates main record element
						$createEachRecord = $common->createElement("record"); 						
						
						$this_table = $common->getElementsByTagName($tabName)->item(0)->getElementsByTagName("record")->item(0)->childNodes;				
						foreach($this_table as $each)
						{						
							if($each->nodeName != "#text")
							{						
								$recordCol = $each->nodeName;
								$recordValue = $this->get_value_if_record_exists($recordCol, $tabName, $i, $master);						
								
								//pretty print xml
								$txtn = $common->createTextNode("\n\t\t\t\t\t");
								$createEachRecord->appendChild($txtn);									
								
								//removes first record node (no longer needed)
								$tmp = $common->getElementsByTagName($tabName)->item(0)->getElementsByTagName("records")->item(0);
								$tmp2 = $tmp->childNodes;
								
								//experimental
								$bool = false;
								//experimental
								
								while($tmp2->length > 0 && $deleteFirst == 1)
								{
									$tmp->removeChild($tmp2->item(0));
								}								
								
								//creates each individual record element
								$createElement = $common->createElement($recordCol); 
								$createRecordValue = $common->createTextNode($recordValue); 
								$createElement->appendChild($createRecordValue);					
								$createEachRecord->appendChild($createElement);									
								
								$deleteFirst = 0; //stops from removing first child until another table is processed
							}
						}	
						
						//creates each record node
						$recordsElement = $common->getElementsByTagName($tabName)->item(0)->getElementsByTagName("records")->item(0);
						$recordsElement->appendChild($createEachRecord);										
						
						//pretty print xml
						$txtn2 = $common->createTextNode("\n\t\t\t\t");
						$recordsElement->appendChild($txtn2);
					}			
				}
			}
		}	
		
		$common->save($this->xml, LIBXML_NOEMPTYTAG);
	}
	
	private function get_value_if_tab_exists($tab, $mst) {
		$tabContent = false;
		
		foreach($mst->tables->children() as $eachTab)
		{
			if($tab == $eachTab->getName())
			{
				$tabContent =  true;
				break;
			}
		}
		
		return $tabContent;
	}
	
	private function get_value_if_record_exists($col, $tabl, $ite, $mst) 
	{
		$recordValue = "";
		
		$array = $mst->tables->$tabl->records->children();
		foreach($array[$ite] as $each)
		{
			if($col == $each->getName())
			{
				$recordValue .= $each;
			}
		}
		
		return $recordValue;
	}
}
?>