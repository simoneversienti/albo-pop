<?php 
/**
 * A single entry of Albo Torino
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
class AlboTorinoEntry{
	public $year; 
	public $number;
	public $type; //string
	public $subject;
	public $link; //url as String
	public $startDate; //DateTime
	public $endDate; //DateTime
	public $parseErrors=""; //empty string if no parse error has been detected
}