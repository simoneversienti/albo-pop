<?php 
/**
 * This class is a parser for the bullettin board of the University of Catania.
 * Every time it is instanced, the current and actual bullettin board is downloaded
 * and parsed. This happens at the construction phase. The class implements the
 * Iterator interface, so that all the bullettin entries can be visited sequentially.
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
 *
 * @author Cristiano Longo
 */

define('ALBO_UNICT_URL','http://ws1.unict.it/albo/');
//define('ALBO_UNICT_URL','sample.html');
define('DATE_FORMAT','d/m/Y');

/**
 * Convenience class to represent single AlboUnict entries.
 *
 * @author Cristiano Longo
 *
 */
class AlboUnictEntry{
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

/**
 * Parse and visit a AlboUnict snapshot
 * 
 * @author Cristiano Longo
 *
 */
class AlboUnictParser implements Iterator{

	private $rows;
	private $i=1;

	/**
	 *  Open the AlboUnict web page. Retrieve the entries insied it.
	 */
	public function __construct() {
		$src = new DOMDocument();
		$src->loadHTMLfile(ALBO_UNICT_URL);
		$table=$this->retrieveTable($src);
		$this->rows=$table->getElementsByTagName("tr");
	}

	/**
	 * Get the element corresponding to the table in the Albo Unict document.
	 * It is the solely child of the div with id='boge'
	 *
	 * @param doc the DOMDocument instance corresponding to Albo Unict, as retrieved
	 * by the web site
	 * @return the table elemetnt of interest
	 * @exception if the document structure is not as expected
	 */
	private function retrieveTable($doc){
		$div = $this->getElementById($doc->documentElement, "boge");
		if ($div==null)
			throw new Exception("No such element with id boge");
		$children = $div->getElementsByTagName("table");
		if ($children->length==0)
			throw new Exception("No table element.");
		if ($children->length>1)
			throw new Exception("Multiple table elements.");
		return $children->item(0);
	}

	/**
	 * Just a workaround, see https://github.com/aborruso/albo-pop/issues/102
	 * Get an element with the specified value for the id attribute in the subtree 
	 * rooted at the specified element
	 * 
	 */
	private function getElementById($root,$id){
		$idValue=$root->getAttribute("id");
		if (isset($idValue) && !strcmp($id, $idValue))
			return $root;
		for($i=0; $i<$root->childNodes->length; $i++){
			$child=$root->childNodes->item($i);
			if ($child->nodeType==XML_ELEMENT_NODE)
			$r=$this->getElementById($child, $id);
			if (isset($r))
				return $r;
		}
		return null;
	}
	
	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		if ($this->rows->length<2)
			return null;
		return new AlboUnictEntry($this->rows->item($this->i));
	}
	
	
	public function key (){
		return $this->i;
	}
	
	public function next(){
		if ($this->i<$this->rows->length)
			++$this->i;
	}
	
	public function rewind(){
		if ($this->rows->length>1)
			$this->i=1;		
	}
	
	public function valid(){
		return $this->rows->length>1 && $this->i<$this->rows->length;
	}	
}

?>