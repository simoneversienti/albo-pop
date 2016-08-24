<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Luigi Rizzo
 */

//the following url is url-encoded
define('ALBO_URL','http://albopretorio.datamanagement.it/?ente=SantAgataLiBattiati&tipoSubmit=ricerca');


//number of months before today from which retrieve the notices
define("NMONTHS","1");

/**
 * Convenience class to represent single entry.
 */
class AlboEntry {
	public $anno;
	public $numero;
	public $link;
	public $mittente_descrizione;

	/**
	 * Create an entry by parsing a table row.
	 */
	public function __construct($row) {

		$cells=$row->getElementsByTagName("td");

		if ($cells->length > 0) {
			$this->numero = $cells->item(0)->nodeValue;

			$this->anno = $cells->item(1)->nodeValue;

			$this->mittente_descrizione = html_entity_decode(utf8_decode($cells->item(7)->nodeValue));

			$tmp = $cells->item(12)->getElementsByTagName("a")->item(0)->getAttribute("href");
			$guid = substr($tmp, 29, 36);
			//<a id="dettagli0" class="link0" href="javascript:recuperaDettagli('006fad15-9f02-4fda-9345-1a76cd15e25a', 0);">Visualizza atto</a>

			//http://albopretorio.datamanagement.it/ajax.jsp?Richiesta=Allegati&idAtto=006fad15-9f02-4fda-9345-1a76cd15e25a

			$url = "http://albopretorio.datamanagement.it/ajax.jsp?Richiesta=Allegati&idAtto=".$guid;

			$ch=curl_init($url);
			if (!$ch) throw new Exception("Unable to initialize cURL session");
			
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// eseguo la chiamata, salvo il risultato in una variabile
			$pageDetails = curl_exec($ch);
			
			// chiudo cURL
			curl_close($ch);
			$dom=new DOMDocument();
			$dom->loadHTML($pageDetails);
			$domNodeList = $dom->getElementsByTagName("a");
			$href = "";
			if ($domNodeList->length > 0) {
				$href = $domNodeList->item(0)->getAttribute("href");
			}
			$this->link = "http://albopretorio.datamanagement.it/" . $href;
		}

	}
}
/**
 * Get and parse the entries of a single year of the Albo Pretorio.
 */
class AlboParser implements Iterator{

	private $rows;
	private $items;
	private $i=1;
	
	/**
	 * Retrieve the entries in the notice board results page.
	 *
	 * @param $from_date a DateTime object
	 * @param $to_date a DataTime object
	 */
	public function __construct($page) {
		$this->rows=$this->getRows($page);
	}

	/**
	 * Retrieve the albo pages with all the notices of the current year
	 */
	public static function createByYear(){
		$currentYear=(new DateTimeImmutable())->format('Y');
		
		$h=curl_init(ALBO_URL);
		if (!$h) throw new Exception("Unable to initialize cURL session");
		curl_setopt($h, CURLOPT_POST, TRUE);
		curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($h, CURLOPT_POSTFIELDS, array("anno"=>$currentYear));
	
		//curl_setopt($h, CURLOPT_HTTPHEADER, array("Accept-Charset: utf-8"));
		$page=curl_exec($h);
		if( $page==FALSE)
			throw new Exception("Unable to execute POST request: "+curl_error());
		curl_close($h);
		return new AlboParser($page);
	}
	
	/**
	 * Extract the rows of the table containing the Albo Entries from a result page.
	 *
	 * @param string $page
	 */
	private function getRows($page){
		$d=new DOMDocument();
 		$d->loadHTML($page);

		$tables= $d->getElementsByTagName('form')->item(0)->getElementsByTagName('table');

		if ($tables->length==0)
			throw new Exception("No table element found");
		if ($tables->length>1)
			throw new Exception("Multiple table elements found");
		$rows=$tables->item(0)->getElementsByTagName("tr");

		return $rows;
	}
	
	//helper function
	/**
	 * Get the (first) item if any, null otherwise.
	 */
	public function getFirst($repertorio){
		if ($this->rows->length<2) 
			return null;
		return new AlboEntry($this->rows->item(0));
		for($j=1; $j<$this->rows->length; $j++){
			$entry=new AlboEntry($this->rows->item($j));
			if (!strcmp($repertorio, $entry->repertorio))
				return $entry;
		}
		return null;
	}
	
	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		if ($this->rows->length<2)
			return null;
		return new AlboEntry($this->rows->item($this->i));
	}
	
	
	public function key (){
		return $this->i;
	}
	
	public function next(){
		if ($this->i<$this->rows->length)
			++$this->i;
	}
	
	public function rewind(){
		if ($this->rows->length>2)
			$this->i=2;
	}
	
	public function valid(){
		return $this->rows->length>2 && $this->i<$this->rows->length;
	}
}
?>