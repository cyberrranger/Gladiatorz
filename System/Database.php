<?php
/**
 * Gladiatorz ist ein Browsergame
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, see <http://www.gnu.org/licenses/>.
 *
 * Verbatim copying and distribution of this entire article is permitted in any medium without royalty provided this notice is preserved. 
 * 
 * @link http://www.gladiatorgame.de
 * @version 0.9
 * @copyright Copyright: 2009-2011 Patrick Schön
 * @author Patrick Schön <info@cyberrranger.com>
 */
// Datenbankclass erstellen
class DB
{
 	function DB($Server,$User,$Pass,$Dbname)
	{
		mysql_connect($Server,$User,$Pass);
		mysql_select_db($Dbname);
	}
	
 	function connect($Server,$User,$Pass,$Dbname)
	{
		mysql_connect($Server,$User,$Pass);
		mysql_select_db($Dbname);
	}
}

$db = new DB($GLOBALS['conf']['db_host'],$GLOBALS['conf']['db_user'],$GLOBALS['conf']['db_pass'],$GLOBALS['conf']['db_name']);
?>
