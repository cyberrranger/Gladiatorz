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

// BERARBEITEN!!!
error_reporting(E_ALL^E_NOTICE);

$Ally = new AllyClass();

echo '<center>Hier kannst du dich mit anderen Gladiatoren in Duelle messen. <br />

Du kannst allerdings nur Duelle annehmen, wenn dein Gegner maximal 7 Level unter oder 10 Level &uuml;ber dir ist. Ab Level 90 gilt dann: Jeder gegen Jeden.<br /><br />

Für jeden Sieg gibt es 30 EXP plus 1 Medaille für deine Schule, allerdings nur wenn du gegen einen feindlichen Schulenmember gewonnen hast. Bei einer Niederlage bekommst du immerhin noch 15 Exp.</center><br />';

$mintime = time();
$abfrage = "SELECT * FROM duelle WHERE duellant_2 = '0' AND wann <= '$mintime'";
$select = mysql_query($abfrage);
while($kill = mysql_fetch_assoc($select)) // Duelle die keinen 2. Duellpartner haben und älter als 1 T sind löschen
{
	mysql_delete("duelle","id='$kill[id]'");

	$empf_user = get_user_things($kill['duellant_1']);

	$timestamp = time();
	$text = "
	Hallo $empf_user[name],

	leider ist die 24 Stunden Duellfrist verstrichen ohne das sich jemand als Duellpartner für dich eingetragen hat, darum wurde dein Duell geschlossen. Den Einsatz erhälst du natürlich zurück.

	MfG die Duellleitung";

	mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender"
	,"'$empf_user[name]','Duellleitung','Keiner wollte mit dir ein Duell ausfechten!',
	'$text','$timestamp','n','n','n'");

	$empf_user[gold] = $empf_user[gold] + $kill[einsatz];
	mysql_update("user","gold='$empf_user[gold]'","id='$empf_user[id]'");
}

$eventzeit = time();
$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
$Event = @mysql_fetch_assoc($Event);

$mintime = time() - 840;

if($Event['event'] == 1)
{
	$mintime = time() - 300;
}

include"fight2.php";

