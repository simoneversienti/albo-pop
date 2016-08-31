<?php 

require ('../phpalbogenerator/AlboToRSSItemConverter.php');
require ('../RSS/RSSFeedItem.php');

class AlboCittaDellaSaluteItemConverter implements AlboToRSSItemConverter {
	/**
	 *
	 * @param AlboUnitoEntry $alboTorinoItem
	 */
	function getRSSItem($alboBelpassoItem) {
		$rssItem = new RSSFeedItem ();
		$rssItem->title=$alboBelpassoItem->oggetto;
		$rssItem->description="nr.atto:" . $alboBelpassoItem->ndelibera . " data atto:" . $alboBelpassoItem->ddelibera . " oggetto:" . $alboBelpassoItem->oggetto;
		$rssItem->pubDate=$alboBelpassoItem->dpubblicazione;
		$rssItem->link="https://www.cittadellasalute.to.it/albo/pubblicazione.xml";
		$rssItem->guid=$rssItem->link;
		return $rssItem;
	}
}


?>