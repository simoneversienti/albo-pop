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
require ('../phpparsing/AlboTableParser.php');
require ('AlboUnitoRowParser.php');
class AlboUnitoParserFactory {
	public static $alboPageUri = 'https://www.serviziweb.unito.it/albo_ateneo/';
	
	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnitoParser instance obtained by parsing the specified page.
	 */
	public static function createFromWebPage() {
		$htmlPage = new DOMDocument ();
		$uri = AlboUnitoParserFactory::$alboPageUri;
		$rowParser=new AlboUnitoRowParser();
		if (! $htmlPage->loadHTMLfile ( $uri ))
			throw new Exception ( "Unable to download page $uri" );
		return new AlboTableParser ( $htmlPage, $rowParser );
	}
}
?>