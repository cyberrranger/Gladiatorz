<script language="javascript" type="text/javascript">
<!--

function openKB(KBNum)
{
  document.getElementById('kb'+KBNum).style.visibility = 'visible';

  if(KBNum == 0)
  {
    document.getElementById('body').style.overflow = 'hidden';
    window.scrollTo(0,190);
  }
}

function hideKB(KBNum)
{
  document.getElementById('kb'+KBNum).style.visibility = 'hidden';

  if(KBNum == 0)
  {
    document.getElementById('body').style.overflow = 'auto';
    window.scrollTo(0,0);
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
$KBN = 0;
$Coolness = 0;

class Fighter
{
	function getAbilitys($ID)
	{
		$Sql = "SELECT * FROM user WHERE id='$ID' LIMIT 1";
		$Select = mysql_query($Sql);
		$User = mysql_fetch_assoc($Select);

		$Sql2 = "SELECT * FROM moves WHERE id='$ID' LIMIT 1";
		$Select2 = mysql_query($Sql2);
		$moves = mysql_fetch_assoc($Select2);

		foreach($User as $Ability => $Assign) //alles aus User wird aus der DB geholt
		{
			$this->$Ability = $Assign;
		}

		foreach($moves as $move => $mov) //moves werden aus der DB geholt
		{
			$this->$move = $mov;
		}

		$this->ID = $this->id;
		$this->Name = $this->name;

		$this->Kind = 'Human';
		$this->HP = (($this->staerke*50)+($this->kondition*120)+($this->geschick*5)+($this->heilkunde*30)+($this->level*50)+($this->Kraftprotz*50)); // maximale HP am anfang des Duells
		$this->minimal = $this->HP;

		//Array f�r alle Arten von Items
		$ItemTitle = array(
		'head',
		'shoulder',
		'lowbody',
		'cape',
		'belt',
		'gloves',
		'foots');

		for($i=0;$i<7;$i++)
		{
			$Item = getEquipment($User['id'],$ItemTitle[$i]);
			$this->PlusOff += $Item['off'];
			$this->PlusDef += $Item['deff'];
		}

		$this->EqpWeapon = get_equipment($ID, 'weapon');
		$this->EqpArmor = get_equipment($ID, 'armor');
		$this->EqpShield = get_equipment($ID, 'shield');


		$this->EqpWeapon['off'] = $this->EqpWeapon['off']  + (($this->EqpWeapon['off']/150)*(($this->waffenkunde+$this->staerke)/2)) + (($this->EqpWeapon['off']/100)*($this->level/3));
		$this->EqpShield['off'] = $this->EqpShield['off']  + (($this->EqpShield['off']/150)*(($this->zweiwaffenkampf+$this->staerke)/2)) + (($this->EqpShield['off']/100)*($this->level/3));
		$this->EqpShield['deff'] = $this->EqpShield['deff']  + (($this->EqpShield['deff']/150)*(($this->schildkunde+$this->geschick)/2)) + (($this->EqpShield['deff']/100)*($this->level/3));

		$this->Off = $this->EqpWeapon['off'] + $this->EqpArmor['off'] + $this->EqpShield['off'] + $this->PlusOff + ($this->Schlagkraft*5);
		$this->Deff = $this->EqpWeapon['deff'] + $this->EqpArmor['deff'] + $this->EqpShield['deff'] + $this->PlusDef + ($this->Einstecken*4);

		if($this->schule != 0)
		{
			$GebetSql = "SELECT * FROM ally_gebet WHERE schule='$this->schule' LIMIT 1";
			$GebetQuery = mysql_query($GebetSql);
			$Gebet = mysql_fetch_assoc($GebetQuery);

			if($Gebet['time'] >= time())
			{
				switch($Gebet['effekt'])
				{
					case 1: // 10% off/deff
					$this->Off = $this->Off *1.1;
					$this->Deff = $this->Deff *1.1;
					break;

					case 2: // SM +2
					$this->kraftschlag +=2;
					$this->wutschrei +=2;
					$this->armor +=2;
					$this->kritischer_schlag +=2;
					$this->wundhieb +=2;
					$this->kraftschrei +=2;
					$this->block +=2;
					$this->taeuschen +=2;
					$this->koerperteil_abschlagen +=2;
					$this->sand_werfen +=2;
					$this->ausweichen +=2;
					$this->todesschlag +=2;
					$this->konter +=2;
					$this->berserker +=2;
					$this->anti_def +=2;
					break;

					case 4:
					$this->inteligenz += 5;
					break;

					case 5:
					$this->charisma += 5;
					break;
				}
			}

			$SchoolSql = "SELECT id,name,tactic FROM ".TABALLY." WHERE id='$this->schule' LIMIT 1";
			$SchoolQuery = mysql_query($SchoolSql);
			$School = mysql_fetch_assoc($SchoolQuery);

			switch($School['tactic']) //tag 4
			{
				case 1: //Hau nei
					$this->Off += round(($this->EqpWeapon['off']/100)*15);
					$this->Deff += round(($this->Deff/100)*5);
					$this->wundhieb +=2;
				break;

				case 2: //un�berwindbare verteidigung
					$this->Deff += round(($this->Deff/100)*15);
					if($this->EqpShield['off'] < $this->EqpShield['deff']) { $this->Deff -= ($this->EqpShield['deff']*0.15); }
					$this->kritischer_schlag +=2;
				break;

				case 3: //2 Waffen sind besser als eine
					if($this->EqpShield['off'] > $this->EqpShield['deff']) { $this->Off += ($this->EqpShield['off']*0.5); }
					$this->Deff += round(($this->Deff/100)*5);
					$this->geschick +=3;
				break;

				case 4: //Schrei der macht
					$this->kraftschlag -=5;
					$this->wutschrei +=15;
					$this->armor -=5;
					$this->kritischer_schlag -=5;
					$this->wundhieb -=5;
					$this->kraftschrei +=15;
					$this->block -=5;
					$this->taeuschen -=5;
					$this->koerperteil_abschlagen -=5;
					$this->sand_werfen +=15;
					$this->ausweichen -=5;
					$this->todesschlag -=5;
					$this->konter -=5;
					$this->berserker -=5;
					$this->anti_def +=15;
				break;

				case 6: //Schild hoch
					if($this->EqpShield['off'] < $this->EqpShield['deff']) { $this->Deff += ($this->EqpShield['deff']*0.40); }
					$this->anti_def +=3;
					$this->kritischer_schlag -=5;
					$this->wundhieb -=5;
					$this->koerperteil_abschlagen -=5;
				break;
			}
		}

		$this->Off = round($this->Off,0);
		$this->Deff = round($this->Deff,0);
		$this->HP = round($this->HP,0);

		$this->Bluten = 0; // bleed rounds
		$this->BlutenDam = 0; // bleed damage

		$this->StatPoison = 0; // poison rounds
		$this->StatPoisonDam = 0; // poison damage

		$this->AntiDef = 0;

		$this->Paralysis = 0; // paralysis rounds

		$this->Schreien = 0; // warSchreien rounds
		$this->SchreienDam = 0; // warSchreien higher damage percent

		$this->Death = false;
	}
}
class Animal
{
  function getAbilitys($ID)
  {
    $Sql = "SELECT * FROM tiergrube WHERE id='$ID' LIMIT 1";
    $Select = mysql_query($Sql);
    $Animal = mysql_fetch_assoc($Select);

	foreach($Animal as $Ability => $Assign)
	{
	  $this->$Ability = $Assign;
	}

	$this->Kind = 'Animal';

	$this->Off = $this->attack*1.0;
	$this->Deff = $this->armor*1.0;

	$this->Name = $this->name;
	$this->HP = $this->health*1.0;
	$this->minimal = $this->HP;

	$this->Evade = $this->exp * 4;

	$this->StatPoison = 0; // poison rounds
	$this->StatPoisonDam = 0; // poison damage

	$this->Bluten = 0; // bleed rounds
	$this->BlutenDam = 0; // bleed damage

	$this->Paralysis = 0; // paralysis rounds

	$this->AntiDef = 0;

	$this->Schreien = 0; // warSchreien rounds
	$this->SchreienDam = 0; // warSchreien higher damage percent

	$this->Death = false;
  }
}
class NPC
{
  function getAbilitys($ID)
  {
    $Query = @mysql_query("SELECT * FROM ".TAB_NPCS." WHERE id='".$ID."' LIMIT 1");
    $NPC = @mysql_fetch_assoc($Query);

	foreach($NPC as $Ability => $Assign)
	{
	  $this->$Ability = $Assign;
	}

	$this->Kind = 'Human';

	$this->Bite = 0;

	$this->Off = $this->off;
	$this->Deff = $this->def;

	$this->Name = $this->name;
	$this->HP = $this->hp;
	$this->minimal = $this->HP;

	$this->ausweichen = rand(1,20);
	$this->block = rand(1,20);
	$this->armor = rand(1,20);

	$this->StatPoison = 0; // poison rounds
	$this->StatPoisonDam = 0; // poison damage

	$this->Bluten = 0; // bleed rounds
	$this->BlutenDam = 0; // bleed damage

	$this->Paralysis = 0; // paralysis rounds

	$this->AntiDef = 0;

	$this->Schreien = 0; // warSchreien rounds
	$this->SchreienDam = 0; // warSchreien higher damage percent

	$this->Death = false;

	$this->staerke = rand(1,20);
	$this->geschick = rand(1,20);
	$this->kondition = rand(1,20);
	$this->charisma = rand(1,20);
  }
}
class Battle
{
	function showTeams($G1, $G2) //Funktion um die beiden teams nach dem Kampf anzuzeigen.
	{
		global $Report,$Show;
		$Show = '<center><strong style="font-size:20px;">Auf in den Kampf!</strong></center><br />';
		$MinHeight = 50;

		if(count($G1) >= count($G2))
		{
			$MinHeight = count($G1) * $MinHeight;
		}
		else
		{
			$MinHeight = count($G2) * $MinHeight;
		}

		$Show.= '<fieldset style="width:45%;float:left;height:'.$MinHeight.'px;"><legend>Erstes Team</legend>';

		foreach($G1 as $ShowTeam)
		{
			$Show.= '<div style="font-size:10px;"><strong>'.$ShowTeam->Name.'</strong>
			<br />('.$ShowTeam->HP.' Kraft | '.$ShowTeam->Off.' Off | '.$ShowTeam->Deff.' Deff)</div><br />';
		}

		$Show.= '</fieldset>';

		$Show.= '<fieldset style="width:45%;float:none;height:'.$MinHeight.'px;"><legend>Zweites Team</legend>';

		foreach($G2 as $ShowTeam)
		{
			$Show.= '<div style="font-size:10px;"><strong>'.$ShowTeam->Name.'</strong>
			<br />('.$ShowTeam->HP.' Kraft | '.$ShowTeam->Off.' Off | '.$ShowTeam->Deff.' Deff)</div><br />';
		}

		$Show.= '</fieldset>';
	}

	function StatusAnzeige($Rounds,$Guy, $Kind) //Funktion um ggf. den ver�nderten Status anzuzeigen
	{
		$KindDam = $Kind.'Dam';

		switch($Kind)
		{
			case 'Bluten':

				$Message = '<em><b>'.$Guy->Name.'</b></em> blutet und verliert '.$Guy->BlutenDam.' HP! <em><b>'.$Guy->Name.'</b></em> wird noch '.$Guy->Bluten.' Runden bluten und hat nur noch '.$Guy->HP.' HP.';
			break;

			case 'StatPoison':

				$Message = '<em><b>'.$Guy->Name.'</b></em> ist vergiftet und verliert '.$Guy->StatPoisonDam.' HP! <em><b>'.$Guy->Name.'</b></em> ist noch '.$Guy->StatPoison.' Runden vergiftet und hat nur noch '.$Guy->HP.' HP.';

			break;

			case 'Schreien':

				$Message = '<em><b>'.$Guy->Name.'</b></em> wird durch seinen Schrei motiviert! In <em><b>'.$Guy->Schreien.'</b></em> Runden wird die Wirkung abflauen.';
			break;

			case 'AntiDef':

				$Message = '<em><b>'.$Guy->Name.'</b></em> kann seine R�stung nicht nutzen! In <em><b>'.$Guy->AntiDef.'</b></em> Runden wird die Wirkung abflauen.';
			break;
		}


		return $this->Tab($Rounds,$Message);
	}

	function showDefenseTab($Rounds,$Guy,$Enemy,$Damage,$DamageAgainst,$DefAktion)
	{
		if($Damage < 0)
		{
			$Damage = 0;
		}
		if($DamageAgainst < 0)
		{
			$DamageAgainst = 0;
		}

		$Guy->HP -= $Damage;
		$Enemy->HP -= $DamageAgainst;

		switch($DefAktion)
		{
			case 'ausweichen':
				$Message = '<b>'.$Enemy->Name.'</b> kann den un�berlegten Schlag von <b>'.$Guy->Name.'</b> mit Leichtigkeit Ausweichen';
			break;

			case 'taeuschen':
				$Message = '<b>'.$Enemy->Name.'</b> kann <b>'.$Guy->Name.'</b> t�uschen und erleidet nur <b>'.$Damage.'</b> Schaden!<br> Gleichzeitig kann <b>'.$Enemy->Name.'</b> einen leichten Schlag bringen und verursacht <b>'.$DamageAgainst.'<b> Schaden!<br><b>'.$Enemy->Name.'</b>k�mpft mit <b>'.$Enemy->HP.'</b> und <b>'.$Guy->Name.'</b> mit <b>'.$Guy->HP.'</b> Kraft weier.';
			break;

			case 'berserker':
				$Message = '<b>'.$Guy->Name.'</b> dringt durch <b>'.$Enemy->Name.'</b> R�stung und kann <b>'.$Damage.'</b> Schaden verursachen.<br>Erb�st drischt <b>'.$Enemy->Name.'</b> auf <b>'.$Guy->Name.'</b> ein und verursacht <b>'.$DamageAgainst.'<b> Schaden!<br><b>'.$Enemy->Name.'</b>k�mpft mit <b>'.$Enemy->HP.'</b> und <b>'.$Guy->Name.'</b> mit <b>'.$Guy->HP.'</b> Kraft weier.';
			break;

			case 'block':
				$Message = '<b>'.$Enemy->Name.'</b> kann den Gro�teil des Schlages blockn und erleidet nur <b>'.$Damage.'</b> Schaden,<br>mit <b>'.$Enemy->HP.'</b> kann <b>'.$Enemy->Name.'</b> weiterk�mpfen';
			break;

			case 'konter':
				$Message = '<b>'.$Enemy->Name.'</b> kann den Schlag blocken und erleidet nur <b>'.$Damage.'</b>, darauf kontert <b>'.$Enemy->Name.'</b> und verursacht <b>'.$DamageAgainst.'<b> Schaden bei <b>'.$Guy->Name.'!</b><br><b>'.$Enemy->Name.'</b>k�mpft mit <b>'.$Enemy->HP.'</b> und <b>'.$Guy->Name.'</b> mit <b>'.$Guy->HP.'</b> Kraft weier.';
			break;

			case 'armor':
				$Message = '<b>'.$Enemy->Name.'</b> kann einen Teil des Schlages blockn und erleidet nur <b>'.$Damage.'</b> Schaden,<br>mit <b>'.$Enemy->HP.'</b> kann <b>'.$Enemy->Name.'</b> weiterk�mpfen';
			break;
		}

		return $this->Tab($Rounds,$Message);
	}

	function showAttackTab($Rounds,$Guy,$Enemy,$Damage,$Aktion)
	{

		switch($Aktion)
		{
			case 'Hit':
				$Message = '<b>'.$Guy->Name.'</b> greift an und verursacht <b>'.$Damage.'</b> Schaden! <br><b>'.$Enemy->Name.'</b> hat noch <b>'.$Enemy->HP.'</b> Kraft.';
			break;

			case 'kraftschlag':
				$Message = '<b>'.$Guy->Name.'</b> holt aus und trifft <b>'.$Enemy->Name.'</b> mit einem Kraftschlag. <br><b>'.$Enemy->Name.'</b> nimmt <b>'.$Damage.'</b> Schaden und hat noch <b>'.$Enemy->HP.'</b> Kraft.';
			break;

			case 'kritischer_schlag':
				$Message = '<b>'.$Guy->Name.'</b> trifft <b>'.$Enemy->Name.'</b>`s Schwachstelle und verursacht  <b>'.$Damage.'</b> Schaden! <br><b>'.$Enemy->Name.'</b> hat noch <b>'.$Enemy->HP.'</b> Kraft.';
			break;

			case 'wundhieb':
				$Message = '<b>'.$Guy->Name.'</b> trifft <b>'.$Enemy->Name.'</b> und verursacht <b>'.$Damage.'</b>. <br><b>'.$Enemy->Name.'</b> hat eine tiefe Fleischwunde und nur noch <b>'.$Enemy->HP.'</b> Kraft.';
			break;

			case 'todesschlag':
				$Rand = rand(0,2);
				switch($Rand)
				{
					case 0:
						$Message = '<b>'.$Guy->Name.'</b> hackt <b>'.$Enemy->Name.'</b> den Kopf ab, ohne Kopf wird es wohl schwer mit dem Weiterk�mpfen';
					break;

					case 1:
						$Message = '<b>'.$Guy->Name.'</b> rammt seine Waffe mit voller Wucht in <b>'.$Enemy->Name.'</b>`s Herz. <b>'.$Enemy->Name.'</b> h�ngt leblos an <b>'.$Guy->Name.'</b>`s Waffe!';
					break;

					case 2:
						$Message = '<b>'.$Guy->Name.'</b> springt in die Luft und zerteilt <b>'.$Enemy->Name.'</b> mit einen vertikalen Todesschlag von oben nach unten!';
					break;
				}
			break;

			case 'koerberteil_abschlagen':
				$Rand = rand(0,2);
				switch($Rand)
				{
					case 0:
						$Message = '<em><b>'.$Guy->name.'</b></em> hackt <em><b>'.$Enemy->name.'</b></em> einen Arm ab und macht <b>'.$Damage.'</b> Schaden!<br><b>'.$Enemy->name.'</b> k�mpft mit einen Arm und <b>'.$Enemy->HP.'</b> Kraft weiter!';
					break;

					case 1:
						$Message = '<em><b>'.$Guy->name.'</b></em> schl�gt <em><b>'.$Enemy->name.'</b></em> ein Bein ab und macht <b>'.$Damage.'</b> Schaden!<br><b>'.$Enemy->name.'</b> humpelt durch die Arena und hat noch <b>'.$Enemy->HP.'</b> Kraft!';
					break;

					case 2:
						$Message = '<em><b>'.$Guy->name.'</b></em> schlitzt <em><b>'.$Enemy->name.'</b></em> den Bauch auf und macht <b>'.$Damage.'</b> Schaden!<br><b>'.$Enemy->name.'</b>`s R�stung f�rbt sich blutrot, mit <b>'.$Enemy->HP.'</b> Kraft gehts weiter!';
					break;
				}
			break;

			case 'sand_werfen':
				$Message = '<b>'.$Guy->Name.'</b> nimmt eine Hand voll Sand vom Boden und wirft sie <b>'.$Enemy->name.'</b> in die Augen, <br>dieser ist erstmal geblendet und kann nicht angreifen.';
			break;

			case 'wutschrei':
				$Message = '<b>'.$Guy->Name.'</b> ist so w�tend auf <b>'.$Enemy->name.'</b> das er nur noch schreind auf ihn losst�rmt.<br><b>'.$Guy->Name.'</b> Statuswerte erh�hen sich!';
			break;

			case 'kraftschrei':
				$Message = '<b>'.$Guy->Name.'</b> schreit in den Himmel, ein heiliger Schein f�llt auf ihn und heilt seine Wunden.<br><b>'.$Guy->Name.'</b> hat jetzt wieder <b>'.$Guy->HP.' Kraft!</b>';
			break;

			case 'anti-def':
				$Message = '<b>'.$Guy->Name.'</b> analysiert <b>'.$Enemy->name.'</b> R�stung und findet alle Schwachstelln.<br><b>'.$Enemy->name.'</b> hat nun geine R�stungswirkung mehr!</b>';
			break;

			case 'Biss':
				$Message = '<em><b>'.$Guy->name.'</b></em> bei�t <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden!';
			break;

			case 'vergiften':
				$Message = '<em><b>'.$Guy->name.'</b></em> vergiftet <em><b>'.$Enemy->name.'</b></em> und macht '.$Damage.' Schaden. <em><b>'.$Enemy->name.'</b></em> ist vergiftet!';
			break;

			case 'Hufschlag':
				$Message = '<em><b>'.$Guy->name.'</b></em> trifft <em><b>'.$Enemy->name.'</b></em> mit seinen Hufen und verursacht '.$Damage.' Schaden!';
			break;

			case 'HufschlagWund':
				$Message = '<em><b>'.$Guy->name.'</b></em> trifft <em><b>'.$Enemy->name.'</b></em> mit seinen Hufen und verursacht '.$Damage.' Schaden! <em><b>'.$Enemy->name.'</b></em> blutet.';
			break;

			case 'Aufspie�en':
				$Message = '<em><b>'.$Guy->name.'</b></em> spie�t <em><b>'.$Enemy->name.'</b></em> mit seinen H�rnern auf, <em><b>'.$Enemy->name.'</b></em> hat eine gro�e Wunde und verliert '.$Damage.' Kraft!';
			break;

			case 'Pferdekuss':
				$Message = '<em><b>'.$Guy->name.'</b></em> verabreicht <em><b>'.$Enemy->name.'</b></em> einen Pferdekuss und verursacht '.$Damage.' Schaden!';
			break;

			case 'Rammen':
				$Message = '<em><b>'.$Guy->name.'</b></em> rammt <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden!';
			break;

			case 'RammenBlenden':
				$Message = '<em><b>'.$Guy->name.'</b></em> rammt mit voller Wucht <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden! <em><b>'.$Enemy->name.'</b></em> ist davon wie bet�ubt.';
			break;

			case 'Bellen':
				$Message = '<em><b>'.$Guy->name.'</b></em> bellt <em><b>'.$Enemy->name.'</b></em> an. <em><b>'.$Enemy->name.'</b></em> kann sich vor Angst nicht Bewegen!';
			break;

			case 'Kopfsto�':
				$Message = '<em><b>'.$Enemy->name.'</b></em> wird von <em><b>'.$Guy->name.'</b></em>s Kopf getroffen und erleidet '.$Damage.' Schaden!';
			break;

			case 'Schnabel':
				$Message = '<em><b>'.$Guy->name.'</b></em> kann <em><b>'.$Enemy->name.'</b></em> mit seinen Schnabel bei�en. <em><b>'.$Enemy->name.'</b></em> hat eine Platzwunde und '.$Damage.' Schaden!';
			break;

			case 'Tritt':
				$Message = '<em><b>'.$Guy->name.'</b></em> tritt <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden!';
			break;

			case 'Sturzflug':
				$Message = '<em><b>'.$Guy->name.'</b></em> kommt auf den Wolgen steil herab geflogen und trift <em><b>'.$Enemy->name.'</b></em>, dieser erleidet '.$Damage.' Schaden!';
			break;

			case 'Schlag':
				$Message = '<em><b>'.$Guy->name.'</b></em> schl�gt <em><b>'.$Enemy->name.'</b></em>, dieser ist davon wie benommen und erleidet '.$Damage.' Schaden!';
			break;

			case 'Lauerangriff':
				$Message = '<em><b>'.$Guy->name.'</b></em> lauert auf seine Chance, fletscht die Z�hne und ist auser Rand und Band. <em><b>'.$Guy->name.'</b></em> greift <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden!';
			break;

			case 'Kratzen':
				$Message = '<em><b>'.$Guy->name.'</b></em> kratzt <em><b>'.$Enemy->name.'</b></em> �ber sein dreckiges Gesicht und verursacht '.$Damage.' Schaden! <em><b>'.$Enemy->name.'</b></em> blutet.';
			break;

			case 'Kehlenbiss':
				$Message = '<em><b>'.$Guy->name.'</b></em> springt an <em><b>'.$Enemy->name.'</b></em>s Khle und beist zu! <em><b>'.$Enemy->name.'</b></em> erleidet '.$Damage.' Schaden!';
			break;

			case 'Prankenhieb':
				$Message = '<em><b>'.$Guy->name.'</b></em> schl�gt mit seiner Pranke zu und verursacht '.$Damage.' Schaden!';
			break;

			case 'R�ssel':
				$Message = '<em><b>'.$Guy->name.'</b></em> schwingt seinen R�ssel mit voller Wucht  und verursacht '.$Damage.' Schaden!';
			break;

			case 'koangriff':
				$Message = '<em><b>'.$Guy->name.'</b></em> bereiten sich auf einen Angriff gegen <em><b>'.$Enemy->name.'</b></em> vor. Ihre Statuswerte erh�hen sich und sie greifen an. <em><b>'.$Enemy->name.'</b></em> erleidet '.$Damage.' Schaden!';
			break;

			case 'Heulen':
				$Message = '<em><b>'.$Guy->name.'</b></em> heult dem Mond zu und erh�ht den Schaden f�r die n�chsten Runden!';
			break;

			case 'Schwanzschlag':
				$Message = '<em><b>'.$Guy->name.'</b></em> zieht <em><b>'.$Enemy->name.'</b></em> mit seinen Schwanz die Beine weg und l�sst seinen Gegner so schnell nicht wieder aufstehen!';
			break;

			case 'Hornsto�':
				$Message = '<em><b>'.$Guy->name.'</b></em> durchbohrt mit seinen H�rnern <em><b>'.$Enemy->name.'</b></em>! <em><b>'.$Enemy->name.'</b></em> hat eine gro�e Wunde und verliert '.$Damage.' Kraft!';
			break;

			case '�berrennen':
				$Message = '<em><b>'.$Guy->name.'</b></em> �berrennt <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden!';
			break;

			case '�berrennenBlenden':
				$Message = '<em><b>'.$Guy->name.'</b></em> �berrennt <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden! <em><b>'.$Enemy->name.'</b></em> ist davon wie bet�ubt.';
			break;

			case 'Jagt':
				$Message = '<em><b>'.$Guy->name.'</b></em> jagt mit verbesserten Instinkten hinter <em><b>'.$Enemy->name.'</b></em> her und verursacht '.$Damage.' Schaden! Vor Ersch�pfung kann <em><b>'.$Enemy->name.'</b></em> nicht angreifen';
			break;

			case 'Tollwut':
				$Message = 'Der tollw�tige <em><b>'.$Guy->name.'</b></em> beisst <em><b>'.$Enemy->name.'</b></em> und verursacht '.$Damage.' Schaden! Dieser ist jetzt auch angesteckt';
			break;

			case 'Wutgeheul':
				$Message = '<em><b>'.$Guy->name.'</b></em> heult vor Wut und erholt sich!';
			break;

			case 'Zertrampeln':
				$Message = '<em><b>'.$Guy->name.'</b></em> zertrampelt <em><b>'.$Enemy->name.'</b></em>!<br> Man erkennt nurnoch einen roten Brei unter <em><b>'.$Guy->name.'</b></em>.';
			break;

			case 'trampelt':
				$Message = '<em><b>'.$Guy->name.'</b></em> trampelt &uuml;ber <em><b>'.$Enemy->name.'</b></em>!<br> und verursacht '.$Damage.' Schaden!';
			break;

			case 'R�stungZerst�ren':
				$Message = '<em><b>'.$Guy->name.'</b></em> Schl�gt mit seinem R�ssel ein gro�es Loch in <em><b>'.$Enemy->name.'</b></em> R�stung!';
			break;
		}

		return $this->Tab($Rounds,$Message);
	}

	function showSpecialTab($Rounds,$Name,$Runden,$Modus)
	{
		if($Modus == 'Paralysis')
		{
			$Runden -= 1;
			if($Runden == 0){ $Message = '<em><b>'.$Name.'</b></em> ist kaum noch geblendet, gleich kann er wieder angreifen.';}
			else{$Message = '<em><b>'.$Name.'</b></em> ist noch '.$Runden.' Runden geblendet und kann nicht angreifen.';}
		}
		else
		{
			$Message = '<em><b>'.$Name.'</b></em> liegt tot in der Arena...';
			$Message = '<span style="background-color:lightblue;">'.$Message.'</span>';
		}
		return $this->Tab($Rounds,$Message);
	}

	function Tab($Rounds,$String)
	{
		$Tab = '';

		if($Rounds % 2 != 0)
		{
			$Color = '#ececda'; // ungerade
		}
		else
		{
			$Color = '#d2d2b7'; // gerade
		}

		$Tab.=
		'<tr style="background-color:'.$Color.';">
		<td style="font-size:14px;border:1px solid black;padding:2px;width:20px;text-align:center;">'.$Rounds.'</td>
		<td style="font-size:10px;border:1px solid black;padding:2px;">'.$String.'</td>
		</tr>';

		return $Tab;
	}

	function countDeadMembers($Group)
	{
		$Kills = 0;
		foreach($Group as $Ask)
		{
			if($Ask->Death == true)
			{
				$Kills++;
			}
		}

		return $Kills;
	}

	function attack(&$G1, &$G2, $Rounds)
	{
		global $Report,$Coolness;

		$G1Kill = 0; //Angreifer
		$G2Kill = 0; //Verteidiger

		reset($G1);
		reset($G2);

		while (list($key, $Attacker) = each($G1))
		{
			$Random = null;
			$Default = null;

			$Damage = 0;
			$Aktion = null;

			$BleedRounds = 0;
			$BleedDam = 0;

			$PoisonRounds = 0;
			$PoisonDam = 0;

			$DefAktion = null;
			$DamageAgainst = null;

			if ($Attacker->Death == true)
			{

				continue;
			}

			$G1Kill = $this->countDeadMembers($G1);
			$G2Kill = $this->countDeadMembers($G2);

			if($G1Kill == count($G1) || $G2Kill == count($G2)) break;

			$RandEnemy = rand(1,count($G2));
			$LoopEnd = $G2["$RandEnemy"]->Death;

			while($LoopEnd == true)
			{
				$RandEnemy = rand(1,count($G2));
				$LoopEnd = $G2["$RandEnemy"]->Death;
			}

			if($Attacker->StatPoison >= 1) // member is poisoned
			{
				$Attacker->StatPoisonDam *= round(rand(90,110)/100);

				if($Attacker->StatPoisonDam < $Attacker->HP) // member will not die
				{
					$Attacker->HP -= $Attacker->StatPoisonDam;

					$Report .= $this->StatusAnzeige($Rounds, $Attacker,'StatPoison');

					$Attacker->StatPoison--;
				}
				else // member die
				{
					$Attacker->HP = 0;
					$Attacker->Death = true;

					$Report .= $this->StatusAnzeige($Rounds, $Attacker,'StatPoison');
					$Report .= $this->showSpecialTab($Rounds, $Attacker->Name,'Death');

					$Coolness += 25;

					continue;
				}
			}

			if($Attacker->Bluten >= 1) // member is bleeding
			{
				$Attacker->BlutenDam *= round(rand(90,110)/100);

				if($Attacker->BlutenDam < $Attacker->HP) // member will not die
				{
					$Attacker->HP -= $Attacker->BlutenDam;

					$Report .= $this->StatusAnzeige($Rounds, $Attacker, 'Bluten');

					$Attacker->Bluten--;
				}
				else // member die
				{
					$Attacker->HP = 0;
					$Attacker->Death = true;

					$Report .= $this->StatusAnzeige($Rounds, $Attacker,'Bluten');
					$Report .= $this->showSpecialTab($Rounds, $Attacker->Name,$Attacker->Paralysis,'Death');

					$Coolness += 20;

					continue;
				}
			}

			if($Attacker->AntiDef >= 1) // member Schreit
			{
				$Report .= $this->StatusAnzeige($Rounds, $Attacker, 'AntiDef');
				$Attacker->AntiDef--;
			}

			if($Attacker->Schreien >= 1) // member Schreit
			{
				$Report .= $this->StatusAnzeige($Rounds, $Attacker, 'Schreien');
				$Attacker->Schreien--;
			}

			if($Attacker->Kind == 'Human') // different kinds make different specials
			{
				If($Attacker->kraftschlag >= 1 AND $Attacker->wutschrei >= 1)
				{
					$Random = rand(1,9);
					if($Random = 9) { $Random = 34;}
				}
				elseif($Attacker->kraftschlag >= 1 AND $Attacker->koerperteil_abschlagen < 1 AND $Attacker->level <= 70)
				{
					$Random = rand(1,3);
				}
				elseif($Attacker->kraftschlag >= 1)
				{
					$Random = rand(1,5);
				}
				else
				{
					$Random = rand(6,9);
					if($Random = 9) { $Random = 34;}
				}
			}
			else
			{
				if($Attacker->move3 == 0)
				{
					if($Attacker->move2 == 0)
					{
						$spezial = rand(0,70);

						if($spezial <= 10)
						{
							$Random = $Attacker->move1;
						}
						else
						{
							$Default = true;
						}
					}
					else
					{
						$spezial = rand(0,85);

						if($spezial <= 9)
						{
							$Random = $Attacker->move1;
						}
						elseif($spezial <= 18)
						{
							$Random = $Attacker->move2;
						}
						else
						{
							$Default = true;
						}
					}
				}
				else
				{
					$spezial = rand(0,50);
					if($spezial <= 5)
						{
							$Random = $Attacker->move1;
						}
						elseif($spezial <= 10)
						{
							$Random = $Attacker->move2;
						}
						elseif($spezial <= 40)
						{
							$Random = $Attacker->move3;
						}
						else
						{
							$Default = true;
						}
				}
			}

			$RandOff = $Attacker->Off * (rand(90,110)/100); // random damage
			if($G2["$RandEnemy"]->AntiDef == 0)
			{
				$RandOff -= $G2["$RandEnemy"]->Deff; //Abzug der Verteidigung. Geschieht nun vor den Moves damit der schaden ordentlich multipliziert wird.
				if($RandOff <= 1) {$RandOff == 1;}
			}

			switch($Random)
			{
				case 1: //Kraftschlag

					if(rand(0,100) < $Attacker->kraftschlag)
					{
						$Damage = $RandOff * 1.5 + $Attacker->taktik*5;
						$Aktion = 'kraftschlag';

						$Coolness += 3;
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 2: //krit

					if(rand(0, 150) < $Attacker->kritischer_schlag)
					{
						$Damage = $RandOff * 2.5 + $Attacker->taktik*5;
						$minKrit = $G2["$RandEnemy"]->minimal/10;
						if($Damage < $minKrit) { $Damage = $minKrit; }
						$Aktion = 'kritischer_schlag';

						$Coolness += 5;
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 3: //wundhieb

					if($G2["$RandEnemy"]->Bluten < 1)
					{
						if(rand(0, 120) < $Attacker->wundhieb)
						{
							if(rand(0, 100) < $Attacker->geschick)
							{
								$Damage = $RandOff * 2;
								$Aktion = 'wundhieb';

								$BlutenMin = round($Attacker->taktik / 40 + $Attacker->inteligenz / 40);
								$BlutenMax = round($Attacker->taktik / 20 + $Attacker->inteligenz / 20);
								$BleedRounds =  rand($BlutenMin,$BlutenMax) + 1;
								$BlutenSchadenMin = round(($Attacker->Off/20)*($Attacker->taktik/30));
								$BlutenSchadenMax = round(($Attacker->Off/10)*($Attacker->taktik/30));
								$BleedDam = rand($BlutenSchadenMin,$BlutenSchadenMax);

								$Coolness += 10;
							}
							else
							{
								$Default = true;
								$Coolness += 1;
							}
						}
						else
						{
							$Default = true;
							$Coolness += 1;
						}
					}
				break;

				case 4: //verletzen

					if(rand(0, 150) < $Attacker->koerperteil_abschlagen)
					{
						if(rand(0, 100) < $Attacker->geschick)
						{
							$Damage = $RandOff * 4 + $Attacker->taktik*5;
							$minVerletzen = $G2["$RandEnemy"]->minimal/5;
							if($Damage < $minVerletzen) { $Damage = $minVerletzen; }
							$Aktion = 'koerberteil_abschlagen';

							$Coolness += 25;
						}
						else
						{
							$Default = true;
							$Coolness += 1;
						}
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 5: //todesschlag

					if(rand(0, 200) < $Attacker->todesschlag)
					{
						if(rand(0, 150) < $Attacker->geschick)
						{
							$Damage = 50000;
							$Aktion = 'todesschlag';

							$Coolness += 50;
						}
						else
						{
							$Default = true;
							$Coolness += 1;
						}
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 6: //sand werfen

					if($G2["$RandEnemy"]->Paralysis < 1)
					{
						if(rand(0,150) < $Attacker->sand_werfen)
						{
							$Damage = 0;
							$Aktion = 'sand_werfen';

							$Paralysis = rand($Attacker->inteligenz/40,$Attacker->Inteligenz/20)+1;

							$Coolness += 10;
						}
						else
						{
							$Default = true;
							$Coolness += 1;
						}
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 7: // kraftschrei

					if(rand(0,150) < $Attacker->kraftschrei)
					{
						$Damage = 0;
						$Attacker->HP += (10*$Attacker->taktik + 10*$Attacker->ausweichen + 10*$Attacker->inteligenz + 10*$Attacker->kondition + 10*$Attacker->heilkunde);
						$Aktion = 'kraftschrei';

						$Coolness += 6;
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 8: //wutschrei

					if(rand(0,150) < $Attacker->wutschrei)
					{
						$Damage = 0;
						$Aktion = 'Schreien';

						$minRounds = round($Attacker->inteligenz/10+1);
						$maxRounds = round($Attacker->inteligenz/5+2);
						$SchreienRounds = rand($minRounds,$maxRounds);
						$SchreienDam = rand($Attacker->heilkunde*8,$Attacker->heilkunde*16)+1;

						$Coolness += 3;
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 9: // Hufschlag

					if(rand(0,1) == 1)
					{
						$Damage = $RandOff * 1.5;
						$Aktion = 'HufschlagWund';
						$BleedRounds = rand(1,3);
						$BleedDam = round(rand($Attacker->Off/7,$Attacker->Off/3));
					}
					else
					{
						$Damage = $RandOff * 2;
						$Aktion = 'Hufschlag';
					}

				break;

				case 10: // Aufspie�en

					$Damage = $RandOff * 1.5;
					$Aktion = 'Aufspie�en';

					$BleedRounds = rand(1,3);
					$BleedDam = round(rand($Attacker->Off/7,$Attacker->Off/3));
				break;

				case 11: // Biss

					$Damage = $RandOff * 1.2;
					$Aktion = 'Biss';
				break;

				case 12: // Pferdekuss

					$Damage = $RandOff * 1.8;
					$Aktion = 'Pferdekuss';
				break;

				case 13: // Rammen

					if($G2["$RandEnemy"]->Paralysis < 1)
					{
						if(rand(0,2) == 1)
						{
							$Damage = $RandOff * 1.5;
							$Paralysis = rand(1,3);
							$Aktion = 'RammenBlenden';
						}
						else
						{
							$Damage = $RandOff * 1.5;
							$Aktion = 'Rammen';
						}
					}
					else
					{
						$Damage = $RandOff * 1.5;
						$Aktion = 'Rammen';
					}

				break;

				case 14: // Bellen

					$Paralysis = rand(1,3);
					$Aktion = 'Bellen';

				break;

				case 15: // Kopfsto�

					$Damage = $RandOff * 1.5;
					$Aktion = 'Kopfsto�';

				break;

				case 16: // Schnabel

					$Damage = $RandOff * 1.4;
					$Aktion = 'Schnabel';

					$BleedRounds = 1;
					$BleedDam = round(rand($Attacker->Off/7,$Attacker->Off/3));

				break;

				case 17: // Tritt

					$Damage = $RandOff * 1.5;
					$Aktion = 'Tritt';

				break;

				case 18: // Sturzflug

					$Damage = $RandOff * 2;
					$Aktion = 'Sturzflug';

				break;

				case 19: // Schlag

					$Damage = $RandOff * 1.8;
					$Aktion = 'Schlag';
					$Paralysis = rand(1,3);

				break;

				case 20: // Lauerangriff

					$Damage = $RandOff * 2;
					$Aktion = 'Lauerangriff';
					$CryRounds = 1;
					$CryDam = rand($Attacker->Off/10,$Attacker->Off/5);

				break;

				case 21: // Kratzen

					$Damage = $RandOff * 1.5;
					$Aktion = 'Kratzen';
					$BleedRounds = 2;
					$BleedDam = round(rand($Attacker->Off/7,$Attacker->Off/3));

				break;

				case 22: // Kehlenbiss

					$Damage = $RandOff * 3;
					$Aktion = 'Kehlenbiss';

				break;

				case 23: // Wutgeheul

					$Attacker->HP += 400;
					$Aktion = 'Wutgeheul';

				break;

				case 24: // koangriff

					$Damage = $RandOff * 1.5;
					$CryRounds = 3;
					$CryDam = $Attacker->Off*2;
					$Aktion = 'koangriff';

				break;

				case 25: // �berrennen

					if($G2["$RandEnemy"]->Paralysis < 1)
					{
						if(rand(0,1) == 1)
						{
							$Damage = $RandOff * 1.8;
							$Paralysis = rand(1,3);
							$Aktion = '�berrennenBlenden';
						}
						else
						{
							$Damage = $RandOff * 1.8;
							$Aktion = '�berrennen';

						}
					}
					else
					{
						$Damage = $RandOff * 1.8;
						$Aktion = '�berrennen';

					}

				break;

				case 26: // Heulen

					$CryRounds = rand(2,4);
					$CryDam = rand($Attacker->Off/10,$Attacker->Off/6);
					$Aktion = 'Heulen';

				break;

				case 27: // Schwanzschlag

					if($G2["$RandEnemy"]->Paralysis < 1)
					{
						$Paralysis = rand(1,3);
						$Aktion = 'Schwanzschlag';
					}
					else
					{
						$Default = true;
					}

				break;

				case 28: // Hornsto�

					$Damage = $RandOff * 2;
					$Aktion = 'Hornsto�';
					$BleedRounds = rand(1,3);
					$BleedDam = round(rand($Attacker->Off/7,$Attacker->Off/3));

				break;

				case 29: // Prankenhieb

					$Damage = $RandOff * 1.8;
					$Aktion = 'Prankenhieb';

				break;

				case 30: // vergiften

					$Damage = $RandOff * 1.5;
					$Aktion = 'vergiften';

					$PoisonRounds = rand(1,4);
					$PoisonDam = round(($RandOff/2),$RandOff);

				break;

				case 31: // R�ssel

					$Damage = $RandOff * 2.6;
					$Aktion = 'R�ssel';

				break;

				case 32: // Tollwut

					$Damage = $RandOff * 1.5;
					$Aktion = 'Tollwut';

					$PoisonRounds = rand(1,3);
					$PoisonDam = round(rand(($RandOff/3),($RandOff/2)));

				break;

				case 33: // Jagt

					if($G2["$RandEnemy"]->Paralysis < 1 AND rand(1,2) == 2)
					{
						$CryRounds = rand(2,4);
						$CryDam = rand($Attacker->Off/6,$Attacker->Off/3);
						$Paralysis = rand(1,2);
						$Damage = $RandOff * 1.5;
						$Aktion = 'Jagt';
					}
					else
					{
						$CryRounds = rand(2,4);
						$CryDam = rand($Attacker->Off/6,$Attacker->Off/3);
						$Damage = $RandOff * 1.5;
						$Aktion = 'Jagt';
					}

				break;

				case 34: //AntiDef

					if(rand(0,220) < $Attacker->anti_def)
					{
						$Damage = 0;
						$Aktion = 'anti-def';

						$AntiDefR = rand(2,4);

						$Coolness += 15;
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}
				break;

				case 35: //Zertrampeln

					if(rand(1, 10) == 2)
					{
					echo "*";
						$Damage = 50000;
						$Aktion = 'Zertrampeln';
					}
					else
					{
					echo "+";
						$Damage = rand(1452, 3465);
						$Aktion = 'trampelt';
					}

				break;

				case 36: //R�stung zerst�ren

						$Damage = 0;
						$Aktion = 'R�stungzerst�ren';

						$AntiDefR = rand(2,4);

				break;

				case 40: //verletzen NPC-Team

						if(rand(1, 2) == 2)
						{
							$Damage = $RandOff * 4;
							$Aktion = 'koerberteil_abschlagen';

							$Coolness += 25;
						}
						else
						{
							$Default = true;
							$Coolness += 1;
						}
				break;

				case 41: //wundhieb NPC-Team

					if($G2["$RandEnemy"]->Bluten < 1)
					{
							if(rand(1,2) ==2)
							{
								$Damage = $RandOff * 2;
								$Aktion = 'wundhieb';

								$BleedRounds =  rand(1,3);
								$BleedDam = rand(300,600);

								$Coolness += 10;
							}
							else
							{
								$Default = true;
								$Coolness += 1;
							}
					}
					else
					{
						$Default = true;
						$Coolness += 1;
					}

				break;

			case 42: //krit

						$Damage = $RandOff * 3;
						$Aktion = 'kritischer_schlag';

						$Coolness += 5;
				break;

			case 43: //krit

						$Damage = $RandOff * 1.5;
						$Aktion = 'kraftschlag';

						$Coolness += 2;
				break;
			}

			if($Default) // no special attack
			{
				$Damage = $RandOff;
				$Aktion = 'Hit';

				$Default = null;
			}

			$Damage = round($Damage);

			if($G2["$RandEnemy"]->block >=1 && $G2["$RandEnemy"]->taeuschen >=1)
			{
				$SMdefRand = rand(1,6);
			}
			elseif($G2["$RandEnemy"]->block >=1)
			{
				$SMdefRand = rand(1,4);
			}
			elseif($G2["$RandEnemy"]->taeuschen >=1)
			{
				$SMdefRand = rand(4,6);
			}
			else
			{
				$SmdefRand = 4;
			}


			if($G2["$RandEnemy"]->Paralysis == 0)
			{
				switch($SMdefRand)
				{
					case 1: //block

						if(250 < $G2["$RandEnemy"]->block)
						{
							$Damage /= 3;
							$DefAktion = 'block';

							$Coolness += 10;
						}
					break;

					case 2: //konter

						if($School['tactic']==5)
						{
							if(250 < $G2["$RandEnemy"]->konter)
							{
								$Damage /= 3;
								$schaden = $G2["$RandEnemy"]->Off - $Attacker->Deff;
								//$DamageAgainst = round($schaden*2.5+($G2["$RandEnemy"]->ausweichen*4));
								$DamageAgainst = 7500;
								$DefAktion = 'konter';

								$Coolness += 25;
							}
						}
						else
						{
							if(350 < $G2["$RandEnemy"]->konter)
							{
								$Damage /= 3;
								$schaden = $G2["$RandEnemy"]->Off - $Attacker->Deff;
								//$DamageAgainst = round($schaden*2+($G2["$RandEnemy"]->ausweichen*4));
								$DamageAgainst = 7500;
								$DefAktion = 'konter';

								$Coolness += 25;
							}
						}
					break;

					case 3: //ausweichen

						if(300 < $G2["$RandEnemy"]->ausweichen)
						{
							$Damage = 0;
							$DefAktion = 'ausweichen';

							$Coolness += 15;
						}
					break;

					case 4: //schutz

						if(200 < $G2["$RandEnemy"]->armor)
						{
							$Damage /= 1.5;
							$DefAktion = 'armor';

							$Coolness += 5;
						}
					break;

					case 5: //t�uschen

						if($School['tactic']==5)
						{
							if(200 < $G2["$RandEnemy"]->taeuschen)
							{
								//$Damage /= 4;
								$Damage = $Attacker->off/4;
								$schaden = $G2["$RandEnemy"]->Off - $Attacker->Deff;
								$DamageAgainst = round(($schaden*1)+($G2["$RandEnemy"]->ausweichen*8));
								$DefAktion = 'taeuschen';

								$Coolness += 20;
							}
						}
						else
						{
							if(300 < $G2["$RandEnemy"]->taeuschen)
							{
								//$Damage /= 4;
								$Damage = $Attacker->off/4;
								$schaden = $G2["$RandEnemy"]->Off - $Attacker->Deff;
								$DamageAgainst = round(($schaden*0.75)+($G2["$RandEnemy"]->ausweichen*8));
								$DefAktion = 'taeuschen';

								$Coolness += 20;
							}
						}
					break;

					case 6: //berserker

						if($School['tactic']==5)
						{
							if(400 < $G2["$RandEnemy"]->berserker)
							{
								$Damage *= 2;
								$DamageAgainst = round($G2["$RandEnemy"]->Off*4.5+($G2["$RandEnemy"]->ausweichen*8));
								$DefAktion = 'berserker';

								$Coolness += 30;
							}
						}
						else
						{
							if(500 < $G2["$RandEnemy"]->berserker)
							{
								$Damage *= 2;
								$DamageAgainst = round($$G2["$RandEnemy"]->Off*4+($G2["$RandEnemy"]->ausweichen*8));
								$DefAktion = 'berserker';

								$Coolness += 30;
							}
						}
					break;
				}
			}

			if($Attacker->Paralysis >= 1) // geblendet kann man nicht angreifen
			{
				$Report .= $this->showSpecialTab($Rounds, $Attacker->Name, $Attacker->Paralysis,'Paralysis');
				$Attacker->Paralysis--;
			}
			else
			{
				if($Damage < 0)
				{
					$Damage = 1;
				}

				if($DefAktion != null)
				{
					if($DamageAgainst != null)
					{
						if($DamageAgainst < 0)
						{
							$DamageAgainst = 1;
						}
					}
					else
					{
						$DamageAgainst = 0;
					}

					$DamageAgainst = $DamageAgainst * (rand(90,110)/100); // random damage
					$DamageAgainst = round($DamageAgainst);

					$Report .= $this->showDefenseTab($Rounds, $Attacker, $G2["$RandEnemy"], $Damage, $DamageAgainst, $DefAktion);

					if($Damage >= $G2["$RandEnemy"]->HP) // enemy die
					{
						$G2["$RandEnemy"]->HP = 0;
						$G2["$RandEnemy"]->Death = true;

						$Report .= $this->showSpecialTab($Rounds,$G2["$RandEnemy"]->Name,$Attacker->Paralysis,'Death');
					}
					else // enemy don't die
					{
						$G2["$RandEnemy"]->HP -= $Damage;
					}

					if($DamageAgainst >= $Attacker->HP) // attacker die
					{
						$Attacker->HP = 0;
						$Attacker->Death = true;

						$Report .= $this->showSpecialTab($Rounds, $Attacker->Name,$Attacker->Paralysis, 'Death');
					}
					else // attacker dont die
					{
						$Attacker->HP -= $DamageAgainst;
					}
				}
				else // normal attack
				{
					$G2["$RandEnemy"]->Paralysis += $Paralysis;

					$Paralysis = 0;

					$G2["$RandEnemy"]->AntiDef += $AntiDefR;

					$AntiDefR = 0;

					$Attacker->Schreien += $SchreienRounds;
					$Attacker->SchreienDam += $SchreienDam;

					$SchreienRounds = 0;
					$SchreienDam = 0;

					$G2["$RandEnemy"]->Bluten += $BleedRounds;
					$G2["$RandEnemy"]->BlutenDam += round($BleedDam);

					$G2["$RandEnemy"]->StatPoison += $PoisonRounds;
					$G2["$RandEnemy"]->StatPoisonDam += round($PoisonDam);

					if($Damage >= $G2["$RandEnemy"]->HP) // enemy die
					{
						$G2["$RandEnemy"]->HP = 0;
						$G2["$RandEnemy"]->Death = true;

						$Report .= $this->showAttackTab($Rounds, $Attacker, $G2["$RandEnemy"], $Damage, $Aktion);
						$Report .= $this->showSpecialTab($Rounds, $G2["$RandEnemy"]->Name,$Attacker->Paralysis, 'Death');
					}
					else // enemy don't die
					{
						$G2["$RandEnemy"]->HP -= $Damage;

						$Report .= $this->showAttackTab($Rounds, $Attacker, $G2["$RandEnemy"], $Damage, $Aktion);
					}
				}
			}
			$G1[$key] = $Attacker;
		}
		return $G2Kill;
	}

	function newBattle($G1,$G2)
	{
		//error_reporting(E_ALL^E_NOTICE); // show errors
		srand((double)microtime()*1000000); // use pseudo random

		$this->showTeams($G1, $G2); // show teams

		$Rounds = 1;

		$G1Kill = 0;
		$G2Kill = 0;

		global $Report;
		global $Show;
		global $Coolness;

		while($G1Kill < count($G1) && $G2Kill < count($G2))
		{
			$G2Kill = $this->attack($G1, $G2, $Rounds);
			$G1Kill = $this->attack($G2, $G1, $Rounds);

			$Rounds++;
		}

		if($G1Kill == count($G1))
		{
			$Winner = '2';
			$WinGroup = $G2;
		}
		elseif($G2Kill == count($G2))
		{
			$Winner = '1';
			$WinGroup = $G1;
		}

		$Report = '<table width="465" align="center">'.$Report.'</table>';

		$Rounds--;

		$Loop = 1;
		while($Loop <= $Rounds)
		{
			$Search = '<td style="font-size:14px;border:1px solid black;padding:2px;width:20px;text-align:center;">'.$Loop.'</td>';
			$Count = substr_count($Report,$Search);
			$Replace = '<td style="font-size:14px;border:1px solid black;padding:2px;width:20px;text-align:center;" rowspan="'.$Count.'">'.$Loop.'</td>';

			$Report = ReplaceOne($Search, $Replace, $Report);
			$Report = str_replace($Search,'',$Report);

			$Loop++;
		}

		global $KBN;

		$_SESSION['Kampfbericht'.$KBN] = $Report;
		$msg_kb = $Report;

		echo'<div id="kb'.$KBN.'"><a href="javascript:hideKB('.$KBN.')">Kampfbericht schlie�en</a><br /><br />'.$Report.'</div>';

		$Report = '';

		$Show.= '<br /><center style="font-size:14px;"><em>... ein harter Kampf �ber '.$Rounds.' Runden beginnt ...</em></center><br /><center><a href="javascript:openKB('.$KBN.')">Kampfbericht</a></center><br />';

		$KBN = $KBN + 1;

		foreach($WinGroup as $Win)
		{
			$Names .= ' und '.$Win->name;
		}

		$Show.= '<center><strong>Letztendlich hat doch '.substr($Names,5).' gewonnen!</strong></center>';

		$Return = array();
		$Return['winner'] = $Winner;
		$Return['show'] = $Show;
		$Return['coolness'] = round($Coolness,0);

		foreach($G1 as $GiveOut)
		{
			if($GiveOut->Kind == 'Human')
			{
				$Return[$GiveOut->ID] = round($GiveOut->HP);
			}
		}

		foreach($G2 as $GiveOut)
		{
			if($GiveOut->Kind == 'Human')
			{
				$Return[$GiveOut->ID] = round($GiveOut->HP);
			}
		}
		$Return['kampfbericht'] = $msg_kb;
		return $Return;
	}
}
?>
