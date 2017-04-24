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
 * Alle nachrichten aus DB lesen
 */
function getAllNewIGM($to) {
	$query = @mysql_query("SELECT * FROM igm WHERE touser=".$to." ");
	
	$countnew = 0;
	$countold = 0;
	
	$typ[1]['status'] = false;
	$typ[1]['typ'] = 1;
	$typ[1]['name'] = "IGM";
	$typ[2]['status'] = false;
	$typ[2]['typ'] = 2;
	$typ[2]['name'] = "Markt";
	$typ[3]['status'] = false;
	$typ[3]['typ'] = 3;
	$typ[3]['name'] = "Schule";
	$typ[4]['status'] = false;
	$typ[4]['typ'] = 4;
	$typ[4]['name'] = "Systemmail";
	$typ[5]['status'] = false;
	$typ[5]['typ'] = 5;
	$typ[5]['name'] = "Kampf";
	$typ[6]['status'] = false;
	$typ[6]['typ'] = 6;
	$typ[6]['name'] = "Rundmail";
	
	$returnarray = array();
	while($igm = @mysql_fetch_assoc($query))
	{
		//Zählen
		if ($igm['read']) {
			$countold++;
		} else {
			$countnew++;
		}
		$typ[$igm['typ']]['status'] = true;
		$returnarray['igm'][$igm['id']] = $igm;
	}
	
	$returnarray['new'] = $countnew;
	$returnarray['old'] = $countold;
	
	foreach($typ as $t) {
		if($t['status']) {
			$returnarray['typ'][$t['typ']] = $t;
		}
	}
	return $returnarray;
} 

/*
 * Neue Nachricht versenden
 */
function sendNewIGM($from, $to, $title, $msg, $typ = 1) {
	if ($title == "") {
		$title = "Kein Betreff";
	}
	
	if ($msg == "") {
		return false;
	}
	
	if (!is_numeric($to)) {
		//$to ist keine Zahl user suchen
		$touser = mysql_fetch_assoc(mysql_query("SELECT * FROM user WHERE name LIKE'".$to."' LIMIT 1"));
		if ($touser) {
			$to = $touser['id'];
		} else {
			return false;
		}
	}
	
	$insert = mysql_query("INSERT INTO igm (fromuser,touser,title,msg,typ) VALUES ('$from','$to','$title','$msg','$typ')");
	if ($insert) {
		return true;
	} else {
		return false;
	}
}

/*
 * Funktion zum bekommen eines bestimmten angezogenen items
 */
