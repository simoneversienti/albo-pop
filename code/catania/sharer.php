<?php 
/**
 * A page to share feed items of facebook.
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

require("AlboComuneCTParser.php");
$repertorio=$_GET['repertorio'];
if (!isset($repertorio))
	die("E' necessario specificare un numero di repertorio.");

$entry = (new AlboComuneCTParser(2016))->getByRepertorio($repertorio);
//if ($entry==null)
//	die("Nessun elemento col numero di repertorio $repertorio");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Albo POP Comune di Catania - Avviso <?php echo $repertorio;?></title>
		<link rel="stylesheet" type="text/css" href="http://opendatahacklab.github.io/odhl.css" />
		
		<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1196238777060410',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  FB.ui({
	  method: 'share',
	  href: 'https://developers.facebook.com/docs/',
	}, function(response){});

 /**
  * Share the current page on facebook.
  */
function sharefb(){
	FB.ui({
		method: 'share',
		href: window.location.href
	}, function(response){});
}
		</script>
		<meta property="og:title" content="Albo POP - Comune di Catania - Avviso <?php echo $entry->repertorio ?>" />
		<meta property="og:description" content="<?php echo $entry->mittente_descrizione;?>" />
		<meta property="og:image" content="http://dev.opendatasicilia.it/albopop/catania/ct-logo-pop.jpg" />
	</head>
	<body>
	<header class="main-header" id="top">
		<img class="logo" src="ct-logo-pop.jpg" alt="logo albo pop comune di catania" />
		<h1>comune di catania - avviso <?php echo $repertorio;?></h1>
		<p class="subtitle">Questo non &egrave; l'albo del comune, &egrave; un <a href="http://albopop.it">Albo POP</a>!</p>
	</header>
	<h1></h1>
		<p><em>Tipo:</em> <?php echo $entry->tipo;?></p>
		<p><em>Mittente/Descrizione :</em> <?php echo $entry->mittente_descrizione;?></p>
		<p><a href="<?php echo $entry->link?>" >Avviso sul sito del comune</a></p>		
		<p><a href="#" onclick="sharefb()">Condividi su Facebook</a>
		
		<h2>Crediti</h2>
		
		<img class="logo" src="http://opendatahacklab.github.io/imgs/logo_cog4_ter.png" alt="the opendatahacklab logo" />
		<p>Il logo di questo albo pop &egrave; stato ottenuto dalla 
			pagina di Wikipedia che riporta lo <a href="https://it.wikipedia.org/wiki/File:Catania-Stemma.png">stemma del comune di Catania</a>,
			elaborandolo poi con il tool <a href="https://photofunia.com/effects/popart">PhotoFunia</a>.
		</p>
		
		<p>		
		L'albo pop del comune di Catania è stato realizzato da <a href="http://hackspacecatania.it">Hackspace Catania</a>
		nell'ambito del progetto <a href="http://opendatahacklab.org"><code>opendatahacklab</code></a>.</p>
	</body>	
</html>

