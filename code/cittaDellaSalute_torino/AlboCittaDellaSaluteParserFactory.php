<?php 

require ('../phpparsing/AlboParserFactory.php');
require ('./AlboCittaDellaSaluteParser.php');
//define('SELECTION_FORM_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio;jsessionid=A7AAB8DEA03B8B38A523391514236713?p_auth=8GWyser9&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiFiltro');

class AlboCittaDellaSaluteParserFactory implements AlboParserFactory {
	public static $alboPageUri = 'https://www.cittadellasalute.to.it/albo/pubblicazione.xml';

	/**
	 * The landing page of the Official Albo
	 */
	public function getAlboPretorioLandingPage() {
		return AlboCittaDellaSaluteParserFactory::$alboPageUri;
	}

	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnictParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() {
		/*$url = "https://www.cittadellasalute.to.it/albo/pubblicazione.xml";
		$file = file_get_contents($url, FALSE, stream_context_create(array('http' =>array('user_agent' => 'php' ))));
		$page = simplexml_load_string($file);*/
		/*$page = new DOMDocument();
		$page->load(AlboCittaDellaSaluteParserFactory::$alboPageUri);*/
		//print_r($page);
		$page=simplexml_load_file(AlboCittaDellaSaluteParserFactory::$alboPageUri);
		return new AlboCittaDellaSaluteParser($page);
	}

	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		$page = new DOMDocument();
		$page->loadHTMLfile(SELECTION_FORM_URL."&numeroRegistrazioneDa=$number&annoRegistrazioneDa=$year");
		return new AlboBelpassoParser($page);
	}
}
?>

