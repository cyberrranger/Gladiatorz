<script language="javascript" type="text/javascript">
<!--
function kbdrop(nr)
{
	var xid = nr;

	if (document.getElementById(xid).style.display != 'none')
	{
		document.getElementById(xid).style.display = 'none';
	} else {
		document.getElementById(xid).style.display = 'block';
	}
}
//-->
</script>

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
 * Beide Gegner erstellen
 */
function creat_fight($user_id_1, $user_id_2, $type = '')
{
	//var_dump('creat_fight');
	$both = array();
	/*
	 * Kämpfer 1
	 */
	$both['user1'] = array();
	$both['user1']['id'] = $user_id_1;
	$both['user1']['name'] = get_user_things($both['user1']['id'], 'name');
	$both['user1']['kraft'] = get_health($both['user1']['id']);
	$both['user1']['kraftorg'] = $both['user1']['kraft'];
	$both['user1']['moves'] = get_moves($both['user1']['id']);
	$both['user1'] = make_body($both['user1']);
	$both['user1']['spezial_time'] = 0;
	$both['user1']['geschick'] = get_user_things($both['user1']['id'],'geschick');
	$both['user1']['inteligenz'] = get_user_things($both['user1']['id'],'inteligenz');
	
	/*
	 * Kämpfer 2
	 */
	$both['user2'] = array();
	$both['user2']['id'] = $user_id_2;
	$both['user2']['name'] = get_user_things($both['user2']['id'], 'name', $type);
	$both['user2']['kraft'] = get_health($both['user2']['id'], $type);
	$both['user2']['kraftorg'] = $both['user2']['kraft'];

	if($type == 'npc')
	{	//kleiner workaround von der Basarleitung die SM klauen
		$both['user2']['moves'] = get_moves(4);
		$both['user2']['type'] = 'npc';
		$both['user2']['geschick'] = 0;
		$both['user2']['inteligenz'] = 0;

	}elseif($type == 'animal')
	{
		$both['user2']['moves'] = get_moves($user_id_2, 'animal');;
		$both['user2']['type'] = 'animal';
		$both['user2']['geschick'] = 0;
		$both['user2']['inteligenz'] = 0;

	}else
	{
		$both['user2']['moves'] = get_moves($both['user2']['id'], $type);
		$both['user2']['geschick'] = get_user_things($both['user2']['id'],'geschick');
		$both['user2']['inteligenz'] = get_user_things($both['user2']['id'],'inteligenz');
	}

	$both['user2'] = make_body($both['user2'], $type);
	$both['user2']['spezial_time'] = 0;

	return $both;
}

/*
 * Benötigte Funktionen
 */
function make_body($user, $type = '')
{
	//var_dump('make_body');
	$user['kopf'] = 1;
	if($type == 'animal')
	{
		$user['koerperteile'] = 4;
	}else
	{
		$user['bein'] = 2;
		$user['hand'] = 2;
	}

	return $user;
}

/*
 * Mit was angreifen?
 */
function random_what_attack($both, $who, $ad = 0)
{
	//var_dump('random_what_attack');

	if($both[$who]['type'] == 'npc')
	{
		//als workaround Basarleitung benutzten
		$id = 4;
	}elseif($both[$who]['type'] == 'animal')
	{
		$off = get_attack($both[$who]['id'],'animal');

		if($ad == 1)
		{
			$both[$who]['make_attack_adamage'] = $off;
		}else
		{
			$both[$who]['make_damage'] = $off;
		}

		return $both;
	}else
	{
		$id = $both[$who]['id'];
	}

	$other_who = other_user($who);

	if($both[$other_who]['type'] == 'animal')
	{
		$what_item = 1;
	}else
	{
		$what_item = mt_rand(1,16);
		if ($what_item > 8 && $what_item < 13)
		{
			$what_item = 2;
		} elseif ($what_item > 12)
		{
			$what_item = 1;
		}
	}

	switch ($what_item) {
  		case 1:
			$off_item = get_attack($id,'people', 'weapon_zhw');
  			$off = $off_item;
  			break;
  		case 2:
  			$off_item = get_attack($id,'people', 'weapon');
			$off = $off_item;
  			break;
  		case 3:
  			$off_item = get_attack($id,'people', 'shield');
			$off = $off_item;
  			break;
  		case 4:
  			$off_item = get_attack($id,'people', 'head');
			$off = $off_item;
  			break;
  		case 5:
  			$off_item = get_attack($id,'people', 'shoulder');
			$off = $off_item;
  			break;
  		case 6:
  			$off_item = get_attack($id,'people', 'cape');
			$off = $off_item;
  			break;
  		case 7:
  			$off_item = get_attack($id,'people', 'gloves');
			$off = $off_item;
  			break;
  		case 8:
  			$off_item = get_attack($id,'people', 'foots');
			$off = $off_item;
  			break;
	}
	if($ad == 1)
	{
		$both[$who]['make_attack_adamage'] = $off;
	}else
	{
		$both[$who]['make_damage'] = $off;
	}

	$both[$who]['item'] = $what_item;

	return $both;
}
/*
 * Mit was verteidigen?
 */
