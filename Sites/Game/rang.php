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
/*
 * NPC erstellen wenn keine vorhanden
 */
$enemyquery = @mysql_query("SELECT * FROM rangnpcs WHERE rang='".$User['rang']."' LIMIT 1");
$enemy = @mysql_fetch_assoc($enemyquery);
if(!$enemy)
{	//es gibt keine NPC in diesem Rang deshalb müssen neue erstellt werden
	for($i=1;$i<=20;$i++)
	{
		$new_npc = array();
		$new_npc['name'] = createName();
		$new_npc['rang'] = $User['rang'];
		$new_npc['place'] = $i;
		$new_npc['off'] = round($User['level'] * 15 * rand(75,125)/100);
		$new_npc['def'] = round($User['level'] * 10 * rand(75,125)/100);
		$new_npc['hp'] = round($User['level'] * 50 * rand(75,125)/100);

		mysql_query("INSERT INTO ".TAB_NPCS." (name,rang,place,off,def,hp) VALUES ('".$new_npc['name']."','".$new_npc['rang']."','".$new_npc['place']."','".$new_npc['off']."','".$new_npc['def']."','".$new_npc['hp']."')");
	}
}
//Rang prüfen
if($User['rankplace'] == 0)
{
	$User['rankplace'] = 21;
}
if($User['rankplace'] > 21)
{
	$User['rankplace'] = 21;
}

if (getRangName($User['level'], true) <= 5 && $User['rankplace'] > 11)
{
	$User['rankplace'] = 11;
}

$enemy_rank = $User['rang'];

/*
 * Ausgabe der Liste der Gladiatoren wenn es gerade keinen Kampf gibt
 */
