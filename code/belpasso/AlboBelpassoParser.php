<?php 
/**
 * These classes are intended for parsing of the web page of the Belpasso Albo Pretorio.
 * In this preliminar version just the first page of the Albo is parsed.
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
define('ALBO_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio');
//define('ALBO_UNICT_URL','sample.html');
define('DATE_FORMAT','d/m/Y');
define('CONTENT_SEPARATOR','/');

/**
 * A single notice.
 */
class AlboBelpassoEntry{
	var $anno_registro;
	var $numero_registro;
	var $tipo_atto;
	var $sottotipo_atto;
	var $oggetto;
	var $data_inizio_pubblicazione;
	var $data_fine_pubblicazione;
	var $url;

	/**
	 * Create an entry by parsing a table row.
	 * 
	 * @param $row a table row element
	 */
	public function __construct($row) {
		$cells=$row->getElementsByTagName('td');
		
		$anno_numero_registro=$this->parseTwoLinesField($cells->item(0)->textContent);
		$this->anno_registro=$anno_numero_registro[0];
		$this->numero_registro=$anno_numero_registro[1];
		
		$tipo_sottotipo=$this->parseTwoLinesField($cells->item(1)->textContent);
		$this->tipo_atto=$tipo_sottotipo[0];
		$this->sottotipo_atto=$tipo_sottotipo[1];
		
		$this->oggetto=$cells->item(2)->textContent;
		
		$inizio_fine_pubblicazione=$this->parseInizioFinePubblicazione($cells->item(3));
		$this->data_inizio_pubblicazione=$inizio_fine_pubblicazione[0];
		if (count($inizio_fine_pubblicazione)>1)
			$this->data_fine_pubblicazione=$inizio_fine_pubblicazione[1];
		
		$this->url=$this->parseURL($cells->item(4));
		
	}	
	
	/**
	 * Parse a content placed on two lines in two different contents.
	 * 
	 * @param $fullContent the content as string, placed on two lines
	 * 
	 * @return an array of one or two contents with no trailing spaces. If the second piece
	 * is not present, use the empty string instead
	 */
	private function parseTwoLinesField($fullContent){
		$contentPieces=explode(CONTENT_SEPARATOR, $fullContent);		
		if (count($contentPieces>1)) return array(trim($contentPieces[0]), trim($contentPieces[1]));
		return array(trim($contentPieces[0]),'');
	}

	/**
	 * Parse the content of the periodo pubblicazione field.
	 *
	 * @param $td the table cell
	 *
	 * @return an array of one or two dates with no trailing spaces. 
	 */
	private function parseInizioFinePubblicazione($td){
		//remove all whitespaces
		$inizio_fine_pubblicazione=str_replace(' ', '', $td->textContent);
		$l=strlen($inizio_fine_pubblicazione);
		if ($l<10 || $l>20) throw new Excepition("Invalid dates $inizio_fine_pubblicazione");
		
		//just the start date
		if ($l==10)
			return array($this->parseDate($inizio_fine_pubblicazione));
		
		//both start and end time
		$inizio_pubblicazione=substr($inizio_fine_pubblicazione,0,10);
		$fine_pubblicazione=substr($inizio_fine_pubblicazione,10);
		return array($this->parseDate($fine_pubblicazione), 
				$this->parseDate($fine_pubblicazione));		
	}

	/**
	 * Get the url from the corresponding table cell.
	 * 
	 * @return the url as string if any
	 */
	private function parseURL($td){
		$anchors=$td->getElementsByTagName('a');
		foreach($anchors as $a)
			if (strcmp($a->getAttribute('title'), 'Apri Dettaglio')==0)
				return $a->getAttribute('href');
		throw new Exception("No URL found.");		
	}
	
	/**
	 *
	 * @param string $dateStr
	 */
	private function parseDate($dateStr){
		$d=DateTime::createFromFormat(DATE_FORMAT,
				trim($dateStr));
		if ($d==FALSE) throw new Exception("Unable to parse date - $dateStr -");
		$d->setTime(0,0);
		return $d;
	}	
}

/**
 * A representation of the Albo downloaded at a given instant in time.
 * 
 * @author Cristiano Longo
 *
 */
class AlboBelpassoParser implements Iterator{
	private $rows;
	private $index;
	
	/**
	 * Parse the entries of the Albo from the rows of the table in the Albo Pretorio page.
	 * 
	 * Private constructor. Use factory methods.
	 */
	private function __construct($page) {
		$table=AlboBelpassoParser::getTableElement($page);
		$this->rows=$table->getElementsByTagName('tr');
		$this->index=1;
	}

	/**
	 * Factory Method. Get the entries from the albo pretorio web page.
	 */
	public static function createFromWebPage(){
		$page = new DOMDocument();
		$page->loadHTMLfile(ALBO_URL);
		return new AlboBelpassoParser($page);
	}
	
	/**
	 * Extract the table element from the web page.
	 * 
	 * @param $page the the albo web page as DOMDocument instance.
	 */
	private static function getTableElement($page){
		$tables = $page->getElementsByTagName('table');
		if ($tables->length<1) throw new Exception("No table element found.");
		if ($tables->length>1) throw new Exception("Multiple table elements.");
		return $tables->item(0);
	}

	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		return new AlboBelpassoEntry($this->rows->item($this->index));
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
}

$albo=AlboBelpassoParser::createFromWebPage();
foreach($albo as $e){
	echo "Anno ".$e->anno_registro." numero ".$e->numero_registro.
		"tipo ".$e->tipo_atto." sottotipo ".$e->sottotipo_atto." oggetto ".$e->oggetto.
		" inizio ".$e->data_inizio_pubblicazione->format(DATE_FORMAT)." fine ".$e->data_fine_pubblicazione->format(DATE_FORMAT)." URL ".$e->url."\n";
}

?>