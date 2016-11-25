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

require("AlboCittaMetroTorinoParser.php");

$subpage=$_GET['subpage'];
$year=$_GET['year'];
$number=$_GET['number'];

if (!isset($subpage) || !isset($year) || !isset($number))
	die("E' necessario specificare sottopagina, anno e numero.");

$entry = (new AlboCittaMetroTorinoParser())->getEntry(urldecode($subpage), $year, $number);
$date=$entry->startDate->format('d/m/Y');
  
$title="Albo POP Citt&agrave; Metropolitana di Torino - Avviso $year / $number del $date";
$news="<a href=\"http://osmele.elilan.com/ont/\">Le entit&agrave; delle pubbliche amministrazioni mappate sul territorio nazionale</a>";
$logo="http://albopop.it/images/logo.png";
$description=$entry->category.'-'.$entry->subject;

$link=$entry->link;
$css="../RSS/sharer.css";
$supporter_name="Riccardo Grosso";
$supporter_img="../torino/grosso.png";
// $credits="Il logo di questo albo pop &egrave; stato ottenuto da una foto di Marta Grosso
// 		della Mole Antonelliana elaborata con il tool <a href=\"https://photofunia.com/effects/popart\">PhotoFunia</a>.";

require("../RSS/sharer-template.php");
?>
