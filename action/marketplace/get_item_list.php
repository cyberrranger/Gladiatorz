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
header('content-type: text/html; charset=utf-8');
session_start();

if(!isset($_SESSION['id']))
{
	exit;
}
else
{
	$sid = $_SESSION['id'];
}

require('../../System/conf.php');
require('../../System/Database.php');
require_once('../../System/bbcode.php');
require_once('../../System/GameFunctions.php');
require('../../System/new_functions.php');
require('../../System/Functions.php');

$ausgabe = array();

$ausgabe['text'] = '<br /><table width="100%" id="tab"><thead><tr><th>Pic</th><th>Item</th><th width="160">Preis</th><th width="80">Kaufen</th></tr></thead><tfoot>';
$ausgabe['tool'] = '';

if ($_POST['what'] == 0)
{
	$ausgabe['text'] = $ausgabe['text'].'</tfoot></table>';

	echo json_encode($ausgabe);
	exit;
}

if ($_POST['what'] == 1)
{
	$search = "SELECT * FROM ausruestung WHERE ort = 'schmiede'";
}
if ($_POST['what'] == 2)
{
	$search = "SELECT * FROM ausruestung WHERE ort = 'lager'";
}
if ($_POST['what'] == 3)
{
	$search = "SELECT * FROM ausruestung WHERE ort = 'markt'";
}
if ($_POST['what'] == 4)
{
	$search = "SELECT * FROM ausruestung WHERE ort = 'basar'";
}
if ($_POST['what'] == 5)
{
	$searchname = $_SESSION['name'].'s';

	$search = "SELECT * FROM ausruestung WHERE user_id = '4' AND typ='name' AND ort = 'inventar' AND name LIKE '$searchname%'";
}
if ($_POST['what'] == 6)
{
	//Meine Gebote
	$search = "SELECT * FROM ausruestung WHERE ort != 'user' AND ort != 'inventar' AND user_id = '".$sid."'";
}
if ($_POST['what'] == 7)
{
	//Meine Items
	$search = "SELECT * FROM ausruestung WHERE ort != 'user' AND ort != 'inventar' AND user_id = '".$sid."'";
}
if ($_POST['what'] == 8)
{
	$search = "SELECT * FROM ausruestung WHERE ort != 'user' AND ort != 'inventar' AND ort != 'standard'";
}
if (is_numeric($_POST['minRang']))
{
	 $search .= " AND rang <= ".$_POST['minRang'];
}

switch($_POST['type'])
{
	case 1: $search .= " AND art='weapon'"; break;
	case 2: $search .= " AND art='shield' AND deff = '0'"; break;
	case 3: $search .= " AND art='shield'"; break;
	case 4: $search .= " AND art='armor'"; break;
	case 5: $search .= " AND art='head'"; break;
	case 6: $search .= " AND art='gloves'"; break;
	case 7: $search .= " AND art='lowbody'"; break;
	case 8: $search .= " AND art='belt'"; break;
	case 9: $search .= " AND art='foots'"; break;
	case 10: $search .= " AND art='cape'"; break;
	case 11: $search .= " AND art='shoulder'"; break;
}


switch($_POST['sort'])
{
	case 1: $search .= " ORDER BY name ASC"; break;
	case 2: $search .= " ORDER BY off DESC"; break;
	case 3: $search .= " ORDER BY deff DESC"; break;
	case 4: $search .= " ORDER BY preis DESC"; break;
	case 5: $search .= " ORDER BY dauer ASC"; break;
	case 6: $search .= " ORDER BY preis ASC"; break;
	default: // is also case 1 !!!
}

$user = get_user_things($sid);

