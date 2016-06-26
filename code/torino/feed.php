<?php
/**
 * Generate the feed of Albo POP torino
 
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
require('AlboTorinoParser.php');

//$a=new AlboTorinoSubPageParser("http://www.comune.torino.it/albopretorio/albogiunta.shtml", "tipe");
$i=0;
$a=new AlboTorinoParser();
foreach($a as $e){
	echo "$i year ".($e->year)." number ".($e->number)." category $e->category \n";
	echo "link ".($e->link)." \n";
	echo "subPage uri $e->subPageURI \n";
	echo "subject ".$e->subject." \n";
	if ($e->startDate!=null && $e->endDate!=null)
		echo "start ".$e->startDate->format('d/m/Y')." end ".$e->endDate->format('d/m/Y')."\n";
	echo "errors ".$e->parseErrors."\n -------------------- \n";
	$i++;
}
?>