<?php
/**
 * Base class to generate an Albo POP. Create delegates and pass them 
 * as constructor arguments to create an Albo POP.
 * 
 * @author Cristiano Longo
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
require ("../RSS/RSSFeedGenerator.php");
require ('AccessLogUtils.php');
class AlboPopGenerator {
	private $parserFactory;
	private $converter;
	
	/**
	 *
	 * @param AlboParserFactory $parserFactory
	 *        	@para AlboToRSSItemConverter $converter;
	 */
	public function __construct($parserFactory, $converter) {
		$this->parserFactory = $parserFactory;
		$this->converter = $converter;
	}
	
	/**
	 * Send header and the generated feed on the standard output.
	 *
	 * @param string $title
	 *        	the feed title
	 * @param string $description
	 *        	optional, a feed description
	 * @param string $url
	 *        	the url where the feed is published
	 */
	public function outputFeed($title, $description, $url) {
		$parser = $this->parserFactory->createFromWebPage ();
		$feed = new RSSFeedGenerator ( $title, $description, $this->parserFactory->getAlboPretorioLandingPage (), $url );
		
		foreach ( $parser as $e ) {
			$item = $this->converter->getRSSItem ( $e );
			if (strlen ( $item->errors ))
				$feed->addComment ( $item->errors );
			else 
				$feed->addItemObject ( $item );
		}
		// output
		header ( 'Content-type: application/rss+xml; charset=UTF-8' );
		/*
		 * Impostazioni locali in italiano, utilizzato per la stampa di data e ora
		 * (il server deve avere il locale italiano installato
		 */
		setlocale ( LC_TIME, 'it_IT' );
		AccessLogUtils::logAccess();
		echo $feed->getFeed ();
	}	
}

