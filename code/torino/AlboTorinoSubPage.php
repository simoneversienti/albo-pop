<?php 
/**
 * Represents a subpage of the Torino's Albo
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
require('AlboTorinoSubPageParser.php');

class AlboTorinoSubPage{
	private $uri;
	private $title;
	
	public function __construct($uri, $title){
		$this->uri=$uri;
		$this->title=$title;
	}
	
	/**
	 * Retrieve the web page from the internet
	 * 
	 * @return an Iterator of AlboTorinoEntry instances representing the entries of the page.
	 * @throws Exception if retrieving fails for some reason
	 */
	public function retrieve(){
		$page = new DOMDocument();
		if (!$page->loadHTMLfile($this->uri))
			throw new Exception("Unable to download page $this->uri");
		return new AlboTorinoSubPageParser($page);
	}
	
	/**
	 * Return the title of the subpage. It represents a sort of category.
	 */
	public function getTitle(){
		return $this->title;
	}
} 
?>