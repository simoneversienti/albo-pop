<?php
/**
 * Convert AlboBelpassoEntry instances to RSSItem ones 
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
require ('../phpalbogenerator/AlboToRSSItemConverter.php');
require ('../RSS/RSSFeedItem.php');
/**
 * @author cristianolongo
 *
 */
class AlbojCityGovItemConverter implements AlboToRSSItemConverter {
	
	private $sharerUrl;
	
	function __construct($sharerUrl){
		$this->sharerUrl=$sharerUrl;	
	}
	/** 
	 *
	 * @param AlboUnitoEntry $alboTorinoItem        	
	 */
	function getRSSItem($albojCityGovItem) {
		$rssItem = new RSSFeedItem ();
		$rssItem->title=$albojCityGovItem->oggetto;
		$rssItem->description=$albojCityGovItem->anno_registro.'/'.$albojCityGovItem->numero_registro.'['.$albojCityGovItem->tipo_atto.','.$albojCityGovItem->sottotipo_atto.']'.$albojCityGovItem->oggetto;
		$rssItem->pubDate=$albojCityGovItem->data_inizio_pubblicazione;
		$rssItem->link=$this->sharerUrl.'?year='.$albojCityGovItem->anno_registro."&number=".$albojCityGovItem->numero_registro;
		$rssItem->guid=$rssItem->link;
		return $rssItem;
	}
}
?>