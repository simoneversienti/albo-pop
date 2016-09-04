<?php
/**
 * Factory methods to get entries of the Albo of the Municipality of Belpasso
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
require ('../phpparsing/AlboParserFactory.php');
require ('AlbojCityGovParser.php');

class AlbojCityGovParserFactory implements AlboParserFactory {
	private $alboPageUri;
	private $selectionFormUri;
	
	/**
	 * 
	 * @param unknown $alboPageUri TODO write me
	 * @param unknown $selectionFormUri TODO write me
	 */
	public function __construct($alboPageUri, $selectionFormUri){
		$this->alboPageUri=$alboPageUri;
		$this->selectionFormUri=$selectionFormUri;
	}
	
	/**
	 * The landing page of the Official Albo
	 */
	public function getAlboPretorioLandingPage() {
		return $this->alboPageUri;
	}
	
	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnictParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() {
		$page = new DOMDocument();
		$page->loadHTMLfile($this->alboPageUri);
		return new AlbojCityGovParser($page);
	}
	
	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		$page = new DOMDocument();
		$page->loadHTMLfile($this->selectionFormUri."&numeroRegistrazioneDa=$number&annoRegistrazioneDa=$year");
		return new AlbojCityGovParser($page);
	}
}
?>