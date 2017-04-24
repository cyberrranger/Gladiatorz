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
function calcHS($u) {
	//aktuelle minute beachten
	$min = date('i');
	if($min<=29) {
		$tabell_name = 'highscore';
	}else {
		$tabell_name = 'highscore30';
	}
		
	$prestige = $u['prestige'];
	$meds = $u['medallien'];
	$exp = $u['exp'];
	$gold = $u['gold'];
	$pm = $u['powmod'];
	$schule = $u['schule'];
	$regstamp = $u['regstamp'];
	$lonline = $u['lonline'];
	$turnierpoints = $u['turnierpoints'];

	$level = $u['level']; // * 10
	$rang = $u['arenarang']; // * 5
	$quest = $u['quest']; // *3

	$char_1 = $u['staerke']; //St�rke * 100
	$char_2 = $u['geschick']; //Geschick * 100
	$char_3 = $u['kondition']; //Kondition * 100
	$char_4 = $u['charisma']; //Charisma * 100
	$char_5 = $u['inteligenz']; // intelligenz * 100
	$char_all = $char_1 + $char_2 + $char_3 + $char_4 + $char_5;

	$char2_1 = $u['Schlagkraft']; //Schlagkraft * 100
	$char2_2 = $u['Einstecken']; //Einstecken * 100
	$char2_3 = $u['Kraftprotz']; //Kraftprotz * 100
	$char2_4 = $u['Glueck']; //Gl�ck * 100
	$char2_5 = $u['Sammler']; // Sammler * 100
	$char2_all = $char2_1 + $char2_2 + $char2_3 + $char2_4 + $char2_5;

	$trainer_1 = $u['waffenkunde']; //Waffenkunde *50
	$trainer_2 = $u['ausweichen']; //Ausweichen *50
	$trainer_3 = $u['taktik']; //Taktik *50
	$trainer_4 = $u['zweiwaffenkampf']; //2-Waffenkampf *50
	$trainer_5 = $u['heilkunde']; // Heilkunde *50
	$trainer_6 = $u['schildkunde']; // Schildkunde *50
	$trainer_all = $trainer_1 + $trainer_2 + $trainer_3 + $trainer_4 + $trainer_5 + $trainer_6;

	//Spezialmoves sammeln
	$sql = "SELECT * FROM moves WHERE uid=".$u['id']." LIMIT 1";
	$res = mysql_query($sql);
	$move = mysql_fetch_assoc($res);

	$move_1 = $move['kraftschlag']; // * 10
	$move_2 = $move['wutschrei'];// * 10
	$move_3 = $move['armor'];// * 10

	$move10 = $move_1 + $move_2 + $move_3;

	$move_4 = $move['kritischer_schlag'];// * 15
	$move_5 = $move['wundhieb'];// * 15
	$move_6 = $move['kraftschrei'];// * 15
	$move_7 = $move['block'];// * 15
	$move_8 = $move['taeuschen'];// * 15

	$move15 = $move_4 + $move_5 + $move_6 + $move_7 + $move_8;

	$move_9 = $move['koerperteil_abschlagen'];// * 20
	$move_10 = $move['sand_werfen'];// * 20
	$move_11 = $move['ausweichen'];// * 20

	$move20 = $move_9 + $move_10 + $move_11;

	$move_12 = $move['todesschlag'];// * 25
	$move_13 = $move['konter'];// * 25
	$move_14 = $move['berserker'];// * 25
	$move_15 = $move['anti_def'];// * 25

	$move25 =  $move_12 + $move_13 + $move_14 + $move_15;

	$move_all = $move_1 + $move_2 + $move_3 + $move_4 + $move_5 + $move_6 + $move_7 + $move_8 + $move_9 + $move_10 + $move_11 + $move_12 + $move_13 + $move_14 + $move_15;

	//kills miteinander verrechnen
	$tier_win = $u['tier_kills_win']; // * 0.5
	$tier_lose = $u['tier_kills_lost']; // - 0.5
	$tier_points = round($tier_win * 0.5 - $tier_lose * 0.5);

	$arena_win = $u['ppl_kills_win']; // * 1
	$arena_lose = $u['ppl_kills_lost']; // - 1
	$arena_points = round($arena_win * 3 - $arena_lose * 3);

	$prestige_win = $u['prest_kills_win']; // *0.75
	$prestige_lose = $u['prest_kills_lost']; // - 0.75
	$prestige_points = round($prestige_win * 0.75 - $prestige_lose * 0.75);

	$duell_win = $u['duell_kills_win']; // * 2.5
	$duell_lose = $u['duell_kills_lost']; // - 2.5
	$duell_points = round($duell_win * 2.5- $duell_lose * 2.5);

	$rang_win = $u['rang_kills_win']; // * 1
	$rang_lose = $u['rang_kills_lost']; // - 1
	$rang_points = round($rang_win - $rang_lose );

	$max_off = get_user_attack($u['id']);
	$max_deff = get_user_armor($u['id']);

	$other_point = $tier_points+$arena_points+$prestige_points+$duell_points+$rang_points;

	if($u['rankplace'] == 0)
	{
		$u['rankplace'] = 21;
	}
	//rangplatz mitberechne
	$rangplace = 210 - $u['rankplace']*10;

	$rang_points = getRangName($u['level'], true) * 100;

	$all_points = $rang_points + $level * 10 + $other_point + $rangplace + $quest * 3 + $rang * 5 + $char_all * 100 + $char2_all * 100 + $trainer_all * 50 + $move10 * 10 + $move15 * 15 + $move20 * 20 + $move25 * 25 + $turnierpoints;

	$brutality = round(($tier_win + $arena_win + $prestige_win + $duell_win + $rang_win) / ((time() - $regstamp)/ 86400));

	$insert = mysql_query(
	"INSERT INTO ".$GLOBALS['conf']['db_name'].".`$tabell_name` (`id`, `name`, `user_id`, `points`, `brutality`, `prestige`, `meds`, `exp`, `gold`, `pm`, `schule`, `regstamp`, `lonline`, `level`, `arenarang`, `Quest`, `char_1`, `char_2`, `char_3`, `char_4`, `char_5`, `char_all`, `char2_1`, `char2_2`, `char2_3`, `char2_4`, `char2_5`, `char2_all`, `trainer_1`, `trainer_2`, `trainer_3`, `trainer_4`, `trainer_5`, `trainer_6`, `trainer_all`, `move_1`, `move_2`, `move_3`, `move_4`, `move_5`, `move_6`, `move_7`, `move_8`, `move_9`, `move_10`, `move_11`, `move_12`, `move_13`, `move_14`, `move_15`, `move_all`, `tier_win`, `tier_lost`, `tier_points`, `arena_win`, `arena_lost`, `arena_points`, `prestige_win`, `prestige_lost`, `prestige_points`, `duell_win`, `duell_lost`, `duell_points`, `rang_win`, `rang_lost`, `rang_points` ,`off`, `deff`, `turnierpoints`)
	VALUES (NULL,
	'".$u['name']."',
	'".$u['id']."',
	'".$all_points."',
	'".$brutality."',
	'".$prestige."',
	'".$meds."',
	'".$exp."',
	'".$gold."',
	'".$pm."',
	'".$schule."',
	'".$regstamp."',
	'".$lonline."',
	'".$level."',
	'".$rang."',
	'".$quest."',
	'".$char_1."',
	'".$char_2."',
	'".$char_3."',
	'".$char_4."',
	'".$char_5."',
	'".$char_all."',
	'".$char2_1."',
	'".$char2_2."',
	'".$char2_3."',
	'".$char2_4."',
	'".$char2_5."',
	'".$char2_all."',
	'".$trainer_1."',
	'".$trainer_2."',
	'".$trainer_3."',
	'".$trainer_4."',
	'".$trainer_5."',
	'".$trainer_6."',
	'".$trainer_all."',
	'".$move_1."',
	'".$move_2."',
	'".$move_3."',
	'".$move_4."',
	'".$move_5."',
	'".$move_6."',
	'".$move_7."',
	'".$move_8."',
	'".$move_9."',
	'".$move_10."',
	'".$move_11."',
	'".$move_12."',
	'".$move_13."',
	'".$move_14."',
	'".$move_15."',
	'".$move_all."',
	'".$tier_win."',
	'".$tier_lose."',
	'".$tier_points."',
	'".$arena_win."',
	'".$arena_lose."',
	'".$arena_points."',
	'".$prestige_win."',
	'".$prestige_lose."',
	'".$prestige_points."',
	'".$duell_win."',
	'".$duell_lose."',
	'".$duell_points."',
	'".$rang_win."',
	'".$rang_lose."',
	'".$rang_points."',
	'".$max_off."',
	'".$max_deff."',
	'".$turnierpoints."'
	);");
	$this_user = @mysql_fetch_assoc($insert);
	
}

function foundBuild($schule, $typ ,$area) {
	if ($schule == 0) {
		return 0;
	}
	
	$b = explode('|',$area);
	return $b[$typ];
}

function makeSmilie($msg)
{
	$msg = str_replace(":)", 				"[img]Images/smile/smilie_.).gif[/img]", $msg);
	$msg = str_replace(";)", 				"[img]Images/smile/smilie_.).gif[/img]", $msg);
	$msg = str_replace(":D", 				"[img]Images/smile/smilie_.D.gif[/img]", $msg);
	$msg = str_replace(";D", 				"[img]Images/smile/smilie_,D.gif[/img]", $msg);
	$msg = str_replace(":P", 				"[img]Images/smile/smilie_.P.gif[/img]", $msg);
	$msg = str_replace(":banned", 			"[img]Images/smile/smilie_banned.gif[/img]", $msg);
	$msg = str_replace(":beta", 			"[img]Images/smile/smilie_beta.gif[/img]", $msg);
	$msg = str_replace(":tv", 				"[img]Images/smile/smilie_fernsehen.gif[/img]", $msg);
	$msg = str_replace(":freu", 			"[img]Images/smile/smilie_freu.gif[/img]", $msg);
	$msg = str_replace(":geb", 				"[img]Images/smile/smilie_geburtstag.gif[/img]", $msg);
	$msg = str_replace(":gruppenknuddel", 	"[img]Images/smile/smilie_gruppenknuddel.gif[/img]", $msg);
	$msg = str_replace(":help", 			"[img]Images/smile/smilie_help.gif[/img]", $msg);
	$msg = str_replace(":knuddel", 			"[img]Images/smile/smilie_knuddel.gif[/img]", $msg);
	$msg = str_replace(":kotz", 			"[img]Images/smile/smilie_kotz.gif[/img]", $msg);
	$msg = str_replace(":krank", 			"[img]Images/smile/smilie_krank.gif[/img]", $msg);
	$msg = str_replace(":2krank", 			"[img]Images/smile/smilie_krank2.gif[/img]", $msg);
	$msg = str_replace(":kuss", 			"[img]Images/smile/smilie_kuss.gif[/img]", $msg);
	$msg = str_replace(":2kuss", 			"[img]Images/smile/smilie_kuss2.gif[/img]", $msg);
	$msg = str_replace("rofl", 				"[img]Images/smile/smilie_rofl.gif[/img]", $msg);
	$msg = str_replace(":schild", 			"[img]Images/smile/smilie_schildhoch.gif[/img]", $msg);
	$msg = str_replace(":sleep", 			"[img]Images/smile/smilie_sleep.gif[/img]", $msg);
	$msg = str_replace(":schlafen", 		"[img]Images/smile/smilie_sleep2.gif[/img]", $msg);
	$msg = str_replace(":smoke", 			"[img]Images/smile/smilie_smoke.gif[/img]", $msg);
	$msg = str_replace(":heul", 			"[img]Images/smile/smilie_traurig.gif[/img]", $msg);
	$msg = str_replace(":(", 				"[img]Images/smile/smilie_.(.gif[/img]", $msg);
	$msg = str_replace(":admin", 			"[img]Images/smile/schild_admin.gif[/img]", $msg);
	$msg = str_replace(":danke", 			"[img]Images/smile/schild_danke.gif[/img]", $msg);
	$msg = str_replace(":google", 			"[img]Images/smile/schild_google.gif[/img]", $msg);
	$msg = str_replace(":icq", 				"[img]Images/smile/schild_icq.gif[/img]", $msg);
	$msg = str_replace(":spam", 			"[img]Images/smile/schild_nospam.gif[/img]", $msg);
	$msg = str_replace(":regeln", 			"[img]Images/smile/schild_regeln.gif[/img]", $msg);
	$msg = str_replace(":verwarnung", 		"[img]Images/smile/schild_verwarnung.gif[/img]", $msg);
	$msg = str_replace(":1kampf", 			"[img]Images/smile/smilie_kampf1.gif[/img]", $msg);
	$msg = str_replace(":2kampf", 			"[img]Images/smile/smilie_kampf2.gif[/img]", $msg);
	$msg = str_replace(":3kampf", 			"[img]Images/smile/smilie_kampf3.gif[/img]", $msg);
	$msg = str_replace(":4kampf", 			"[img]Images/smile/smilie_kampf4.gif[/img]", $msg);
	$msg = str_replace(":5kampf", 			"[img]Images/smile/smilie_kampf5.gif[/img]", $msg);
	$msg = str_replace(":6kampf", 			"[img]Images/smile/smilie_kampf6.gif[/img]", $msg);
	$msg = str_replace(":7kampf", 			"[img]Images/smile/smilie_kampf7.gif[/img]", $msg);
	$msg = str_replace("lol", 				"[img]Images/smile/smilie_lol.gif[/img]", $msg);
	$msg = str_replace(":seuftz", 			"[img]Images/smile/smilie_traurig.gif[/img]", $msg);
	$msg = str_replace(":head", 			"[img]Images/smile/banghead.gif[/img]", $msg);
	
	return $msg;	
}

function isEvent($what = 0)
{
	$eventtime = time();
	$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventtime."' LIMIT 1");
	$Event = @mysql_fetch_assoc($Event);
	
	if ($Event['event'] == $what)
	{
		return true;
	} else 
	{
		return false;
	}
	
}
// GET_SPECIAL IS TURNED OUT OF SERVICE IN THE MEANING OF CODING standardS AND THE IMPORTANCE/USE OF THE FUNCTION! THIS INFORMATION IS
// IMPORTANT SO REMOVE THE FUNCTION AS FAST AS POSSIBLE!!! _>said by admin
// Special Move Chancen Kraftschlag, Block und Finte bei einem beliebigen User herausfinden
function get_special($id,$modus,$special)
{
  // ID = ID vom 'Opfer'

  // Modus = 1 -> Special Equipment
  // Modus = 2 -> Special Skills
  // Modus = 3 -> Special Gesamt
  // Modus = 4 -> Special F&auml;higkeiten

  // Special = f, k oder b

  // EQP = weapon, shield oder armor

  $user = mysql_select("*","user","id='$id'",null,"1");

  $weapon = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='weapon' AND ort='user'", null,"1");
  if($weapon == false)
  {
    $weapon = mysql_select("*", "ausruestung", "ort='standard' AND art='weapon'", null, null);
  }

  $armor = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='armor' AND ort='user'", null, null);
  if($armor == false)
  {
    $armor = mysql_select("*", "ausruestung", "ort='standard' AND art='armor'", null, null);
  }

  $shield = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='shield' AND ort='user'", null, null);
  if($shield == false)
  {
    $shield = mysql_select("*", "ausruestung", "ort='standard' AND art='shield'", null, null);
  }

  if($modus == 1 OR $modus == 3) // Berechnen der Menge von Special des Equipments
  {
    $slots_eqp = array();
	$special_eqp = 0;

	$slots_eqp[0] = substr($weapon[ups],0,1);
    $slots_eqp[1] = substr($weapon[ups],1,1);
    $slots_eqp[2] = substr($weapon[ups],2,1);
    $slots_eqp[3] = substr($weapon[ups],3,1);
    $slots_eqp[4] = substr($weapon[ups],4,1);

	$slots_eqp[5] = substr($shield[ups],0,1);
    $slots_eqp[6] = substr($shield[ups],1,1);
    $slots_eqp[7] = substr($shield[ups],2,1);
    $slots_eqp[8] = substr($shield[ups],3,1);
    $slots_eqp[9] = substr($shield[ups],4,1);

	$slots_eqp[10] = substr($armor[ups],0,1);
    $slots_eqp[11] = substr($armor[ups],1,1);
    $slots_eqp[12] = substr($armor[ups],2,1);
    $slots_eqp[13] = substr($armor[ups],3,1);
    $slots_eqp[14] = substr($armor[ups],4,1);

	$schleife = 0;
    while($schleife < 16)
    {
      if($slots_eqp[$schleife] == "$special")
	  {
	    $special_eqp++;
	  }

      $schleife++;
    }
  }

  if($special == 'k') // Kraftschlag
  {
	$skill = ($user[staerke] / 2);
  }
  elseif($special == 'b') // Blocken
  {
	$skill = ($user[kondition] / 2);
  }
  elseif($special == 'f') // Finte
  {
	$skill = ($user[geschick] / 2);
  }
  elseif($special == 'w') // Wundhieb
  {
	$skill = ($user[staerke] / 5);
  }
  elseif($special == 'bl') // Blendpulver
  {
	$skill = ($user[geschick] / 5);
  }

  $special_skill = floor($skill);

  if($special == 'k') // Kraftschlag
  {
	$fae = ($user[waffenkunde] / 5) + ($user[taktik] / 2) + ($user[zweiwaffenkampf] / 3);
  }
  elseif($special == 'b') // Blocken
  {
	$fae = ($user[taktik] / 5);
  }
  elseif($special == 'f') // Finte
  {
	$fae = ($user[ausweichen] / 2);
  }
  elseif($special == 'w') // Wundhieb
  {
	$fae = ($user[waffenkunde] / 2) + ($user[zweiwaffenkampf] / 5);
  }
  elseif($special == 'bl') // Blendpulver
  {
	$fae = ($user[ausweichen] / 3);
  }

  $fae = floor($fae);

  // Ab hier die R&auml;ckgabe der Funktion

  if($modus == 1)
  {
    return $special_eqp;
  }
  elseif($modus == 2)
  {
    return $special_skill;
  }
  elseif($modus == 4)
  {
    return $fae;
  }
  else
  {
    $special_gesamt = $special_eqp + $special_skill + $fae;
    return $special_gesamt;
  }
}

