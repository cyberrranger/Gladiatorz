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

echo'
<center style="margin-bottom:5px;">
<a href="index.php?site=gasthaus" target="_self">Schankraum</a> |
<a href="index.php?site=chat" target="_self">Chat</a>
(<a href="javascript:PopUp(\'Sites/Game/chat.php\')" target="_self">im PopUp</a>) |
<a href="index.php?site=schlafraum" target="_self">Schlafraum</a> |
<a href="index.php?site=gluecksspiel" target="_self">Glücksspiel</a>
</center>';

if($user['schule'] != 0)
{
  $T = explode('|',$schule['area']);
  $T = $T[1];

  if($T == 2)
  {
    $AllyBonus = 1;
  }
  elseif($T == 1)
  {
    $AllyBonus = 0.5;
  }
  else
  {
    $AllyBonus = 0;
  }
}
else
{
  $AllyBonus = 0;
}

if(isset($_GET["abbr"]) AND $user[schlafen] > time())
{
  $ausruhen = ceil(($user[schlafen] - time()) / 3600);
  $ausruhen = $ausruhen * (0.5 + $AllyBonus);
  $user[powmod] = $user[powmod] - $ausruhen;

  mysql_update("user","powmod='$user[powmod]',schlafen='0'","name='$user[name]'");

  echo"<center>Du wachst aus deinem tiefen Schlaf auf. Da du nicht mehr Müde bist verlässt du den Schlafraum,
  dein Geld wirst du wohl nicht vom Wirt wiederbekommen. Ausserdem fühlst du dich nicht so ausgeruht, als hättest
  du durchgeschlafen.</center>";

  exit;
}

$DuellSql = "SELECT count(id) FROM duelle WHERE duellant_1 = '$user[id]' OR duellant_2 = '$user[id]' LIMIT 1";
$DuellQuery = mysql_query($DuellSql);
$DuellRows = mysql_fetch_row($DuellQuery);

if(isset($_POST["ausruhen"]) AND $user['schlafen'] < time())
  {
    $ausruhen = substr($_POST["ausruhen"],0,2);
	$ausruhen = trim($ausruhen);

    $user[gold] = $user[gold] - ($ausruhen * 50);
	$user[powmod] = $user[powmod] + ($ausruhen * (0.5 + $AllyBonus));
	$user[schlafen] = time() + ($ausruhen * 3600);

	mysql_update("user","gold='$user[gold]',powmod='$user[powmod]',schlafen='$user[schlafen]'","name='$user[name]'");

    echo"<center>Du ruhst dich ab jetzt $_POST[ausruhen] auf der Matratze aus.</center>";
  }
  elseif(!isset($_POST["ausruhen"]) AND $user[gold] >= 50 AND $user[schlafen] < time())
  {
    echo"<center>Im schlecht eingerichteten Schlafraum des Gasthauses kannst du dir eine Matratze mieten und dich ausruhen.
    Für jede Stunde die du dich ausruhst, steigt dein PowMod um 0.5 Punkte!
	Bedenke, dass die Matratze 50 Gold/Stunde kostet.<br /><br /><font color=\"green\">Allianzbonus: +$AllyBonus PM/h</font></center>";

    echo"<br><center>Wie lange möchtest du dich ausruhen?</center><br>
    <center><form method=post action=index.php?site=schlafraum>
    <select name=ausruhen>";
	$fakedgold = $user[gold];
	if($AllyBonus == 1)
	{
		if($user[powmod] < 4)
		{
			$stash = 4;
		}
		elseif($user[powmod] < 6)
		{
			$stash = 3;
		}
		elseif($user[powmod] < 8)
		{
			$stash = 2;
		}
		elseif($user[powmod] < 10)
		{
			$stash = 1;
		}
		else
		{
			$stash = 0;
		}
	}
	elseif($AllyBonus == 0.5)
	{
		if($user[powmod] < 4.5)
		{
			$stash = 4;
		}
		elseif($user[powmod] < 6)
		{
			$stash = 3;
		}elseif($user[powmod] < 7.5)
		{
			$stash = 2;
		}elseif($user[powmod] < 9.0)
		{
			$stash = 1;
		}
		else
		{
			$stash = 0;
		}	
	}
	else
	{
		if($user[powmod] < 4)
		{
			$stash = 6;
		}
		elseif($user[owmod] < 5)
		{
			$stash = 5;
		}
		elseif($user[powmod] < 6)
		{
			$stash = 4;
		}elseif($user[powmod] < 7)
		{
			$stash = 3;
		}
		elseif($user[powmod] < 8)
		{
			$stash = 2;
		}
		elseif($user[powmod] < 9)
		{
			$stash = 1;
		}
		else
		{
			$stash = 0;
		}
	}
	
	while($fakedgold >= 50 AND $schleife < $stash)
		{
		  $schleife++;
		  $fakedgold = $fakedgold - 50;
		  if($schleife == "1"){$string = "Stunde";}
		  else{$string = "Stunden";}

		  echo"<option>$schleife $string</option>";
		}

	echo"</select><br><br><input type=submit value=Ausruhen></center></form>";
  }
  elseif($user[schlafen] < time())
  {
    echo"<center>Du hast nicht genügend Gold um dir eine Matratze im Schlafraum zu mieten!</center>";
  }
  else
  {
    $sleep_hours = ($user[schlafen] - time()) / 3600;
	$sleep_mins = ($sleep_hours - floor($sleep_hours)) * 3600;
	$sleep_hours = floor($sleep_hours);
	$sleep_mins = floor($sleep_mins / 60);

    echo"<center>Du ruhst dich noch $sleep_hours Stunden und $sleep_mins Minuten aus.<br><br>Wenn du das Schlafen abbrichst verlierst du für jede nicht voll ausgeschlafene Stunde den Bonus des Schlafens.<br><br>
	<a href=index.php?site=schlafraum&abbr=j id=button>Ausruhen abbrechen!</a></center>";
  }

?>
