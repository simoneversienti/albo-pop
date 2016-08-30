<?php 
/**
 * A page to share feed items of facebook.
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

//backward compatibility
if (isset($_GET['repertorio'])){
	$repertorioPieces=explode('/',$_GET['repertorio']);
	$anno=trim($repertorioPieces[0]);
	$numero=trim($repertorioPieces[1]);		
} else {
	$anno=$_GET['anno'];
	$numero=$_GET['numero'];
}

if (!isset($anno) || !isset($numero))
	die("E' necessario specificare anno e numero di repertorio.");

$entry = AlboComuneCTParser::createByRepertorio($anno, $numero)->current();
if ($entry==null)
  die("Nessun elemento col numero di repertorio $anno \ $numero");

$title="Albo POP Comune di Catania - Avviso $repertorio";
$logo="ct-logo-pop.jpg";
$description=$entry->repertorio." - ".$entry->tipo.": ".$entry->mittente_descrizione;
$link=$entry->link;
$css="../RSS/sharer.css";
$news="<code><a href=\"http://opendatahacklab.org\">opendahacklab</a></code> partecipa
al raduno di Open Data Sicilia <a href=\"http://ods16.opendatahacklab.org\">#ODS16</a>
che si terr&agrave; a Messina il 2,3 e 4 Settembre a Messina.";

$credits="Il logo di questo albo pop &egrave; stato ottenuto dalla 
			pagina di Wikipedia che riporta lo <a href=\"https://it.wikipedia.org/wiki/File:Catania-Stemma.png\">stemma del comune di Catania</a>,
			elaborandolo poi con il tool <a href=\"https://photofunia.com/effects/popart\">PhotoFunia</a>.";
require("../RSS/sharer-template.php");
?>
