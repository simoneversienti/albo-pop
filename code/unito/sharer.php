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

require("AlboUnitoParserFactory.php");

$year=$_GET['year'];
$number=$_GET['number'];

if (!isset($year) || !isset($number))
	die("E' necessario specificare  anno e numero.");

$parser=(new AlboUnitoParserFactory())->createByYearAndNumber($year, $number);
if (!$parser->valid())
	die("L'avviso non esiste, potrebbe essere stato rimosso");

$entry = $parser->current();
$date=$entry->inizio_pubblicazione->format('d/m/Y');
  
$title="Albo POP Universita` di Torino - Avviso $year / $number del $date";
$logo="http://albopop.it/images/logo.png";
$description= "$entry->struttura : $entry->oggetto";
if (count ( $entry->links )>0)
	$link = each ( $entry->links ) ['key'];

$css="../RSS/sharer.css";
require("../RSS/sharer-template.php");
?>
