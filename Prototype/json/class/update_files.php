<?php
/*
Author: Miguel Barahona
Date: February 16, 2013
*/

class updateXMLFiles
{
	private $headers;
	private $data;
	private $table;
	private $title;
	private $xml;
	
	public function __construct($hd, $dt, $tb, $tt)
	{
		//initialize vars
		$this->headers = $hd;
		$this->data = $dt;
		$this->table = $tb;
		$this->title = $tt;
		$this->xml = "xml/masterdata.xml";
	}	
	
	public function updateXML()
	{
		$doc = new DOMDocument();
		$doc->load($this->xml);			
		
		$tableToUpdate = $this->table;
			
		$tagName = $doc->getElementsByTagName($tableToUpdate)->item(0)->getElementsByTagName('records')->item(0);
		$tagChildren = $tagName->childNodes;
		
		while($tagChildren->length > 0)  //removes children
		{
			$tagName->removeChild($tagChildren->item(0));
		}
		
		$counter = 0;
		
		for($i=0; $i<sizeof($this->data); $i++) //sizeof($this->data) - sizeof($this->headers) for ignoring last spare record
		{
			
			if($i == 0 || ($i%sizeof($this->headers) == 0))
			{				
				$recordElement = $doc->createElement('record');
			}						
			
			//pretty print xml
			$txtn = $doc->createTextNode("\n\t\t\t\t\t");
			$recordElement->appendChild($txtn);
			//pretty print xml			
			
			$eachElement = $doc->createElement(strtolower($this->headers[$counter]), 
			get_magic_quotes_gpc() == true ? strip_tags(stripslashes($this->data[$i])) : strip_tags($this->data[$i])); //portability
			$recordElement->appendChild($eachElement);			
			
			$counter++;
			
			if(($i+1)%sizeof($this->headers) == 0)
			{				
				//pretty print xml
				$txtn2 = $doc->createTextNode("\n\t\t\t\t");
				$recordElement->appendChild($txtn2);
				$textn3 = $doc->createTextNode("\n\t\t\t\t");
				$tagName->appendChild($textn3);
				//pretty print xml
				
				$tagName->appendChild($recordElement);
				$counter = 0;
			}
		}			
		
		
		//pretty print xml
		$txtn4 = $doc->createTextNode("\n\t\t\t");
		$tagName->appendChild($txtn4);
		//pretty print xml
		
		$xmlString = $doc->save($this->xml, LIBXML_NOEMPTYTAG); //use LIBXML_NOEMPTYTAG for preserving empty nodes <node></node>				
	}
}
?>