function random_what_defend($both, $who, $ad = 0)
{
	//var_dump('random_what_defend');

	if($both[$who]['type'] == 'npc')
	{
		//als workaround Basarleitung benutzten
		$id = 4;
	}elseif($both[$who]['type'] == 'animal')
	{
		$deff = get_armor($both[$who]['id'],'animal');

		if($ad == 1)
		{
			$both[$who]['make_attack_adamage'] = $deff;
		}else
		{
			$both[$who]['make_damage'] = $deff;
		}

		return $both;
	}else
	{
		$id = $both[$who]['id'];
	}

	$other_who = other_user($who);

	if($both[$other_who]['type'] == 'animal')
	{
		$what_item = 1;
	}else
	{
		$what_item = mt_rand(1,17);
		if($what_item < 12 AND $what_item > 8)
		{
			$what_item = 1;
		} elseif($what_item > 11)
		{
			$what_item = 5;
		}
	}

	switch ($what_item) {
  		case 1:
  			$deff = get_armor($id,'people', 'shield');
  			break;
  		case 2:
  			$deff = get_armor($id,'people', 'head');
  			break;
  		case 3:
  			$deff = get_armor($id,'people', 'shoulder');
  			break;
  		case 4:
  			$deff = get_armor($id,'people', 'armor');
  			break;
  		case 5:
  			$deff = get_armor($id,'people', 'lowbody');
  			break;
  		case 6:
  			$deff = get_armor($id,'people', 'cape');
  			break;
  		case 7:
  			$deff = get_armor($id,'people', 'belt');
  			break;
  		case 8:
  			$deff = get_armor($id,'people', 'gloves');
  			break;
  		case 9:
  			$deff = get_armor($id,'people', 'foots');
  			break;
	}

	if ($ad == 1)
	{
		$both[$who]['make_defend_adamage'] = $deff;
	} else
	{
		$both[$who]['make_defend'] = $deff;
	}

	$both[$who]['item'] = $what_item;

	return $both;
}
/*
 * Runde ausführen
 */
function a_round($r,$both)
{
	//var_dump('a_round');
	$both['coolness'] = $both['coolness'] + rand(1,3);
	$both = a_round_fight($both, 'user1');

	if($both['user2']['kraft'] != 0)
	{
		$both = a_round_fight($both, 'user2');
	}
	$both['msg'] = '';

	//nach dem eine runde um ist die rundenzähler -1
	if($both['user1']['spezial_time'] != 0)
	{
		$both['user1']['spezial_time']--;
		if ($both['user1']['spezial_time'] == 0) {
			$both['user1']['spezial_damage'] = 0;
		}
	}
	if($both['user2']['spezial_time'] != 0)
	{
		$both['user2']['spezial_time']--;
		if ($both['user2']['spezial_time'] == 0) {
			$both['user3']['spezial_damage'] = 0;
		}
	}
	return $both;
}

/*
 * Gegner tauschen
 */
function other_user($user)
{
	//var_dump('other_user');
	if($user == 'user1')
	{
		$user = 'user2';
		return $user;
	}
	if($user == 'user2')
	{
		$user = 'user1';
		return $user;
	}

}
/*
 * Kämpfen
 */
function a_round_fight($both, $who)
{
	//var_dump('a_round_fight');
	//Kampf ausführen
	$both = make_damage($both, $who);

	//einen Text ausgeben
	$both= make_msg($both, $who);
	return $both;
}

/*
 * HAt der verteidiger eventuell einen deff move?
 */