$abfrage = "SELECT * FROM duelle WHERE duellant_2 != '0' AND wann <= '$mintime'";
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

	$EnSchool = getSchool($verlierer['id']);

	if($Ally->isWar($EnSchool['id']) == true)
	{
	    $Query = mysql_query("SELECT * FROM ally_schule WHERE id='".$gewinner['schule']."' LIMIT 1");
	    $Schule = mysql_fetch_assoc($Query);

		$Schule['medallien']++;
		mysql_query("UPDATE ally_schule Set medallien='".$Schule['medallien']."' WHERE id='".$Schule['id']."'");
	}

	$report = mysql_query("INSERT INTO duelle_report (`report`, `winner`, `loser`, `winner_level`, `loser_level`, `einsatz`) VALUES ('".$kampfbericht."','".$winner_id."','".$looser_id."','".$gewinner['level']."','".$verlierer['level']."','".$auswertung['einsatz']."')");
	$report_id = mysql_insert_id();

	#$Stats = mysql_query("INSERT INTO duell_stats (d1,d2,winner,wonlvl,loslvl,einsatz) VALUES ('".$gewinner['name']."','".$verlierer['name']."','".$gewinner['name']."','".$gewinner['level']."','".$verlierer['level']."','".$auswertung[einsatz]."')");

	$gewinner[gold] = $gewinner[gold] + ($auswertung[einsatz] * 2);
	$gewinner[duell_kills_win] = $gewinner[duell_kills_win] + 1;
	$gewinner[exp] = $gewinner[exp] + 30;
	mysql_update("user","gold='$gewinner[gold]',duell_kills_win='$gewinner[duell_kills_win]',exp='$gewinner[exp]'","id='$gewinner[id]'");

	$verlierer[duell_kills_lost] = $verlierer[duell_kills_lost] + 1;
	$verlierer[exp] = $verlierer[exp] + 15;
	mysql_update("user","duell_kills_lost='$verlierer[duell_kills_lost]',exp='$verlierer[exp]'","id='$verlierer[id]'");

	send_igm('Du hast ein Duell gewonnen!',
	"du hast ein Duell gegen $verlierer[name] gewonnen. Der Einsatz betrug $auswertung[einsatz] Gold, mit deinem Sieg
	konntest du dir den Einsatz deines Gegners sichern und bekommst somit ".($auswertung[einsatz]*2)." Gold.
	<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=duell&id=$report_id]hier[/url]",
	"$gewinner[name]",'Duellleitung');

	send_igm('Du hast ein Duell verloren!',
	"du hast ein Duell gegen $gewinner[name] verloren. Der Einsatz betrug $auswertung[einsatz] Gold, da du verloren hast ist
	auch dein Einsatz futsch.
	<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=duell&id=$report_id]hier[/url]",
	"$verlierer[name]",'Duellleitung');

	#send_igm("Duellbericht","<p id=\"kb4\">$kampfbericht</p>","$gewinner[name]",'Duellleitung');
	#send_igm("Duellbericht","<p id=\"kb4\">$kampfbericht</p>","$verlierer[name]",'Duellleitung');

	mysql_delete("duelle","id='$auswertung[id]'");

	$wettfrage = "SELECT * FROM wetten WHERE duell='$auswertung[id]'";
	$wettselect = mysql_query($wettfrage);
	while($wetten = mysql_fetch_assoc($wettselect))
	{
		$wetter = mysql_select("*","user","id='$wetten[wer]'",null,"1");
		if($wetten[duellant] == $wettwinner) // gewonnen
		{
			$wetter[gold] = $wetter[gold] + ($wetten[wieviel]*2);
			mysql_update("user","gold='$wetter[gold]'","id='$wetter[id]'");

			send_igm('Du hast eine Wette gewonnen!',
			"deine Wette ($wetten[wieviel] Gold) auf $gewinner[name] im Duell $gewinner[name] vs. $verlierer[name] hat sich bewahrheitet, da
			$gewinner[name] gewonnen hat! Du erhälst das doppelte deines Wetteinsatzes.",
			"$wetter[name]",'Duellleitung');
		}
		else // verloren
		{
			send_igm('Du hast dein Wettgeld in den Sand gesetzt!',
			"deine Wette ($wetten[wieviel] Gold) auf $verlierer[name] im Duell $gewinner[name] vs. $verlierer[name] ist nicht aufgegangen, da
			$gewinner[name] gewonnen hat! Vielleicht klappt es ja beim nächsten mal.",
			"$wetter[name]",'Duellleitung');
		}
		mysql_delete("wetten","id='$wetten[id]'");
	}
}

// Wenn ein neues Duell eröffnet wird oder jmd. einem Duell Beitritt

if(!empty($_GET["duell"])){$aktion=$_GET["duell"];}

