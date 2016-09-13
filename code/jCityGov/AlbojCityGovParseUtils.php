<?php 
/**
 * Some utilities to parse rows.
 
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
 *
 * @author Cristiano Longo
 * 
 */
define('DATE_FORMAT','d/m/Y');
define('CONTENT_SEPARATOR','/');

class AlbojCityGovParseUtils{
	/**
	 * Parse a content placed on two lines in two different contents.
	 *
	 * @param $fullContent the content as string, placed on two lines
	 *
	 * @return an array of one or two contents with no trailing spaces. If the second piece
	 * is not present, use the empty string instead
	 */
	public static function parseTwoLinesField($fullContent){
		$contentPieces=explode(CONTENT_SEPARATOR, $fullContent);
		if (count($contentPieces>1)) return array(trim($contentPieces[0]), trim($contentPieces[1]));
		return array(trim($contentPieces[0]),'');
	}
	
	/**
	 * Parse the content of the periodo pubblicazione field.
	 *
	 * @param $td the table cell
	 *
	 * @return an array of one or two dates with no trailing spaces.
	 */
	public static function parseInizioFinePubblicazione($td){
		//remove all whitespaces
		$inizio_fine_pubblicazione=str_replace(' ', '', $td->textContent);
		$l=strlen($inizio_fine_pubblicazione);
		if ($l<10 || $l>20) throw new Exception("Invalid dates $inizio_fine_pubblicazione");
	
		//just the start date
		if ($l==10)
			return array(AlbojCityGovParseUtils::parseDate($inizio_fine_pubblicazione));
	
			//both start and end time
			$inizio_pubblicazione=substr($inizio_fine_pubblicazione,0,10);
			$fine_pubblicazione=substr($inizio_fine_pubblicazione,10);
			return array(AlbojCityGovParseUtils::parseDate($inizio_pubblicazione),
					AlbojCityGovParseUtils::parseDate($fine_pubblicazione));
	}
	
	/**
	 * Get the url from the corresponding table cell.
	 *
	 * @return the url as string if any
	 */
	public static function parseURL($td){
		$anchors=$td->getElementsByTagName('a');
		foreach($anchors as $a)
			if (strcmp($a->getAttribute('title'), 'Apri Dettaglio')==0)
				return $a->getAttribute('href');
				throw new Exception("No URL found.");
	}
	
	/**
	 *
	 * @param string $dateStr
	 */
	public static function parseDate($dateStr){
		$d=DateTime::createFromFormat(DATE_FORMAT,
				trim($dateStr));
		if ($d==FALSE) throw new Exception("Unable to parse date - $dateStr -");
		$d->setTime(0,0);
		return $d;
	}
}
?>