<?php 
/**
 * This script produce the rss feed from the jCityGov albo of the municipality of
 * Belpasso.
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

require("../jCityGov/AlbojCityGovParser.php");
require("../RSS/RSSFeedGenerator.php");
define("ALBO_URL","http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio;jsessionid=80B6AF9A15507BC123B7E2811D89A54B?p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=exportList&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_format=csv");

$parser = new AlbojCityGovParser(ALBO_URL);
$feed=new RSSFeedGenerator("Albo del Comune di Belpasso", "Versione POP dell'Albo Pretorio del Comune di Belpasso", 
 		ALBO_URL,"http://dev.opendatasicilia.it/albopop/belpasso/albofeed.php");
foreach($parser as $r){
 	$link="http://dev.opendatasicilia.it/albopop/belpasso/albofeed.php?anno=".urlencode($r->anno_registrazione)."&numero=".urlencode($r->numero_registrazione);
 	$feed->addItem($r->oggetto, 
 			$r->anno_registrazione.'/'.$r->numero_registrazione.'['.$r->titolo_categoria.','.$r->titolo_sottocategoria.','.$r->proponente_descrizione.']'.$r->oggetto, 
 		$r->data_inizio_pubblicazione, $link, $link);
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