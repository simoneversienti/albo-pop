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
require('AlboUnictItem.php');

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
	 *  @param strin $uri
	 */
	public function __construct($uri) {
		$src = new DOMDocument();
		$src->loadHTMLfile($uri);
		$table=$this->retrieveTable($src);
		$this->rows=$table->getElementsByTagName("tr");
	}

	/**
	 * Get the item with the specified number, if any. Null otherwise.
	 */
	public function getByNumber($number){
		if ($this->rows->length<2)
			return null;
		for($j=1; $j<$this->rows->length; $j++){
			$entry=new AlboUnictItem($this->rows->item($j));
			if (!strcmp($number, $entry->numero))
				return $entry;
		}
		return null;
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
		return new AlboUnictItem($this->rows->item($this->i));
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