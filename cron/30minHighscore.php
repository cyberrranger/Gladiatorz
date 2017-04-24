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
require('../System/conf.php');
require('../System/Database.php');
require('../System/Functions.php');
require('../System/GameFunctions.php');

//aktuelle minute beachten
$min = date('i');
if($min<=29) {
	$tabell_name = 'highscore';
}else {
	$tabell_name = 'highscore30';
}
		
//Inhalt der Tabelle Highscore entfernen
$Query_clear = mysql_query("TRUNCATE TABLE $tabell_name");

//alle aktiven Spieler selectieren
$zahl = 60 * 60 * 24 * 7 * 5;
$last_online = time() - $zahl;
$Query = mysql_query("SELECT * FROM user WHERE aktiv=1 AND lonline>=$last_online AND id!=4");

while($User = mysql_fetch_assoc($Query))
{
	//ein user nach dem andern in die Tabelle schreiben
	echo $User['name']."<br />";
	calcHS($User);
}
echo "Fertig<br />";
?>
