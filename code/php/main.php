#!/usr/bin/php

//-------------------------------------------------------------------------------
// Albo POP Project main script
//-------------------------------------------------------------------------------
//  Author      Matteo Tempestini
//  Date        11 02 2016
//  License     MIT
//-------------------------------------------------------------------------------
//  Select the right parser and Build RSS Feed
//-------------------------------------------------------------------------------

<?php

require_once('class.rssbuilder.php');
require_once('parsing_vallata.php');
require_once('utility.php');


date_default_timezone_set('Europe/Rome');


//CANTAGALLO
rss_build('cantagallo');
//VERNIO
rss_build('vernio');
//VAIANO
rss_build('vaiano');


//FEED BUILDER
function rss_build($comune) {

//build the RSS feed
$RB = new RSSBuilder();

$file_rss=dirname(__FILE__). "/". $comune."AlboPOP.xml";
$web_link="http://194.243.23.67:8080/albopretorio/albo". $comune.".php";

		$parsed=parsing_albo_vallata($web_link);
		
		$RB->addChannel(); 
		$RB->addChannelElement('title', 'alboPOP'.$comune);
		$RB->addChannelElement('link', $web_link);
		$RB->addChannelElement('description', '*non ufficiale* feed RSS Albo Pretorio di '.$comune);
		$RB->addChannelElement( 'pubDate', 'Fri, 12 Feb 2016 00:00:00 +0100' );
		$RB->addChannelElement('lastBuildDate', 'Fri, 12 Feb 2016 00:00:00 +0100');
		$RB->addChannelElement('generator', 'PHP Framework');

	for($i = 0; $i <count($parsed[1]); $i++)
		{ 
			$RB->addItem();
			$RB->addItemElement('title', $parsed[0][$i][count($parsed[0][$i])-1]);
			$RB->addItemElement('description', $parsed[0][$i][count($parsed[0][$i])-1]);
			$RB->addItemElement('link', $parsed[1][$i]);
			$RB->addItemElement('pubDate', string2dataRSS($parsed[0][$i][count($parsed[0][$i])-3]));
		}


//echo $RB;
file_put_contents($file_rss, $RB);
//insert no track indications
add_notrack_info($file_rss);


}
