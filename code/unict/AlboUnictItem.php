<?php 
/**
 * An entry in the bullettin board of the University of Catania
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

class AlboUnictItem{
	public $numero;
	public $data_registrazione; //DateTime object
	public $richiedente;
	public $description;
	public $link;
	public $inizio_pubblicazione; //DateTime object
	public $fine_pubblicazione; //DateTime object

	/**
	 * Create an entry by parsing and AlboUnict table row.
	 */
	public function __construct($row) {
		$cells=$row->getElementsByTagName("td");
		if ($cells->length<6)
			throw new Exception("Invalid number of cells in row");
			$this->numero=$this->getElementPlainContent($cells->item(0));
			$this->data_registrazione=$this->getElementDateContent($cells->item(1));
			$this->richiedente=$this->getElementPlainContent($cells->item(2));

			//description and link are in the same cell, the description is enclosed in
			//a span tag whereas the link is the first item of a ul list.
			$oggettoElem=$cells->item(3);
			$this->description=$this->retrieveDescription($oggettoElem);
			$this->link=$this->retrieveLink($oggettoElem);

			$this->inizio_pubblicazione=$this->getElementDateContent($cells->item(4));
			$this->fine_pubblicazione=$this->getElementDateContent($cells->item(5));
	}

	/**
	 * Perform some preprocessing on a table cell content in order
	 * to get it as plain text.
	 *
	 * @param DOMElement $td
	 */
	function getElementPlainContent($td){
		return 	html_entity_decode(
				str_replace("\t", '',
						str_replace("\r", '',
								str_replace("\n", ' ', strip_tags($td->nodeValue)))));
	}

	/**
	 * Perform some preprocessing on a table cell content in order
	 * to get it as date.
	 *
	 * @param DOMElement $td
	 */
	function getElementDateContent($td){
		$d=DateTime::createFromFormat(DATE_FORMAT,
				$this->getElementPlainContent($td));
		$d->setTime(0,0);
		return $d;
	}

	/**
	 * Retrieve the description from the oggetto field in a Albo row.
	 *
	 * @param DOMElement $oggettoElem
	 * @return the description as text, if any. Null if no description is provided.
	 */
	function retrieveDescription($oggettoElem){
		$oggettoSpanChildren=$oggettoElem->getElementsByTagName("span");
		if ($oggettoSpanChildren->length>1)
			throw new Exception("Unable to retrieve description: multiple span elements in the oggetto field.");
			if ($oggettoSpanChildren->length==1)
				return $this->getElementPlainContent($oggettoSpanChildren->item(0));
				return null;
	}

	/**
	 * Retrieve the link from the oggetto field in a Albo row.
	 * Just the first link is taken into account
	 *
	 * @param DOMElement $oggettoElem
	 * @return the first link, if any. Null if no link is provided.
	 */
	function retrieveLink($oggettoElem){
		$oggettoLinkChildren=$oggettoElem->getElementsByTagName("ul");
		if ($oggettoLinkChildren->length==0)
			return null;
			$liChildren=$oggettoLinkChildren->item(0)->getElementsByTagName("li");
			if ($liChildren->length==0)
				return null;
				$aChildren=$liChildren->item(0)->getElementsByTagName("a");
				if ($aChildren->length==0)
					return null;
					return ALBO_UNICT_URL.$aChildren->item(0)->getAttribute("href");
					return null;
	}
}

?>