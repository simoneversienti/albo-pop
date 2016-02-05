<?php
/**
 * This class allows one to get and parse the entries of a specified year in the Albo 
 * Pretorio of the municipality of Catania.
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

//the following url is url-encoded
define('ALBO_CT_URL','http://www.comune.catania.gov.it/EtnaInWeb/AlboPretorio.nsf/Web%20Ricerca?OpenForm&AutoFramed');

/**
 * Get and parse the entries of a single year of the Albo Pretorio of the municipality
 * of Catania.
 *
 * @author Cristiano Longo
 *
 */
class AlboComuneCTParser{

	private $rows;

	/**
	 *  Retrieve the entries relatives to a year.
	 */
	public function __construct($year) {
		$page=$this->getPage($year);
		$this->rows=$this->getRows($page);
		echo $this->rows->length;
// 		$src = new DOMDocument();
// 		$src->loadHTMLfile(ALBO_UNICT_URL);
// 		$table=$this->retrieveTable($src);
// 		$this->rows=$table->getElementsByTagName("tr");
	}
	
	/**
	 * Retrieve the page by performing a post request.
	 * 
	 * @param int $year
	 * @return string the retrieved web page
	 */
	private function getPage($year){
		$h=curl_init(ALBO_CT_URL);
		if (!$h) throw new Exception("Unable to initialize cURL session");
		curl_setopt($h, CURLOPT_POST, TRUE);
		curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($h, CURLOPT_POSTFIELDS, array("__Click" => 0, "Anno"=>$year));
		$page=curl_exec($h);
		if( $page==FALSE)
			throw new Exception("Unable to execute POST request: "+curl_error());
		curl_close($h);
		return $page;
	}
	
	/**
	 * Extract the rows of the table containing the Albo Entries from a result page.
	 *
	 * @param string $page
	 */
	private function getRows($page){
		$d=new DOMDocument();
 		$d->loadHTML($page);
 		$tables=$d->getElementsByTagName("table");
 		if ($tables->length==0)
 			throw new Exception("No table element found");
 		if ($tables->length>1)
 			throw new Exception("Multiple table elements found");
		$rows=$tables->item(0)->getElementsByTagName("tr");
		return $rows; 			
	}
	
}

new AlboComuneCTParser("2015");
 ?>