function check_deff_move($both, $who)
{
	//var_dump('check_deff_move');
	$other_who = other_user($who);

	$make_this_move = make_move($both, $who);

	if($make_this_move != '')
	{
		$both['coolness'] = $both['coolness'] + 2;
	}

	if($make_this_move == 'kraftschlag')
	{
		//hier gibt es nur sachen für den Angreifer
	}elseif($make_this_move == 'wutschrei')
	{
		$both[$who]['make_defend'] = round($both[$who]['make_defend'] * 1.2);
		$both = get_sm_time($both, $who, $make_this_move);
		$both[$who]['move_deff_msg'] = make_msg_deff_move('wutschrei');

	}elseif($make_this_move == 'armor')
	{
		$both[$other_who]['make_damage'] = $both[$other_who]['make_damage'] / 2;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('armor');

	}elseif($make_this_move == 'kritischer_schlag')
	{
		//hier gibt es nur sachen für den Angreifer

	}elseif($make_this_move == 'wundhieb')//wuchtschlag
	{
		//hier gibt es nur sachen für den Angreifer

	}elseif($make_this_move == 'kraftschrei')
	{
		$extra_kraft = rand(10,15)/10;
		$both[$who]['kraft'] = round($both[$who]['kraft'] * $extra_kraft);
		$both[$who]['move_deff_msg'] = make_msg_deff_move('kraftschrei');

	}elseif($make_this_move == 'block')
	{
		$both[$other_who]['make_damage'] = $both[$other_who]['make_damage'] / 4;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('block');

	}elseif($make_this_move == 'taeuschen')
	{
		$both[$other_who]['make_damage'] = $both[$other_who]['make_damage'] / 4;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('taeuschen');

		//ADamage
		$both = random_what_attack($both, $who, 1);
		$both = random_what_defend($both, $other_who, 1);

	}elseif($make_this_move == 'koerperteil_abschlagen')
	{
		//hier gibt es nur sachen für den Angreifer

	}elseif($make_this_move == 'sand_werfen')
	{
		$both[$other_who]['make_damage'] = 0;
		$both = get_sm_time($both, $who, 'sand_werfen');
		$both[$who]['move_deff_msg'] = make_msg_deff_move($make_this_move);
		$both[$who]['move_deff_msg'] .= make_msg_top_sm($make_this_move, $both[$who]['spezial_time']);

	}elseif($make_this_move == 'ausweichen')
	{
		$both[$other_who]['make_damage'] = 0;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('ausweichen');

	}elseif($make_this_move == 'todesschlag')
	{
		//hier gibt es nur sachen für den Angreifer

	}elseif($make_this_move == 'konter')
	{
		$both[$other_who]['make_damage'] = $both[$other_who]['make_damage'] / 4;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('konter');

		//ADamage
		$both = random_what_attack($both, $who, 1);
		$both = random_what_defend($both, $other_who, 1);

		$both[$who]['make_attack_adamage'] = $both[$who]['make_attack_adamage'] * 2;

	}elseif($make_this_move == 'berserker')
	{
		$both[$other_who]['make_damage'] = $both[$other_who]['make_damage'] * 2;
		$both[$who]['move_deff_msg'] = make_msg_deff_move('berserker');

		//ADamage
		$both = random_what_attack($both, $who, 1);
		$both = random_what_defend($both, $other_who, 1);

		$both[$who]['make_attack_adamage'] = $both[$who]['make_attack_adamage'] * 5;

	}elseif($make_this_move == 'anti_def')
	{
		//hier gibt es nur sachen für den Angreifer
	}

	//die körperliche verfassung mit einberechnen
	$body_check = check_body_full($both, $who);

	$both[$who]['make_defend'] = round($both[$who]['make_defend'] * $body_check);
	return $both;
}

/*
 * Hat der angreifer eventuell einen off move?
 */