function get_this_eqi($id,$what, $zhw = 0)
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
function get_attack($id,$what = 'people',$what_item = 'all')
{
	if ($what == 'animal')
	{
		$sql = "SELECT attack FROM tiergrube WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);
		$_this_attack = $_this['attack'];

		return $_this_attack;

	} elseif ($what == 'npc')
	{
		$sql = "SELECT * FROM rangnpcs WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

		$_this_attack = $_this['off'];

  		return $_this_attack;

	} elseif ($what == 'people' && $what_item != 'all')
	{
		//bestimmte waffe verwenden
		$_this_attack = 0;

		if($what_item == 'weapon_zhw')
		{
			$this_item = get_this_eqi($id,'weapon', 1);

			if(!$this_item)
			{
				//der spieler hat keine 2 handwaffe !!!
				$this_item = get_this_eqi($id,'weapon');

				//zweiwaffenkampf beachten
				$train = get_user_things($id,'zweiwaffenkampf');

				//taktik 1 beachten Haunei
				if (get_tactic($id) == 1)
				{
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 1.18);
				}elseif (get_tactic($id) == 3)
				{	//taktik 3 beachte 2 Waffen sind besser als eine!
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 1.5);
				}elseif (get_tactic($id) == 6)
				{	//taktik 6 beachte Schild hoch
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 0.5);
				}else
				{
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 1);
				}

			}else
			{
				//waffenkunde beachten
				$train = get_user_things($id,'waffenkunde');

				//taktik 1 beachten Haunei
				if(get_tactic($id) == 1)
				{
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 1.18);
				}else
				{
					$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3) * 1);
				}
			}

		} elseif ($what_item == 'weapon')
		{
			$this_item = get_this_eqi($id,$what_item);

			//zweiwaffenkampf beachten
			$train = get_user_things($id,'zweiwaffenkampf');

			if (get_tactic($id) == 1) //taktik 1 beachten Haunei
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1.18);

			} elseif (get_tactic($id) == 3) //taktik 3 beachte 2 Waffen sind besser als eine!
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1.5);

			} elseif (get_tactic($id) == 6) //taktik 6 beachte Schild hoch
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 0.5);

			} else
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1);
			}

		} else
		{
			$this_item = get_this_eqi($id,$what_item);
			$_this_attack = $_this_attack + $this_item['off'];
		}

	}elseif($what == 'people')
	{
		//wenn kein tier dann ist ein user
		$_this_attack = 0;

		//hat user eine zhw an ?
		$this_item = get_this_eqi($id,'weapon', 1);

		//wenn nicht dann nach normaler waffe und schild pr�fen
		if(!$this_item)
		{
			$this_item = get_this_eqi($id,'weapon');
			//zweiwaffenkampf beachten
			$train = get_user_things($id,'zweiwaffenkampf');

			//taktik 1 beachten Haunei
			if(get_tactic($id) == 1)
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1.18);
			}elseif(get_tactic($id) == 3)
			{	//taktik 3 beachte 2 Waffen sind besser als eine!
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1.5);
			}elseif(get_tactic($id) == 6)
			{	//taktik 6 beachte Schild hoch
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 0.5);
			}else
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1);
			}

			$this_item = get_this_eqi($id,'shield');
			$_this_attack = $_this_attack + $this_item['off'];
		}else
		{
			//waffenkunde beachten
			$train = get_user_things($id,'waffenkunde');

			//taktik 1 beachten Haunei
			if(get_tactic($id) == 1)
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1.18);
			}else
			{
				$_this_attack = $_this_attack + round(($this_item['off'] + $train * 3)* 1);
			}
		}

		$this_item = get_this_eqi($id,'head');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'shoulder');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'armor');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'lowbody');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'cape');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'belt');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'gloves');
		$_this_attack = $_this_attack + $this_item['off'];
		$this_item = get_this_eqi($id,'foots');
		$_this_attack = $_this_attack + $this_item['off'];

	}

	//staerke beachten
	$train = get_user_things($id,'staerke');
	$_this_attack = $_this_attack + $train * 2;

	//Schlagkraft beachten
	$train = get_user_things($id,'Schlagkraft');
	$_this_attack = $_this_attack + $train * 5;

	//gebet 1 beachten
	if(get_gebet($id) == 1)
	{
		$_this_attack = round($_this_attack * 1.1);
	}
	//taktik 2 beachten  Un�berwindbare Verteidigung
	if(get_tactic($id) == 2)
	{
		$_this_attack = round($_this_attack * 0.82);
	}
	//taktik 6 beachte Schild hoch
	if(get_tactic($id) == 6)
	{
		$_this_attack = round($_this_attack * 0.9);
	}

	 return $_this_attack;
}

/*
 * Funktion zum berechnen der aktuellen deff
 */