$Query = mysql_query($search);
while($Items = mysql_fetch_assoc($Query))
{
	$thisuser = get_user_things($Items['user_id'], 'name');
	
	if ($_POST['what'] == 3)
	{
		if ($Items['dauer'] < time())
		{
			//Item ist bereits abgelaufen
			$Update = mysql_query("UPDATE ausruestung SET ort = 'inventar', preis = '0', dauer = '0'  WHERE id='".$Items['id']."' LIMIT 1");
			
			$Titel = 'Item auf dem Markt nicht verkauft';
			$Empfaenger = get_user_things($Items['user_id'], 'name');
			$Text = "Hallo $Empfaenger,<br />du konntest dein Item ".utf8_encode($Items['name'])." nicht auf dem Markt verkaufen.";
			$Absender = 'Marktleitung';

			sendIGM($Titel,$Text,$Empfaenger,$Absender);
			continue;
		}
		
		$Bid = '<a href="javascript:buyItem(\''.$Items['id'].'\');">sofort kaufen</a>';
		$Price = $Items['preis'].' (Sofortkauf)';
		$_Price = $Items['preis'];
		
		$Owner = $thisuser;
		$Type = translateItemNames($Items['art']);
	}
	elseif($_POST['what'] == 4 || $_POST['what'] == 6)
	{
		if ($_POST['what'] == 6)
		{
			$GoOnSql = "SELECT * FROM gebote WHERE item = '$Items[id]' AND bieter='$sid'";
			$GoOnQuery = mysql_query($GoOnSql);
			$GoOn = mysql_fetch_assoc($GoOnQuery);
	
			if($GoOn == false)
			{
				continue;
			}
		}
		
		if ($Items['dauer'] < time())
		{
			$Gebot = mysql_query("SELECT * FROM gebote WHERE item='$Items[id]' AND repay='n' ORDER BY preis DESC LIMIT 1");
			$Gebot = mysql_fetch_assoc($Gebot);
			
			//es gibt keine Bieter also zurÃ¼ck
			if (!$Gebot)
			{
				//Item ist bereits abgelaufen und nicht verkauft
				$Update = mysql_query("UPDATE ausruestung SET ort = 'inventar', preis = '0', dauer = '0'  WHERE id='".$Items['id']."' LIMIT 1");
				
				$Titel = 'Item auf dem Basar nicht verkauft';
				$Empfaenger = get_user_things($Items['user_id'], 'name');
				$Text = "Hallo $Empfaenger,<br />du konntest dein Item ".utf8_encode($Items['name'])." nicht auf dem Basar verkaufen.";
				$Absender = 'Basarleitung';
	
				sendIGM($Titel,$Text,$Empfaenger,$Absender);
			} else
			{
				//Item wurde verkauft
				$Update = mysql_query("UPDATE ausruestung SET user_id=".$Gebot[bieter]." ,ort = 'inventar', preis = '0', dauer = '0'  WHERE id=".$Gebot[item]." LIMIT 1");
				$Update = mysql_query("UPDATE user SET gold=gold+".$Gebot[preis]." WHERE id='".$buyitem[user_id]."' LIMIT 1");
				
				//alle gebote lÃ¶schen
				$DeleteBids = mysql_query("DELETE FROM gebote WHERE item='$Items[id]'");
				
				$Titel = 'Item auf dem Basar verkauft';
				$Empfaenger = get_user_things($Items['user_id'], 'name');
				$Text = "Hallo $Empfaenger,<br />du konntest dein Item ".utf8_encode($Items['name'])." erfolgreich fÃ¼r ".$Gebot['preis']." auf dem Basar verkaufen.";
				$Absender = 'Basarleitung';
	
				sendIGM($Titel,$Text,$Empfaenger,$Absender);
				
				$Titel = 'Item auf dem Basar gekauft';
				$Empfaenger = get_user_things($Gebot['bieter'], 'name');
				$Text = "Hallo $Empfaenger,<br />du konntest das Item ".utf8_encode($Items['name'])." erfolgreich fÃ¼r ".$Gebot['preis']." auf dem Basar kaufen.";
				$Absender = 'Basarleitung';
	
				sendIGM($Titel,$Text,$Empfaenger,$Absender);
			}
			continue;
		}
		
		$Bid = '<a href="javascript:bieteItem(\''.$Items['id'].'\');">jetzt bieten</a>';
		
		//Gebote herausfinden
		$_Gebot = mysql_query("SELECT * FROM gebote WHERE item='$Items[id]' AND repay='n' ORDER BY preis DESC LIMIT 1");
		$_Gebot = mysql_fetch_assoc($_Gebot);
		
		//es gibt keine Bieter 
		if (!$_Gebot)
		{
			$Price = $Items['preis'].' (noch kein Gebot)';
			$_Price = $Items['preis'];
		} else
		{
			$hGebot = get_user_things($_Gebot['bieter'], 'name');
			$Price = $_Gebot['preis'].' (Gebot von '.$hGebot.')';
			$_Price = $_Gebot['preis'];
		}
		
		$Owner = $thisuser;
	} else
	{
		if ($_POST['what'] == 2)
		{
			//Prüfen ob man einer Schule angehört
			if ($user['schule'] == 0)
			{
				continue;
			}
			//ist dies ein Item aus der selben Schule?
			if ($user['schule'] != get_schule($Items['user_id']))
			{
				continue;
			}
		}		
		//lagerware :)
		if ($Items['dauer'] < time() && $Items['ort'] == 'lager')
		{
			//Item ist bereits abgelaufen
			$Update = mysql_query("UPDATE ausruestung SET ort = 'inventar', preis = '0', dauer = '0'  WHERE id='".$Items['id']."' LIMIT 1");
			
			$Titel = 'Item auf dem Schullager nicht verkauft';
			$Empfaenger = get_user_things($Items['user_id'], 'name');
			$Text = "Hallo $Empfaenger,<br />du konntest dein Item ".utf8_encode($Items['name'])." nicht in der Schule verkaufen verkaufen.";
			$Absender = 'Lagerleitung';

			sendIGM($Titel,$Text,$Empfaenger,$Absender);
			continue;
		}
		
		//namensitems, lager und bzw schmiede
		$Bid = '<a href="javascript:buyItem(\''.$Items['id'].'\');">sofort kaufen</a>';
		$Price = $Items['wert'].' (Sofortkauf)';
		$_Price = $Items['wert'];
		$Owner = $thisuser;
		
		if ($_POST['what'] == 7)
		{
			//Meine Items
			if ($Items['ort'] == 'basar')
			{
				//Gebote herausfinden
				$_Gebot = mysql_query("SELECT * FROM gebote WHERE item='$Items[id]' AND repay='n' ORDER BY preis DESC LIMIT 1");
				$_Gebot = mysql_fetch_assoc($_Gebot);
				
				//es gibt keine Bieter 
				if (!$_Gebot)
				{
					$Price = $Items['preis'].' (noch kein Gebot)';
					$_Price = $Items['preis'];
				} else
				{
					$hGebot = get_user_things($_Gebot['bieter'], 'name');
					$Price = $_Gebot['preis'].' (Gebot von '.$hGebot.')';
					$_Price = $_Gebot['preis'];
				}
			}
		}

		if ($Items['ort'] == 'schmiede')
		{
			$Owner = 'der Schmiede';
		}
		
		if ($Items['ort'] == 'lager')
		{
			$Price = $Items['preis'].' (Sofortkauf)';
			$_Price = $Items['preis'];
		}
		
	}
	
	if ($Items['user_id'] == $sid)
	{
		$Bid = 'Dein Angebot';
	}
		
	if($Items['art'] == 'shield' && $Items['deff'] == 0)
	{
		$Type = 'Zweitwaffe';
	}

	$thisitem = findItem($Items['art'], $sid);
	
	$Itemword = '<b>Off:</b> '.$Items['off'].' ('.$thisitem['off'].')<br /><b>Deff:</b> '.$Items['deff'].' ('.$thisitem['deff'].')<br /><br /><b>Wert: </b>'.$Price.'<br /><br /><b>min. Stärke: </b>'.$Items['minStaerke'].' ('.$user['staerke'].')<br /><b>min. Geschick: </b>'.$Items['minGeschick'].' ('.$user['geschick'].')<br /><b>min. Kondition: </b>'.$Items['minKondition'].' ('.$user['kondition'].')<br /><b>min. Charisma: </b>'.$Items['minCarisma'].' ('.$user['charisma'].')<br /><b>min. Intelligenz: </b>'.$Items['minIntelligenz'].' ('.$user['inteligenz'].')<br /><b>min. Rang: </b>'.getRangName2($Items['rang']).' ['.$Items['rang'].'] ('.getRangName2($user['rang']).'['.$user['rang'].'])<br /><br />';
	
	if ($Items['ort'] == 'basar' || $Items['ort'] == 'lager' || $Items['ort'] == 'markt')
	{
			$Itemword .= '<b>Auktionsende: </b><br />- '.date('H:i',$Items['dauer']).'<br />- '.date('d.m.Y',$Items['dauer']).'<br /><br />';
	}
	
	$Itemword .= 'Item gehört <em>'.$Owner.'</em>';
	
	$ausgabe['text'] = $ausgabe['text'].'<tr><td><img onmouseout="hideTooltip();" onmouseover="initTooltip(\''.$Items['id'].'\');" src="Images/Items/'.$Items['pic'].'" /></td><td class="border" >'.utf8_encode($Items['name']).'</td><td>'.$Price.'</td><td>'.$Bid.'</td></tr>';
	
	$ausgabe['tool'] = $ausgabe['tool'].'<div class="tooltip" id="'.$Items['id'].'"><b>'.utf8_encode($Items['name']).'</b><p>'.$Itemword.'</p></div>';
}