function check_off_move($both, $who)
{
	$other_who = other_user($who);
	//var_dump('check_off_move');
	// wenn geblendet bin gibt es keinen off move
	if($both[$who]['spezial'] == 'sand_werfen' && $both[$who]['spezial_time'] != 0)
	{
		$both[$who]['move_off_msg'] .= make_msg_top_sm_round('sand_werfen', $both[$who]['spezial_time']);
		return $both;
	}
	// wenn ich blute gibt es keinen move
	if($both[$who]['spezial'] == 'wundhieb' && $both[$who]['spezial_time'] != 0)
	{
		$both[$who]['move_off_msg'] .= make_msg_top_sm_round('wundhieb', $both[$who]['spezial_time'], $both[$other_who]['spezial_damage']);
		return $both;
	}
	// wenn ich einen wutschrei habe gibt es keinen move
	if($both[$who]['spezial'] == 'wutschrei' && $both[$who]['spezial_time'] != 0)
	{
		$both[$who]['move_off_msg'] .= make_msg_top_sm_round('wutschrei', $both[$who]['spezial_time']);
		return $both;
	}

	$make_this_move = make_move($both, $who);

	if($make_this_move != '')
	{
		$both['coolness'] = $both['coolness'] + 2;
	}

	if($make_this_move == 'kraftschlag')
	{
		$both[$who]['make_damage'] = $both[$who]['make_damage'] * 1.5;
		$both[$who]['move_off_msg'] = make_msg_off_move('kraftschlag');

	}elseif($make_this_move == 'wutschrei')
	{
		$both[$who]['make_damage'] = round($both[$who]['make_damage'] * 1.2);
		$both = get_sm_time($both, $who, $make_this_move);
		$both[$who]['move_off_msg'] = make_msg_off_move($make_this_move);
		$both[$who]['move_off_msg'] .= make_msg_top_sm($make_this_move, $both[$who]['spezial_time']);

	}elseif($make_this_move == 'armor')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'kritischer_schlag')
	{
		$both[$who]['make_damage'] = ($both[$who]['make_damage'] * 1.5) + $both[$who]['make_damage'];
		$both[$who]['move_off_msg'] = make_msg_off_move('kritischer_schlag');

	}elseif($make_this_move == 'wundhieb')//wuchtschlag
	{
		$both[$who]['make_damage'] = $both[$who]['make_damage'] * 2;
		$both = get_sm_time($both, $other_who, 'wundhieb');
		$both[$who]['spezial_damage'] = round($both[$who]['make_damage'] * (rand(100,200) / 1000));
		$both[$who]['move_off_msg'] = make_msg_off_move('wundhieb');
		$both[$who]['move_off_msg'] .= make_msg_top_sm('wundhieb', $both[$other_who]['spezial_time'], $both[$who]['spezial_damage']);

	}elseif($make_this_move == 'kraftschrei')
	{
		$extra_kraft = rand(10,15)/10;
		$both[$who]['kraft'] = round($both[$who]['kraft'] * $extra_kraft);
		$both[$who]['move_off_msg'] = make_msg_off_move('kraftschrei');

	}elseif($make_this_move == 'block')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'taeuschen')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'koerperteil_abschlagen')
	{
		$both[$who]['make_damage'] = $both[$who]['make_damage'] * 3;
		if($both[$other_who]['type'] == 'animal')
		{
			if($both[$other_who]['koerperteile'] > 2)
			{
				$both[$other_who]['koerperteile'] = $both[$other_who]['koerperteile'] - 1;
				$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen');
			}else
			{
				$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen').", jetzt hat das Tier zu wenig Gliedmaßen.";
				$both[$other_who]['kraft'] = 0;
			}
		}else
		{
			$what = rand(1,2);
			if($rand == 1)
			{
				if($both[$other_who]['bein'] == 2)
				{
					//bein ab
					$both[$other_who]['bein'] = $both[$other_who]['bein'] - 1;
					$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen_bein');
				}else
				{
					$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen_bein').", jetzt hat der Gegner keine Beine mehr.";
					$both[$other_who]['kraft'] = 0;
				}


			}else
			{
				if($both[$other_who]['hand'] == 2)
				{
					//hand ab
					$both[$other_who]['hand'] = $both[$other_who]['hand'] - 1;
					$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen_hand');
				}else
				{
					$both[$who]['move_off_msg'] = make_msg_off_move('koerperteil_abschlagen_hand').", jetzt hat der Gegner keine Hände mehr.";
					$both[$other_who]['kraft'] = 0;
				}

			}
		}
	}elseif($make_this_move == 'sand_werfen')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'ausweichen')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'todesschlag')
	{
		$both[$other_who]['kopf'] = 0;
		$both[$other_who]['kraft'] = 0;
		$both[$who]['move_off_msg'] = make_msg_off_move('todesschlag');

	}elseif($make_this_move == 'konter')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'berserker')
	{
		//hier gibt es nur sachen für den Verteider

	}elseif($make_this_move == 'anti_def')
	{
		$both[$other_who]['make_defend'] = 0;
		$both[$who]['move_off_msg'] = make_msg_off_move('anti_def');
	}

	//die körperliche verfassung mit einberechnen
	$body_check = check_body_full($both, $who);

	$both[$who]['make_damage'] = round($both[$who]['make_damage'] * $body_check);

	return $both;
}


/*
 * Bei manchen SM gibt es eine runden länge
 */
function get_sm_time($both, $who, $sm)
{
	//var_dump('get_sm_time');
	//blenden,wutschrei und bluten
	//bei max den skill inteligenz beachten
	$other_who = other_user($who);
	
	$max = 1 + round($both[$other_who]['inteligenz'] / 10);
	$spezial_time = rand(1,$max);
	$both[$who]['spezial'] = $sm;
	$both[$who]['spezial_time'] = $spezial_time;

	$both['coolness'] = $both['coolness'] + $spezial_time;

	return $both;
}


/*
 * Prüfen ob body noch ganz ist ;)
 */
function check_body($both, $who)
{
	//var_dump('check_body');
	if($both[$who]['type'] == 'animal')
	{
		if($both[$who]['kopf'] == 0)
		{
			$both[$who]['kraft'] = 0;
		}elseif($both[$who]['koerperteile'] == 2)
		{
			$both[$who]['kraft'] = 0;
		}
	}else
	{
		if($both[$who]['kopf'] == 0)
		{
			$both[$who]['kraft'] = 0;
		}elseif($both[$who]['hand'] == 0)
		{
			$both[$who]['kraft'] = 0;
		}elseif($both[$who]['bein'] == 0)
		{
			$both[$who]['kraft'] = 0;
		}
	}
	return $both;
}


/*
 * Prüfen wie viel koerperteile es noch gibt ;)
 */
function check_body_full($both, $who)
{
	//var_dump('check_body_full');
	if($both[$who]['type'] == 'animal')
	{
		$count = $both[$who]['koerperteile'];
	}else
	{
		$count = $both[$who]['hand'] + $both[$who]['bein'];
	}

	if($count == 4)
	{
		$return = 0.8;
	}elseif($count == 3)
	{
		$return = 0.6;
	}

	return $return;
}
/*
 * Funktion um tmp einträge im array zurückzusetzen
 */

