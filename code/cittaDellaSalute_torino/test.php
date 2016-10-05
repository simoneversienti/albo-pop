<?php 
require ('./AlboCittaDellaSaluteParserFactory.php');

$url = "https://www.cittadellasalute.to.it/albo/pubblicazione.xml";
$parser = (new AlboCittaDellaSaluteParserFactory())->createFromWebPage();
$i=0;
foreach($parser as $atto){
	$i++;	
	echo "Atto $i\n";
	echo "ndelibera $atto->ndelibera\n";
	echo "ddelibera $atto->ndelibera\n";
	echo "\n\n";
}
?>