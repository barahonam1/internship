<?php
class updateXMLFiles
{
	private $headers;
	private $data;
	private $table;
	private $title;
	
	public function __construct($hd, $dt, $tb, $tt)
	{
		//initialize vars
		$this->headers = $hd;
		$this->data = $dt;
		$this->table = $tb;
		$this->title = $tt;
	}	
	
	public function updateXML()
	{
		$doc = new DOMDocument();
		$doc->load("xml/master.xml");			
		
		$tableToUpdate = $this->table;
			
		$tagName = $doc->getElementsByTagName($tableToUpdate)->item(0)->getElementsByTagName('records')->item(0);
		$tagChildren = $tagName->childNodes;
		
		while($tagChildren->length > 0)  //removes children
		{
			$tagName->removeChild($tagChildren->item(0));
		}
		
		$counter = 0;
		
		for($i=0; $i<sizeof($this->data) - sizeof($this->headers); $i++) //ignore last spare record
		{
			
			if($i == 0 || ($i%sizeof($this->headers) == 0))
			{				
				$recordElement = $doc->createElement('record');
			}						
			
			//pretty print xml
			$txtn = $doc->createTextNode("\n\t\t\t\t");
			$recordElement->appendChild($txtn);
			//pretty print xml
			
			$eachElement = $doc->createElement(strtolower($this->headers[$counter]), $this->data[$i]); //make sure not empty
			$recordElement->appendChild($eachElement);			
			
			$counter++;
			
			if(($i+1)%sizeof($this->headers) == 0)
			{				
				//pretty print xml
				$txtn2 = $doc->createTextNode("\n\t\t\t");
				$recordElement->appendChild($txtn2);
				$textn3 = $doc->createTextNode("\n\t\t\t");
				$tagName->appendChild($textn3);
				//pretty print xml
				
				$tagName->appendChild($recordElement);
				$counter = 0;
			}
		}			
		
		
		//pretty print xml
		$txtn4 = $doc->createTextNode("\n\t\t");
		$tagName->appendChild($txtn4);
		//pretty print xml
		
		$xmlString = $doc->saveHTML(); //use saveHTML() better for preserving empty nodes <node></node>
		
		$xmlf = fopen("xml/master.xml", "w"); 
		if(!$xmlf)
		{
			echo "There was an error. Please try again.";
			exit;
		}
		@fputs($xmlf, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".$xmlString);
		@fclose($xmlf);	
		
	}
}
?>