function reset_user($both, $who)
{
	//var_dump('reset_user');
	$both[$who]['make_damage'] = 0;
	$both[$who]['make_defend'] = 0;
	$both[$who]['move_off_msg'] = '';
	$both[$who]['move_deff_msg'] = '';
	$both[$who]['make_attack_adamage'] = 0;
	$both[$who]['make_defend_adamage'] = 0;

	return $both;
}

/*
 * Gemachter Schaden abziehen
 */
function make_damage($both, $who)
{
	//var_dump('make_damage');
	$other_who = other_user($who);

	$both = reset_user($both, $other_who);
	$both = reset_user($both, $who);

	//ausrüstung herausfinden
	$both = random_what_attack($both, $who);
	$both = random_what_defend($both, $other_who);

	//auf einen move prüfen
	$both = check_off_move($both, $who);
	$both = check_deff_move($both, $other_who);

	//werte voneinander abziehen
	$both[$other_who]['damage'] = $both[$other_who]['make_defend'] - $both[$who]['make_damage'];
	$both[$other_who]['damage'] = check_damage($both[$other_who]['damage']);

	//blute mein gegner?

	if($both[$who]['spezial_damage'] != 0)
	{
		$both[$other_who]['kraft'] = $both[$other_who]['kraft'] - $both[$who]['spezial_damage'];
		$both[$other_who]['kraft'] = check_kraft($both[$other_who]['kraft']);
	}
	//oha der verteidiger schlägt zurück
	if($both[$other_who]['make_attack_adamage'] && $both[$who]['make_defend_adamage'])
	{
		//werte voneinander abziehen
		$both[$who]['damage'] = $both[$who]['make_defend_adamage'] - $both[$other_who]['make_attack_adamage'];
		$both[$who]['damage'] = check_damage($both[$who]['damage']);

		//neue Kraft berechen nach abzug des Schadens
		$both[$who]['kraft'] = $both[$who]['kraft'] - $both[$who]['damage'];
		$both[$who]['kraft'] = check_kraft($both[$who]['kraft']);
	}

	//neue Kraft berechen nach abzug des Schadens
	$both[$other_who]['kraft'] = $both[$other_who]['kraft'] - $both[$other_who]['damage'];

	//Kraft prüfen (nicht kleiner als 0)
	$both[$other_who]['kraft'] = check_kraft($both[$other_who]['kraft']);


	return $both;
}


/*
 * Funktion zum prüfen des gemachten Schadens (min. 1)
 */
function check_damage($damage)
{
	//var_dump('check_damage');
	if($damage >= 0)
	{
		$damage = 1;
	}else
	{ //da dort eine minuszahl als ergebniss kommt muss diese ins plus
		$damage = abs($damage);
	}

	return $damage;
}

/*
 * Funktion zum prüfen der aktuellen Kraft
 */
function check_kraft($health)
{
	//var_dump('check_kraft');
	if($health < 0)
	{
		$health = 0;
	}
	return $health;
}


/*
 * Gibt es ein SM?
 */
function make_move($both, $who)
{
	//var_dump('make_move');
	//welcher move kommt nun?
	$smcount = count($both[$who]['moves']);
	if ($smcount == 0) {
		return "";
	}
	
	$what_move = mt_rand(1,$smcount);
	
	$count = 0;
	foreach($both[$who]['moves'] as $mov => $sm)
	{
		$count++;
		if($count == $what_move)
		{
			$this_movie[$mov] = $sm;
			break;
		}
	}
	
	//Wahrscheinlichkeiten für die SpezialMoves
	$maxrand = 8 + round($both[$who]['geschick'] / 3);
	if($mov  == 'kraftschlag')
	{
		$check = 111 - $sm;
	}elseif($mov  == 'wutschrei')
	{
		$check = 111 - $sm;
	}elseif($mov  == 'armor')
	{
		$check = 111 - $sm;
	}elseif($mov  == 'kritischer_schlag')
	{
		$check = 121 - $sm;
	}elseif($mov  == 'wundhieb')//wuchtschlag
	{
		$check = 121 - $sm;
	}elseif($mov  == 'kraftschrei')
	{
		$check = 121 - $sm;
	}elseif($mov  == 'block')
	{
		$check = 121 - $sm;
	}elseif($mov  == 'taeuschen')
	{
		$check = 121 - $sm;
	}elseif($mov  == 'koerperteil_abschlagen')
	{
		$check = 131 - $sm;
	}elseif($mov  == 'sand_werfen')
	{
		$check = 131 - $sm;
	}elseif($mov  == 'ausweichen')
	{
		$check = 131 - $sm;
	}elseif($mov  == 'todesschlag')
	{
		if ($smcount == 1) {
			$check = 200 - $sm;
			$maxrand = 6;
		} else {
			$check = 141 - $sm;
			$maxrand = 6;
		}
	}elseif($mov  == 'konter')
	{
		$check = 141 - $sm;
		$maxrand = 6;
	}elseif($mov  == 'berserker')
	{
		$check = 141 - $sm;
		$maxrand = 6;
	}elseif($mov  == 'anti_def')
	{
		$check = 141 - $sm;
		$maxrand = 6;
	}
	
	$check_move = mt_rand(1,$check);
	
	if($check_move > $maxrand)
	{
		return "";
	}
	return $mov;
}