if($aktion == 'new')
{
	$new_duell = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

	if($new_duell == false)
	{
		echo"<center>
		<form method=post name=duell_einsatz action=index.php?site=duelle&duell=set_einsatz>
		<i>Einsatz:</i> <input type=text name=einsatz>&nbsp;<input type=submit value=setzen>
		</form></center><br />";
	}
	else
	{
		echo"<center>Du nimmst bereits an einem anderen Duell teil! Du kannst immer nur
		an einem Duell gleichzeitig teilnehmen.</center><br />";
	}
}
elseif($aktion == 'new_turnier')
{
	$new_duell = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");
	$TurnierGegnerTab = mysql_select("*","TurnierGegner","Gegner_1='$user[id]' OR Gegner_2='$user[id]'",null,"1");

	if($TurnierGegnerTab['Gegner_1'] == $User['id'])
	{
		$TurnierGegner = $TurnierGegnerTab['Gegner_2'];
	}
	else
	{
		$TurnierGegner = $TurnierGegnerTab['Gegner_1'];
	}
	$einstatz=0;
	$wann = time() + 86400;

	if($new_duell == false)
	{
		mysql_insert("duelle","duellant_1,duellant_2,einsatz,wann,turnier","'$user[id]','0','$einsatz','$wann','$TurnierGegner'");
		echo"Du hast dich für die nächste Runde des Turniers erfolgreich eingeschrieben.";
	}
	else
	{
		echo"<center>Du nimmst bereits an einem anderen Duell teil! Du kannst immer nur
      an einem Duell gleichzeitig teilnehmen.</center><br />";
	}
}
elseif($aktion == 'set_einsatz')
{
	$einsatz = $_POST["einsatz"];
	$new_duell = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

	if($new_duell == false)
	{
		if($einsatz >= 500)
		{
			if($user[gold] >= $einsatz)
			{
				$wann = time() + 86400;
				mysql_insert("duelle","duellant_1,duellant_2,einsatz,wann","'$user[id]','0','$einsatz','$wann'");

				$user[gold] = $user[gold] - $einsatz;
				mysql_update("user","gold='$user[gold]'","id='$user[id]'");

				echo"<center>Du hast ein Duell eröffnet, wenn sich innerhalb von 24 Stunden niemand mit dir duellieren will,
				wird das Duell zu den Akten gelegt.</center><br />";
			}
			else
			{
				echo"<center>Du hast nicht genügend Gold um den Einsatz für das Duell zu bezahlen!</center><br />";
			}
		}
		else
		{
			echo"<center>Du musst mindestens 500 Gold auf den Kampf setzen!</center><br />";
		}
	}
	else
	{
		echo"<center>Du kannst keine 2 Duelle auf einmal führen!</center><br />";
	}
}
elseif($aktion == 'into' AND !empty($_GET["nr"]))
{
	$join_duell = mysql_select("*","duelle","id='$_GET[nr]'",null,"1");

	if($join_duell == true)
	{
		if($join_duell['turnier'] > 0) //Schauen ob das Duell für ein Turnier bstimmt ist
		{
			$Duellant = $join_duell['duellant_1'];

			$Sql = "SELECT * FROM TurnierGegner WHERE Gegner_1 = '$Duellant' AND Gegner_2 = '$User[id]' OR Gegner_2 = '$Duellant' AND Gegner_1 = '$User[id]' LIMIT 1";
			$Query = mysql_query($Sql);
			$Fight = mysql_fetch_assoc($Query);

			$bla = time() + 60;

			if($Fight == true)
			{
				echo"Du hast deinen Kampf im Turnier erfolgreich angenommen.";
				mysql_update("duelle","duellant_2='$user[id]',wann='$bla'","id='$join_duell[id]'");
			}
			else
			{
				echo"Dieses Duell ist ein Turnier-Duell und kann nur von den derzeitigen Turnier-Gegner des Erstellers angenommen werden";
			}
		}
		elseif($user[gold] >= $join_duell[einsatz])
		{
			$user[gold] = $user[gold] - $join_duell[einsatz];
			$time = time();

			//Levelabfrage

			//Die ID und Level des 1. Duellanten aus der Datenbank abfragen
			$duellantid_1 = mysql_select("duellant_1","duelle","id='$join_duell[id]'",null,"1");
			$duellantid_1 = $duellantid_1['duellant_1'];

			$duellant_1_level = mysql_select("*","user","id='$duellantid_1'",null,"1");
			$duellant_1_level = $duellant_1_level['level'];

			//Min und MAxlevel bestimmen
			$eventzeit = time();
			$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
			$Event = @mysql_fetch_assoc($Event);

			if($Event['event'] == 3)
			{
				mysql_update("user","gold='$user[gold]'","id='$user[id]'");
				mysql_update("duelle","duellant_2='$user[id]',wann='$time'","id='$join_duell[id]'");

				echo"<center>Du bezahlst den Einsatz und schreibst dich als Duellpartner ein!</center><br />";
			}
			else
			{
				// Wenn der 1. duellant ein level über 90 hat dann wird die abfrage umgangen

				if($duellant_1_level<90)
				{
					//Level Vergleichen

					if($eigenes_level>$maxlevel OR $eigenes_level<$minlevel)
					{
						if($eigenes_level>$maxlevel)
						{
							echo "<center>Du darfst, dieses Duell nicht annehmen, da dein Level zu hoch ist !</center><br />";
						}
						else
						{
							echo "<center>Du darfst, dieses Duell nicht annehmen, da dein Level zu niedrig ist !</center><br />";
						}
					}
					else
					{
						mysql_update("user","gold='$user[gold]'","id='$user[id]'");
						mysql_update("duelle","duellant_2='$user[id]',wann='$time'","id='$join_duell[id]'");

						echo"<center>Du bezahlst den Einsatz und schreibst dich als Duellpartner ein!</center><br />";
					}
				}
				else
				{
					if ($eigenes_level<90)
					{
						echo"<center><strong>Willst du Selbstmord begehen ?</strong></br> Der Gegner is doch viel zu stark für dich</center></br>";
						mysql_update("user","gold='$user[gold]'","id='$user[id]'");
						mysql_update("duelle","duellant_2='$user[id]',wann='$time'","id='$join_duell[id]'");

						echo"<center>Du bezahlst den Einsatz und schreibst dich als Duellpartner ein!</center><br />";
					}
					else
					{
						mysql_update("user","gold='$user[gold]'","id='$user[id]'");
						mysql_update("duelle","duellant_2='$user[id]',wann='$time'","id='$join_duell[id]'");

						echo"<center>Du bezahlst den Einsatz und schreibst dich als Duellpartner ein!</center><br />";
					}
				}
			}
		}
		else
		{
			echo"<center>Du hast nicht genügend Einsatzgeld um dem Duell beizutreten!</center><br />";
		}
	}
	else
	{
		echo"<center>Dieses Duell gibt es nicht!</center><br />";
	}

}
// Wenn ein User wetten möchte

