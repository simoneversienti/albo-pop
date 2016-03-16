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
	</head>
	<body>
		<h1>Albo POP Comune di Catania - Avviso <?php echo $repertorio;?></h1>
		<p><em>Tipo:</em> <?php echo $entry->tipo;?></p>
		<p><em>Mittente/Descrizione :</em> <?php echo $entry->mittente_descrizione;?></p>
		<p><a href="<?php echo $entry->link?>" >Avviso sul sito del comune</a></p>		
	</body>	
</html>

