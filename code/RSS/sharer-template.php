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
<link rel="stylesheet" type="text/css" href="<?php echo $css;?>" />

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
		<h1>
			<?php echo $title;?>
		</h1>
	</header>
	<?php if (isset($news))
		echo "\t<section id=\"news\">\n\t\t<p>$news</p>\n\t</section>\n";
		?>
	<section id="avviso">
		<blockquote cite="<?php echo $link?>">
			<p>
				<?php echo $description; ?>
			</p>
		</blockquote>
		<p>
			Vedi l'<a href="<?php echo $link?>" target="_blank">avviso originale</a>
			- Condividi su <a href="#" onclick="sharefb()">Facebook</a>
		</p>
	</section>
	<section id="links">
		<a href="http://albopop.it" target="_blank"> <img class="logo" alt="logo albo pop"
			src="http://albopop.it/images/logo.png" />
		</a>
		<p>
			Per saperne di pi&ugrave visita il sito del progetto <a
				href="http://albopop.it" target="_blank">Albo POP</a>.
		</p>
	</section>

	<section id="credits">
		<h2>Crediti</h2>
		<p>
			Questo albo pop &egrave; stato realizzato da <a
				href="http://hackspacecatania.it" target="_blank">Hackspace Catania</a>
			nell'ambito del progetto <a href="http://opendatahacklab.org"
				target="_blank"><code>opendatahacklab</code> </a>
<?php if (isset($supporter_name))
	echo " col supporto di <em>$supporter_name</em>"?>
		.</p>
		<p class="links">
			<a href="http://hackspacecatania.it/" target="_blank"> <img
				src="http://hackspacecatania.it/wp-content/uploads/2014/04/logo-hackspace-learn1.png"
				alt="LEARN MAKE HACK SHARE. Hackspace Catania" />
			</a> <a href="http://opendatahacklab.org" target="_blank"> <img
				src="http://opendatahacklab.org/commons/imgs/logo_cog4_ter.png"
				alt="logo opendatahacklab" />
			</a>
		<?php if (isset($supporter_name) && isset($supporter_img))
			echo "<img src=\"$supporter_img\" alt=\"$supporter_name\" />"?>	
		</p>
		<?php 
		if (isset($credits)) echo "<p class=\"credits\">$credits</p>\n";
		?>
	</section>

</body>
</html>