if(!empty($_GET["wetten"]))
{
	$wette = $_GET["wetten"];
	$duell = substr($wette,2);
	$duellant = substr($wette,0,2);

	$gibts_duell = mysql_select("*","duelle","id='$duell'",null,"1");

	if($duellant == 'd1' OR $duellant == 'd2' AND $duell != '')
	{
		$wette_alt = mysql_select("*","wetten","duell='$duell' AND wer='$user[id]'",null,"1");

		if($wette_alt == false AND !isset($_POST["wett_einsatz"]) AND $gibts_duell != false)
		{
			echo"<center>
      <form method=post name=wette_einsatz action=index.php?site=duelle&wetten=$wette>
      <i>Wetteinsatz:</i> <input type=text name=wett_einsatz>&nbsp;<input type=submit value=setzen>
      </form></center><br />";
		}
		elseif($wette_alt == false AND !empty($_POST["wett_einsatz"]) AND $gibts_duell != false)
		{
			if($user[gold] >= $_POST["wett_einsatz"] AND $_POST["wett_einsatz"] <= 10000 AND is_numeric($_POST["wett_einsatz"]) && $_POST["wett_einsatz"] >= 1)
			{
				$duell = mysql_select("*","duelle","id='$duell'",null,"1");

				if($user[name] != $duell[duellant_1] AND $user[name] != $duell[duellant_2])
				{
					mysql_insert("wetten","wer,wieviel,duell,duellant","'$user[id]','$_POST[wett_einsatz]','$duell[id]','$duellant'");

					$user[gold] = $user[gold] - $_POST["wett_einsatz"];
					mysql_update("user","gold='$user[gold]'","id='$user[id]'");

					$duell_abfr = mysql_select("*","duelle","id='$duell[id]'",null,"1");
					$duellstring = "duellant_".substr($duellant,1,1);

					$duellant_1 = mysql_select("name,id","user","id='$duell_abfr[duellant_1]'",null,"1");
					$duellant_2 = mysql_select("name,id","user","id='$duell_abfr[duellant_2]'",null,"1");
					$favorit = mysql_select("name,id","user","id='$duell_abfr[$duellstring]'",null,"1");

					echo"<center>Du hast $_POST[wett_einsatz] Gold auf <b>$favorit[name]</b> im
	    Kampf <b>$duellant_1[name] gegen $duellant_2[name]</b> gesetzt!</center><br />";
				}
				else
				{
					echo"<center>Du kannst nicht auf ein Duell setzen in dem du mitkmpfst!</center><br />";
				}
			}
			elseif($_POST["wett_einsatz"] > 10000)
			{
				echo"<center>Du darfst nicht mehr als 10000 Goldstücke verwetten!</center><br />";
			}
			elseif($user[gold] < $_POST["wett_einsatz"])
			{
				echo"<center>Du hast nicht genügend Gold um diese Summe zu verwetten!</center><br />";
			}
		}
		elseif($gibts_duell == false)
		{
			echo"<center>Dieses Duell gibt es nicht!</center><br />";
		}
		else
		{
			echo"<center>Du kannst immer nur einmal auf einen Kampf setzen!</center><br />";
		}
	}
}

