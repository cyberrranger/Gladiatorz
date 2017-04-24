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
$Query = @mysql_query("SELECT * FROM collected WHERE user_id='".$User['id']."' ORDER BY id DESC LIMIT 1"); //der aktulle quest wird aus der datenbank geholt
$collected = @mysql_fetch_assoc($Query);

if(!$collected)
{
	//collected spalte anlegen
	$Query2 = @mysql_query("INSERT INTO collected (user_id) VALUES ('".$User['id']."')"); //der aktulle quest wird aus der datenbank geholt
	$collected = @mysql_fetch_assoc($Query2);
}

echo'<center><a href="index.php?site=arena" target="_self">Arena</a> | <a href="index.php?site=quest" target="_self">Quest</a> |
<a href="index.php?site=quests" target="_self">Spezial Quests</a> |
<a href="index.php?site=tool" target="_self">Werkstatt</a>
</center><br />';

if ($_REQUEST['c'] && $_REQUEST['q'])
{
	$count = $collected['collected_name_'.$_REQUEST['c']];
	if ($count > 0)
	{
		$name = "collected_name_".$_REQUEST['c'];

		$Sql = "SELECT * FROM quest_all_what WHERE id=".$_REQUEST['q']." LIMIT 1";
		$check_gold = mysql_fetch_array(mysql_query($Sql));

		$Update1 = mysql_query("UPDATE quest_all_what SET count=count+'$count' WHERE id=".$_REQUEST['q']."");
		$Update2 = mysql_query("UPDATE collected SET ".$name."='0' WHERE user_id='$user[id]' LIMIT 1");

		if ($check_gold['gold'])
		{
			$this_gold = $user['gold'];
			$plus_gold = $collected[$name] * $check_gold['gold'];
			$new_gold = $this_gold + $plus_gold;

			$Update3 = mysql_query("UPDATE user Set gold='$new_gold' WHERE id='$user[id]' LIMIT 1");
		}

		echo "<center>";
		echo "Du hast erfolgreich alle deine ".collection_name($_REQUEST['c'])." (".$count.") f&uuml;r ".$plus_gold." Gold übertragen.<br /><br /><br />";
		echo "</center>";

		$Query = @mysql_query("SELECT * FROM collected WHERE user_id='".$User['id']."' ORDER BY id DESC LIMIT 1"); //der aktulle quest wird aus der datenbank geholt
		$collected = @mysql_fetch_assoc($Query);
	} else
	{
		echo "<center>";
		echo "Du hast keine ".collection_name($_REQUEST['c'])." mehr.<br /><br /><br />";
		echo "</center>";
	}
}

/*
 * 1. Schritt
 * aktuelles Quest herausfinden
 */

$Sql = "SELECT * FROM quest_all WHERE aktiv=1 ORDER by ID ASC LIMIT 1";
$quest = mysql_fetch_array(mysql_query($Sql));

/*
 * 2. Schritt
 * gibt es in diesem Quest noch offene Aufgaben?
 */
if ($quest)
{
	$Sql = "SELECT * FROM quest_all_what WHERE quest_id=".$quest['id']." ORDER by ID ASC";
	$Query = @mysql_query($Sql);

	$thisquestdo = array();
	while($quest_what = mysql_fetch_assoc($Query))
	{
		$thisquestdo[] = $quest_what;
	}

	$is_this_quest_ok = false;
	foreach ($thisquestdo as $quest_what)
	{
		if ($quest_what['count'] < $quest_what['max'])
		{
			$is_this_quest_ok = true;
			break;
		}
	}

	if (!$is_this_quest_ok)
	{
		$close_quest = mysql_query("UPDATE quest_all SET aktiv=0 WHERE id=$quest[id] LIMIT 1");

	} else
	{
		echo "<center>";
		echo "Dieses Quests ist von allen Gladiatoren in dieser Arena zu erfüllen, in diesem Fall müsst Ihr für die Belohnung zusammenarbeiten und nicht gegeneinander.<br /><br />";
		echo "</center>";

		/*
		 * 3. Schritt
		 * Aufgabe anzeigen
		 */
		echo "<center>";
		echo $quest['name'].":<br /><br />";
		echo $quest['short']."<br /><br />";
		echo "<ul>";

		foreach ($thisquestdo as $quest_what)
		{
			echo "<li> Sammelt insgesamt ".$quest_what['max']." ".collection_name($quest_what['what'])."</li>";
		}
		echo "</ul>";

		echo "<br />Davon sind noch folgende Aufgaben offen:<br /><br />";

		echo "<ul>";

		foreach ($thisquestdo as $quest_what)
		{
			if ($quest_what['count'] < $quest_what['max'])
			{
				echo "<li>".$quest_what['count']." von ".$quest_what['max']." ".collection_name($quest_what['what'])."</li>";
			}
		}
		echo "</ul>";

		echo "<br />Möchtest du helfen dieses Quest zu lösen?";

		echo "<ul>";
		foreach ($thisquestdo as $quest_what)
		{
			if($collected['collected_name_'.$quest_what['what']] != 0 &&  $quest_what['count'] < $quest_what['max'])
			{
				echo "<li>Ja ich will all meine gesammelten ".$collected['collected_name_'.$quest_what['what']]." ".collection_name($quest_what['what'])." <a href='index.php?site=quests&c=".$quest_what['what']."&q=".$quest_what['id']."' target='_self'>übertragen</a>.</li>";
			}
		}
		echo "</ul>";
		echo "</center>";

	}
} else
{
	echo "<center>";
	echo "Derzeit gibt es kein Quest zu lösen.<br /><br />";
	echo "</center>";

}
?>
