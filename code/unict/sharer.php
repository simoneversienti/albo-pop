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

require("AlboUnictParser.php");
$number=$_GET['number'];
if (!isset($number))
	die("E' necessario specificare un numero di avviso.");

$entry = (new AlboUnictParser())->getByNumber($number);
if ($entry==null)
  die("Nessun elemento con numero $number");

$css="../RSS/sharer.css";
$title="Albo POP Universit&agrave; di Catania - Avviso $number";
$logo="logo.png";
$description=$entry->richiedente.": ".$entry->description;
$link=$entry->link;
$news="Vieni a conoscere il progetto <a href=\"http://albopop.it\">Albo POP</a> al prossimo <a href=\"http://www.opendatahacklab.org/site/eventdetails.html?iri=http%3A%2F%2Fopendatahacklab.org%2Fontology%2Fevents%2F02072016\">Aperitivo Pop</a></em>
a Belpasso (CT) Sabato 2 Luglio 2016 alle ore 19:00 presso il Wine Bar Efesto in via XIX traversa numero 66
		(vedi anche l'<a href=\"https://www.facebook.com/events/602434039925008/\">Evento Facebook</a>).";

require("../RSS/sharer-template.php");
?>