function get_armor($id,$what = 'people', $what_item = 'all')
{
	if($what == 'animal')
	{
		$sql = "SELECT armor FROM tiergrube WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);
  		$_this_armor = $_this['armor'];

  		return $_this_armor;
	}elseif($what == 'npc')
	{
		$sql = "SELECT * FROM rangnpcs WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

  		$_this_armor = $_this['def'];

  		return $_this_armor;
	}elseif($what == 'people' && $what_item != 'all')
	{
		//bestimmte waffe verwenden
		$_this_armor = 0;

		if($what_item == 'weapon_zhw')
		{
			$this_item = get_this_eqi($id,'weapon', 1);

			if(!$this_item)
			{
				$this_item = get_this_eqi($id,'weapon');
				$_this_armor = $_this_armor + $this_item['deff'];
			}else
			{
				$_this_armor = $_this_armor + $this_item['deff'];
			}
		}elseif($what_item == 'shield')
		{
			$this_item = get_this_eqi($id,'shield');

			//schildkunde beachten
			$train = get_user_things($id,'schildkunde');

			//taktik 6 beachte Schild hoch
			if(get_tactic($id) == 6)
			{
				$_this_armor = $_this_armor + round(($this_item['deff'] + $train * 3) * 1.33);
			}else
			{
				$_this_armor = $_this_armor + ($this_item['deff'] + $train * 3);
			}
		}else
		{
			$this_item = get_this_eqi($id,$what_item);
			$_this_armor = $_this_armor + $this_item['deff'];
		}
	}elseif($what == 'people')
	{
		//wenn kein tier dann ist ein user
		$_this_armor = 0;

		//hat user eine zhw an ?
		$this_item = get_this_eqi($id,'weapon', 1);

		//wenn nicht dann nach normaler waffe uns schild pr�fen
		if(!$this_item)
		{
			$this_item = get_this_eqi($id,'weapon');
			$_this_armor = $_this_armor + $this_item['deff'];
			$this_item = get_this_eqi($id,'shield');

			//schildkunde beachten
			$train = get_user_things($id,'schildkunde');

			//taktik 6 beachte Schild hoch
			if(get_tactic($id) == 6)
			{
				$_this_armor = $_this_armor + round(($this_item['deff'] + $train * 3) * 1.33);
			}else
			{
				$_this_armor = $_this_armor + ($this_item['deff'] + $train * 3);
			}

		}else
		{
			$_this_armor = $_this_armor + $this_item['deff'];
		}


		$this_item = get_this_eqi($id,'head');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'shoulder');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'armor');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'lowbody');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'cape');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'belt');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'gloves');
		$_this_armor = $_this_armor + $this_item['deff'];
		$this_item = get_this_eqi($id,'foots');
		$_this_armor = $_this_armor + $this_item['deff'];
	}

	//Einstecken beachten
	$train = get_user_things($id,'Einstecken');
	$_this_armor = $_this_armor + $train * 4;

	//gebet 1 beachten
	if(get_gebet($id) == 1)
	{
		$_this_armor = round($_this_armor * 1.1);
	}

	//taktik 1 beachten Haunei
	if(get_tactic($id) == 1)
	{
		$_this_armor = round($_this_armor * 0.75);
	}

	//taktik 2 beachten  Un�berwindbare Verteidigung
	if(get_tactic($id) == 2)
	{
		$_this_armor = round($_this_armor * 1.2);
	}
	//taktik 3 beachte 2 Waffen sind besser als eine!
	if(get_tactic($id) == 3)
	{
		$_this_armor = round($_this_armor * 0.75);
	}
	return $_this_armor;
}

/*
 * Funktion zum bekommen der SM
 */
function get_moves($id, $type = '')
{
	if($type == 'animal')
	{
		$sql = "SELECT * FROM tiergrube_moves WHERE uid='$id' LIMIT 1";

	}else
	{
		$sql = "SELECT * FROM moves WHERE uid='$id' LIMIT 1";
	}
	$query = mysql_query($sql);
	$_this = mysql_fetch_assoc($query);
	$_this_moves = array();

	$_charisma = get_user_things($id,'charisma');
	$_inteligenz = get_user_things($id,'inteligenz');
	
	foreach($_this as $sm => $count)
	{
		//ids brauch ma nicht
		if($sm == 'uid' || $sm == 'id' )
		{
			continue;
		}
		//gebet 2 beachten
		if(get_gebet($id) == 2)
		{
			$count = $count + 2;
		}

		//taktik 1 beachten Haunei
		if(get_tactic($id) == 1 && $sm == 'todesschlag')
		{
			$count = $count + 5;
		}

		//taktik 3 beachte 2 Waffen sind besser als eine!
		if(get_tactic($id) == 3 && $sm == 'kritischer_schlag')
		{
			$count = $count + 5;
		}

		//taktik 4 beachte 2 Schrei dich an die Macht!
		if(get_tactic($id) == 4 && ($sm == 'wutschrei' || $sm == 'kraftschrei' || $sm == 'sand_werfen' || $sm == 'anti_def'))
		{
			$count = $count + 15;
		}elseif(get_tactic($id) == 4)
		{
			$count = $count - 5;
		}

		//taktik 5 beachte Seine St�rke ist meine St�rke!
		if(get_tactic($id) == 5 && ($sm == 'konter' || $sm == 'taeuschen' || $sm == 'berserker'))
		{
			$count = $count + 20;
		}

		//taktik 6 beachte Schild hoch
		if(get_tactic($id) == 6 && $sm == 'ausweichen')
		{
			$count = $count + 3;
		}
		
		//char Charisma beachten
		if($sm == 'kraftschrei')
		{
			if ($count != 0) {
				$count = $count + round($_charisma / 5);
			}
		}
		
		//char Charisma und inteligenz beachten
		if($sm == 'sand_werfen')
		{
			if ($count != 0) {
				$count = $count + round($_charisma / 6);
				$count = $count + round($_inteligenz / 6);
			}
		}
		
		//char inteligenz beachten
		if($sm == 'wutschrei')
		{
			if ($count != 0) {
				$count = $count + round($_inteligenz / 5);
			}
		}
		
		if($count > 0)
		{
			$_this_moves[$sm] = $count;
		}
	}
	return $_this_moves;
}

