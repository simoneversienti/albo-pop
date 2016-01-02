<?php 
/**
 * This script turn the html page of the official 'Albo' of the University
 * of Catania into a rss feed.
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

define('ALBO_UNICT_URL','http://ws1.unict.it/albo/');
//define('ALBO_UNICT_URL','sample.html');
define('DATE_FORMAT','d/m/Y');

//PARSING FUNCTIONS

/**
 * Get the element corresponding to the table in the Albo Unict document.
 * It is the solely child of the div with id='boge'
 *
 * @param doc the DOMDocument instance corresponding to Albo Unict, as retrieved
 * by the web site
 * @return the table elemetnt of interest
 * @exception if the document structure is not as expected
 */
function retrieveTable($doc){
	$div = $doc->getElementById("boge");
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
 * Visit all the rows of the table of Albo items.
 *
 * @param DOMElement $table
 * @param function $rowHandler a function with arguments the fields of the Albo items
 * (see the items in the web page)
 */
function visitTable($table, $rowHandler){
	$rows=$table->getElementsByTagName("tr");
	//NOTE: ignore the first line
	for ($i=1;$i<$rows->length; $i++)
		try{
		visitRow($rows->item($i), $rowHandler);
	}catch(Exception $e){
		echo "Error processing row $i: ".$e->getMessage()+"\n";
	}
}

/**
 * Process a single row in the table of Albo items.
 *
 * @param DOMElement $table
 * @param function $rowHandler a function with arguments the fields of the Albo items
 * (see the items in the web page)
 */
function visitRow($row, $rowHandler){
	$cells=$row->getElementsByTagName("td");
	if ($cells->length<6)
		throw new Exception("Invalid number of cells in row");
	$numero=getElementPlainContent($cells->item(0));
	$data_registrazione=getElementDateContent($cells->item(1));
	$richiedente=getElementPlainContent($cells->item(2));

	//description and link are in the same cell, the description is enclosed in
	//a span tag whereas the link is the first item of a ul list.
	$oggettoElem=$cells->item(3);
	$description=retrieveDescription($oggettoElem);
	$link=retrieveLink($oggettoElem);
	
	$inizio_pubblicazione=getElementDateContent($cells->item(4));
	$fine_pubblicazione=getElementDateContent($cells->item(5));
	
	$rowHandler($numero, $data_registrazione, $richiedente, $description, $link, 
			$inizio_pubblicazione, $fine_pubblicazione);	
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
	return date_parse_from_format(getElementPlainContent($td), DATE_FORMAT);
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
		return getElementPlainContent($oggettoSpanChildren->item(0));
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

function rowBasicHandler($numero,
		$data_registrazione,
		$richiedente,
		$description, 
		$link, 
		$inizio_pubblicazione, 
		$fine_pubblicazione){
	echo "$numero \t $link \n";
}

/**
 * To generate an RSS feed.
 * 
 * @author Cristiano Longo
 *
 */
class RSSFeedGenerator{

	private $doc;
	private $channelEl;
	
	/**
	 * @param string $title the feed title
	 * @param string $description optional, a feed description
	 * @param string $homepage optional, an home page describing the feed
	 * @param string $url the url where the feed is published
	 * @return number
	 */
	public function __construct($title, $description, $homepage, $url) {		
		$this->doc=new DOMDocument('1.0', 'UTF-8');
		$this->doc->formatOutput = true;
		
		$rssEl=$this->doc->createElement('rss');
		$rssEl->setAttribute('version', '2.0');
		$rssEl->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:atom','http://www.w3.org/2005/Atom');
		
		$this->doc->appendChild($rssEl);
		
		$this->channelEl=$this->doc->createElement('channel');
		$rssEl->appendChild($this->channelEl);
		
		$this->channelEl->appendChild($this->doc->createElement('title', $title));
		
		if (isset($description))
			$this->channelEl->appendChild(
					$this->doc->createElement('description', $description));
		
		if (isset($homepage))
			$this->channelEl->appendChild(
					$this->doc->createElement('link', $homepage));
		
		if (isset($url)){
			$urlEl=$this->doc->createElementNS('http://www.w3.org/2005/Atom','atom:link');
			$urlEl->setAttribute('href', $url);
			$urlEl->setAttribute('rel','self');
			$urlEl->setAttribute('type','application/rss+xml');
			$this->channelEl->appendChild($urlEl);
		}
	}
	
	/**
	 * Get the feed as string
	 */
	public function getFeed(){
		return $this->doc->saveXML();
	}
}
$src = new DOMDocument();
$src->loadHTMLfile(ALBO_UNICT_URL);
$table=retrieveTable($src);


//visitTable($table, 'rowBasicHandler');
$feed=new RSSFeedGenerator("titolo", "descrizione", "Home", "url");
echo $feed->getFeed();
?>