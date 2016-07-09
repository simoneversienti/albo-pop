<?php
/**
 * Convert AlboUnitoEntry instances to RSSItem 
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
class AlboUnitoItemConverter implements AlboToRSSItemConverter {
	/**
	 *
	 * @param AlboUnitoEntry $alboUnitoItem        	
	 */
	function getRSSItem($alboUnitoItem) {
		$rssItem = new RSSFeedItem ();
		if (strlen ( $alboUnitoItem->parseErrors )) {
			$rssItem->errors = $alboUnitoItem->parseErrors;
			return $rssItem;
		}
		
		$rssItem->title = $alboUnitoItem->oggetto;
		$rssItem->description = "$alboUnitoItem->anno_repertorio/$alboUnitoItem->numero_repertorio $alboUnitoItem->struttura : $alboUnitoItem->oggetto";
		$rssItem->pubDate = $alboUnitoItem->inizio_pubblicazione;
		if (count ( $alboUnitoItem->links )==0)
			$rssItem->errors = "No attachment found";
		else{
			$rssItem->link = each ( $alboUnitoItem->links ) ['key'];
			$rssItem->guid = $rssItem->link;
		}
		return $rssItem;
	}
}
?>