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

require("AlboTorinoParser.php");
define('SELECTION_FORM_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio;jsessionid=A7AAB8DEA03B8B38A523391514236713?p_auth=8GWyser9&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiFiltro');

$subpage=$_GET['subpage'];
$year=$_GET['year'];
$number=$_GET['number'];

if (!isset($subpage) || !isset($year) || !isset($number))
	die("E' necessario specificare sottopagina, anno e numero.");

$entry = (new AlboTorinoParser())->getEntry(urldecode($subpage), $year, $number);
$date=$entry->startDate->format('d/m/Y');
  
$title="Albo POP Comune di Torino - Avviso $year / $number del $date";
$logo="logo.png";
$news="Albo Pop Torino sostiene l'iniziativa <a href=\"https://www.facebook.com/groups/271334679900477\">#openamat</a>
per la liberazione dei dati sul trasporto pubblico a Palermo.";
$description=$entry->category.'-'.$entry->subject;

$link=$entry->link;
$css="../RSS/sharer.css";
$supporter_name="Riccardo Grosso";
$supporter_img="grosso.png";
$credits="Il logo di questo albo pop &egrave; stato ottenuto da una foto di Marta Grosso
		della Mole Antonelliana elaborata con il tool <a href=\"https://photofunia.com/effects/popart\">PhotoFunia</a>.";
$news="<code><a href=\"http://opendatahacklab.org\">opendahacklab</a></code> partecipa
al raduno di Open Data Sicilia <a href=\"http://ods16.opendatahacklab.org\">#ODS16</a>
che si terr&agrave; a Messina il 2,3 e 4 Settembre a Messina.";

require("../RSS/sharer-template.php");
?>
