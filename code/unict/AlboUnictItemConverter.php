<?php
/**
 * Convert AlboUnictItem instances to RSSItem ones 
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
class AlboUnictItemConverter implements AlboToRSSItemConverter {
	/**
	 *
	 * @param AlboUnitoEntry $alboTorinoItem        	
	 */
	function getRSSItem($alboUnictItem) {
		$rssItem = new RSSFeedItem ();
		$rssItem->title=$alboUnictItem->description;
		$rssItem->description="Avviso ".$alboUnictItem->numero.".".$alboUnictItem->richiedente.": ".$alboUnictItem->description;
		$rssItem->pubDate=$alboUnictItem->inizio_pubblicazione;
		$rssItem->link="http://dev.opendatasicilia.it/albopop/unict/sharer.php?number=$alboUnictItem->numero";
		$rssItem->guid=$rssItem->link;
		return $rssItem;
	}
}
?>