<?php
/**
 * Factory object to create parser for a specific Albo Pretorio.
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
 */
interface AlboParserFactory{
	
	/**
	 * The landing page of the Official Albo
	 */
	function getAlboPretorioLandingPage();

	/**
	 * Create a parser for the albo which retrieves all the albo entries.
	 */
	function createFromWebPage();	
}

?>