/*
 * Funktion zum bekommen eines userwertes
 */
function get_user_things($id,$what = 'all', $type = '')
{
	if($type == 'npc')
	{
		$sql = "SELECT * FROM rangnpcs WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

		return $_this[$what];
	}
	if($type == 'animal')
	{
		$sql = "SELECT * FROM tiergrube WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

		return $_this[$what];
	}
	if($what == 'schule')
	{
		/*
		 * verursacht endlosschleife deswegen
		 */
		return get_schule($id);
	}else
	{
		$sql = "SELECT * FROM user WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);
		$_this_char = array();

		if (!$_this)
		{
			if($what == 'all')
			{
				return array();
			} else
			{
				return '';
			}

		}
		if($what == 'all')
		{
			foreach($_this as $char => $count)
			{
				//gebet 4 beachten +5 inteligenz
				if(get_gebet($id) == 4 && $char == 'inteligenz')
				{
					$count = $count + 5;
				}
				//gebet 5 beachten +5 charisma
				if(get_gebet($id) == 4 && $char == 'charisma')
				{
					$count = $count + 5;
				}
				//taktik 2 beachten  Un�berwindbare Verteidigung
				if(get_tactic($id) == 2 && $char == 'taktik')
				{
					$count = $count + 5;
				}
				$_this_char[$char] = $count;
			}
		}else
		{
			foreach($_this as $char => $count)
			{
				if($char == $what)
				{
					//gebet 4 beachten +5 inteligenz
					if(get_gebet($id) == 4 && $char == 'inteligenz')
					{
						$count = $count + 5;
					}
					//gebet 5 beachten +5 charisma
					if(get_gebet($id) == 4 && $char == 'charisma')
					{
						$count = $count + 5;
					}
					//taktik 2 beachten  Un�berwindbare Verteidigung
					if(get_tactic($id) == 2 && $char == 'taktik')
					{
						$count = $count + 5;
					}
					$_this_char = $count;
				}
			}
		}
		return $_this_char;
	}
}
/*
 * Funktion zum berechnen der aktuellen Kraft
 */
function get_health($id,$what = 'people')
{
	if($what == 'animal')
	{
		$sql = "SELECT * FROM tiergrube WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

  		$_this_health = $_this['health'];
	}elseif($what == 'npc')
	{
		$sql = "SELECT * FROM rangnpcs WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		$_this = mysql_fetch_assoc($query);

  		$_this_health = $_this['hp'];
	}else
	{
		//wenn kein tier dann ist ein user
		$_this_health = 10; //Startwert

		$staerke = get_user_things($id,'staerke');
		$_this_health = $_this_health +  $staerke * 50;

		$kondition = get_user_things($id,'kondition');
		$_this_health = $_this_health +  $kondition * 120;

		$geschick = get_user_things($id,'geschick');
		$_this_health = $_this_health +  $geschick * 5;

		$heilkunde = get_user_things($id,'heilkunde');
		$_this_health = $_this_health +  $heilkunde * 30;

		$kraftprotz = get_user_things($id,'Kraftprotz');
		$_this_health = $_this_health +  $kraftprotz * 50;

		$level = get_user_things($id,'level');
		$_this_health = $_this_health +  $level * 50;
	}

	  return $_this_health;
}

/*
 *  Funktion um herauszufinden in welcher schule man sich befindet
 */
function get_schule($user_id)
{
	$sql = "SELECT * FROM user WHERE id='$user_id' LIMIT 1";
	$query = mysql_query($sql);
	$_this = mysql_fetch_assoc($query);

	$schul_id = $_this['schule'];
	return $schul_id;
}
/*
 *  Funktion um herauszufinden ob die schule gerade ein aktuelles Gebet hat
 */
