<?php 
/**
 * Parse rows as entries 
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
 *
 * @author Cristiano Longo
 * 
 */
require('../jCityGov/AlbojCityGovParseUtils.php');
require('../jCityGov/AlbojCityGovEntryParser.php');

class AlboCittaMetropolitanaCataniaEntryParser implements AlbojCityGovEntryParser{
	
	/**
	 * Generate an AlbojCityGovEntry from a table row
	 */
	public function parse($row){
		$entry=new AlbojCityGovEntry();
		$cells=$row->getElementsByTagName('td');
		$anno_numero_registro=explode('/', $cells->item(0)->textContent);
		if (count($anno_numero_registro)!=2)
			file_put_contents('php://stderr', $cells->item(0)->textContent);
				
		$entry->anno_registro=trim($anno_numero_registro[0]);
		$entry->numero_registro=trim($anno_numero_registro[1]);
		
		$tipo_sottotipo=AlbojCityGovParseUtils::parseTwoLinesField($cells->item(2)->textContent);
		$entry->tipo_atto=$tipo_sottotipo[0];
		$entry->sottotipo_atto=$tipo_sottotipo[1];
		
		$entry->oggetto=$cells->item(3)->textContent;
		
		$inizio_fine_pubblicazione=AlbojCityGovParseUtils::parseInizioFinePubblicazione($cells->item(4));
		$entry->data_inizio_pubblicazione=$inizio_fine_pubblicazione[0];
		if (count($inizio_fine_pubblicazione)>1)
			$entry->data_fine_pubblicazione=$inizio_fine_pubblicazione[1];
		
		$entry->url=AlbojCityGovParseUtils::parseURL($cells->item(6));
		return $entry;
	}	
}
?>