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

define('DATE_FORMAT','d/m/Y');

/**
 * Convenience class to store albo entries
 * 
 * @author Cristiano Longo
 */
class AlbojCityGovEntry{
	public $proponente;
	public $proponente_descrizione;
	public $oggetto;
	public $anno;
	public $numero;
	public $anno_registrazione;
	public $numero_registrazione;
	public $anno_protocollo;
	public $numero_protocollo;
	public $classifica_descrizione;
	public $dirigente_descrizione;
	public $assessore_descrizione;
	public $data_esecutivita;
	public $mittente;
	public $numero_allegati;
	public $data_documento;
	public $data_atto;
	public $spesa_prevista;
	public $titolo_categoria;
	public $titolo_sottocategoria;
	public $contenuto;
	public $estremi_dei_principali_documenti;
	public $data_inizio_pubblicazione;
	public $data_fine_pubblicazione;
	
	public $is_well_formed;
	
	/**
	 * Create an entry from a csv row
	 */
	public function __construct($rowStr){
		$row=str_getcsv($rowStr);
		$size=count($row);
		
		if ($size<24){
			$this->is_well_formed=false;
			return;
		} 
				
		$this->proponente=$row[0];
		$this->proponente_descrizione=$row[1];
		$this->oggetto=$row[2];
		$this->anno=$row[3];
		$this->numero=$row[4];
		$this->anno_registrazione=$row[5];
		$this->numero_registrazione=$row[6];
		$this->anno_protocollo=$row[7];
		$this->numero_protocollo=$row[8];
		$this->classifica_descrizione=$row[9];
		$this->dirigente_descrizione=$row[10];
		$this->assessore_descrizione=$row[11];
		$this->data_esecutivita=AlbojCityGovEntry::getDate($row[12]);
		$this->mittente=$row[13];
		$this->numero_allegati=$row[14];
		$this->data_documento=AlbojCityGovEntry::getDate($row[15]);
		$this->data_atto=AlbojCityGovEntry::getDate($row[16]);
		$this->spesa_prevista=$row[17];
		$this->titolo_categoria=$row[18];
		$this->titolo_sottocategoria=$row[19];
		$this->contenuto=$row[20];
		$this->estremi_dei_principali_documenti=$row[21];
		$this->data_inizio_pubblicazione=AlbojCityGovEntry::getDate($row[22]);
		$this->data_fine_pubblicazione=AlbojCityGovEntry::getDate($row[23]);
		$this->is_well_formed=$this->data_inizio_pubblicazione!=null && $this->data_fine_pubblicazione!=null;		
	}	
	

	/**
	 * Get a date from a string, if the string is not null and not empty.
	 * Otherwise, return null.
	 */
	private static function getDate($dateStr){
		if ($dateStr==null || strlen($dateStr)==0)
			return null;
		return DateTime::createFromFormat(DATE_FORMAT, $dateStr, new DateTimeZone('Europe/Rome'));
	}
}

/**
 * Download and parse a jCityGov albo provided as csv.
 */
class  AlbojCityGovParser implements Iterator{
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
//		$handle=fopen("feed.csv", "r");
//		$this->rows=fgetcsv($handle,"\n");
		$this->index=1;
	}	
	
	//Iterator functions,  see http://php.net/manual/en/class.iterator.php
	
	public function current(){
		return new AlbojCityGovEntry($this->rows[$this->index]);
	}
	
	
	public function key (){
		return $this->index;
	}
	
	public function next(){
		++$this->index;
	}
	
	public function rewind(){
		$this->index=1;
	}
	
	public function valid(){
		$numItems=count($this->rows)-1;
		return $numItems>1 && $this->index<($numItems+1);
	}
}