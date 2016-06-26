<?php
/**
 * The notice board of the municipality of Turin si divided into sub-pages.
 * This parser retrieves all these and parse them.
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
require ('AlboTorinoSubPage.php');
define ( 'LINK_URI_PREFIX', 'http://www.comune.torino.it' );
class AlboTorinoParser implements Iterator {
	private $subPages;
	private $index;
	private $itIndex;
	private $currentIt = null;
	public function __construct() {
		$this->subPages = array (
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/albopretorio/alboconsiglio.shtml', 'ATTI E DOCUMENTI DEL CONSIGLIO COMUNALE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/albopretorio/albogiunta.shtml', 'ATTI DELLA GIUNTA COMUNALE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/albopretorio/albodetermine.shtml', 'DETERMINAZIONI DIRIGENZIALI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=13', 'DECRETI E PROVVEDIMENTI DEL SINDACO' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=9', 'ORDINANZE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=15', 'PROCEDIMENTI AMMINISTRATIVI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=7', 'DETERMINAZIONI DIRIGENZIALI SENZA IMPEGNO DI SPESA' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=2', 'ATTI CIRCOSCRIZIONALI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=16', 'DECRETI E AVVISI DI NOMINA' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=25', 'AVVISO AI CREDITORI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=26', 'RENDICONTI SPESE ELETTORALI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=8', 'MANIFESTI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=12', 'MANIFESTI E ATTI ELETTORALI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=1', 'PUBBLICAZIONI DI MATRIMONIO' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=5', 'VARIAZIONI ANAGRAFICHE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=4', 'AVVISI DI DEPOSITO' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=6', 'AVVISI PUBBLICI E ALTRI DOCUMENTI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=31', 'VEICOLI C/O CUSTODI ACQUIRENTI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/albopretorio/alboordinanze.shtml', 'ORDINANZE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=3', 'PERMESSI DI COSTRUIRE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=14', 'OPERE EDILIZIE ABUSIVE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=24', 'EDILIZIA SOCIALE' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=10', 'ALTRI BANDI ED ESITI' ),
				new AlboTorinoSubPage ( 'http://www.comune.torino.it/cgi-bin/albopretorio/index.cgi?c=11', 'PUBBLICAZIONI DA ALTRI ENTI' ) 
		);
		$this->index = 0;
		$this->itIndex = - 1;
		$this->goToNextNotEmptyIterator ();
	}
	
	/**
	 * Get the entry with the specified coordinates if any,
	 * null otherwise.
	 */
	public function getEntry($subPageURI, $year, $number) {
		$subPage = $this->getSubPage ( $subPageURI );
		return $this->getEntryInSubPage ( $subPage, $year, $number );
	}
	
	/**
	 * Get the subpage with the specified URI.
	 * Throw an exception if no such
	 * subpage exists.
	 */
	private function getSubPage($uri) {
		foreach ( $this->subPages as $p )
			if (! strcmp ( $p->uri, $uri ))
				return $p;
		throw new Exception ( "No such subpage $uri" );
	}
	
	/**
	 * Get the entry with the specified year and number in the given subpage, if exists.
	 * Throws an exception otherwise.
	 */
	private function getEntryInSubPage($subPage, $year, $number) {
		$subPageParser = new AlboTorinoSubPageParser ( $subPage->uri, $subPage->title, LINK_URI_PREFIX );
		foreach ( $subPageParser as $e )
			if (! strcmp ( $e->year, $year ) && ! strcmp ( $e->number, $number ))
				return $e;
		throw new Exception ( "No such entry $year/$number in subpage $subPage->uri" );
	}
	
	// ITERATOR METHODS
	/**
	 * MOve index and initialize current to the next (w.r.t.
	 * the one currently in index)
	 * not empty sub page iterator.
	 */
	private function goToNextNotEmptyIterator() {
		$max = count ( $this->subPages );
		$this->itIndex ++;
		if ($this->itIndex >= $max)
			return;
		$subPage = $this->subPages [$this->itIndex];
		$this->currentIt = new AlboTorinoSubPageParser ( $subPage->uri, $subPage->title, LINK_URI_PREFIX );
		if (! $this->currentIt->valid ())
			$this->goToNextNotEmptyIterator ();
	}
	public function current() {
		return $this->currentIt->current ();
	}
	public function key() {
		return $this->index;
	}
	public function next() {
		$this->index ++;
		$this->currentIt->next ();
		if (! $this->currentIt->valid ())
			$this->goToNextNotEmptyIterator ();
	}
	public function rewind() {
		$this->index = 0;
		$this->itIndex = - 1;
		$this->goToNextNotEmptyIterator ();
	}
	
	public function valid() {
		return $this->itIndex < count ( $this->subPages );
	}
}
?>