function get_gebet($user_id)
{
	$schul_id = get_schule($user_id);
	if($schul_id != 0)
	{
		$GebetSql = "SELECT * FROM ally_gebet WHERE schule='$schul_id' LIMIT 1";
		$GebetQuery = mysql_query($GebetSql);
		$Gebet = mysql_fetch_assoc($GebetQuery);
		if($Gebet['time'] >= time() && $Gebet)
		{
			switch($Gebet['effekt'])
			{
				case 1:
					/*
					 * Off und Deff +10%
					 */
					$gebet = 1;
				break;

				case 2:
					/*
					 * alle SM + 2
					 */
					$gebet = 2;
				break;

				case 3:
					/*
					 * Schmiedepreise sinken um 15%
					 */
					$gebet = 3;
				break;

				case 4:
					/*
					 * intelligenz + 5
					 */
					$gebet = 4;
				break;

				case 5:
					/*
					 * charisma + 5
					 */
					$gebet = 5;
				break;

				case 6:
					/*
					 * Belohnung in der Arena steigt um 25%
					 */
					$gebet = 6;
				break;

				case 7:
					/*
					 * Droprate steigt im 5%
					 */
					$gebet = 7;
				break;

				default:
					$gebet = 0;
			}
		}else
		{
			$gebet = 0;
		}
		return $gebet;
	}else
	{
		return 0;
	}
}

/*
 *  Funktion um herauszufinden ob die schule gerade ein aktuelles Gebet hat
 */
function get_tactic($user_id)
{
	$schul_id = get_user_things($user_id, 'schule');

	if ($schul_id != 0)
	{
		$sql = "SELECT * FROM ally_schule WHERE id='$schul_id' LIMIT 1";
		$query = mysql_query($sql);
		$select = mysql_fetch_assoc($query);

		$tactic = 0;

		switch ($select['tactic'])
		{
			case 0:
				/*
				 * nichts
				 */
				$tactic = 0;
			break;

			case 1:
				/*
				 * Hau nei!
				 * 18% mehr Off auf deine Hauptwaffe
				 * und einen um 5 Punkte verbesserten Todesschlag,
				 * daf�r hast du aber auch nurnoch 75% deiner Def!
				 */
				$tactic = 1;
			break;

			case 2:
				/*
				 * Un�berwindbare Verteidigung
				 * 20% mehr Def und
				 * erh�ht deine Taktik um 5,
				 * daf�r kannst du aber auch nurnoch 82% deiner Off nutzen!
				 */
				$tactic = 2;
			break;

			case 3:
				/*
				 * 2 Waffen sind besser als eine!
				 * Diese Taktik erh�ht die Off deiner Zweitwaffe um 50%
				 * und bringt dir 5 Punkte mehr im kritischen Schlag,
				 * einziger nachteil sind deine um 25% veringerten Def!
				 */
				$tactic = 3;
			break;

			case 4:
				/*
				 * Schrei dich an die Macht!
				 * Diese Taktik erh�ht Wutschrei, Kraftschrei, Sand werfen und Anti-Defense um 15 Punkte,
				 * verringert aber jeden anderen Move um 5 Punkte!
				 */
				$tactic = 4;
			break;

			case 5:
				/*
				 * Seine St�rke ist meine St�rke!
				 * Diese Takik erh�ht die Wahrscheinlichkeit und St�rke von Konter, T�uschen und Berserker enorm!"
				 */
				$tactic = 5;
			break;

			case 6:
				/*
				 * Schild hoch!
				 * Diese Taktik erh�ht die Def deines Schildes um 33%
				 * und gibt dir 3 Punkte in Ausweichen,
				 * allerdings sind Zweitwaffen nurnoch halb so wirkungsvoll
				 * und dein allgemeiner Waffenschaden sinkt um 10% wenn du ein Schild tr�gst
				 */
				$tactic = 6;
			break;
		}

		return $tactic;

	}else
	{
		return 0;
	}
}

/*
 * Quest pr�fen
 */
function check_quest($userid, $what, $count)
{
	$query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$userid."' LIMIT 1");
	$quest_now = @mysql_fetch_assoc($query);

	if($quest_now['typ'] == $what)
	{
		mysql_query("UPDATE quest_what SET count=count+".$count." WHERE user_id='".$userid."' LIMIT 1");
	}
}
?>
