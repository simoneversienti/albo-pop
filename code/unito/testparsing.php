<?php 
require('AlboUnitoParserFactory.php');
require('AlboUnitoItemConverter.php');
//$a=AlboUnitoParserFactory::createFromWebPage(ALBO_UNITO_URL);
$f=new AlboUnitoParserFactory();
$a=$f->createByYearAndNumber(2016,1715);

foreach($a as $e){
	echo "Numero repertorio $e->numero_repertorio\n";
	echo "Anno repertorio $e->anno_repertorio\n";
	echo "Data inserimento ".($e->data_inserimento)->format('d/m/Y')."\n";
	echo "Struttura $e->struttura\n";
	echo "Oggetto $e->oggetto\n";
	echo "Data inizio pubblicazione ".($e->inizio_pubblicazione)->format('d/m/Y')."\n";
	echo "Data fine pubblicazione ".($e->fine_pubblicazione)->format('d/m/Y')."\n";
	echo "Links\n";
	foreach($e->links as $l => $t)
		echo "\t$t $l \n";
	echo "--------\n";
}
?>