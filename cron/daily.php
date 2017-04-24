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
if($_GET["hash"] == "asdaf355235DDAd")
{

	require('../System/conf.php');
	require('../System/Database.php');

	require("../System/JesusCryOld.php");
	require("../System/GameFunctions.php");

	$anzahl_gewinner = mysql_count("user","id","prestige > 0");

	if($anzahl_gewinner > 12)
	{
		$anzahl_gewinner = 12;
	}

	$schleife = 1;
	$abfrage = "SELECT * FROM user WHERE prestige > 0 ORDER BY prestige DESC";
	$select = mysql_query($abfrage);
	while($user = mysql_fetch_assoc($select))
	{
		echo $schleife." -> ".$user['name']."<br />";

		$meds_string = 'Medallien';

		switch($schleife)
		{
			case 1:
				$user[medallien] = $user[medallien] + 15;
				$meds_won = 15;
				break;
			case 2:
				$user[medallien] = $user[medallien] + 12;
				$meds_won = 12;
				break;
			case 3:
				$user[medallien] = $user[medallien] + 10;
				$meds_won = 10;
				break;
			case 4:
				$user[medallien] = $user[medallien] + 9;
				$meds_won = 9;
				break;
			case 5:
				$user[medallien] = $user[medallien] + 8;
				$meds_won = 8;
				break;
			case 6:
				$user[medallien] = $user[medallien] + 7;
				$meds_won = 7;
				break;
			case 7:
				$user[medallien] = $user[medallien] + 6;
				$meds_won = 6;
				break;
			case 8:
				$user[medallien] = $user[medallien] + 5;
				$meds_won = 5;
				break;
			case 9:
				$user[medallien] = $user[medallien] + 4;
				$meds_won = 4;
				break;
			case 10:
				$user[medallien] = $user[medallien] + 3;
				$meds_won = 3;
				break;
			case 11:
				$user[medallien] = $user[medallien] + 2;
				$meds_won = 2;
				break;
			case 12:
				$user[medallien] = $user[medallien] + 1;
				$meds_won = 1;
				$meds_string = 'Medallie';
				break;
		}



		mysql_update("user","medallien='$user[medallien]'","id='$user[id]'");

		$timestamp = time();
		$text = "Hallo $user[name],<br />du hast im heutigen Prestigewettbewerb den $schleife. Platz gemacht.<br />Damit hast du dir $meds_won $meds_string redlich verdient!<br />MfG die Arenaleitung";

		mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender","'$user[name]','admin','Gewinn beim Prestigewettbewerb!','$text','$timestamp','n','n','n'");

		$schleife++;

		if ($schleife >= $anzahl_gewinner)
		{
			break;
		}
	}

	$abfrage = "SELECT * FROM user ORDER BY prestige DESC";
	$select = mysql_query($abfrage);

	while($user = mysql_fetch_assoc($select))
	{
		mysql_update("user","prestige='0'","id='$user[id]'");
	}

	echo "Prestige berechnet";

} else
{
	echo "Kein Zutritt!!!";
}
?>
