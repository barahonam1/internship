<?php
/*
Author: Miguel Barahona
Date: February 16, 2013
*/

class XMLParser
{	
	private $category;
	private $colHeaders;
	private $colWidths;
	private $records;
	private $title;

	public function __construct($cat)
	{
		$this->category = $cat;
		$this->xml = "xml/master.xml";
		$this->colHeaders = array();
		$this->colWidths = array();
		$this->records = array();
	}	
	
	public function loadProperties()
	{
		//in case server is busy
		$sxml = @simplexml_load_file("xml/master.xml"); 				
		if(!$sxml) { 
			$sxml = @simplexml_load_file("xml/master.xml");
			if(!sxml) {
				echo "There was a problem with the server. Please try again.";
				return false;
			}
		}	
		
		$category = $this->category;	
		$this->title = $sxml->$category->title;
				
		foreach($sxml->$category->records->record as $eachRecord) //changes made "guitars included"
		{
			$subarray = array();
			
			foreach($eachRecord->children() as $recordItem)
			{
				if($recordItem->getName() == "id"){} 
				else {
					$subarray[] = stripslashes($recordItem);
				}
			}
			$this->records[] = $subarray;
		}	
		
		foreach($sxml->$category->records->record->children() as $eachHeader) //changes made "guitars included"
		{
			if($eachHeader->getName() != "id")
			{
				$this->colHeaders[] = ucfirst($eachHeader->getName()); 				
				$this->colWidths[] = 80;
			}
		}	
	}
	
	public function obtainColHeaders()
	{		
		$colHeaders = "[";
						
		for($i=0; $i<sizeof($this->colHeaders); $i++)
		{
			$colHeaders .= "\"".$this->colHeaders[$i]."\"";
			$colHeaders .= $i != sizeof($this->colHeaders) - 1 ? ", " : false;
		}
		
		$colHeaders .= "]";
		return $colHeaders;
	}	
	
	public function defineColWidths()
	{
		$colWidths = "[";
		for($i=0; $i<sizeof($this->colWidths); $i++)
		{
			$colWidths .= $this->colWidths[$i];
			$colWidths .= $i != sizeof($this->colWidths) - 1 ? ", " : false;
		}
		
		$colWidths .= "]";
		return $colWidths;
	}
	
	public function createData()
	{
		$records = "[";
					 
		for($i=0; $i<sizeof($this->records); $i++)
		{
			$records .= "[";
			for($j=0; $j<sizeof($this->records[$i]); $j++)
			{
				$records .= $this->testIfNumber($this->records[$i][$j]);
				$records .= $j != sizeof($this->records[$i]) - 1 ? ", " : false; 
			}			
			$records .= "]";
			$records .= $i != sizeof($this->records) - 1 ? ", " : false;
		}
		
		$records .= "]";
		return $records;
	}
	
	public function obtainTitle()
	{
		return $this->title;
	}
	
	private function testIfNumber($theVar)
	{
		if(is_numeric($theVar))
		{
			return $theVar;
		} else {
			return "\"".$theVar."\"";
		}
	}
}
?>