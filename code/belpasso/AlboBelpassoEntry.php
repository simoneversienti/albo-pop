<?php 
/**
 * An entry in the bullettin board of the Municipality of Belpasso
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

class AlboBelpassoEntry{
	var $anno_registro;
	var $numero_registro;
	var $tipo_atto;
	var $sottotipo_atto;
	var $oggetto;
	var $data_inizio_pubblicazione;
	var $data_fine_pubblicazione;
	var $url;

	/**
	 * Create an entry by parsing a table row.
	 * 
	 * @param $row a table row element
	 */
	public function __construct($row) {
		$cells=$row->getElementsByTagName('td');
		
		$anno_numero_registro=$this->parseTwoLinesField($cells->item(0)->textContent);
		$this->anno_registro=$anno_numero_registro[0];
		$this->numero_registro=$anno_numero_registro[1];
		
		$tipo_sottotipo=$this->parseTwoLinesField($cells->item(1)->textContent);
		$this->tipo_atto=$tipo_sottotipo[0];
		$this->sottotipo_atto=$tipo_sottotipo[1];
		
		$this->oggetto=$cells->item(2)->textContent;
		
		$inizio_fine_pubblicazione=$this->parseInizioFinePubblicazione($cells->item(3));
		$this->data_inizio_pubblicazione=$inizio_fine_pubblicazione[0];
		if (count($inizio_fine_pubblicazione)>1)
			$this->data_fine_pubblicazione=$inizio_fine_pubblicazione[1];
		
		$this->url=$this->parseURL($cells->item(4));
		
	}	
	
	/**
	 * Parse a content placed on two lines in two different contents.
	 * 
	 * @param $fullContent the content as string, placed on two lines
	 * 
	 * @return an array of one or two contents with no trailing spaces. If the second piece
	 * is not present, use the empty string instead
	 */
	private function parseTwoLinesField($fullContent){
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
	private function parseInizioFinePubblicazione($td){
		//remove all whitespaces
		$inizio_fine_pubblicazione=str_replace(' ', '', $td->textContent);
		$l=strlen($inizio_fine_pubblicazione);
		if ($l<10 || $l>20) throw new Excepition("Invalid dates $inizio_fine_pubblicazione");
		
		//just the start date
		if ($l==10)
			return array($this->parseDate($inizio_fine_pubblicazione));
		
		//both start and end time
		$inizio_pubblicazione=substr($inizio_fine_pubblicazione,0,10);
		$fine_pubblicazione=substr($inizio_fine_pubblicazione,10);
		return array($this->parseDate($inizio_pubblicazione), 
				$this->parseDate($fine_pubblicazione));		
	}

	/**
	 * Get the url from the corresponding table cell.
	 * 
	 * @return the url as string if any
	 */
	private function parseURL($td){
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
	private function parseDate($dateStr){
		$d=DateTime::createFromFormat(DATE_FORMAT,
				trim($dateStr));
		if ($d==FALSE) throw new Exception("Unable to parse date - $dateStr -");
		$d->setTime(0,0);
		return $d;
	}	
}
?>