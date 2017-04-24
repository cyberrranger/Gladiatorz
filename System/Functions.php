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
function calcUEcost($stufe, $what)
{
	$query = mysql_query("Select * FROM settings_untereigenschaften WHERE name = '$what'");
	$ue = mysql_fetch_assoc($query);

	$pm = $ue['GrundPM'] + ($ue['PMSteigerung'] * $stufe);
	
	$i = 0;
	$gold = $ue['Grundkosten'];
	while ($i < $stufe)
	{
		$gold = $gold*$ue['KostenSteigerung']+$gold;
		$i++;
	}
	$gold = (int)round($gold);
	
	return array('gold' => $gold, 'pm' => $pm);
}

function calcEcost($stufe)
{
	$pm = (0.5 + ($stufe * 0.1) + round(($stufe/3.9)*0.5,1));
	
	if($pm < 0) $pm = 0;
	
	return $pm;
}

function isMod()
{
	global $User;
	  
	if(in_array($User['name'],$GLOBALS['conf']['team']))
	{
		return true;
	} else {
		return false;
	}
}

function readreport($id, $what)
{
	if ($what == 'tunier')
	{
		$InfoSql = "SELECT * FROM turnier_report WHERE id=".$id." LIMIT 1";
	}

	if ($what == 'duell')
	{
		$InfoSql = "SELECT * FROM duelle_report WHERE id=".$id." LIMIT 1";
	}

	if ($what == 'challenge')
	{
		$InfoSql = "SELECT * FROM open_fights_report WHERE id=".$id." LIMIT 1";
	}

	$InfoQuery = mysql_query($InfoSql);
	$__place = mysql_fetch_assoc($InfoQuery);

	if ($__place)
	{
		return $__place;
	}

	return false;
}


function collection_name($id)
{
	switch ($id)
	{
		case 1:
			$name = "Felle";
			break;
		case 2:
			$name = "Zähne";
			break;
		case 3:
			$name = "Krallen";
			break;
		case 4:
			$name = "Federn";
			break;
		case 5:
			$name = "Stöcke";
			break;
		case 6:
			$name = "spitze Steine";
			break;
		case 7:
			$name = "Pfeile";
			break;
		case 8:
			$name = "Köcher";
			break;
		case 9:
			$name = "Lametta";
			break;
		case 10:
			$name = "Kerzen";
			break;
		case 11:
			$name = "Glöckchen";
			break;
		case 12:
			$name = "Christbaumkugeln";
			break;
		case 13:
			$name = "Strohsterne";
			break;
		default:
			$name = 'not found';
			break;
	}

	return utf8_encode($name);
}

function getRangName2($rang)
{
	switch ($rang)
	{
		case 1:$name = "Sklave";break;
		case 2:$name = "Fu&szlig;abtreter";break;
		case 3:$name = "Neuling";break;
		case 4:$name = "Lehrling";break;
		case 5:$name = "Besserwisser";break;
		case 6:$name = "Getreideknirscher";break;
		case 7:$name = "B&uuml;rger";break;
		case 8:$name = "Kampfhund";break;
		case 9:$name = "K&auml;mpfer";break;
		case 10:$name = "W&uuml;terer";break;
		case 11:$name = "Gladiator";break;
		case 12:$name = "Gladiatorenmeister";break;
		case 13:$name = "Meister";break;
		case 14:$name = "Meister des Schwertes";break;
		case 15:$name = "Meister des Kampfes";break;
		case 16:$name = "Meister der Arena";break;
		case 17:$name = "Held des P&ouml;bels";break;
		case 18:$name = "Held der Arena";break;
		case 19:$name = "Held des Kolosseums";break;
		case 20:$name = "Held des Imperiums";break;
		case 21:$name = "Kriegsf&uuml;rst";break;
		case 22:$name = "&Uuml;bermensch";break;
		case 23:$name = "Triumphator";break;
	}
	return utf8_encode($name);
}

