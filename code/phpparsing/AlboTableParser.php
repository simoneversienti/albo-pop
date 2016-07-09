<?php 
/**
 * Basic implementation for Albos consisting of rows in a table assuming that:
 * 
 * - there is just one table element in the web page
 * - this element contains an header
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

class AlboTableParser implements Iterator{
	private $rowParser;
	private $rows;
	private $index;
	
	
	/**
	 * Retrieve elements from the given page according to the specified row-parser.
	 * 
	 * @param DOMDocument $htmlPage
	 * @param AlboRowParser $rowParser
	 */
	public function __construct($htmlPage, $rowParser){
		$this->rowParser=$rowParser;
		$tables=$htmlPage->getElementsByTagName("table");
		if ($tables->length<1){
			$this->rows=new DOMNodeList();
			$this->index=-1;
		}
		else if ($tables->length>1)
			throw new Exception("Multiple table elements found");
		else{
			$this->rows=$tables->item(0)->getElementsByTagName('tr');
			$this->index=1;
			$count=$this->rows->length;
		}		
	}
	
	public function current(){
		return $this->rowParser->parseRow($this->rows->item($this->index));
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