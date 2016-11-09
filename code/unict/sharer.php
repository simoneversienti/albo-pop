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

require("AlboUnictParserFactory.php");
require("../phpalbogenerator/AccessLogUtils.php");
define ("RSSPATH","http://dev.opendatasicilia.it/albopop/catania/");
AccessLogUtils::logAccess("accessNotices.log");

$number=$_GET['number'];
if (!isset($number))
	die("E' necessario specificare un numero di avviso.");

$entry = (new AlboUnictParserFactory())->createFromWebPage()->getByNumber($number);
if ($entry==null)
  die("Nessun elemento con numero $number");

$css="../RSS/sharer.css";
$ente="Universit&agrave; di Catania";
$title="Albo POP Universit&agrave; di Catania - Avviso $number";
$logo="logo.png";
$description=$entry->richiedente.": ".$entry->description;
$link=$entry->link;
$news="<code><a href=\"http://opendatahacklab.org\">opendahacklab</a></code> sostiene il
<a href=\"http://bastionedeglinfettict.it\">Bastione degli Infetti</a> nel concorso <em>I LUOGHI DEL CUORE</em>.
<a href=\"http://iluoghidelcuore.it/luoghi/86006\">Sostieni anche tu il Bastione degli Infetti!
		<img src=\"http://bastionedeglinfettict.it/img/heart-gray.png\" style=\"vertical-align: middle; height:1.8em; padding-left:1em\" alt=\"cuoricino\" /></a>";

require("../RSS/sharer-template.php");
?>
