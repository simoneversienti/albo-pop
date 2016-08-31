<?php 


define('DATE_FORMAT','d/m/Y');
define('CONTENT_SEPARATOR','/');

class AlboCittaDellaSaluteEntry{
	
	var $tipologia;
	var $ndelibera;
	var $ddelibera;
	var $oggetto;
	var $dpubblicazione;
	var $d_pubblicazionea1;
	var $ufficio;
	var $allegati;
	
	public function __construct($atto) {
		
		$tipologia=$atto->tipologia;
		$ndelibera=$atto->ndelibera;
		$ddelibera=$atto->ddelibera;
		$oggetto=$atto->oggetto;
	    $dpubblicazione=$atto->dpubblicazione;
	    $d_pubblicazionea1=$atto->d_pubblicazionea1;
		$ufficio=$atto->ufficio;
		$allegati=$atto->allegati;
		
	}	
	
	
}

?>