/***************************************************
 * Dieser bereich ist für die Nachrichten bestimmt *
 ***************************************************/


/*
 * Eine text ausgeben
 */
function make_msg($both, $who)
{
	//var_dump('make_msg');
	$other_who = other_user($who);

	if($who == 'user1')
	{
		$msg .= '<tr style="background-color:#ececaa;">';
		$msg .= '<td style="font-size:14px;color:#000000;"> Runde: '.$both['runde'].'</td>';
		$msg.= '</tr>';

		$msg .= '<tr style="background-color:#ececda;">';

	}else
	{
		$msg .= '<tr style="background-color:#ececda;">';
	}

	//Prüfen ob es einen off move gibt

	//ausnahemen in den es zu keinem deff move mehr kommt
	if($both[$who]['move_off_msg'] && $both[$other_who]['kopf'] == 0)
	{
		$msg.= '<tr style="background-color:#ececda;">';
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$who]['move_off_msg'].'</span></td>';
		$msg.= '</tr>';

		if($both[$other_who]['kraft'] == 0)
		{
			$msg.= '<tr style="background-color:#ececda;">';
			$msg.= '<td style="font-size:10px;color:#000000;"><span style="background-color: lightblue;">'.make_msg_death($both[$other_who]['name']).'</span></td>';
			$msg.= '</tr>';
		}
		$both['kb'] .= $msg;
		return $both;

	}elseif($both[$who]['move_off_msg'] && $both[$other_who]['bein'] != 2 && $both[$other_who]['kraft'] == 0)
	{
		$msg.= '<tr style="background-color:#ececda;">';
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$who]['move_off_msg'].'</span></td>';
		$msg.= '</tr>';

		if($both[$other_who]['kraft'] == 0)
		{
			$msg.= '<tr style="background-color:#ececda;">';
			$msg.= '<td style="font-size:10px;color:#000000;"><span style="background-color: lightblue;">'.make_msg_death($both[$other_who]['name']).'</span></td>';
			$msg.= '</tr>';
		}
		$both['kb'] .= $msg;
		return $both;

	}elseif($both[$who]['move_off_msg'] && $both[$other_who]['hand'] != 2 && $both[$other_who]['kraft'] == 0)
	{

		$msg.= '<tr style="background-color:#ececda;">';
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$who]['move_off_msg'].'</span></td>';
		$msg.= '</tr>';

		if($both[$other_who]['kraft'] == 0)
		{
			$msg.= '<tr style="background-color:#ececda;">';
			$msg.= '<td style="font-size:10px;color:#000000;color:blue;"><span style="background-color: lightblue;">'.make_msg_death($both[$other_who]['name'])."</span></td>";
			$msg.= '</tr>';
		}
		$both['kb'] .= $msg;
		return $both;

	}elseif($both[$who]['move_off_msg'] && $both[$who]['spezial_time'] != 0 && $both[$who]['spezial'])
	{
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$who]['move_off_msg'].'</span></td>';
		$msg.= '</tr>';
		$msg .= '<tr style="background-color:#ececda;">';
	}elseif($both[$who]['move_off_msg'])
	{
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$who]['move_off_msg'].'</span></td>';
		$msg.= '</tr>';
		$msg .= '<tr style="background-color:#ececda;">';
	}

	//Prüfen ob es einen deff move gibt
	if($both[$other_who]['move_deff_msg'])
	{
		$msg .= '<td style="font-size:10px;color:#000000;"><span style="color:red;">'.$both[$other_who]['move_deff_msg'].'</span></td>';
		$msg.= '</tr>';
		$msg .= '<tr style="background-color:#ececda;">';
	}

	$msg.= '<td style="font-size:10px;color:#000000;"><b>'.$both[$who]['name'].'</b>';


	switch($both[$who]['item'])
	{
		case 1:
			switch(rand(1,3))
			{
				case 1:
					$msg.= ' schlägt kraftvoll mit seinem Zweihänder zu und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
				case 2:
					$msg.= ' spannt seine Arme an und schlägt mit seinen Zweihänder auf <b>'.$both[$other_who]['name'].'</b> ein und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
				case 3:
					$msg.= ' trotzt der Unhandlichkeit seines Zweihänders, schlägt zu und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
			}
		break;
		case 2:
			switch(rand(1,3))
			{
				case 1:
					$msg.= ' schlägt kraftvoll mit seiner Waffe zu und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
				case 2:
					$msg.= ' schwingt gefühlsvoll seine Waffe und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
				case 3:
					$msg.= ' stösst seine Waffe hinter den Schild hervor und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
				break;
			}
		break;
		case 3:
			$msg.= ' rammt <b>'.$both[$other_who]['name'].'</b> sein Schild entgegen und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		case 4:
			$msg.= ' gibt <b>'.$both[$other_who]['name'].'</b> eine Kopfnuss und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		case 5:
			$msg.= ' rammt <b>'.$both[$other_who]['name'].'</b> die Schulter entgegen und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		case 6:
			$msg.= ' benutzt seinen Umhang als Peitsche und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		case 7:
			$msg.= ' boxt <b>'.$both[$other_who]['name'].'</b> zu und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		case 8:
			$msg.= ' tritt <b>'.$both[$other_who]['name'].'</b> und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';
		break;
		default:
			$msg.= ' greift an und verursacht <b>'.$both[$other_who]['damage'].'</b> Schaden, <br><b>'.$both[$other_who]['name'].'</b> hat noch <b>'.$both[$other_who]['kraft'].'</b> Kraft<hr /></td>';

	}

	$msg.= '</tr>';

	if($both[$other_who]['kraft'] == 0)
	{
		$msg.= '<tr style="background-color:#ececda;">';
		$msg.= '<td style="font-size:10px;color:#000000;"><span style="background-color: lightblue;">'.make_msg_death($both[$other_who]['name']).'</span></td>';
		$msg.= '</tr>';
	}

	$both['kb'] .= $msg;
	return $both;
}


