<?php
/**
 * This class allows one to get and parse the entries of every jCityGov Albo Pretorio
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

/**
 * Convenience class to store albo entries
 * 
 * @author Cristiano Longo
 */
class AlbojCityGovEntry{
	
}

/**
 * Download and parse a jCityGov albo provided as csv.
 */
class  AlbojCityGovParser{
	private $rows;
	private $index;
	
	/**
	 * Download a notice board provided as csv
	 *
	 * @param alboURI the url where it is possible to get the albo as csv
	 * @throws Exception
	 */
	public function __construct($alboURI){
		$h=curl_init($alboURI);
		if (!$h) throw new Exception("Unable to initialize cURL session");
		curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
		$retrievedData=curl_exec($h);
		if($retrievedData==FALSE)
			throw new Exception("Unable to execute request: ".curl_error($h));
		curl_close($h);
		$this->rows=str_getcsv($retrievedData,"\n");
		$this->numItems=count($this->rows)-1;
		$this->index=0;
		echo $retrievedData;
	}	
}