$ausgabe['text'] = $ausgabe['text'].'</tfoot></table>';

echo json_encode($ausgabe);

function translateItemNames($Name)
{
	switch($Name)
	{
		case 'weapon': $Return = 'Waffen'; break;
		case 'shield': $Return = 'Schilder'; break;
		case 'armor': $Return = 'Rüstungen'; break;
		case 'head': $Return = 'Helme'; break;
		case 'gloves': $Return = 'Handschuhe'; break;
		case 'lowbody': $Return = 'Beinrüstungem'; break;
		case 'belt': $Return = 'Gürtel'; break;
		case 'foots': $Return = 'Schuhe'; break;
		case 'cape': $Return = 'Umhänge'; break;
		case 'shoulder': $Return = 'Schulterrüstungen'; break;
		default: $Return = false;
	}

	return $Return;
}

function findItem($art, $sid)
{
	$Query = mysql_query("SELECT * FROM ausruestung WHERE user_id='".$sid."' AND ort = 'user' AND art='".$art."' LIMIT 1");
	$useritem = mysql_fetch_assoc($Query);

	$array = array();
	if ($useritem)
	{
		$array['off'] = $useritem['off'];
		$array['deff'] = $useritem['deff'];
		
	} else
	{
		$array['off'] = 0;
		$array['deff'] = 0;
	}
	
	
	return $array;
}

?>
