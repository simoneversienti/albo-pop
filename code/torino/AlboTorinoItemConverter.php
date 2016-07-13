<?php
/**
 * Convert AlboTorinoEntry instances to RSSItem 
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
class AlboTorinoItemConverter implements AlboToRSSItemConverter {
	/**
	 *
	 * @param AlboUnitoEntry $alboTorinoItem        	
	 */
	function getRSSItem($alboTorinoItem) {
		$rssItem = new RSSFeedItem ();
		if (strlen ( $alboTorinoItem->parseErrors )) {
			$rssItem->errors = $alboTorinoItem->parseErrors;
			return $rssItem;
		}
		
		$sharer_url="http://dev.opendatasicilia.it/albopop/torino/sharer.php?subpage=".urlencode($alboTorinoItem->subPageURI)."&year=".$alboTorinoItem->year."&number=".$alboTorinoItem->number;
		$rssItem->title = $alboTorinoItem->subject;
		$rssItem->description = "$alboTorinoItem->year/$alboTorinoItem->number $alboTorinoItem->category - $alboTorinoItem->subject";
		$rssItem->pubDate = $alboTorinoItem->startDate;
		$rssItem->link = $sharer_url;
		$rssItem->guid = $rssItem->link;
		return $rssItem;
	}
}
?>