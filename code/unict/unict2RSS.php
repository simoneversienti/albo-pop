<?php 
/**
 * This script turn the html page of the official 'Albo' of the University
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

require("AlboUnictParser.php");
require("RSSFeedGenerator.php");

//parsing
$parser = new AlboUnictParser();
$feed=new RSSFeedGenerator("Albo dell'Università di Catania", "Versione POP dell'Albo Ufficiale di Ateneo dell'Università degli Studi di Catania", 
		"http://ws1.unict.it/albo/","http://www.dmi.unict.it/~longo/albo-pop/unict/unict2RSS.php");
foreach($parser as $r){
	$feed->addItem("Avviso ".$r->numero, $r->description, $r->inizio_pubblicazione, $r->link, 'http://ws1.unict.it/albo/'.$r->numero);
}

//output
header('Content-type: application/atom+xml; charset=UTF-8');
/*
 * Impostazioni locali in italiano, utilizzato per la stampa di data e ora
* (il server deve avere il locale italiano installato
		*/
setlocale(LC_TIME, 'it_IT');
echo $feed->getFeed();
?>