function getRangName($lvl, $id = false, $item = false)
{
	if($lvl < 10)
	{
		$name = "Sklave";
		$rang = 1;
		$itemfaktor = 1;

	} elseif ($lvl < 20)
	{
		$name = "Fu&szlig;abtreter";
		$rang = 2;
		$itemfaktor = 2;

	} elseif ($lvl < 30)
	{
		$name = "Neuling";
		$rang = 3;
		$itemfaktor = 4;

	} elseif ($lvl < 40)
	{
		$name = "Lehrling";
		$rang = 4;
		$itemfaktor = 6;

	} elseif ($lvl < 50)
	{
		$name = "Besserwisser";
		$rang = 5;
		$itemfaktor = 8;

	} elseif ($lvl < 60)
	{
		$name = "Getreideknirscher";
		$rang = 6;
		$itemfaktor = 10;

	} elseif ($lvl < 70)
	{
		$name = "B&uuml;rger";
		$rang = 7;
		$itemfaktor = 12;

	} elseif ($lvl < 80)
	{
		$name = "Kampfhund";
		$rang = 8;
		$itemfaktor = 14;


	} elseif ($lvl < 90)
	{
		$name = "K&auml;mpfer";
		$rang = 9;
		$itemfaktor = 17;

	} elseif ($lvl < 100)
	{
		$name = "W&uuml;terer";
		$rang = 10;
		$itemfaktor = 20;

	} elseif ($lvl < 110)
	{
		$name = "Gladiator";
		$rang = 11;
		$itemfaktor = 23;

	} elseif ($lvl < 120)
	{
		$name = "Gladiatorenmeister";
		$rang = 12;
		$itemfaktor = 26;

	} elseif ($lvl < 130)
	{
		$name = "Meister";
		$rang = 13;
		$itemfaktor = 29;

	} elseif ($lvl < 140)
	{
		$name = "Meister des Schwertes";
		$rang = 14;
		$itemfaktor = 32;

	} elseif ($lvl < 150)
	{
		$name = "Meister des Kampfes";
		$rang = 15;
		$itemfaktor = 36;

	} elseif ($lvl < 160)
	{
		$name = "Meister der Arena";
		$rang = 16;
		$itemfaktor = 40;

	} elseif ($lvl < 170)
	{
		$name = "Held des P&ouml;bels";
		$rang = 17;
		$itemfaktor = 44;

	} elseif ($lvl < 180)
	{
		$name = "Held der Arena";
		$rang = 18;
		$itemfaktor = 48;

	} elseif ($lvl < 190)
	{
		$name = "Held des Kolosseums";
		$rang = 19;
		$itemfaktor = 52;

	} elseif ($lvl < 200)
	{
		$name = "Held des Imperiums";
		$rang = 20;
		$itemfaktor = 56;

	} elseif ($lvl < 210)
	{
		$name = "Kriegsf&uuml;rst";
		$rang = 21;
		$itemfaktor = 61;

	} elseif ($lvl < 220)
	{
		$name = "&Uuml;bermensch";
		$rang = 22;
		$itemfaktor = 66;

	} else
	{
		$name = "Triumphator";
		$rang = 23;
		$itemfaktor = 71;
	}

	if ($id)
	{
		return $rang;
	}
	if ($item)
	{
		return $itemfaktor;
	}
	return utf8_encode($name);
}


function displayPages($seite,$maxseite,$url="",$anzahl=4,$get_name="seite")
   {
   $anhang = "&";

   if(substr($url,-1,1) == "&") {
      $url = substr_replace($url,"",-1,1);
      }
   else if(substr($url,-1,1) == "?") {
      $anhang = "?";
      $url = substr_replace($url,"",-1,1);
      }

   if($anzahl%2 != 0) $anzahl++; //Wenn $anzahl ungeraden, dann $anzahl++

   $a = $seite-($anzahl/2);
   $b = 0;
   $blaetter = array();
   while($b <= $anzahl)
      {
      if($a > 0 AND $a <= $maxseite)
         {
         $blaetter[] = $a;
         $b++;
         }
      else if($a > $maxseite AND ($a-$anzahl-2)>=0)
         {
         $blaetter = array();
         $a -= ($anzahl+2);
         $b = 0;
         }
      else if($a > $maxseite AND ($a-$anzahl-2)<0)
         {
         break;
         }

      $a++;
      }
   $return = "";
   if(!in_array(1,$blaetter) AND count($blaetter) > 1)
      {
      if(!in_array(2,$blaetter)) $return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=1\">1</a>&nbsp;...";
      else $return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=1\">1</a>&nbsp;";
      }

   foreach($blaetter AS $blatt)
      {
      if($blatt == $seite) $return .= "&nbsp;<b>$blatt</b>&nbsp;";
      else $return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=$blatt\">$blatt</a>&nbsp;";
      }

   if(!in_array($maxseite,$blaetter) AND count($blaetter) > 1)
      {
      if(!in_array(($maxseite-1),$blaetter)) $return .= "...&nbsp;<a href=\"{$url}{$anhang}{$get_name}=$maxseite\">letzte</a>&nbsp;";
      else $return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=$maxseite\">$maxseite</a>&nbsp;";
      }
   return $return;
   }
?>
