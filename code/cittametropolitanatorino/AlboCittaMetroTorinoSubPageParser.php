<?php 
/**
 * The notice board of the Città Metropolitana di Torino is divided into sub-pages,
 * one per notice type. This parser retrieve the information from such a sub-page.
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
require('AlboCittaMetroTorinoEntry.php');

class AlboCittaMetroTorinoSubPageParser implements Iterator
{
	private $uri;
	private $category;	
	private $linkUriPrefix;
	private $rows;
	private $index;
	
	
	/**
	 * Parse the entries of the Albo from the rows of the table in the Albo Pretorio page.
	 *
	 * @param $uri of the sub page to be parsed
	 * @param $category category of the retrieved notices
	 * @param $linkUriPrefix prefix for links
	 */
	public function __construct($uri, $category, $linkUriPrefix) {
		$this->uri=$uri;
		$this->linkUriPrefix=$linkUriPrefix;
		$page = new DOMDocument();
		if (!$page->loadHTMLfile($uri))
			throw new Exception("Unable to download page $uri");
		$this->category=$category;
		$tables=$page->getElementsByTagName("table");
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
			
	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		return $this->parseRow($this->rows->item($this->index));
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
	
	/**
	 * Parse a table row
	 * 
	 * @param unknown $row
	 */
	private function parseRow($row){
		$entry=new AlboCittaMetroTorinoEntry();
		$entry->subPageURI=$this->uri;
		$tds=$row->getElementsByTagName('td');
		$this->parseNumeriAllegatiCell($tds->item(0), $entry);
		$this->parsePubblicazioneCell($tds->item(1), $entry);
		$this->parseOggettoCell($tds->item(2), $entry);
		$entry->category=$this->category;
		return $entry;
	}
	
	/**
	 * Get the firts child of an element which is an element itself
	 * 
	 * @param DOMElement $el
	 */
	private function getFirstChildElement($el){
		foreach($el->childNodes as $c)
			if ($c->nodeType==XML_ELEMENT_NODE)
				return $c;
		throw new Exception("No child element found.");
	}
	
	/**
	 * Parse the cell Numeri e Allegati
	 * 
	 * @param DOMElement $cell the table row cell
	 * #param AlboCittaMetroTorinoEntry $entry the entry which will receive the parsed content
	 */
	private function parseNumeriAllegatiCell($cell, $entry){
		//we assume that the solely child of the cell is an anchor node
		$aElements=$cell->getElementsByTagName('a');
		if ($aElements->length<1){
			$entry->parseErrors.="Link not found.";
			return;
		}
		$aElement=$aElements->item(0);		
		$entry->link=$this->getAbsoluteURI($aElement->getAttribute('href'));
		
		//and that it has a single child as well which contains the code as child text node
		$numeriAllegati=$aElement->textContent;
		$numeriAllegatiPieces=explode('/',$numeriAllegati);
		$entry->number=trim($numeriAllegatiPieces[0]);
		$entry->year=trim($numeriAllegatiPieces[1]);
	} 
	
	/**
	 * Complete the URI given as argument to an absolute one if
	 * the uri is relative.
	 */
	private function getAbsoluteURI($uri){
		return(preg_match('/^http:/', $uri)) ? $uri : $this->linkUriPrefix.$uri;
		
	}

	/**
	 * Parse the cell Numeri e Allegati
	 * 
	 * @param DOMElement $cell the table row cell
	 * @param AlboCittaMetroTorinoEntry $entry the entry which will receive the parsed content
	 */

	private function parsePubblicazioneCell($cell, $entry)
	{
		$datePattern='/\d+\/\d+\/\d{4}/';


		for($i=0; $i<$cell->childNodes->length; $i++)
		{
			$n=$cell->childNodes->item($i);
			if ($n->nodeType==XML_TEXT_NODE && 
					preg_match($datePattern, $n->textContent))
			{
				preg_match_all($datePattern, $n->textContent, $matches);
				$entry->startDate=DateTime::createFromFormat('d/m/Y',$matches[0][0]);
				//$entry->startDate->setTime(0,0);
				$entry->endDate=DateTime::createFromFormat('d/m/Y',$matches[0][1]);
				//$entry->endDate->setTime(0,0);
			}
		}
	
		if ($entry->startDate==null)
			$entry->parseErrors.="No start date specified.";		
		if ($entry->endDate==null)
			$entry->parseErrors.="No end date specified.";		
	}
	
	
	/**
	 * Parse the cell oggetto
	 *
	 * @param DOMElement $cell the table row cell
	 * @param AlboCittaMetroTorinoEntry $entry the entry which will receive the parsed content
	 */
	private function parseOggettoCell($cell, $entry)
	{
		$entry->subject = $cell->textContent;
	}
			
}
?>
