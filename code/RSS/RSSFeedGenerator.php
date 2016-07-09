<?php 
/**
 * An utility to produce rss feeds.
 * 
 * @author Cristiano Longo
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
		$rssEl->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:creativeCommons','http://backend.userland.com/creativeCommonsRssModule');
		
		$this->doc->appendChild($rssEl);

		$this->channelEl=$this->doc->createElement('channel');
		$rssEl->appendChild($this->channelEl);

		$licenseEl=$this->doc->createElementNS('http://backend.userland.com/creativeCommonsRssModule','creativeCommons:license','http://creativecommons.org/licenses/by/4.0/');
		$this->channelEl->appendChild($licenseEl);
				
		$this->channelEl->appendChild($this->createEscapedElement('title', $title));

		if (isset($description))
			$this->channelEl->appendChild(
					$this->createEscapedElement('description', $description));

		if (isset($homepage)){
			$this->channelEl->appendChild(
					$this->createEscapedElement('link', $homepage));
		}

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

	/**
	 * Add an item with the specified parameters to the feed
	 * 
	 * @param string $title
	 * @param string $description
	 * @param DateTime $pubDate
	 * @param string $link
	 * @param string $guid
	 * 
	 * @deprecated
	 */
	public function addItem($title, $description, $pubDate, $link, $guid){
		$item=new RSSFeedItem();
		$item->title=$title;
		$item->description=$description;
		$item->pubDate=$pubDate;
		$item->link=$link;
		$item->guid=$guid;
		$this->addItemObject($item);
	}
	
	/**
	 * Add an item with the specified parameters to the feed
	 *
	 * @param RSSFeedItem item
	 */
	public function addItemObject($item){
		$itemEl=$this->doc->createElement("item");
		$this->channelEl->appendChild($itemEl);
		$itemEl->appendChild($this->createEscapedElement('title', $item->title));
		if (isset($item->description))
			$itemEl->appendChild($this->createEscapedElement('description', $item->description));
			if (isset($item->link))
				$itemEl->appendChild($this->createEscapedElement('link', $item->link));
				if (isset($item->pubDate))
// 					$itemEl->appendChild($this->createEscapedElement('pubDate',
// 							$item->pubDate->format(DateTime::RFC822)));
					$itemEl->appendChild($this->createEscapedElement('pubDate',
							$item->pubDate->format(DateTime::RSS)));
					$guidEl=$this->createEscapedElement('guid', $item->guid);
					$guidEl->setAttribute('isPermaLink', 'true');
					$itemEl->appendChild($guidEl);
	}
	
	/**
	 * Add a comment as next child of the channel element.
	 * 
	 * @param unknown_type $comment
	 */
	public function addComment($comment){
		$commentEl=$this->doc->createComment($comment);
		$this->channelEl->appendChild($commentEl);
		
	}
	
	/**
	 * Create a DOM element with the specified tag name and a text child with the specified content.
	 * The text child is xml-escaped.
	 */
	private function createEscapedElement($elementName, $textContent){
		$el=$this->doc->createElement($elementName);
		$el->appendChild($this->doc->createTextNode($textContent));
		return $el;
	}
}
?>