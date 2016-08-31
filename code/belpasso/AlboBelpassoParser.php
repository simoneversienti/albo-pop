<?php 
/**
 * These classes are intended for parsing of the web page of the Belpasso Albo Pretorio.
 * In this preliminar version just the first page of the Albo is parsed.
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
require('AlboBelpassoEntry.php');

class AlboBelpassoParser implements Iterator{
	private $rows;
	private $index;
	
	/**
	 * Parse the entries of the Albo from the rows of the table in the Albo Pretorio page.
	 */
	public function __construct($page) {
		$table=AlboBelpassoParser::getTableElement($page);
		$this->rows=$table->getElementsByTagName('tr');
		$this->index=1;
	}
	
	/**
	 * Extract the table element from the web page.
	 * 
	 * @param $page the the albo web page as DOMDocument instance.
	 */
	private static function getTableElement($page){
		$tables = $page->getElementsByTagName('table');
		if ($tables->length<1) throw new Exception("No table element found.");
		if ($tables->length>1) throw new Exception("Multiple table elements.");
		return $tables->item(0);
	}

	private static function getTableElement2($page){
		$table=AlboBelpassoParser::getElementByTagName($page->documentElement, 'table');
		if ($table==null) throw new Exception("No table element found.");
		return $table;
	}	
	
	/**
	 * Get the first element with the specified tag name in the
	 * subtree with the specified root.
	 */
	private static function getElementByTagName($root, $tagname){
		if (strcmp($tagname, $root->tagName)==0) return $root;
		for($i=0; $i<$root->childNodes->length; $i++){
			$child=$root->childNodes->item($i);
			if ($child->nodeType==XML_ELEMENT_NODE)
			$r=AlboBelpassoParser::getElementByTagName($child, $tagname);
			if (isset($r))
				return $r;
		}
		return null;
    }	

	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		return new AlboBelpassoEntry($this->rows->item($this->index));
	}
	
	
	public function key (){
		return $this->index-1;
	}
	
	public function next(){
			++$this->index;
	}
	
	public function rewind(){
			$this->index=1;
	}
	
	public function valid(){
		return $this->rows->length>1 && $this->index<$this->rows->length;
	}
}

?>