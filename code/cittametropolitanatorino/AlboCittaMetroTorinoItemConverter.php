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
class AlboCittaMetroTorinoItemConverter implements AlboToRSSItemConverter 
{
	/**
	 *
	 * @param AlboUnitoEntry $alboMetroTorinoItem        	
	 */
	function getRSSItem($alboMetroTorinoItem) {
		$rssItem = new RSSFeedItem ();
		if (strlen ( $alboMetroTorinoItem->parseErrors )) {
			$rssItem->errors = $alboMetroTorinoItem->parseErrors;
			return $rssItem;
		}
		
		$sharer_url="http://dev.opendatasicilia.it/albopop/cittametropolitanatorino/sharer.php?subpage=".urlencode($alboMetroTorinoItem->subPageURI)."&year=".$alboMetroTorinoItem->year."&number=".$alboMetroTorinoItem->number;
		$rssItem->title = $alboMetroTorinoItem->subject;
		$rssItem->description = "$alboMetroTorinoItem->year/$alboMetroTorinoItem->number $alboMetroTorinoItem->category - $alboMetroTorinoItem->subject";
		$rssItem->pubDate = $alboMetroTorinoItem->startDate;
		$rssItem->link = $sharer_url;
		$rssItem->guid = $rssItem->link;
		return $rssItem;
	}
}
?>