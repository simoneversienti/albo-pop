<?php 
/**
 * This script turn the html page of the official 'Albo' of the municipality
 * of Catania into a rss feed.
 *
 * Copyright 2016 Cristiano Longo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require("AlboComuneCTParser.php");
require("../RSS/RSSFeedGenerator.php");

$parser = AlboComuneCTParser::createByDate();
$feed=new RSSFeedGenerator("Albo del Comune di Catania", "Versione POP dell'Albo Pretorio del Comune di Catania", 
		"http://www.comune.catania.gov.it/EtnaInWeb/AlboPretorio.nsf/HomePage?Open&buffer=A20110301121017437GH","http://dev.opendatasicilia.it/albopop/catania/alboct2RSS.php");
foreach($parser as $r){
	//remove the sender if it is internal to the municipality of Catania
	$title=preg_replace('%^.*- COMUNE DI CATANIA *%', '',$r->mittente_descrizione,1);
	if (empty($title))
		$feed->addItem('ERROR', null, null, $r->link, $r->link);
	else
		$feed->addItem($title, $r->repertorio." - ".$r->tipo.": ".$r->mittente_descrizione, null, $r->link, $r->link);
}

//output
header('Content-type: application/rss+xml; charset=UTF-8');
/*
 * Impostazioni locali in italiano, utilizzato per la stampa di data e ora
 * (il server deve avere il locale italiano installato
 */
setlocale(LC_TIME, 'it_IT');
echo $feed->getFeed();
?>