// Ausgabe der Kämpfe/Wetten...

echo"<center><u><b>Offene Duelle</b></u></center><br />";

echo"<table cellpadding=0 cellspacing=0 width=100% border=0 style=\"border-collapse:collapse;\" align=center>
<thead><tr>
<th width=170><b>Duellant 1</b></td>
<th width=20><b>Level</b></td>
<th width=80><b>Einsatz</b></td>
<th><b>Herausforderung</b></td>
</tr></thead><tfoot>";

$abfrage = "SELECT * FROM duelle WHERE duellant_2='0'";
$select = mysql_query($abfrage);
while($duelle = mysql_fetch_assoc($select))
{
	$duellant = mysql_select("id,name,level","user","id='$duelle[duellant_1]'",null,"1");

	echo"<tr>
<td class=border align=center width=170>
<a href=index.php?site=userinfo&info=$duelle[duellant_1]>$duellant[name]</a></td>
<td class=border align=center width=20>$duellant[level]</td>
<td class=border align=center width=100>$duelle[einsatz]</td>
<td class=border align=center width=170>";

	$player_dueller = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

	if($player_dueller == false)
	{
		$eventzeit = time();
		$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
		$Event = @mysql_fetch_assoc($Event);

		if($Event['event'] == 3)
		{
			$minlevel = $duellant['level'] - 150;
			$maxlevel = $duellant['level'] + 150;
		}
		else
		{
			$minlevel = $duellant['level'] - 10;
			$maxlevel = $duellant['level'] + 7;
		}

		if ($duellant['level']>=90 AND $user['level']>=90){
			echo"
		<a href=index.php?site=duelle&duell=into&nr=$duelle[id]>annehmen</a>";
		}
		elseif($duelle['turnier'] >=1)
		{
			echo"<a href=index.php?site=duelle&duell=into&nr=$duelle[id]>annehmen (Turnier)</a>";
		}
		else
		{
			if ($user['level']>$maxlevel OR $user['level']<$minlevel) {
				echo "---";
			}
			else
			{
				if ($duellant['level']>=90 AND $user['level']<90)
				{
					echo "<a href=index.php?site=duelle&duell=into&nr=$duelle[id]>annehmen</a>";
				}
				else
				{
					echo"
					<a href=index.php?site=duelle&duell=into&nr=$duelle[id]>annehmen</a>";
				}
			}
		}
	}
	else
	{
		echo"---";
	}

	echo"</td>
</tr>";
}

