<?php
/**
 * Generate the feed of Albo POP Città Metropolitana Torino
 
 * Copyright 2016 Cristiano Longo & Michele Maresca
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
require ('../phpalbogenerator/AlboPopGenerator.php');
require ('AlboCittaMetroTorinoParserFactory.php');
require ('AlboCittaMetroTorinoItemConverter.php');

$generator = new AlboPopGenerator ( new AlboCittaMetroTorinoParserFactory (), new AlboCittaMetroTorinoItemConverter () );
$generator->outputFeed ( "Albo POP della Città Metropolitana di Torino", "Versione POP della Città Metropolitana di Torino", "http://dev.opendatasicilia.it/albopop/cittametropolitanatorino/albofeed.php" );
?>