/*
 * Wenn einer von beiden nur noch 0 Kraft hat todesmeldung ausgeben
 */
function make_msg_death($name)
{
	//var_dump('make_msg_death'.$name);
	$rand = rand(1,7);

	switch($rand)
	{
		case 1:
			$msg = '<em><b>'.$name.'</b></em> liegt tot in der Arena...';
		break;

		case 2:
			$msg = '<em><b>'.$name.'</b></em> hats erwischt...';
		break;

		case 3:
			$msg = '<em><b>'.$name.'</b></em> ist Tod... die Zuschauer jubeln!';
		break;

		case 4:
			$msg = '<em><b>'.$name.'</b></em> ist verreckt...';
		break;

		case 5:
			$msg = '<em><b>'.$name.'</b></em> ist von uns gegangen...';
		break;

		case 6:
			$msg = '<em><b>'.$name.'</b></em> braucht einen Sarg.';
		break;

		case 7:
			$msg = 'Aus <em><b>'.$name.'</b></em> söfft das Blut, er ist tot.';
		break;
	}
	$msg = '<em><b>'.$name.'</b></em> braucht einen Sarg.';
	return $msg;
}

function make_msg_top_sm($movename, $runden, $minushp = 0)
{
	//var_dump('make_msg_top_sm');
	if($movename == 'wundhieb')
	{
		$msg = "sein Gegner wird noch $runden Runden Bluten, dabei verliert er $minushp Kraft.";
	}elseif($movename == 'wutschrei')
	{
		$msg = "dieser wirkt noch $runden Runden.";
	}elseif($movename == 'sand_werfen')
	{
		$msg = "dies wird noch $runden Runden anhalten.";
	}
	return $msg;
}

function make_msg_top_sm_round($movename, $runden, $minushp = 0)
{
	//var_dump('make_msg_top_sm_round');
	if($movename == 'wundhieb')
	{
		$msg = "Das Bluten wird noch $runden Runden anhalten, dabei verliert er $minushp Kraft.";
	}elseif($movename == 'wutschrei')
	{
		$msg = "Der Wutschrei wirkt noch $runden Runden.";
	}elseif($movename == 'sand_werfen')
	{
		$msg = "Durch Sand im Auge ist er noch $runden Runden geblendet.";
	}
	return $msg;
}

function make_msg_deff_move($movename)
{
	//var_dump('make_msg_deff_move');
	if($movename == 'wutschrei')
	{
		$msg = 'Der Verteidiger schafft es durch einen Wutschrei seine Verteidigung zu erhöhen, ';
	}elseif($movename == 'armor')
	{
		$msg = 'Der Verteidiger kann sich besser vor dem Angriff schützen.';
	}elseif($movename == 'kraftschrei')
	{
		$msg = 'Der Verteidiger erhöht durch einen Kraftschrei seine Lebenskraft.';
	}elseif($movename == 'block')
	{
		$msg = 'Der Verteidiger kann den Angriff blocken.';
	}elseif($movename == 'taeuschen')
	{
		$msg = 'Der Verteidiger kann seinen Gegner Täuschen.';
	}elseif($movename == 'sand_werfen')
	{
		$msg = 'Der Verteidiger wird seinem Angreifer Sand ins Gesicht, ';
	}elseif($movename == 'ausweichen')
	{
		$msg = 'Der Verteidiger kann dem Angriff geschickt ausweichen.';
	}elseif($movename == 'konter')
	{
		$msg = 'Der Verteidiger kann den Angriff geschickt kontern und verletzt diesen dabei.';
	}elseif($movename == 'berserker')
	{
		$msg = 'Der Verteidiger rennt wie verückt auf seinen Angreifer los und verletzt diesen dabei,';
	}
	return $msg;
}

