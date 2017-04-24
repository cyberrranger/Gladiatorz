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
	<a href="index.php?site=arena" target="_self">Arena</a> |
	<a href="index.php?site=quests" target="_self">Spezial Quests</a> |
	<a href="index.php?site=tool" target="_self">Werkstatt</a>
	</center>
	<br />';


$Ally = new AllyClass();

/*
 * Berechnen der Angriffsfläche
 */
if($User['arenarang'] >= $User['level'] * 2)
{
	$arenarang = $User['level'] * 2;
}else{
	$arenarang = $User['level'];
}
$arenarang = $User['arenarang'];

$arenarangmin = $arenarang - round($User['level'] / 3);
$arenarangmax = $arenarang + round($User['level'] * 2);

/*
 * Welche Gladiatoren d�rfen angezeigt werden?
 */

$arenaquery = mysql_query("SELECT * FROM ".TAB_USER." WHERE id!='".$User['id']."' AND ( arenarang BETWEEN '".$arenarangmin."' AND '".$arenarangmax."' OR rang='".$User['rang']."' ) ORDER BY arenarang ASC");


/*
 * Ausgabe der Liste der Gladiatoren wenn es gerade keinen Kampf gibt
 */
if(isset($_REQUEST['attack']) && is_numeric($_REQUEST['attack']))
{
    if($User['powmod'] < 0.1)
	{
		echo'<center>Zuwenig PowMod! <a href="?site=arena">weiter</a></center>';
	}
	elseif($User['schlafen'] > time())
	{
		echo'<center>Du kannst nicht kämpfen während du schläfst! <a href="?site=arena">weiter</a></center>';
	}
	elseif($User['id'] == $_REQUEST['attack'])
	{
		echo'<center>Du kannst nicht gegen dich selber kämpfen!<a href="?site=arena">weiter</a></center>';
	}
	elseif(get_user_things($_REQUEST['attack'], 'arenarang') == 0)
	{
		echo'<center>Du darfst nicht gegen jemanden mit Arenarang 0 kämpfen!<a href="?site=arena">weiter</a></center>';
	}
	elseif((get_user_things($_REQUEST['attack'], 'arenarang') < $arenarangmin || get_user_things($_REQUEST['attack'], 'arenarang') > $arenarangmax) && get_user_things($_REQUEST['attack'], 'rang') != $User['rang'])
	{
		echo'<center>Dieser Gladiator ist auserhalb deiner Reichweite (zu stark oder gar zu schwach) leg dich bitte nur mit gleich starken Gladiatoren an.!<a href="?site=arena">weiter</a></center>';
	}
	else //los gehts mit dem Kampf
	{
		include"fight2.php";
		$both = creat_fight($User['id'], $_REQUEST['attack']);
		$fight = make_a_new_fight($both);

		$winner = $fight['winner'];
		$looser = $fight['looser'];
		$fight_msg = $fight['msg'];

		$coolness = $fight['coolness'];

		echo $fight_msg;

		$exp = rand(5,15);

		$user_new = $enemy_new = array();
		$enemy = get_user_things($_REQUEST['attack']);

		if($winner == $User['id']) // User hat gewonnen
		{
			$new_gold = round($coolness*20*0.9);
			$new_exp = round($exp*1.5);

			//EXP Quest auff�llen
			check_quest($User['id'], 'exp', $new_exp);

			$user_new['gold'] = $User['gold'] + $new_gold;
			$user_new['exp'] = $User['exp'] + $new_exp;
			$user_new['pm'] = $User['powmod'] - 0.1;
			$user_new['kraft'] = get_health($User['id']);
			$user_new['coolness'] = $coolness;

			if($user['schule'] != 0)
			{
				$gebet = get_gebet($User['id']);

				if($gebet == 6)
				{
					$user_new['coolness'] = $coolness*20;
					$new_exp = round(($exp*1.5)*1.1);
					$new_gold = round($user_new['coolness']);
					$user_new['gold'] = $User['gold'] + $new_gold;
					$user_new['exp'] = $User['exp'] + $new_exp;
				}
			}

			//Meldung
			echo'
				<br /><center>
				Super '.$User['name'].'! Du hast gewonnen.
				Nach dem Kampf bist du ersch&ouml;pft (-0.1 PM) und hast einen Rangplatz gewonnen.
				Dein mutiger Einsatz im Kampf wird mit '.$new_gold.' Gold und '.$new_exp.' Exp belohnt.
				</center>';

			//Update winner
			$winner_update = mysql_query("UPDATE ".TAB_USER." SET
			powmod='".$user_new['pm']."',arenarang=arenarang+1,gold='".$user_new['gold']."',
			exp='".$user_new['exp']."',lattack='".time()."',ppl_kills_win=ppl_kills_win+1,kraft='".$user_new['kraft']."'
			WHERE id='".$User['id']."' LIMIT 1");

			//Der verlierer bekommt auch etwas
			$new_gold = round($coolness*20*0.3);
			$enemy_new['gold'] = $enemy['gold'] + $new_gold;
			$enemy_new['exp'] =  $enemy['exp'] + round($exp);

			//EXP Quest auff�llen
			check_quest($enemy['id'], 'exp', $exp);

			$enemy_new['kraft'] = 0;

			//Arenarang pr�fen darf nicht kleiner wie 0 werden
			if($enemy['arenarang'] != 0)
			{
				$enemy_new['arenarang'] = $enemy['arenarang'] - 1;
			}else
			{
				$enemy_new['arenarang'] = 0;
			}

			//Update verlierer
			$looser_update = mysql_query("UPDATE ".TAB_USER." SET
			arenarang='".$enemy_new['arenarang']."',gold='".$enemy_new['gold']."',
			exp='".$enemy_new['exp']."',ppl_kills_lost=ppl_kills_lost+1,kraft='".$enemy_new['kraft']."'
			WHERE id='".$enemy['id']."' LIMIT 1");

			//Nachricht an der verlierer senden
			$msg = 'Hallo '.$enemy['name'].',<br /> du hast einen Arenakampf gegen '.$User['name'].' verloren.<br />Trotz deiner schmachvollen Niederlage bekommst du von der Arenaleitung '.$new_gold.' Gold. Au�erdem erh�ht sich deine Erfahrung um '.round($exp).' EXP.<br /> Du bist einen Arenaplatz abgestiegen.';
			sendIGM('Arenakampf verloren!',$msg,$enemy['name'],'Arenaleitung');

			$looser_school = getSchool($enemy['id']);

			//die eigene Schule bekommt MEdaillien
			if($Ally->isWar($looser_school['id']) == true && $User['schule'] != 0)
			{
				$Query = mysql_query("SELECT * FROM ally_schule WHERE id='".$User['schule']."' LIMIT 1");
				$Schule = mysql_fetch_assoc($Query);

				$Schule['medallien']++;
				mysql_query("UPDATE ally_schule Set medallien='".$Schule['medallien']."' WHERE id='".$Schule['id']."'");
			}

		}else //user hat verloren
		{
			$new_gold = round($coolness*20*0.3);
			$new_exp = round($exp);

			//EXP Quest auff�llen
			check_quest($User['id'], 'exp', $new_exp);

			$user_new['gold'] = $User['gold'] + $new_gold;
			$user_new['exp'] = $User['exp'] + $new_exp;
			$user_new['pm'] = $User['powmod'] - 0.1;
			$user_new['kraft'] = 0;
			$user_new['coolness'] = $coolness;

			//Arenarang pr�fen darf nicht kleiner wie 0 werden
			if($user['arenarang'] != 0)
			{
				$user_new['arenarang'] = $user['arenarang'] - 1;
			}else
			{
				$user_new['arenarang'] = 0;
			}

			//Meldung
			echo'
				<br /><center>
				Schade '.$User['name'].'! Du hast verloren.<br />
				Nach dem Kampf bist du ersch&ouml;pft (-0.1 PM) und hast einen Arenaplatz verloren.<br />
				Dein mutiger Einsatz im Kampf wird mit '.$new_gold.' Gold und '.$new_exp.' Exp belohnt.<br />
				</center>';

			//Update
			$looser_update = mysql_query("UPDATE ".TAB_USER." SET
			arenarang='".$user_new['arenarang']."',gold='".$user_new['gold']."',
			powmod='".$user_new['pm']."',
			exp='".$user_new['exp']."',ppl_kills_lost=ppl_kills_lost+1,kraft='".$user_new['kraft']."'
			WHERE id='".$User['id']."' LIMIT 1");

			//Der Gewinner bekommt auch etwas
			$new_gold = round($coolness*20*0.9);
			$new_exp = round($exp * 1.5);
			$enemy_new['gold'] = $enemy['gold'] + $new_gold;
			$enemy_new['exp'] =  $enemy['exp'] + $new_exp;

			//EXP Quest auff�llen
			check_quest($enemy['id'], 'exp', $new_exp);

			$enemy_new['kraft'] = get_health($enemy['id']);
			$enemy_new['arenarang'] = $enemy['arenarang'] + 1;

			//update gewinner
			$winner_update = mysql_query("UPDATE ".TAB_USER." SET
			arenarang='".$enemy_new['arenarang']."',gold='".$enemy_new['gold']."',
			exp='".$enemy_new['exp']."',ppl_kills_win=ppl_kills_win+1,kraft='".$enemy_new['kraft']."'
			WHERE id='".$enemy['id']."' LIMIT 1");

			//Nachricht an der gewinnersenden
			$msg = 'Hallo '.$enemy['name'].',<br /> du hast einen Arenakampf gegen '.$User['name'].' Gewonnen.<br />Dank deines mutigen Einsatzes bekommst du von der Arenaleitung '.$new_gold.' Gold. Au�erdem erh�ht sich deine Erfahrung um '.round($exp).' EXP.<br /> Du bist einen Arenaplatz aufgestiegen.';
			sendIGM('Arenakampf gewonnen!',$msg,$enemy['name'],'Arenaleitung');

			$winner_school = getSchool($enemy['id']);

			//die fremde Schule bekommt MEdaillien
			if($Ally->isWar($winner_school['id']) == true && $enemy['schule'] != 0)
			{
				$Query = mysql_query("SELECT * FROM ally_schule WHERE id='".$enemy['schule']."' LIMIT 1");
				$Schule = mysql_fetch_assoc($Query);

				$Schule['medallien']++;
				mysql_query("UPDATE ally_schule Set medallien='".$Schule['medallien']."' WHERE id='".$Schule['id']."'");
			}

		}

		echo'<br /><center><strong><a href="?site=arena" style="font-size:20px;">zur&uuml;ck</a></strong></center>';

		//People Quest auff�llen
		check_quest($winner, 'people', 1);
	}
}else
{
	echo'
		<center><font style=font-size:14px;><u><strong>Arena:</u></strong></font><br><br>
		<center>
		Willkommen in der <strong>Arena</strong> Gladiator,
		dein derzeitiger Rang ist '.$user['arenarang'].'. <br>Lass es krachen '.$user['name'].', aber Vorsicht hier kann es brutal werden...
		</center><br />';

	echo'
		<table>
			<thead>
				<tr>
					<th width="200">Name</th>
					<th width="20">Rang</th>
					<th width="50">Schule</th>
					<th width="20"></th>
					<th width="60">Angriff</th>
				</tr>
			</thead>
			<tfoot>';

	while($arena = mysql_fetch_assoc($arenaquery))
	{
		//wenn Arenarang kleiner als 5 nicht anzeigen
		if($arena['arenarang'] <= 5)
	  	{
	  		continue;
	  	}

	  	$userinfo = UserInfoPanel($arena['id']);

	    $userschool = getSchool($arena['id']);

		if($Ally->isWar($arena['schule']) == true && $User['schule'] != 0 && $userschool != false)
		{
		  $color = 'darkred';
		}elseif($arena['schule'] == $User['schule'])
		{
		  $color = 'green';
		}else
		{
		  $color = '';
		}
	    echo'
				<tr style="background-color:'.$color.';">
					<td><a href="?site=userinfo&info='.$arena['id'].'">'.$arena['name'].'</a></td>
					<td><a href="?site=userinfo&info='.$arena['id'].'">'.$arena['arenarang'].'</a></td>
					<td><a href="?site=schulen&sub='.$userschool['id'].'info">'.$userschool['kuerzel'].'</a></td>
					<td><a href="#" target="_self" onmouseover="return escape(\''.$userinfo.'\')">(?)</a></td>
					<td><a href="?site=arena&attack='.$arena['id'].'">Angriff!</a></td>
				</tr>';

		flush();

	}

		echo'
			</tfoot>
			</table>
			<br />';
}
?>
