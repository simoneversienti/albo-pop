<?php 

require ('../phpalbogenerator/AlboPopGenerator.php');
require ('./AlboCittaDellaSaluteParserFactory.php');
require ('AlboCittaDellaSaluteItemConverter.php');
$generator = new AlboPopGenerator ( new AlboCittaDellaSaluteParserFactory (), new AlboCittaDellaSaluteItemConverter () );
$generator->outputFeed ("Albo POP della città della salute di Torino", "Versione POP dell'Albo Pretorio della città della salute del comune di Torino", "http://dev.opendatasicilia.it/albopop/torino/albofeed.php",
		"http://dev.opendatasicilia.it/albopop/torino/albofeed.php");
?>