function make_msg_off_move($movename)
{
	//var_dump('make_msg_off_move');
	if($movename == 'kraftschlag')
	{
		$msg = 'Der Angreifer kann durch einen starken Schlag die Verteidigung des Gegners durchbrechen.';
	}elseif($movename == 'wutschrei')
	{
		$msg = 'Der Angreifer schafft es durch einen Wutschrei seinen Angriff zu verstärken, ';
	}elseif($movename == 'kritischer_schlag')
	{
		$msg = 'Der Angreifer führt einen kritischen Schlag durch.';
	}elseif($movename == 'wundhieb')
	{
		$msg = 'Der Angreifer schafft es seinen Gegner zum Bluten zu bringen, ';
	}elseif($movename == 'kraftschrei')
	{
		$msg = 'Der Angreifer erhöht durch einen Kraftschrei seine Lebenskraft.';
	}elseif($movename == 'koerperteil_abschlagen')
	{
		$msg = 'Der Angreifer schafft es seinem Gegner ein körperteil abzuschlagen.';
	}elseif($movename == 'koerperteil_abschlagen_bein')
	{
		$msg = 'Der Angreifer schafft es seinem Gegner ein Bein abzuschlagen.';
	}elseif($movename == 'koerperteil_abschlagen_hand')
	{
		$msg = 'Der Angreifer schafft es seinem Gegner eine Hand abzuschlagen.';
	}elseif($movename == 'todesschlag')
	{
		$msg = 'Der Angreifer schafft es seinem Gegner den Kopf abzuschlagen.';
	}elseif($movename == 'anti_def')
	{
		$msg = 'Der Angreifer duchdringt geschickt die Verteidigung seinen Gegners.';
	}
	return $msg;
}

/*************************
 *  Kampf kann beginnen  *
 *************************/

function make_a_new_fight($both, $count = 1)
{
	//var_dump('make_a_new_fight');
	$both['runde'] = 0;

	while($both['user1']['kraft'] > 0 && $both['user2']['kraft'] > 0)
	{
		$both['runde']++;
		$both = a_round($both['runde'],$both);
	}

	$fight = array();

	if($both['user1']['kraft'] == 0)
	{
		$fight['looser'] = $both['user1']['id'];
		$fight['winner'] = $both['user2']['id'];
		$fight['winnername'] = $both['user2']['name'];
	}else
	{
		$fight['looser'] = $both['user2']['id'];
		$fight['winner'] = $both['user1']['id'];
		$fight['winnername'] = $both['user1']['name'];
	}

	$kampfbericht = $both['kb'];
	$kampfbericht = '<table width="465" align="center">'.$kampfbericht.'</table>';
	$coolness = $both['coolness'];
	$runden = $both['runde'];

	$fight['coolness'] = $coolness;

	$fight['msg'] .= '<center><strong style="font-size:20px;">Auf in den Kampf!</strong></center><br />';

	$fight['msg'] .= '<fieldset style="width:45%;float:left;height:75px;"><legend>Erstes Team</legend>';

	$fight['msg'] .= '<div style="font-size:10px;"><strong>'.$both['user1']['name'].'</strong><br />('.$both['user1']['kraftorg'].' Kraft)</div><br />';

	$fight['msg'] .= '</fieldset>';

	$fight['msg'] .= '<fieldset style="width:45%;float:left;height:75px;"><legend>Zweites Team</legend>';

	$fight['msg'] .= '<div style="font-size:10px;"><strong>'.$both['user2']['name'].'</strong><br />('.$both['user2']['kraftorg'].' Kraft)</div><br />';

	$fight['msg'] .= '</fieldset>';

	$fight['msg'] .= '<center>... ein harter Kampf über '.$runden.' Runden beginnt</center><br />';

	$fight['msg'] .= '<center><a href="javascript:kbdrop('.$count.')">Kampfbericht</a></center><br />';

	$fight['msg'] .= '<div id="'.$count.'" style="display:none">'.$kampfbericht.'</div>';

	if($_REQUEST['site'] == 'arena')
	{
		$fight['msg'] .= '<center><em>Wow! Der Kampf hat '.$coolness.' Coolness Punkte.</em></center>';
	}
	if($_REQUEST['site'] == 'duelle' || $_REQUEST['site'] == 'turnierfight' || $_REQUEST['site'] == 'duelle_schule' || $_REQUEST['site'] ==  "challenge")
	{
		$fight['kampfbericht'] = $kampfbericht;
	}

	return $fight;
}
?>