echo"</tfoot></table><br />";

echo"<center><u><b>Feste Duelle</b></u></center><br />";

echo"<table cellpadding=0 cellspacing=0 width=100% border=0 align=center>
<thead><tr>
<th width=120><b>Duellant 1</b></td>
<th width=20><b>Level</b></td>
<th width=100><b>Wetten</b></td>
<th width=120><b>Duellant 2</b></td>
<th width=20><b>Level</b></td>
<th><b>Start in</b></td>
</tr></thead><tfoot>";

$abfrage = "SELECT * FROM duelle WHERE duellant_1 != '0' AND duellant_2 != '0'";
$select = @mysql_query($abfrage);
while($duelle = @mysql_fetch_assoc($select))
{
	$duellant1 = @mysql_select("id,name,level","user","id='$duelle[duellant_1]'",null,"1");
	$duellant2 = @mysql_select("id,name,level","user","id='$duelle[duellant_2]'",null,"1");

	$wette_abfr = @mysql_select("*","wetten","duell='$duelle[id]' AND wer='$user[id]'",null,"1");

	if($user[id] == $duelle[duellant_1] OR $user[id] == $duelle[duellant_2])
	{
		$wettstring = "---";
	}
	elseif($wette_abfr != false)
	{
		$wettstring = "$wette_abfr[wieviel] G";

		if($wette_abfr[duellant] == "d1")
		{
			$wettstring = "<b style=\"font-size:11px;\">&laquo;</b> ".$wettstring;
		}
		else
		{
			$wettstring = $wettstring." <b style=\"font-size:11px;\">&raquo;</b>";
		}
	}
	else
	{
		$wettstring = "<a href=index.php?site=duelle&wetten=d1$duelle[id]>&laquo;&laquo;</a>
&nbsp;
<a href=index.php?site=duelle&wetten=d2$duelle[id]>&raquo;&raquo;</a>";
	}

	$duellbeginn = $duelle[wann] + 840 - time();
	if($Event['event'] == 1)
	{
		$duellbeginn = $duelle[wann] + 300 - time();
	}

	$duellbeginn_hour = $duellbeginn/3600;

	$duelbeginn_hour_rest = ($duellbeginn_hour - floor($duellbeginn_hour)) * 3600;

	$duelbeginn_min = $duelbeginn_hour_rest / 60;

	$duelbeginn_min_rest = ($duelbeginn_min - floor($duelbeginn_min)) * 60;

	$duellbeginn = floor($duellbeginn_hour)."h ".round($duelbeginn_min)."m ".$duelbeginn_min_rest."s";


	echo"<tr>
	<td class=border align=center width=120>
	<a href=index.php?site=userinfo&info=$duelle[duellant_1]>$duellant1[name]</a></td>
	<td class=border align=center width=20>$duellant1[level]</td>
	<td class=border align=center width=100>$wettstring</td>
	<td class=border align=center width=120>
	<a href=index.php?site=userinfo&info=$duelle[duellant_2]>$duellant2[name]</a></td>
	<td class=border align=center width=20>$duellant2[level]</td>
	<td class=border align=center>$duellbeginn</td>
	</tr>";
}

echo"</tfoot></table><br />";

$duellfrage = @mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

if($duellfrage == false)
{
	$Sql = "SELECT * FROM TurnierGegner WHERE Gegner_2 = '$User[id]' OR Gegner_1 = '$User[id]' LIMIT 1";
	$Query = @mysql_query($Sql);
	$Annehmen = @mysql_fetch_assoc($Query);

	echo"<center><a href=index.php?site=duelle&duell=new>Neues Duell</a></center>";

	if($Annehmen == true)
	{
	echo"<center><a href=index.php?site=duelle&duell=new_turnier>Zum Turnier eintragen</a></center>"; //Link zum eintragen eines Turnierkampfes
	}
}

?>
