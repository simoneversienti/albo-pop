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
 * but WITHOUT ANY WARRANTY; withorout even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require('AlboCittaMetroTorinoSubPage.php');
define ( 'LINK_URI_PREFIX', 'http://www.provincia.torino.gov.it/cgi-bin/attiweb' );
class AlboCittaMetroTorinoParser implements Iterator 
{
	private $subPages;
	private $index;
	private $itIndex;
	private $currentIt = null;
	public function __construct() {
		$this->subPages = array (
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/delibere_pubblicazione_consiglio.pl', 'Delibere del Consiglio Metropolitano' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/atti_gc_pubblicazione.pl?flag_ente=I&tipo_doc=9', 'Ordini del giorno del Consiglio Metropolitano' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/atti_gc_pubblicazione.pl?flag_ente=I&tipo_doc=1', 'Convocazioni del Consiglio Metropolitano' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/atti_gc_pubblicazione.pl?flag_ente=I&tipo_doc=2', 'Convocazioni delle Commissioni' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/delibere_pubblicazione_giunta.pl', 'Delibere di Giunta' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/atti_conferenza_pubblicazione.pl', 'Atti della Conferenza Metropolitana in pubblicazione' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/decreto_presidente_pubblicazione.pl', 'Decreti del Presidente in pubblicazione' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/decreti_sindaco_pubblicazione.pl', 'Decreti del Sincaco Metropolitano in pubblicazione' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/determine_pubblicazione.pl', 'Determine in pubblicazione' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/atti_gc_pubblicazione.pl?flag_ente=I&tipo_doc=8', 'Atti di Commisioni Espropri in pubblicazioni' ),
				new AlboCittaMetroTorinoSubPage ( 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/altri_atti_pubblicazione.pl', 'Altri Atti della Citt&agrave Metropolitana o di soggetti esterni in pubblicazione' )

		);
		$this->index = 0;
		$this->itIndex = - 1;
		$this->goToNextNotEmptyIterator ();
	}
	
	/**
	 * Get the entry with the specified coordinates if any,
	 * null otherwise.
	 */
	public function getEntry($subPageURI, $year, $number) 
	{
		$subPage = $this->getSubPage ( $subPageURI );
		return $this->getEntryInSubPage ( $subPage, $year, $number );
	}
	
	/**
	 * Get the subpage with the specified URI.
	 * Throw an exception if no such
	 * subpage exists.
	 */
	private function getSubPage($uri) 
	{
		foreach ( $this->subPages as $p )
			if (! strcmp ( $p->uri, $uri ))
				return $p;
		throw new Exception ( "No such subpage $uri" );
	}
	
	/**
	 * Get the entry with the specified year and number in the given subpage, if exists.
	 * Throws an exception otherwise.
	 */
	private function getEntryInSubPage($subPage, $year, $number) 
	{
		$subPageParser = new AlboCittaMetroTorinoSubPageParser ( $subPage->uri, $subPage->title, LINK_URI_PREFIX );
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
		$this->currentIt = new AlboCittaMetroTorinoSubPageParser ( $subPage->uri, $subPage->title, LINK_URI_PREFIX );
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