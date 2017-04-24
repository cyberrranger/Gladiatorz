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
echo'<center><a href="index.php?site=quest" target="_self">Quest</a> |
<a href="index.php?site=quests" target="_self">Spezial Quests</a> |
<a href="index.php?site=tool" target="_self">Werkstatt</a>
</center><br />';


$Query = @mysql_query("SELECT * FROM collected WHERE user_id='".$User['id']."' ORDER BY id DESC LIMIT 1"); //der aktulle quest wird aus der datenbank geholt
$collected = @mysql_fetch_assoc($Query);

if(!$collected)
{
	//collected spalte anlegen
	$Query2 = @mysql_query("INSERT INTO collected (user_id) VALUES ('".$User['id']."')"); //der aktulle quest wird aus der datenbank geholt
	$collected = @mysql_fetch_assoc($Query2);
}

if($_REQUEST['col'])
{
	if($_REQUEST['col'] == 1 && $collected['collected_name_4'] >= ($_REQUEST['max'] * 3) && $collected['collected_name_5'] >= $_REQUEST['max'] && $collected['collected_name_6'] >= $_REQUEST['max'] )
	{
		for($i = 1;$i<=$_REQUEST['max'];$i++)
		{
		  $Update1 = mysql_query("UPDATE collected SET collected_name_4=collected_name_4-3, collected_name_5=collected_name_5-1, collected_name_6=collected_name_6-1, collected_name_7=collected_name_7+1 WHERE user_id='$user[id]' LIMIT 1");
		}

		echo "<center>";
		echo "Du hast erfolgreich ".$_REQUEST['max']." Pfeil hergestellt.<br /><a href='index.php?site=tool' target='_self'>Zurück</a>";
		echo "</center>";
	}elseif ($_REQUEST['col'] == 1)
	{
		echo "<center>";
		echo "Du hast nicht genügend Teile.<br /><a href='index.php?site=tool' target='_self'>Zurück</a>";
		echo "</center>";
	}

	if($_REQUEST['col'] == 2 && $collected['collected_name_1'] >= ($_REQUEST['max'] * 30) && $collected['collected_name_5'] >= ($_REQUEST['max'] * 6))
	{
		for($i = 1;$i<=$_REQUEST['max'];$i++)
		{
		  $Update1 = mysql_query("UPDATE collected SET collected_name_1=collected_name_1-30, collected_name_5=collected_name_5-6, collected_name_8=collected_name_8+1 WHERE user_id='$user[id]' LIMIT 1");
		}

		echo "<center>";
		echo "Du hast erfolgreich ".$_REQUEST['max']." Köcher hergestellt.<br /><a href='index.php?site=tool' target='_self'>Zurück</a>";
		echo "</center>";
	}elseif ($_REQUEST['col'] == 2)
	{
		echo "<center>";
		echo "Du hast nicht genügend Teile.<br /><a href='index.php?site=tool' target='_self'>Zurück</a>";
		echo "</center>";
	}

}else
{
	echo "<center>";
	echo "Hier in der Werkstatt kannst du deine gesammelten Drops kombinieren.<br />";

	echo "Was willst du machen?<br /><br />";
	echo "</center>";
	if($collected['collected_name_4'] >= 3 && $collected['collected_name_5'] >= 1 && $collected['collected_name_6'] >= 1 )
	{
		echo "Pfeil <a href='index.php?site=tool&col=1&max=1' target='_self' title='Das kostet drei Federn, einen Stock und einem Spitzen Stein'>herstellen</a>?<br />";

		$max_pfeile_1 = floor($collected['collected_name_4']/3);
		$max_pfeile_2 = $collected['collected_name_5'];
		$max_pfeile_3 = $collected['collected_name_6'];

		$max_pfeile = min($max_pfeile_1,$max_pfeile_2,$max_pfeile_3);
		echo "$max_pfeile Pfeile <a href='index.php?site=tool&col=1&max=".$max_pfeile."' target='_self' title='Pfeile erstellen'>herstellen</a>?<br />";

	}else
	{
		echo "Für einen Pfeil benötigst du 3 Federn, 1 Stock und 1 Spitzen Stein.<br />";
	}
	if($collected['collected_name_1'] >= 30 && $collected['collected_name_5'] >= 6)
	{
		echo "Köcher <a href='index.php?site=tool&col=2&max=1' target='_self' title='Das kostet 30 Felle und 6 Stöcke'>herstellen</a>?<br />";
		$max_kocher_1 = floor($collected['collected_name_1']/30);
		$max_kocher_2 = floor($collected['collected_name_5']/6);

		$max_kocher = min($max_kocher_1,$max_kocher_2);
		echo "$max_kocher Köcher <a href='index.php?site=tool&col=2&max=".$max_kocher."' target='_self' title='Köcher erstellen'>herstellen</a>?<br />";

	}else
	{
		echo "Für einen Köcher benötigst du 30 Felle und einem 6 Stöcke.<br />";
	}

	echo "<br /><br /><br /><br /><br /><center>Du hast bereits gesammelt:";
	echo "<ul>";

	foreach ($collected as $col => $key)
	{
		if ($col != 'id' && $col != 'user_id')
		{
			if ($key != 0)
			{
				$id = preg_replace("/\D/", "", $col);
				if ($id > 8 && time() < 1291158000)
				{

				}else
				{
					echo "<li>".$key." ".collection_name($id)."</li>";
				}
			}
		}
	}
	echo "</ul></center>";
}

?>
