<?php
	   $writer = new XMLWriter();  
	   $writer->openMemory();
	   $writer->startDocument('1.0','UTF-8');   
	   $writer->setIndent(4);   
	   $writer->startElement('items');  
	   $writer->startElement("main");  
	   $writer->writeElement('user_id', 3);  
	   $writer->writeElement('msg_count', 11);  
	   $writer->endElement();   
	   $writer->startElement("msg");  
	   $writer->writeAttribute('category', 'test');  
	   $writer->endElement();     
	   $writer->endElement();   
	   $writer->endDocument();   
	   $a = $writer->outputMemory(TRUE);
	   $writer->flush(); 
	   echo $a;
	   
?>