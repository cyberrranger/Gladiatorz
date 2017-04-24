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
echo'<center>
	<a href="index.php?site=duelle" target="_self">Duelle</a> |
	<a href="index.php?site=duellstats" target="_self">Duell Statistik</a> |
	<a href="index.php?site=duelle_schule" target="_self">Trainingsduelle</a>
	</center>
	<br />';

if ($user['schule'] != 0)
{
	echo '<center><strong>Willkommen auf dem Trainingsgelände deiner Schule</strong><br />Hier kannst du ohne PM zu verbrauchen testen wie gut du im vergleich der Schulkameraden bist.</center><br /><br />';

include "fight2.php";

$mintime = time() - 600;
$abfrage = "SELECT * FROM duelle_schule WHERE duellant_2 != '0' AND wann <= '$mintime'";
$select = mysql_query($abfrage);
while($auswertung = mysql_fetch_assoc($select))
{
	$both = creat_fight($auswertung[duellant_1], $auswertung[duellant_2]);
	$fight = make_a_new_fight($both);

	$winner_id = $fight['winner'];
	$looser_id = $fight['looser'];
	$kampfbericht = $fight['kampfbericht'];

	if($winner_id == $both['user2']['id'])
	{
		$winner="duellant_2";
		$looser="duellant_1";
		$wettwinner="d2";
	}
	else
	{
		$winner="duellant_1";
		$looser="duellant_2";
		$wettwinner="d1";
	}

	$gewinner = get_user_things($winner_id);
	$verlierer = get_user_things($looser_id);

	$report = mysql_query("INSERT INTO duelle_report (`report`, `winner`, `loser`, `winner_level`, `loser_level`, `einsatz`, `schule`) VALUES ('".$kampfbericht."','".$winner_id."','".$looser_id."','".$gewinner['level']."','".$verlierer['level']."', 0, 1)");
	$report_id = mysql_insert_id();

	send_igm('Du hast ein Trainingsduell gewonnen!',
	"du hast ein Duell gegen $verlierer[name] gewonnen.<br /> Der Respekt deine Kameraden ist dir gewiss
	<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=duell&id=$report_id]hier[/url]",
  "$gewinner[name]",'Schulenoberhaupt');

  send_igm('Du hast ein Trainingsduell verloren!',
  "du hast ein Duell gegen $gewinner[name] verloren.<br /> Du musst mehr trainieren wenn du nicht nocheinmal den Spott deines Gegners hören willst.
  <br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=duell&id=$report_id]hier[/url]",
  "$verlierer[name]",'Schulenoberhaupt');

	mysql_delete("duelle_schule","id='$auswertung[id]'");

	if (!empty($_GET["duell"]))
	{
		$aktion = $_GET["duell"];
	}
}

if (!empty($_GET["duell"]))
{
	$aktion = $_GET["duell"];
}

if ($aktion == 'new')
{
	$new_duell = mysql_select("*","duelle_schule","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

	if($new_duell == false)
	{
		$wann = time() + 86400;
		mysql_insert("duelle_schule","duellant_1,duellant_2,wann","'$user[id]','0','$wann'");

		echo"<center>Du hast ein Duell eröffnet.</center><br />";
	}
	else
	{
		echo"<center>Du kannst keine 2 Duelle auf einmal führen!</center><br />";
	}
}elseif ($aktion == 'into' && !empty($_GET["nr"]))
{
	$join_duell = mysql_select("*","duelle_schule","id='$_GET[nr]'",null,"1");

	if($join_duell == true)
	{
		$time = time();

		$duellantid_1 = mysql_select("duellant_1","duelle_schule","id='$join_duell[id]'",null,"1");
		$duellantid_1 = $duellantid_1['duellant_1'];

		mysql_update("duelle_schule","duellant_2='$user[id]',wann='$time'","id='$join_duell[id]'");

		echo"<center>Du trägst dich als Duellpartner ein!</center><br />";
	} else
	{
		echo"<center>Dieses Duell gibt es nicht!</center><br />";
	}
}

echo "<center><u><b>Offene Trainingsduelle</b></u></center><br />";

echo "<table cellpadding=0 cellspacing=0 width=100% border=0 style=\"border-collapse:collapse;\" align=center>
	<thead><tr>
	<th width=170><b>Duellant 1</b></td>
	<th width=20><b>Level</b></td>
	<th><b>Herausforderung</b></td>
	</tr></thead><tfoot>";

$abfrage = "SELECT * FROM duelle_schule WHERE duellant_2='0'";
$select = mysql_query($abfrage);

while ($duelle = mysql_fetch_assoc($select))
{
	$duellant = mysql_select("id,name,level,schule","user","id='$duelle[duellant_1]'",null,"1");

	echo "<tr>
	<td class=border align=center width=170>
	<a href=index.php?site=userinfo&info=$duelle[duellant_1]>$duellant[name]</a></td>
	<td class=border align=center width=20>$duellant[level]</td>
	<td class=border align=center width=170>";

	$player_dueller = mysql_select("*","duelle_schule","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

	if ($player_dueller == false)
	{
		if ($duellant['schule'] != $User['schule'])
		{
			echo"---";
		} else
		{
			echo "<a href=index.php?site=duelle_schule&duell=into&nr=$duelle[id]>annehmen</a>";
		}
	} else
	{
		echo"---";
	}

	echo "</td></tr>";
}

echo "</tfoot></table><br />";

echo "<center><u><b>Feste Duelle</b></u></center><br />";

echo "<table cellpadding=0 cellspacing=0 width=100% border=0 align=center>
	<thead><tr>
	<th width=120><b>Duellant 1</b></td>
	<th width=20><b>Level</b></td>
	<th width=120><b>Duellant 2</b></td>
	<th width=20><b>Level</b></td>
	<th><b>Start in</b></td>
	</tr></thead><tfoot>";

$abfrage = "SELECT * FROM duelle_schule WHERE duellant_1 != '0' AND duellant_2 != '0'";
$select = mysql_query($abfrage);

while ($duelle = mysql_fetch_assoc($select))
{
	$duellant1 = mysql_select("id,name,level","user","id='$duelle[duellant_1]'",null,"1");
	$duellant2 = mysql_select("id,name,level","user","id='$duelle[duellant_2]'",null,"1");

	$duellbeginn = $duelle[wann] + 840 - time();

	$duellbeginn_hour = $duellbeginn/3600;

	$duelbeginn_hour_rest = ($duellbeginn_hour - floor($duellbeginn_hour)) * 3600;

	$duelbeginn_min = $duelbeginn_hour_rest / 60;

	$duelbeginn_min_rest = ($duelbeginn_min - floor($duelbeginn_min)) * 60;

	$duellbeginn = floor($duellbeginn_hour)."h ".round($duelbeginn_min)."m ".$duelbeginn_min_rest."s";


	echo "<tr>
		<td class=border align=center width=120>
		<a href=index.php?site=userinfo&info=$duelle[duellant_1]>$duellant1[name]</a></td>
		<td class=border align=center width=20>$duellant1[level]</td>
		<td class=border align=center width=120>
		<a href=index.php?site=userinfo&info=$duelle[duellant_2]>$duellant2[name]</a></td>
		<td class=border align=center width=20>$duellant2[level]</td>
		<td class=border align=center>$duellbeginn</td>
		</tr>";
}

echo "</tfoot></table><br />";

$duellfrage = mysql_select("*","duelle_schule","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

if ($duellfrage == false)
{
	echo "<center><a href=index.php?site=duelle_schule&duell=new>Neues Duell</a></center>";
}

} else
{
	echo "<center>Du musst zuerst einer Schule beitreten.</center>";
}
?>
