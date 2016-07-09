<?php
/**
 * Facotry methods to get entries of the Albo of the University of Torino
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
require ('../phpparsing/AlboTableParser.php');
require ('AlboUnitoRowParser.php');

https: // www.serviziweb.unito.it/albo_ateneo/?area=Albo&action=Read&go=Cerca&advsearch%5Bnum_rep%5D=1715&advsearch%5Byear%5D=2016
class AlboUnitoParserFactory implements AlboParserFactory {
	public static $alboPageUri = 'https://www.serviziweb.unito.it/albo_ateneo/';
	
	/**
	 * The landing page of the Official Albo
	 */
	function getAlboPretorioLandingPage() {
		return AlboUnitoParserFactory::$alboPageUri;
	}
	
	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnitoParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() {
		return $this->readPage ( AlboUnitoParserFactory::$alboPageUri );
	}
	
	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		$uriWithSearchParameters = AlboUnitoParserFactory::$alboPageUri."?area=Albo&action=Read&go=Cerca&advsearch%5Bnum_rep%5D=$number&advsearch%5Byear%5D=$year";
		return $this->readPage ( $uriWithSearchParameters );
	}
	
	/**
	 * Read all the entries from the page with the specified uri.
	 *
	 * @return the AlboUnitoParser instance obtained by parsing the specified page.
	 */
	private function readPage($uri) {
		$htmlPage = new DOMDocument ();
		$rowParser = new AlboUnitoRowParser ( $uri );
		if (! $htmlPage->loadHTMLfile ( $uri ))
			throw new Exception ( "Unable to download page $uri" );
		return new AlboTableParser ( $htmlPage, $rowParser );
	}
}
?>