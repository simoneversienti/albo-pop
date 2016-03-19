<?php 
/**
 * A template for generated pages to share feed items on facebook.
 * Just set the following variables and import this file in your own sharer.php page:
 * 
 * - title 
 * - logo an url of the page logo
 * - description
 * - link the link of the reported test
 * - credits html code to be put in the Credits section
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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo $title;?></title>
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
		<meta property="og:title" content="<?php echo $title; ?>" />
		<meta property="og:description" content="<?php echo $description; ?>" />
		<meta property="og:image" content="<?php echo $logo; ?>" />
	</head>
	<body>
	<header class="main-header" id="top">
		<img class="logo" src="<?php echo $logo; ?>" alt="logo" />
		<h1><?php echo $title;?></h1>
		<p class="subtitle">Questo  &egrave; un <a href="http://albopop.it">Albo POP</a>!</p>
	</header>
	<section>
		<p><?php echo $description; ?></p>
		<p><a href="<?php echo $link?>" >Vedi l'avviso originale</a></p>		
		<p><a href="#" onclick="sharefb()">Condividi su Facebook</a>
	</section>
	<section>
		<h2>Crediti</h2>
		<?php echo $credits;?>		
	</section>
		
	</body>	
</html>

