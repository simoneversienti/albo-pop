<?php 
/**
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

  * @author Luigi Rizzo
 */

require("AlboParser.php");
require("../RSS/RSSFeedGenerator.php");
require("../RSS/RSSFeedItem.php");

$parser = AlboParser::createByYear();
$feed=new RSSFeedGenerator("Albo del Comune di Sant'Agata li Battiati",
		"Versione POP dell'Albo Pretorio del Comune di Sant'Agata li Battiati",
		"http://albopretorio.datamanagement.it/?ente=SantAgataLiBattiati",
		"http://dev.opendatasicilia.it/albopop/santagatalibattiati/albo2RSS.php");
foreach($parser as $r){
	$title = $r->mittente_descrizione;
	if (empty($title)) {
		$feed->addItem('ERROR', null, null, $r->link, $r->link);
	} else {
		$feed->addItem($title, $r->anno . "/" . $r->numero . " - " . $r->mittente_descrizione, null, $r->link, $r->link);
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