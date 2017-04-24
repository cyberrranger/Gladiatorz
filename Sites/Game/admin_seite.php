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
if($user['status'] != 'user')
{
	echo'<center><h2>Admin-Men&uuml;</h2><br /><br /></center>';

	echo'<b>PHPMyAdmin:</b> <a target="_blank" href="'.$GLOBALS['conf']['konst']['url'].'/phpmyadmin">&Ouml;ffnen (neue Seite)</a><br /><br />';

	echo'<b>!!!Neue Sitterseite:</b> <a target="_blank" href="index.php?site=sitting">&Ouml;ffnen (neue Seite)</a><br /><br />';

	echo'<b>Neue Multiseite:</b> <a target="_blank" href="index.php?site=multiverdacht">&Ouml;ffnen (neue Seite)</a><br /><br />';

	echo'<b>Die IP-Seite:</b> <a href="?site=admin_seite&do=ips">&Ouml;ffnen</a><br /><br />';

	if($_GET['do'] == 'ips')
	{
		echo'<HR NOSHADE><br />';

		echo'<center><form name="sort">
		<input type="hidden" name="site" value="admin_seite&do=ips" /><select name="sortby"><option value="ip">IP-Adresse</option><option value="user">Username</option><option value="Datum">Datum/Uhrzeit</option></select>
		<select name="updown"><option value="ASC">Aufsteigend</option><option value="DESC">Absteigend</option></select><input type="submit" value="Sortieren">
		</form></center>';

		$sortby = isset($_GET["sortby"])?$_GET["sortby"]:"ip";
		$updown = isset($_GET["updown"])?$_GET["updown"]:"ASC";
		$dbanfrage = "SELECT * from ips ORDER BY ".$sortby." ".$updown;
		$result = mysql_query($dbanfrage) or die(mysql_error());

		echo'<table border=1>
		<tr>
		<th>ID</th>
		<th>Username</th>
		<th>IP-Adresse</th>
		<th>Datum</th>
		</tr>';

		$i = 0;
		while ($ausgabe = mysql_fetch_array ($result))
		{
			$color = "000000";

			$dbanfrage2 = "SELECT * FROM ips WHERE user ='".$ausgabe["user"]."' AND ip='".$ausgabe["ip"]."' AND id!='".$ausgabe["id"]."'";
			$result2 = mysql_query($dbanfrage2) or die(mysql_error());
			while ($ausgabe2 = mysql_fetch_array ($result2))
			{
				if($ausgabe['ip'] == $ausgabe2['ip']) $color = "0000FF";
			}

			$dbanfrage3 = "SELECT * FROM ips WHERE user != '".$ausgabe["user"]."'";
			$result3 = mysql_query($dbanfrage3) or die(mysql_error());
			while ($ausgabe3 = mysql_fetch_array ($result3))
			{
				if($ausgabe['ip'] == $ausgabe3['ip']) $color = "FF0000";
			}

			$i=$i+1;

			echo'
			<tr>
			<td style="color: #'.$color.';">'.$i.'</td>
			<td style="color: #'.$color.';">'.$ausgabe['user'].'</td>
			<td style="color: #'.$color.';">'.$ausgabe['ip'].'</td>
			<td style="color: #'.$color.';">'.$ausgabe['Datum'].'</td>
			</tr>';
		}

		echo'</table><br/>
		<font color="#000000">Schwarz: </font> Kein Multi<br/>
		<font color="#0000FF">Blau: </font> gleicher User<br/>
		<font color="#FF0000">Rot: </font> gleiche IP';

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>Die Erweiterte Userinfo:</b> <a href="?site=admin_seite&do=userinfo">&Ouml;ffnen</a><br /><br />';

	if($_GET['do'] == 'userinfo')
	{
		echo'<HR NOSHADE><br />';

		if(!empty($_POST["suchen"]))
		{
			$suche = strtolower($_POST["suchen"]);
			$links = array();
			$finds = 0;

			$sql = "SELECT * FROM user WHERE name LIKE '%$suche%' OR id = '%$suche%'";
			$query = mysql_query($sql);

			while($ergebniss = mysql_fetch_assoc($query))
			{
				$links[$ergebniss[id]] = $ergebniss[name];
				$finds = $finds + 1;
			}

			if($finds == 0)
			{
				echo"<center>Es wurde kein entsprechender User gefunden.</center>";
			}
			else
			{
				echo"<center><b>Suchergebnisse:</b></center><br /><div style=\"width:250px;text-align:left;margin-left:160px;\">";

				foreach($links AS $key => $value)
				{
					echo"<li><a href=index.php?site=admin_seite&do=userinfo&info=$key>$value</a></li>";
				}

				echo"</div>";
			}
		}
		else
		{
			echo"<center><form name=such method=post action=index.php?site=admin_seite&do=userinfo>
			<input name=suchen type=text>&nbsp;<input type=submit value=Suchen></form></center>";
		}

		if(isset($_GET['info']))
		{
			$ID = $_GET['info'];

			$InfoSql = "SELECT * FROM ".TABUSER." WHERE id='$ID' LIMIT 1";
			$InfoQuery = mysql_query($InfoSql);
			$Info = mysql_fetch_assoc($InfoQuery);

			$InfoSql2 = "SELECT * FROM moves WHERE uid='$ID' LIMIT 1";
			$InfoQuery2 = mysql_query($InfoSql2);
			$Moves = mysql_fetch_assoc($InfoQuery2);

			$text = 'Suche nach '.$Info['name'];
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','userinfo','$text',NOW())";
			mysql_query($sql) OR die(mysql_error());

			echo'<table><tr><td>';

			echo'<br /><br /><table>
			<tr><th colspan="2">Allgemein</th></tr><tr>
			<td>ID:</td><td>'.$Info['id'].'</td>
			</tr><tr>
			<td>Name:</td><td>'.$Info['name'].'</td>
			</tr><tr>
			<td>Gold:</td><td>'.$Info['gold'].'</td>
			</tr><tr>
			<td>Kraft:</td><td>'.$Info['kraft'].'</td>
			</tr><tr><th colspan="2">Eigenschaften</th></tr><tr>
			<td>St�rke:</td><td>'.$Info['staerke'].'</td>
			</tr><tr>
			<td>Geschick:</td><td>'.$Info['geschick'].'</td>
			</tr><tr>
			<td>Kondition:</td><td>'.$Info['kondition'].'</td>
			</tr><tr>
			<td>Intelligenz:</td><td>'.$Info['inteligenz'].'</td>
			</tr><tr>
			<td>Charisma:</td><td>'.$Info['charisma'].'</td>
			</tr><tr><th colspan="2">Fertigkeiten</th></tr><tr>
			<td>Waffenkunde:</td><td>'.$Info['waffenkunde'].'</td>
			</tr><tr>
			<td>2-Waffenkampf:</td><td>'.$Info['zweiwaffenkampf'].'</td>
			</tr><tr>
			<td>Taktik:</td><td>'.$Info['taktik'].'</td>
			</tr><tr>
			<td>Heilkunde:</td><td>'.$Info['heilkunde'].'</td>
			</tr><tr>
			<td>Ausweichen:</td><td>'.$Info['ausweichen'].'</td>
			</tr><tr>
			<td>Schildkunde:</td><td>'.$Info['schildkunde'].'</td>
			</tr><tr><th colspan="2">Untereigenschaften</th></tr><tr>
			<td>Schlagkraft:</td><td>'.$Info['Schlagkraft'].'</td>
			</tr><tr>
			<td>Einstecken:</td><td>'.$Info['Einstecken'].'</td>
			</tr><tr>
			<td>Kraftprotz:</td><td>'.$Info['Kraftprotz'].'</td>
			</tr><tr>
			<td>Gl�ck:</td><td>'.$Info['Glueck'].'</td>
			</tr><tr>
			<td>Sammler:</td><td>'.$Info['Sammler'].'</td>
			</tr><tr><th colspan="2">Rest</th></tr><tr>
			<td>PM:</td><td>'.$Info['powmod'].'</td>
			</tr><tr>
			<td>PM-Gespart:</td><td>'.$Info['pmspar'].'</td>
			</tr><tr>
			<td>Rang:</td><td>'.getRangName($Info['level']).'</td>
			</tr><tr>
			<td>Arenarang:</td><td>'.$Info['arenarang'].'</td>
			</tr><tr>
			<td>Level:</td><td>'.$Info['level'].'</td>
			</tr><tr>
			<td>EXP:</td><td>'.$Info['exp'].'</td>
			</tr><tr>
			<td>Titel:</td><td>'.$Info['titel'].'</td>
			</tr>
			<td>Zuletzt online:</td><td>'.date('d.m.y H:i:s', $Info['lonline']).'</td>
			</tr>
			</table>';



			echo'</td><td>';

			echo'<br /><br /><table>
			<tr><th colspan="2">Off-Moves</th></tr><tr>
			<td>Kraftschlag:</td><td>'.$Moves['kraftschlag'].'</td>
			</tr><tr>
			<td>kritischer Schlag:</td><td>'.$Moves['kritischer_schlag'].'</td>
			</tr><tr>
			<td>Wuchtschlag:</td><td>'.$Moves['wundhieb'].'</td>
			</tr><tr>
			<td>Verletzen:</td><td>'.$Moves['koerperteil_abschlagen'].'</td>
			</tr><tr>
			<td>Todesschalg:</td><td>'.$Moves['todesschlag'].'</td>
			</tr><tr><th colspan="2">Support-Moves</th></tr><tr>
			<td>Wutschrei:</td><td>'.$Moves['wutschrei'].'</td>
			</tr><tr>
			<td>Kraftschrei:</td><td>'.$Moves['kraftschrei'].'</td>
			</tr><tr>
			<td>Sand werfen:</td><td>'.$Moves['sand_werfen'].'</td>
			</tr><tr><th colspan="2">Def-Moves</th></tr><tr>
			<td>Schutz:</td><td>'.$Moves['armor'].'</td>
			</tr><tr>
			<td>T&auml;uschen:</td><td>'.$Moves['taeuschen'].'</td>
			</tr><tr>
			<td>Berserker:</td><td>'.$Moves['berserker'].'</td>
			</tr><tr>
			<td>Block:</td><td>'.$Moves['block'].'</td>
			</tr><tr>
			<td>Ausweichen:</td><td>'.$Moves['ausweichen'].'</td>
			</tr><tr>
			<td>Konter:</td><td>'.$Moves['konter'].'</td>
			</tr>
			</table>';

			echo'</td></tr></table>';
		}
		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>User l&ouml;schen:</b> <a href="?site=admin_seite&do=user_loeschen">l&ouml;schen</a><br /><br />';

	if($_GET['do'] == 'user_loeschen')
	{
		echo'<HR NOSHADE><br />';

		echo'
		<strong>User l&ouml;schen:</strong><br /><br />
		<form name="loeschen" method="post" action="?site=admin_seite&do=user_loeschen">
		Username:<br />
		<input name="Username" /><br />
		User-ID:<br />
		<input name="User_id" /><br />
		<input type="submit" value="l&ouml;schen" />
		</form>';

		if(isset($_POST['Username']))
		{
			if($_POST['Username'] == 'donnergott88')
			{
				echo'Du willst mich l&ouml;schen??? Das h&auml;ttest du wohl gern. Einen Gott kann man nicht l&ouml;schen^^';
				exit;
			}
			if($_POST['Username'] == 'cyberrranger')
			{
				echo'Einen Sysop darf man nicht l&ouml;schern ;)';
				exit;
			}
			if($_POST['Username'] == 'LAURIN')
			{
				echo'Ich wei�, die versuchung ist verdammt gro�, aber den will ich selber l&ouml;schen xD';
				exit;
			}
			else
			{
				if(isset($_POST['User_id']))
				{
					$queryLoeschen = "SELECT * FROM user WHERE id = '".$_POST['User_id']."'";
					$resultLoeschen = mysql_query($queryLoeschen) or die(mysql_error());
					$Loeschen = mysql_fetch_array($resultLoeschen);

					$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','user_l&ouml;schen','$Loeschen[name]',NOW())";
					mysql_query($sql) OR die(mysql_error());

					if($Loeschen['name'] == $_POST['Username'])
					{
						$LoeschenMoves = mysql_query("DELETE FROM moves WHERE id = '".$_POST['User_id']."'");
						$LoeschenSettings = mysql_query("DELETE FROM settings WHERE user_id = id = '".$_POST['User_id']."'");
						$LoeschenQuest = mysql_query("DELETE FROM quests WHERE uid = '".$_POST['User_id']."'");
						$LoeschenIP = mysql_query("DELETE FROM ips WHERE user = '".$_POST['Username']."'");
						$LoeschenInventar = mysql_query("DELETE FROM ausruestung WHERE user_id = '".$_POST['User_id']."' AND ort = 'inventar'");
						$LoeschenAusruestung = mysql_query("DELETE FROM ausruestung WHERE user_id = '".$_POST['User_id']."' AND ort = 'user'");
						$LoeschenUser = mysql_query("DELETE FROM user WHERE id = '".$_POST['User_id']."'");

						if($LoeschenMoves != false) { echo'<br />Moves gel&ouml;scht'; }
						if($LoeschenSettings != false) { echo'<br />Settings gel&ouml;scht'; }
						if($LoeschenQuest != false) { echo'<br />Quest gel&ouml;scht'; }
						if($LoeschenIP != false) { echo'<br />IP gel&ouml;scht'; }
						if($LoeschenInventar != false) { echo'<br />Inventar gel&ouml;scht'; }
						if($LoeschenAusruestung != false) { echo'<br />Ausr�stung gel&ouml;scht'; }
						if($LoeschenUser != false) { echo'<br />User gel&ouml;scht'; }
						if($LoeschenMoves != false AND $LoeschenSettings != false AND $LoeschenQuest != false AND $LoeschenIP != false AND $LoeschenInventar != false AND $LoeschenAusruestung != false AND $LoeschenUser != false)
						{
							echo'<br /><br /><b>Alle Eintr�ge von '.$_POST['Username'].' in der Datenbank wurden erfolgreich gel&ouml;scht</b>';
						}
					}
					else
					{
						echo'Die User-ID stimmt nicht mit dem Username �berein!';
					}
				}
			}
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>User sperren:</b> <a href="?site=admin_seite&do=user_sperren">sperren</a><br /><br />';

	if($_GET['do'] == 'user_sperren')
	{
		echo'<HR NOSHADE><br />';

		if(isset($_POST['sperre']))
		{
			$sperre =0;
			$sperren = substr($_POST['sperre'],0,2);

			if ($sperren == 1)
			{
				$sperre = time() + 60*60*24;
			}
			elseif ($sperren == 2)
			{
				$sperre = time() + 60*60*48;
			}
			elseif ($sperren == 3)
			{
				$sperre = time() + 60*60*72;
			}
			elseif ($sperren == 4)
			{
				$sperre = time() + 60*60*96;
			}
			elseif ($sperren == 5)
			{
				$sperre = time() + 60*60*120;
			}
			elseif ($sperren == 6)
			{
				$sperre = time() + 60*60*144;
			}
			elseif ($sperren == 7)
			{
				$sperre = time() + 60*60*168;
			}

			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','user_sperren','$_POST[Username_sperre]',NOW())";
			mysql_query($sql) OR die(mysql_error());

			$UpdateSql = "UPDATE user Set bann='$sperre' WHERE name='$_POST[Username_sperre]' LIMIT 1";
			$UpdateQuery = mysql_query($UpdateSql);
			echo'User erfolgreich gesperrt';
		}

		echo '<br />
        <form name=sperren method=post action=index.php?site=admin_seite&do=user_sperren>
		Username: <br />
		<input name="Username_sperre" /><br /><br />
		Zeitraum: <br /><select name=sperre>
        <option>1 Tag</option>
        <option>2 Tage</option>
        <option>3 Tage</option>
		<option>4 Tage</option>
		<option>5 Tage</option>
		<option>6 Tage</option>
		<option>7 Tage</option>
		</select> &nbsp; &nbsp; &nbsp; <input type=submit value=sperren> <br /></form>';

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>Event-Seite: </b> <a href="?site=admin_seite&do=event">&Ouml;ffnen</a><br /><br />';

	if($_GET['do'] == 'event')
	{
		echo'<HR NOSHADE><br />';

		$eventzeit = time() - 172800;
		$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
		$Event = @mysql_fetch_assoc($Event);

		if($Event == true AND $Event['dauer'] >= time())
		{
			echo'<b>Es l&auml;uft bereits ein Event</b>';
		}
		elseif($Event == true)
		{
			echo'<b>Es fand in den letzten 48h bereits ein Event statt</b>';
		}
		else
		{
			echo'<b>Duellzeit auf 5 Minuten reduzieren: </b> <a href="?site=admin_seite&do=event1">aktivieren</a><br /><br />';

			echo'<b>Duellgewinn verdoppeln: </b> <a href="?site=admin_seite&do=event2">aktivieren</a><br /><br />';

			echo'<b>Levelbeschr&auml;nkung bei Duellen aufheben: </b> <a href="?site=admin_seite&do=event&do=event3">aktivieren</a><br /><br />';

			echo'<b>Arenarangbeschr&auml;nkung in der Arena deaktivieren: </b> <a href="?site=admin_seite&do=event&do=event4">aktivieren</a><br /><br />';

			echo'<b>Doppeltes Prestige bei Prestigk&auml;mpfen: </b> <a href="?site=admin_seite&do=event&do=event5">aktivieren</a><br /><br />';

			echo'<b>1 pm/h f&uuml;r jeden: </b> <a href="?site=admin_seite&do=event&do=event7">aktivieren</a><br /><br />';

			echo'<b>20 Punkte Todesschlag f&uuml;r jeden: </b> <a href="?site=admin_seite&do=event&do=event8">aktivieren</a><br /><br />';

			echo'<b>Droprate um 10% erh&ouml;hen: </b> <a href="?site=admin_seite&do=event&do=event9">aktivieren</a><br /><br />';

			echo'<b>Rangdroprate um 10% erh&ouml;hen: </b> <a href="?site=admin_seite&do=event&do=event10">aktivieren</a><br /><br />';
		}
		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event1')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer1 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('1','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>Duellzeit wurde f&uuml;r '.$Dauer1.' Stunden auf 5 Minuten reduziert.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','duellzeit',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event2')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(21600,43200);
		$Dauer1 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('2','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>Der Duellgewinn wird in den n&auml;chsten '.$Dauer2.' Stunden doppelt so hoch ausfallen.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','duellgewinn',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event3')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer3 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('3','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>Die Levelbeschr�nkung wird f&uuml;r die n&auml;chsten '.$Dauer3.' Stunden au�er Kraft gesetzt.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','duelllevel',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event4')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer4 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('4','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>Die Arenarangbeschr�nkung wird f&uuml;r die n&auml;chsten '.$Dauer4.' Stunden au�er Kraft gesetzt.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','arenarang',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event5')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = 86400;
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('5','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>f&uuml;r 24h gibt es doppelt so viel Prestige.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','prestige',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event7')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer7 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('7','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>f&uuml;r die n&auml;chsten '.$Dauer7.' Stunden gibt es 1 pm/h.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','powmod',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event8')
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer8 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('8','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>Im PvP wurde der Todesschlag f&uuml;r die n&auml;chsten '.$Dauer8.' Stunden f&uuml;r jeden auf 20 Punkte gesetzt.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','todesschlag',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event9') //fertig
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer9 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('9','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>In den n&auml;chsten '.$Dauer9.' Stunden ist die Dropwahrscheinlichkeit um 10% erh&ouml;ht.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','drops',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	if($_GET['do'] == 'event10') //fertig
	{
		echo'<HR NOSHADE><br />';

		$Dauer = rand(43200,86400);
		$Dauer10 = round($Dauer / 3600);
		$event=mysql_query("INSERT INTO event (event,dauer) VALUES ('10','".($Dauer + time())."')");
		if($event!=false)
		{
			echo'<b>In den n&auml;chsten '.$Dauer10.' Stunden ist die Rangdropwahrscheinlichkeit um 10% erh&ouml;ht.</b>';
			$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','event','RK drops',NOW())";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			echo'es gab leider einen Fehler';
		}

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>Rundmail an alle:</b> <a href="?site=admin_seite&do=rundmail">schreiben</a><br /><br />';

	if($_GET['do'] == 'rundmail')
	{
		echo'<HR NOSHADE><br />';

		function IMGrund($absender,$empfaenger,$titel,$text)
		{
		  $timestamp = time();

		  mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender"
		  ,"'$empfaenger','$absender','$titel',
		  '$text','$timestamp','n','n','n'");
		}

		if(isset($_POST["text"]))
		{
			$titel = strip_tags($_POST["titel"]);
			$text = nl2br(strip_tags($_POST["text"]));

			if($titel != "" AND $text != "")
			{
				$Query = mysql_query("SELECT * FROM user");
				while($empf = mysql_fetch_assoc($Query))
				{
					IMGrund('Spielleitung',$empf['name'],$titel,$text);
				}
			}
			else
			{
				$message = "Bitte alle Felder ausf�llen!";
			}
		}

		echo'<form action="index.php?site=admin_seite&do=rundmail" name="mail" method="post">';
		echo'<div style="margin-left:60px;"><br /><b>'.$message.'</b><br />&Uuml;berschrift:<br /><input name="titel" type="text" style="width:400px;font-family:Verdana;font-size:11px;border:1px solid darkgray;padding:2px;" /><br />';

		echo'<br />Nachricht:<br /><textarea name="text" style="width:400px;height:180px;border:1px solid darkgray;padding:2px;font-family:Verdana;font-size:11px;" scrolling=auto wrapping=auto></textarea><br />
		<input type="submit" value="Absenden" /><br /></div></form>';

		echo'<br /><br /><HR NOSHADE><br /><br />';
	}

	echo'<b>Neuen Tip einf&uuml;gen:</b> <a href="?site=admin_seite&do=tipps">einf&uuml;gen</a><br /><br />';

	if($_GET['do'] == 'tipps')
	{
		echo'<HR NOSHADE><br />';

		echo'<strong>Tip eintragen:</strong><br /><br />
		<form name="tipps" method="post" action="?site=admin_seite&do=tipps">
		Tip:<br />
		<input name="Tip" /><br />
		Autor:<br />
		<input name="Autor" /><br />
		<input type="submit" value="Einf&uuml;gen" />
		</form>';

		if(isset($_POST['Tip']))
		{
			$tip=mysql_query("INSERT INTO tipps (tipp,autor) VALUES ('".$_POST['Tip']."','".$_POST['Autor']."')");

			if($tip!=false)
			{
				echo'<br /><br />Eingef&uuml;gt :-)';
			}
		}
		echo'<br /><br /><HR NOSHADE><br /><br />';
	}
	echo'<b>Neues Turnier starten:</b> <a href="?site=admin_seite&do=Turnier">starten</a><br /><br />';


	if($_GET['do'] == 'Turnier')
	{
		echo'<HR NOSHADE><br />';

		if(isset($_POST['makeTurnier']))
		{
			$turnier=mysql_query("INSERT INTO turnier (`startgold`, `teilnehmer`, `minlvl`, `maxlvl`, `date`) VALUES ('".$_POST['startgold']."','".$_POST['teilnehmer']."','".$_POST['minlvl']."','".$_POST['maxlvl']."','".$_POST['startdatum']."')");
			if($turnier!=false)
			{
				echo'<br /><br />Turnier Eingef&uuml;gt :-)';
			}
		} else
		{
			echo '<strong>Turnier anlegen:</strong><br /><br />';
			echo '<form name="Turnier" method="post" action="?site=admin_seite&do=Turnier">';
			echo "<input type='hidden' name='makeTurnier' value='1'/>Startdatum:<br /><input type='text' name='startdatum' value='2010-11-17 21:00:00'/><br />";
			echo "Startgeb�hr:<br /><input type='text' name='startgold' value='50000'/><br />";
			echo "Anzahl der Teilnehmer (<b>8</b>,16,32): <br /><input type='hidden' name='teilnehmer' value='8'/><br />";
			echo "mindest Level: <br /><input type='text' name='minlvl' value='50' /><br />";
			echo "maximales Level: <br /><input type='text' name='maxlvl' value='100' /><br />";
			echo '<input type="submit" value="Einf&uuml;gen" />';
			echo "</form>";
		}
		echo'<br /><br /><HR NOSHADE><br /><br />';

	}

	echo'<b>Neue Umfrage starten:</b> <a href="?site=admin_seite&do=umfrage">starten</a><br /><br />';

	if($_GET['do'] == 'umfrage')
	{
		echo'<HR NOSHADE><br />';

		$jetzt = time();
		$Zeit = @mysql_query("SELECT * FROM Umfrage WHERE dauer >= '".$jetzt."' LIMIT 1");
		$Zeit = @mysql_fetch_assoc($Event);

		If($Zeit['zeit'] <= $jetzt)
		{
			echo'Es l&auml;uft schon eine Umfrage, bitte warte bis diese zuende ist';
		}
		else
		{
			echo'<strong>Umfrage anlegen:</strong><br /><br />
			<form name="Umfrage" method="post" action="?site=admin_seite&do=umfrage">
			Anzahl der Antwortsm�glichkeiten (2 bis 5):<br />
			<input name="Antworten" /><br />
			Frage:<br />
			<input name="Frage" /><br />
			Antwort 1 (Pflicht):<br />
			<input name="Antwort1" /><br />
			Antwort 2 (Pflicht):<br />
			<input name="Antwort2" /><br />
			Antwort 3:<br />
			<input name="Antwort3" /><br />
			Antwort 4:<br />
			<input name="Antwort4" /><br />
			Antwort 5:<br />
			<input name="Antwort5" /><br />
			Endzeit (Timestamp):<br />
			<input name="Endzeit" /><br />
			<input type="submit" value="Einf&uuml;gen" />
			</form>';

			if(isset($_POST['Frage']) AND isset($_POST['Antwort1']) AND isset($_POST['Antwort2']) AND isset($_POST['Antworten']) AND isset($_POST['Endzeit']))
			{
				$umfrage=mysql_query("INSERT INTO Umfrage (frage,antwort1,antwort2,antwort3,antwort4,antwort5,zeit) VALUES ('".$_POST['Frage']."','".$_POST['Antwort1']."','".$_POST['Antwort2']."','".$_POST['Antwort3']."','".$_POST['Antwort4']."','".$_POST['Antwort5']."','".$_POST['Endzeit']."')");
				$reset=mysql_query("UPDATE user SET contest = 0");

				if($umfrage!=false AND $reset!=false)
				{
					echo'<br /><br />Eingef&uuml;gt :-)';
				}
			}
		}
		echo'<br /><br /><HR NOSHADE><br /><br />';
	}
}
else
{
	echo'Du hast hier nichts verloren. Solltest du nochmals auf eine Admin-Seite zugreifen wollen wirst du gesperrt';
	$sql = "INSERT INTO Admin_log (name,seite,info,time) VALUES ('$user[name]','Admin-Seite','Unerlaubter Zutritt',NOW())";
	mysql_query($sql) OR die(mysql_error());
}

?>
