<?php 
require('AlboUnitoParserFactory.php');
define('ALBO_UNITO_URL','https://www.serviziweb.unito.it/albo_ateneo/');
$a=AlboUnitoParserFactory::createFromWebPage(ALBO_UNITO_URL);

foreach($a as $e){
	echo "Numero repertorio $e->numero_repertorio\n";
	echo "Anno repertorio $e->anno_repertorio\n";
	echo "Data inserimento ".($e->data_inserimento)->format('d/m/Y')."\n";
	echo "Struttura $e->struttura\n";
	echo "Oggetto $e->oggetto\n";
	echo "Data inizio pubblicazione ".($e->inizio_pubblicazione)->format('d/m/Y')."\n";
	echo "Data fine pubblicazione ".($e->fine_pubblicazione)->format('d/m/Y')."\n";
	echo "Links\n";
	foreach($e->links as $l)
		echo "\t$l\n";
	echo "--------\n";
}
?>