if(isset($_REQUEST['attack']) && is_numeric($_REQUEST['attack']))
{
	if($User['powmod'] < 0.1)
	{
		echo'<center>Zuwenig PowMod! <a href="?site=rang">weiter</a></center>';
	}
	elseif($User['schlafen'] > time())
	{
		echo'<center>Du kannst nicht kämpfen während du schläfst! <a href="?site=rang">weiter</a></center>';
	}
	elseif($User['rankplace'] == 1)
	{
		echo'<center>Du bist bereits auf Platz 1!<a href="?site=rang">weiter</a></center>';
	}
	else //los gehts mit dem Kampf
	{
		include"fight2.php";

		//wer wird angegriffen?
		$enemy_rankplace = $User['rankplace'] - 1;


		//suchen ob es einen user gibt
		$enemyquery = @mysql_query("SELECT * FROM user WHERE rang='".$enemy_rank."' AND rankplace='".$enemy_rankplace."' LIMIT 1");
		$enemy = @mysql_fetch_assoc($enemyquery);
		if($enemy)
		{
			$both = creat_fight($User['id'], $enemy['id']);
		}elseif(!$enemy)
		{ //es gibt keinen echten gegner
			$enemyquery = @mysql_query("SELECT * FROM rangnpcs WHERE rang='".$enemy_rank."' AND place='".$enemy_rankplace."' LIMIT 1");
			$enemy = @mysql_fetch_assoc($enemyquery);
			$enemytype = 'npc';
			$both = creat_fight($User['id'], $enemy['id'], 'npc');
		}
		$fight = make_a_new_fight($both);

		$winner = $fight['winner'];
		$looser = $fight['looser'];
		$fight_msg = $fight['msg'];

		$coolness = $fight['coolness'];

		echo $fight_msg;

		$user_new = $enemy_new = array();
		$newexpL = $enemy['rang'];
		$newexpW = $User['rang'] * 2;
		if($winner == $User['id']) // User hat gewonnen
		{
			//Meldung
			echo'
				<br /><center>
				Super '.$User['name'].'! Du hast gewonnen.
				Nach dem Kampf bist du erschöpft (-0.1 PM) und hast einen Rangplatz gewonnen.
				Dein mutiger Einsatz im Kampf wird mit 250 Gold und '.$newexpW.' Exp belohnt.
				</center>';

			$user_new['gold'] = $User['gold'] + 250;
			$user_new['exp'] = $User['exp'] + $newexpW;
			$user_new['pm'] = $User['powmod'] - 0.1;
			$user_new['kraft'] = get_health($User['id']);
	  		$user_new['rang_kills_win'] = $User['rang_kills_win']++;
	  		$user_new['rankplace'] = $User['rankplace']-1;

			//Quest auffüllen
			check_quest($winner, 'people', 1);
			check_quest($winner, 'exp', $newexpW);

			//Update winner
			$winner_update = mysql_query("UPDATE ".TAB_USER." SET
			powmod='".$user_new['pm']."',rankplace='".$user_new['rankplace']."',gold='".$user_new['gold']."',
			exp='".$user_new['exp']."',rang_kills_win=rang_kills_win+1,kraft='".$user_new['kraft']."'
			WHERE id='".$User['id']."' LIMIT 1");

			//update verliere
			if($enemytype != 'npc')
			{
				$enemy = get_user_things($looser);
				$enemy_new['gold'] = $enemy['gold'] + 125;
				$enemy_new['exp'] = $enemy['exp'] + 5;

		  		$winner_update = mysql_query("UPDATE ".TAB_USER." SET rankplace=rankplace+1,gold='".$enemy_new['gold']."',
				exp='".$enemy_new['exp']."',rang_kills_lost=rang_kills_lost+1
				WHERE id='".$looser."' LIMIT 1");

		  		//Quest auffüllen
		  		check_quest($looser, 'exp', $newexpL);

		  		//Nachricht an der verlierer senden
				$msg = 'Hallo '.$enemy['name'].',<br /> du hast einen Rangkampf gegen '.$User['name'].' verloren.<br />Trotz deiner schmachvollen Niederlage bekommst du 125 Gold. Außerdem erhöht sich deine Erfahrung um '.$newexpL.' EXP.<br /> Du bist einen Rangplatz abgestiegen.';
				sendIGM('Rangkampf verloren!',$msg,$enemy['name'],'Rangleitung');
			}
		}else //user hat verloren
		{
			//Meldung
			echo'
				<br /><center>
				Schade '.$User['name'].'! Du hast verloren.
				Nach dem Kampf bist du erschöpft (-0.1 PM).
				Dein mutiger Einsatz im Kampf wird mit 125 Gold und '.$newexpL.' Exp belohnt.
				</center>';

			if($enemytype != 'npc')
			{
				$enemy = get_user_things($winner);

		  		$winner_update = mysql_query("UPDATE ".TAB_USER." SET gold=gold+250,
				exp=exp+'".$newexpW."',rang_kills_win=rang_kills_win+1
				WHERE id='".$winner."' LIMIT 1");

		  		//Quest auffüllen
		  		check_quest($winner, 'exp', $newexpW);
		  		check_quest($winner, 'people', 1);

		  		//Nachricht an der verlierer senden
				$msg = 'Hallo '.$enemy['name'].',<br /> du hast einen Rangkampf gegen '.$User['name'].' gewonnen.<br />Dank deines mutigen Einsatzes bekommst du 250 Gold. Außerdem erhöht sich deine Erfahrung um '.$newexpW.' EXP.';
				sendIGM('Rangkampf gewonnen!',$msg,$enemy['name'],'Rangleitung');
			}

			$user_new['pm'] = $User['powmod'] - 0.1;
			$user_new['kraft'] = get_health($User['id']);

			//Quest auffüllen
			check_quest($looser, 'exp', $newexpL);

			//Update verlierer
			$winner_update = mysql_query("UPDATE ".TAB_USER." SET
			powmod='".$user_new['pm']."',gold=gold+125,
			exp=exp+'".$newexpL."',rang_kills_lost=rang_kills_lost+1,kraft='".$user_new['kraft']."'
			WHERE id='".$User['id']."' LIMIT 1");
		}
		echo'<br /><center><strong><a href="?site=rang" style="font-size:20px;">zurück</a></strong></center>';
	}
}else
{
	echo' <center><font style=font-size:14px;><u><strong>Rangkampf-Arena:</u></strong></font><br><br>';
	echo' Willkommen in der Rangkampf-Arena! Dein derzeitiger Rang ist <strong>'.getRangName($User['level']).'</strong>.<br />Für jeden Rang gibt es eine eigene Liga, in der du dich beweisen musst.<br /><br />';

	echo'
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <thead>
      <tr>
        <th width="50">Platz</th>
        <th width="200">Name</th>
        <th width="50">Infos</th>
        <th>Herausfordern</th>
      </tr>
    </thead>
    <tfoot>';

	$countplace = 20;
	if (getRangName($User['level'], true) <= 5)
	{
		$countplace = 10;
	}

	for($i=1;$i<=$countplace;$i++)
	{
		$enemyquery2 = @mysql_query("SELECT * FROM user WHERE rang='".$enemy_rank."' AND rankplace='".$i."' LIMIT 1");
		$enemy = @mysql_fetch_assoc($enemyquery2);

		if(!$enemy)
		{ //es gibt keinen echten gegner
			$enemyquery2 = @mysql_query("SELECT * FROM rangnpcs WHERE rang='".$enemy_rank."' AND place='".$i."' LIMIT 1");
			$enemy = @mysql_fetch_assoc($enemyquery2);
			$name = $enemy['name'];

			$off = get_user_things($enemy['id'], 'off', 'npc');
			$def = get_user_things($enemy['id'], 'def', 'npc');
			$hp = get_health($enemy['id'], 'npc');
			$Text = '<strong>'.$off.' Off<br />'.$def.' Def<br />'.$hp.' Kraft</strong>';
		}else
		{
			$name = '<a href="?site=userinfo&info='.$enemy['id'].'" target="_self">'.$enemy['name'].'</a>';
			$Text = UserInfoPanel($enemy['id']);
		}

		if( $enemy['id'] != $user['id'] && $user['rankplace']-1 == $i && $user['powmod'] != 0)
		{
			$attack = '<a href="?site=rang&attack=1" target="_self">Los gehts!</a>';
		}else
		{
			$attack = '---';
		}
	    echo'
		    <tr>
		      <td width="50">'.$i.'</td>
		      <td width="200">'.$name.'</td>
		      <td width="50"><a href="#" target="_self" onmouseover="return escape(\''.$Text.'\')">(?)</a></td>
		      <td>'.$attack.'</td>
		    </tr>';
	}
	  echo'</tfoot></table>';
}
?>