// InGame Messages versenden
function sendIGM($Titel,$Text,$Empfaenger,$Absender)
{
  $Insert = mysql_query("INSERT INTO nachrichten (empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender)
  VALUES ('$Empfaenger','$Absender','$Titel','$Text','".time()."','n','n','n')");

  if($Insert == false)
  {
    return false;
  }
  else
  {
    return true;
  }
}

// Einem User eine Ingame Message senden
function send_igm($titel,$text,$empfaenger,$absender)
{
  $timestamp = time();

  $text = "
  Hallo $empfaenger,

  $text

  MfG die $absender";

  mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender"
  ,"'$empfaenger','$absender','$titel',
  '$text','$timestamp','n','n','n'");
 # var_dump(mysql_error());
}

// einen angelegten Ausr&auml;stungsgegenstand eines Users herausfinden
function get_equipment($id, $art)
{
  // ID = ID eines Users
  // Art = weapon, shield, armor

  $sql = "SELECT * FROM ausruestung WHERE user_id='$id' AND art='$art' AND ort='user' LIMIT 1";
  $select = mysql_query($sql);
  $eqp = mysql_fetch_assoc($select);

  if(!$eqp)
  {
    $sql = "SELECT * FROM ausruestung WHERE ort='standard' AND art='$art' LIMIT 1";
    $select = mysql_query($sql);
    $eqp = mysql_fetch_assoc($select);
  }

  if($eqp)
  {
    return $eqp;
  }
  else
  {
    die('Cant find standard Equipment!');
  }
}

function user_loeschen($id)
{
  $user = mysql_select("*","user","id='$id'",null,"1");

  // User aus der Usertabelle l&auml;schen

  mysql_delete("user","id='$user[id]' AND name='$user[name]'");

  // Ausr&auml;stung und Basarangebote des Users l&auml;schen

  $sql = "SELECT * FROM ausruestung WHERE user_id='$user[id]'";
  $query = mysql_query($sql);
  while($eqp = mysql_fetch_assoc($query))
  {
    // Item nicht auf dem Basar
    if($eqp['preis'] == 0 && $eqp['dauer'] == 0 || $eqp['preis'] < time() && $eqp['dauer'] < time())
	{
	  mysql_delete("ausruestung","id='$eqp[id]' AND user_id='$user[id]'");
	}
	else
	{
	  mysql_update("ausruestung","user_id='4'","id='$eqp[id]' AND user_id='$user[id]'");
	}
  }

  // Wetten auf Duelle zur&auml;ckzahlen

  $duell = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

  if($duell != false)
  {

    $sql = "SELECT * FROM wetten WHERE duell='$duell[id]'";
    $query = mysql_query($sql);
    while($wetten = mysql_fetch_assoc($query))
    {
      $wetter = mysql_select("*","user","id='$wetten[wer]'",null,"1");
	  $wetter[gold] = $wetter[gold] + $wetten[wieviel];
	  mysql_update("user","gold='$wetter[gold]'","id='$wetter[id]'");

	  send_igm("Ung&auml;ltige Wette","Hallo,<br /><br />leider ist eine deiner Wetten ung&auml;ltig geworden weil einer der Duellpartner seinen Gladiatorz Account gel&auml;scht hat. Das Gold erh&auml;lst du nat&auml;rlich zur&auml;ck.<br /><br />MfG admin","$wetter[name]","Wettb&auml;ro");

	  mysql_delete("wetten","id='$wetten[id]'");
    }
  }

  // Duelle des Users l&auml;schen

  $duell = mysql_select("*","duelle","duellant_1='$user[id]' OR duellant_2='$user[id]'",null,"1");

  if($duell != false)
  {
    if($duell[duellant_1] == $user[id]) // User ist Duell Ersteller
	{//brief an D2 (nur hinweis kein fixcomment)
	  mysql_delete("duelle","id='$duell[id]'");

	  $duellant_2 = mysql_select("*","user","id='$duell[duellant_2]'",null,"1");
	  $duellant_2[gold] = $duellant_2[gold] + $duell[einsatz];

	  mysql_update("user","gold='$duellant_2[gold]'","id='$duellant_2[id]'");

	  send_igm("Duellpartner verschwunden","Hallo,<br /><br />leider hat dein Duellpartner seinen Account bei Gladiatorz abgemeldet. Da er der Ersteller des Duells war wurde das Duell aufgel&auml;st und dir dein Einsatz zur&auml;ckgegeben.<br /><br />MfG admin","$duellant_2[name]","Arenaleitung");
	}
	else // User macht in einem Duell mit
	{
	  $duell[duellant_2] = 0;
	  $duell[wann] = time() + 86400;

	  mysql_update("duelle","duellant_2='$duell[duellant_2]',wann='$duell[wann]'","id='$duell[id]'");
	  $duellant_1 = mysql_select("*","user","id='$duell[duellant_1]'",null,"1");

	  send_igm("Duellpartner verschwunden","Hallo,<br /><br />leider hat dein Duellpartner seinen Account bei Gladiatorz abgemeldet. Jetzt musst du wieder auf einen neuen Teilnehmer warten, der 24 Stunden Timer wurde dazu resettet.<br /><br />MfG admin","$duellant_1[name]","Arenaleitung");
	}
  }

  // Schule des Users &auml;berpr&auml;fen

  if($user[schule] != 0)
  {
    $schule = mysql_select("*", "schulen", "id = '$user[schule]'", null, "1");

	if($user[id] == $schule[oberhaupt]) // Wenn User der Chef der Ally ist...
    {
      $member = mysql_count("user","id","schule='$user[schule]'") - 1;

	  if($member <= 0) // Kein Nachfolger da -> weg mit der Ally
	  {
	    mysql_delete("schulen","id='$user[schule]'");
	    $user[schule] = 0;
	  }
	  else // Nachfolger bestimmen
	  {
	    $nachfolger = mysql_select("*","user","schule='$user[schule]' AND id != '$user[id]'","id","1");

	    mysql_update("schulen","oberhaupt='$nachfolger[id]'","id='$user[schule]'");

	    $user[schule] = 0;
	  }
    }

	$AllyClass = new AllyClass();
	$AllyClass->LeaveAlly($id);
  }

  // Duellwetten des Users l&auml;schen
  mysql_delete("wetten","wer='$user[id]'");

  // Basargebote auf Items des Users l&auml;schen
  mysql_delete("gebote","bieter='$user[id]'");
}

function get_points($id)
{
	//aktuelle minute beachten
	$min = date('i');
	if($min<=29)
	{
		$tabell_name = 'highscore';
	}else
	{
		$tabell_name = 'highscore30';
	}

  $sql = "SELECT points FROM ".$tabell_name." WHERE user_id='$id' LIMIT 1";
  $query = mysql_query($sql);
  $user = mysql_fetch_assoc($query);

  $punkte = $user['points'];

  return $punkte;
}

function calcTraincost($thisuser, $schule)
{
	$kosten = array();
	//Kosten PM berechnen
	switch(foundBuild($thisuser['schule'],0, $schule['area'])) {
		case 2:
			$max = 300;
			$cost = 0;
			break;
		case 1:
			$max = 150;
			$cost = 0.5;
			break;
		default:
			$max = 75;
			$cost = 1;
			break;
	}
	
	$count_skill = $thisuser[waffenkunde] + $thisuser[ausweichen] + $thisuser[taktik] + $thisuser[zweiwaffenkampf] + $thisuser[heilkunde] + $thisuser[schildkunde];
	
	if($count_skill >= 125) {
		$cost = 1;
	}elseif($count_skill >= 200) {
		$cost = 2;
	}
	$kosten['pm'] = $cost;
	
	//Kosten Gold berechnen
	$kosten['gold'] = train_ability($thisuser[id],'',1);
	$kosten['max'] = $max;
	return $kosten;
}

function train_ability($user_id,$ability,$modus,$special=0,$pmcost=1,$mfert=75)
{
  // $modus = 1 -> RETURN Goldkosten
  // $modus = 2 -> UPDATE

	$user = mysql_select("*","user","id='$user_id'");

	$ab = $user[waffenkunde] + $user[ausweichen] + $user[taktik] + $user[zweiwaffenkampf] + $user[heilkunde] + $user[schildkunde];
	$kosten = $user[waffenkunde] + $user[ausweichen] + $user[taktik] + $user[zweiwaffenkampf] + $user[heilkunde] + $user[schildkunde];
	if($user[trainerpunkte]>$ab) {
		$kosten = 0;
	}

	if($kosten == 0) {
		$kosten=1;
	}

	$kosten = $kosten + $special;

	$schleife = 0;
	while($schleife < $kosten)
	{
		$money_cost = $money_cost + (50 * $schleife);
		$schleife++;
	}

	if($modus == 1)
	{
		return $money_cost;
	}
  else
  {
    if($user[gold] >= $money_cost)
	{
	  if($user[$ability] <= 49)
	  {
	    if($user[powmod] >= $pmcost)
		{
		  if($mfert >= $ab)
		  {
	        $user[$ability] = $user[$ability] + 1;
	        $user[gold] = $user[gold] - $money_cost;
		    $user[powmod] = $user[powmod] - $pmcost;
	        mysql_update("user","$ability='$user[$ability]',gold='$user[gold]',powmod='$user[powmod]'","id='$user[id]'");

			return true;
		  }
	      else
		  {
		    echo"<center>Du kannst maximal bis $mfert Fertigkeitspunkte trainieren!</center><br />";
	      }
		}
		else
		{
		  echo"<center>Du hast nicht gen&auml;gend PowMod!</center><br />";
		}
	  }
	  else
	  {
	    echo"<center>Du kannst F&auml;higkeiten nur bis Stufe 50 trainieren!</center><br />";
	  }
	}
	else
	{
	  echo"<center>Du hast nicht gen&auml;gend Gold!</center><br />";
	}
  }
}

/*F�r altes KS*/
function ReplaceOne($in, $out, $content){
   if ($pos = strpos($content, $in)){
       return substr($content, 0, $pos) . $out . substr($content, $pos+strlen($in));
   } else {
       return $content;
   }
}

function checkPosts($id,$Date)
{
  $p=0;

  $sql = "SELECT id,forums_id FROM forum_topics WHERE forums_id='$id'";
  $query=mysql_query($sql);
  while($row=mysql_fetch_assoc($query))
  {
    $sql2 = "SELECT count(id) FROM forum_answers WHERE topics_id='$row[id]' AND time > '$Date'";
    $query2=mysql_query($sql2);
    $fetch=mysql_fetch_row($query2);

	$p = $p+$fetch[0];
  }

  return $p;
}

/*/////////////////////////////////////////////////////////////////////////////*/
/*/////////////////////////////////////////////////////////////////////////////*/
/*///////////////////////JS CODING standardS 2!////////////////////////////////*/
/*/////////////////////////////////////////////////////////////////////////////*/
/*/////////////////////////////////////////////////////////////////////////////*/


// antiken Namen generieren
function createName()
{
  global $Vorname,$Nachname;

  $Name = $Vorname[rand(0,(count($Vorname)-1))].' '.$Nachname[rand(0,(count($Nachname)-1))];

  return $Name;
}

// Tipp anzeigen lassen
function showTipp()
{
  $Query = mysql_query("SELECT * FROM ".TAB_TIPPS." ORDER BY RAND() LIMIT 1");
  $Tipp = mysql_fetch_assoc($Query);

  echo'
  <p style="text-align:center;">
    <strong>Tipp '.$Tipp['id'].':</strong> "'.utf8_encode($Tipp['tipp']).'" <em>von '.utf8_encode($Tipp['autor']).'</em>
  </p><br />';
}

// Tunier anzeigen lassen
function showTunier($contest)
{
	if($contest == 0)
	{
	   echo '<br /><div class="center"><a href="?site=contest"><h2>Auf zur Turnieranmeldung</h2></a></div>';
	}
	else
	{
	   echo '<br /><div class="center"><a href="?site=contest"><h2>Turnier&auml;bersicht</h2></a></div>';
	}
}

//wieviele neue nachrichten?
function showMSG($contest)
{
	if($contest >= 1)
	{
	  echo'
	  <div class="center">
	    <strong><a href="?site=posteingang">&raquo; '.$contest.' neue Ingame-Nachricht! &laquo;</a></strong>
	  </div><br />';
	}
}
//wie lange schlaf ich noch?
function showSchlafen($contest)
{
	if($contest > time())
	{
	  $SleepHours = ($contest - time()) / 3600;
	  $SleepMins = ($SleepHours - floor($SleepHours)) * 3600;

	  echo'
	  <div class="center">
	    <strong>
		  <a href="?site=schlafraum">
		    &raquo; Du ruhst dich noch '.floor($SleepHours).' Stunden und '.floor($SleepMins / 60).' Minuten aus! &laquo;
		  </a>
		</strong>
	  </div>';
	}
}

// Umfrage anzeigen lassen
function showUmfrage($contest)
{
	if (!$GLOBALS['conf']['activ']['umfrage'])
	{
		$queryfrage = "SELECT * FROM Umfrage ORDER BY id desc LIMIT 1";
		$umfrage = mysql_query($queryfrage);
		$umfrage = mysql_fetch_array($umfrage);

		echo'<div class="center"><a href="?site=umfrage">';
		if($contest == 0)
		{
		   echo '<br /><h2>Nimm teil an unserer '.$umfrage['id'].'. Umfrage!!!</h2></a></div>';
		} else {
		   echo '<h3>Hier kommst du zur &Uuml;bersicht der Umfragen.</h3></a></div>';
		}
	}
}

//Herausforderungen pr&auml;fen
function checkChallenge($contest)
{
	$time = 5*24*60*60 + time();
	$Query = @mysql_query("SELECT * FROM open_fights WHERE user2='".$contest."' AND time<='".$time."' AND status=0 ORDER BY time DESC");
	$count = 0;
	while($chal_today = @mysql_fetch_assoc($Query))
	{
		$count++;
	}

	if($count != 0)
	{
		echo'<center><a href="?site=challenge"><strong>';
		echo 'F&uuml;r dich gibt es '.$count.' neue Herausforderungen.</strong></a><br /></center>';
	}
}

// Event anzeigen lassen
function showEvent()
{
	$eventzeit = time();
	$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
	$Event = @mysql_fetch_assoc($Event);

	if($Event == true)
	{
		echo "<br><center><b>Ein Event l&auml;uft gerade!<br />";

		switch($Event['event'])
		{
			case 1:
				echo "Die Duellzeit wurde auf 5 Minuten reduziert";
				break;
			case 2:
				echo "der Duellgewinn wurde verdoppelt (bei Duellen bis zu einem Einsatz von 10.000 Gold)";
				break;
			case 3:
				echo "Die Levelbeschr&auml;nkung bei Duellen wurde aufgehoben";
				break;
			case 4:
				echo "Die Arenarangbeschr&auml;nkung wurde aufgehoben";
				break;
			case 5:
				echo "Der Prestiggewinn bei Prestigk&auml;mpfen wurde verdoppelt";
				break;
			case 7:
				echo "Es gibt f&uuml;r alle 1pm/h";
				break;
			case 8:
				echo "Im PvP wurde der Todesschlag f&uuml;r alle auf 20 Punkte gesetzt";
				break;
			case 9:
				echo "Die Dropwahrscheinlichkeit wurde um 10% erh&ouml;ht";
				break;
			case 10:
				echo "Die Rangdropwahrscheinlichkeit wurde um 10% erh&ouml;ht";
				break;
		}

		echo "<br></b></center>";

		$EventHours = ($Event['dauer'] - time()) / 3600;
		$EventMins = ($EventHours - floor($EventHours)) * 3600;

		echo'<b><center>Das Event l&auml;uft noch '.floor($EventHours).' Stunden und '.floor($EventMins / 60).' Minuten!</center></b>';
	}
}

// Schule eines Users + will er Ally beitreten?
function getSchool($ID)
{
  $Query = mysql_query("SELECT id,schule FROM ".TAB_USER." WHERE id='".$ID."' LIMIT 1");
  $Guy = mysql_fetch_assoc($Query);

  if(strstr($Guy['schule'],'j') != '')
  {
    //$Guy['AllyJoin'] = substr(strstr($user['schule'],'j'),1);
    $Guy['schule'] = 0;
  }

  if($Guy['schule'] != 0)
  {
    $Query = mysql_query("SELECT * FROM ".TABALLY." WHERE id='".$Guy['schule']."' LIMIT 1");
    $School = mysql_fetch_assoc($Query);

	return $School;
  }
  else
  {
    return '';
  }
}

function UserInfoPanel($ID)
{
  	$level = get_user_things($ID, 'level');
  	$kraft = get_health($ID);
	$max_off = get_user_attack($ID);
	$max_deff = get_user_armor($ID);

	$text = '<strong>Level: '.$level.'<br /><br />'.$max_off.' Off<br />'.$max_deff.' Def<br />'.$kraft.' Kraft</strong><br /><br /><font size=-2><em>Achtung! Die Boni wie z.B. Schulentaktiken werden in diese Werte <u>nicht</u> hineingerechnet!</em></font>';

	return $text;
}

// Ausr&auml;stung eines Users auslesen
function getEquipment($ID,$Type)
{
  // ID = ID eines Users
  // Art = weapon, shield, armor

  $Query = mysql_query("SELECT * FROM ".TAB_ITEMS." WHERE user_id='".$ID."' AND art='".$Type."' AND ort='user' LIMIT 1");
  $EQP = mysql_fetch_assoc($Query);

  if(!$EQP)
  {
    $Query = mysql_query("SELECT * FROM ".TAB_ITEMS." WHERE ort='standard' AND art='".$Type."' LIMIT 1");
    $EQP = mysql_fetch_assoc($Query);
  }

  if($EQP)
  {
    return $EQP;
  }
  else
  {
    die('Cant find standard Equipment!');
  }
}


// EXP ausgeben die zum n&auml;chsten Level ben&auml;tigt werden
function neededEXP($Level)
{
	$exp['sql'] = mysql_query("SELECT exp FROM level WHERE Level=".$Level." LIMIT 1");
	$exp['erf'] = mysql_result($exp['sql'], 0, "exp");

	return $exp['erf'];
}

/*
 * Funktion zum bekommen eines bestimmten angezogenen items
 */
function get_eqi($id,$what, $zhw = 0)
{
	if(!$zwh)
	{
		$sql = "SELECT * FROM ausruestung WHERE user_id='$id' AND ort='user' AND art='".$what."'";
		$query = mysql_query($sql);

		$art = mysql_fetch_assoc($query);
		if(!$art)
		{
			$sql = "SELECT * FROM ausruestung WHERE user_id=0 AND ort='standard' AND art='".$what."'";
			$query = mysql_query($sql);
			$art = mysql_fetch_assoc($query);
		}
		return $art;
	}else
	{
		$sql = "SELECT * FROM ausruestung WHERE user_id='$id' AND ort='user' AND art='".$what."' AND ZHW='1'";
		$query = mysql_query($sql);

		$art = mysql_fetch_assoc($query);
		if(!$art)
		{
			return false;
		}else
		{
			return $art;
		}

	}

}

/*
 * Funktion zum berechnen der aktuellen off
 */
function get_user_attack($id)
{
	$_this_attack = 0;

	//hat user eine zhw an ?
	$this_item = get_eqi($id,'weapon', 1);

	//wenn nicht dann nach normaler waffe und schild pr&auml;fen
	if(!$this_item)
	{
		$this_item = get_eqi($id,'weapon');
		$_this_attack = $_this_attack + $this_item['off'];

		$this_item = get_eqi($id,'shield');
		$_this_attack = $_this_attack + $this_item['off'];
	}else
	{
		$_this_attack = $_this_attack + $this_item['off'];
	}

	$this_item = get_eqi($id,'head');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'shoulder');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'armor');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'lowbody');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'cape');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'belt');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'gloves');
	$_this_attack = $_this_attack + $this_item['off'];
	$this_item = get_eqi($id,'foots');
	$_this_attack = $_this_attack + $this_item['off'];

	 return $_this_attack;
}

/*
 * Funktion zum berechnen der aktuellen deff
 */
function get_user_armor($id)
{
	$_this_armor = 0;

	//hat user eine zhw an ?
	$this_item = get_eqi($id,'weapon', 1);

	//wenn nicht dann nach normaler waffe uns schild pr&auml;fen
	if(!$this_item)
	{
		$this_item = get_eqi($id,'weapon');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_eqi($id,'shield');
		$_this_armor = $_this_armor + $this_item['deff'];

	}else
	{
		$_this_armor = $_this_armor + $this_item['deff'];
	}


	$this_item = get_eqi($id,'head');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'shoulder');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'armor');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'lowbody');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'cape');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'belt');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'gloves');
	$_this_armor = $_this_armor + $this_item['deff'];
	$this_item = get_eqi($id,'foots');
	$_this_armor = $_this_armor + $this_item['deff'];



	return $_this_armor;
}
?>
