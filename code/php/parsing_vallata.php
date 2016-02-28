
//-------------------------------------------------------------------------------
//  LIBRARY FOR PARSING Albo POP Project
//-------------------------------------------------------------------------------
//  Author      Matteo Tempestini
//  Date        11 02 2016
//  License     MIT
//-------------------------------------------------------------------------------
//  List of parsers for Albo POP 
//-------------------------------------------------------------------------------

<?php

require_once('utility.php');

//estracting text information, date and links and from albo pretorio of "Vallata" cities. Based on Albo Pretorio formats
function parsing_albo_vallata($url) {

 	$html= file_get_contents($url);
 	//$html= parse_url($url);
 	
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
 
	//XPath Query
	$my_xpath_query = "//p";
	$res1 = $xpath->query($my_xpath_query);
   	
   	$my_xpath_query2 = "//a/@href";
	$res2 = $xpath->query($my_xpath_query2);

	foreach($res1 as $span1)
	   {
			$string = $span1->nodeValue;
			$string = text_do_better($string);
		}
	
	foreach($res2 as $span2)
	   {
			$links[] = "http://194.243.23.67:8080/albopretorio/" .$span2->nodeValue;
		}    
		$links = link_do_better($links);

	
		//divido in items
		$string_arr = explode("Numero: ", $string);

		unset($string_arr[0]);
		//rimuovo i tags
		$string_arr=str_replace("Tipo:", ";",$string_arr);
		$string_arr=str_replace("Data pubblicazione:", ";",$string_arr);
		$string_arr=str_replace("Data scadenza:", ";",$string_arr);	
		$string_arr=str_replace("Area di Riferimento:", ";",$string_arr);
		$string_arr=str_replace("Oggetto:", ";",$string_arr);
		$string_arr=str_replace("Numero:", ";",$string_arr);
		$string_arr=str_replace("Documento", "",$string_arr);		
		$string_arr = str_replace("\xc2\xa0", "", $string_arr);

	for($i = 1; $i <=count($string_arr); $i++)
		{
			$data[$i-1]=explode(";", $string_arr[$i]);
		}

		//DATA
		//print_r($data);

		//LINKS
		//print_r($links);
	
		//print_r(array($data, $links));
		return array($data, $links);

}





