<?php 
/**
 * This script produce the rss feed from the web page of the albo of the municipality of
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

require ('../phpalbogenerator/AlboPopGenerator.php');
require ('AlboBelpassoParserFactory.php');
require ('AlboBelpassoItemConverter.php');
$generator = new AlboPopGenerator ( new AlboBelpassoParserFactory (), new AlboBelpassoItemConverter () );
$generator->outputFeed ("Albo POP del Comune di Belpasso", "Versione POP dell'Albo Pretorio del Comune di Belpasso", "http://dev.opendatasicilia.it/albopop/torino/albofeed.php",
			"http://dev.opendatasicilia.it/albopop/torino/albofeed.php");
?>