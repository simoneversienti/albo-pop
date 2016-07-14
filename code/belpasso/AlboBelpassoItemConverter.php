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
class AlboBelpassoItemConverter implements AlboToRSSItemConverter {
	/** 
	 *
	 * @param AlboUnitoEntry $alboTorinoItem        	
	 */
	function getRSSItem($alboBelpassoItem) {
		$rssItem = new RSSFeedItem ();
		$rssItem->title=$alboBelpassoItem->oggetto;
		$rssItem->description=$alboBelpassoItem->anno_registro.'/'.$alboBelpassoItem->numero_registro.'['.$alboBelpassoItem->tipo_atto.','.$alboBelpassoItem->sottotipo_atto.']'.$alboBelpassoItem->oggetto;
		$rssItem->pubDate=$alboBelpassoItem->data_inizio_pubblicazione;
		$rssItem->link="http://dev.opendatasicilia.it/albopop/belpasso/sharer.php?year=".$alboBelpassoItem->anno_registro."&number=".$alboBelpassoItem->numero_registro;
		$rssItem->guid=$rssItem->link;
		return $rssItem;
	}
}
?>