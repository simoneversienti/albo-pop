<?php
/**
 * Generate the feed of Albo POP torino
 
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
require('AlboTorinoParser.php');
require("../RSS/RSSFeedGenerator.php");

$parser=new AlboTorinoParser();
$feed=new RSSFeedGenerator("Albo POP del Comune di Torino", "Versione POP dell'Albo Pretorio del Comune di Torino",
		'http://www.comune.torino.it/albopretorio/',"http://dev.opendatasicilia.it/albopop/torino/albofeed.php");

foreach($parser as $e){
	//$link="http://dev.opendatasicilia.it/albopop/belpasso/albofeed.php?anno=".urlencode($r->anno_registrazione)."&numero=".urlencode($r->numero_registrazione);
	if (strlen($e->parseErrors))
		$feed->addComment($e->parseErrors);
	else{
		$sharer_url="http://dev.opendatasicilia.it/albopop/torino/sharer.php?subpage=".urlencode($e->subPageURI)."&year=".$e->year."&number=".$e->number;
		$feed->addItem($e->subject,
				"$e->year/$e->number $e->category - $e->subject",
				$e->startDate, $sharer_url, $sharer_url);
	}
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