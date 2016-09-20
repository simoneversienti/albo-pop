<?php 


$url = "https://www.cittadellasalute.to.it/albo/pubblicazione.xml";
/*$simple = new DOMDocument();
$simple->load("https://www.cittadellasalute.to.it/albo/pubblicazione.xml");*/
$page = new DOMDocument();
$page->load('https://www.cittadellasalute.to.it/albo/pubblicazione.xml');
$xml = simplexml_load_string($page->saveXML());
//$var = $xml->getElementsByTagName('atto');
echo $xml->atto[3]->tipologia;
//$file = file_get_contents($url, FALSE, stream_context_create(array('http' =>array('user_agent' => 'php' ))));
//$simple = simplexml_load_string($file);
//echo $simple->saveXML();
/*$x = $simple->documentElement;
foreach ($x->childNodes AS $item) {
	echo $item->nodeName . " = " . $item->nodeValue . "<br>";
}*/





?>