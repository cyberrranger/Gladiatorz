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

//das zu kaufende Item
$Query = mysql_query("SELECT * FROM ausruestung WHERE id='".$_POST['item']."' AND ((ort='markt' AND preis > '0') OR ( ort='lager' AND preis > '0') OR (user_id='4' && ort='inventar') OR ort='schmiede') LIMIT 1");	

$buyitem = mysql_fetch_assoc($Query);

if (!$buyitem)
{
	$ausgabe['errorcode'] = 1;
	$ausgabe['msg'] = "Diese Item gibt es nicht";
	echo json_encode($ausgabe);
	exit;
}

if ($buyitem['user_id'] == $sid)
{
	$ausgabe['errorcode'] = 2;
	$ausgabe['msg'] = "Diese Item wird von dir verkauft du kannst es nicht zurückkaufen";
	echo json_encode($ausgabe);
	exit;
}

//der Käufer
$user = get_user_things($sid);

if ($buyitem['preis'] > 0) {
$cost = $buyitem['preis'];
} else {
$cost = $buyitem['wert'];
}
//Gold prüfen
if ($user['gold'] < $cost)
{
	$ausgabe['errorcode'] = 3;
	$ausgabe['msg'] = "Du hast nicht genug Gold";
	echo json_encode($ausgabe);
	exit;
}

$anbieter = $buyitem['user_id'];

if ($buyitem['ort'] == 'schmiede')
{
	//gold abziehen
	$Update = mysql_query("UPDATE user SET gold=gold-".$buyitem['wert']." WHERE id='".$sid."' LIMIT 1");
	
	$Insert = mysql_query("INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,pic,ZHW) 
	VALUES ('".$user['id']."','".$buyitem['name']."','".$buyitem['art']."','".$buyitem['off']."','".$buyitem['deff']."','".$buyitem['minStaerke']."','".$buyitem['minGeschick']."','".$buyitem['minKondition']."','".$buyitem['minCarisma']."','".$buyitem['minIntelligenz']."','inventar','".$buyitem['wert']."','".$buyitem['pic']."','".$buyitem['ZHW']."')");
	
}elseif ($anbieter == 4)
{
	//gold abziehen
	$Update = mysql_query("UPDATE user SET gold=gold-".$buyitem['wert']." WHERE id='".$sid."' LIMIT 1");
	$Update = mysql_query("UPDATE user SET gold=gold+".$buyitem['wert']." WHERE id='".$anbieter."' LIMIT 1");
	
	$Update = mysql_query("UPDATE ausruestung SET user_id=".$sid." ,ort = 'inventar', preis = '0', dauer = '0'  WHERE id='".$buyitem['id']."' LIMIT 1");
	
} else
{
	//es darf gekauft werden
	$Update = mysql_query("UPDATE ausruestung SET user_id=".$sid." ,ort = 'inventar', preis = '0', dauer = '0'  WHERE id='".$buyitem['id']."' LIMIT 1");
	
	//gold abziehen
	$Update = mysql_query("UPDATE user SET gold=gold-".$buyitem['preis']." WHERE id='".$sid."' LIMIT 1");
	//gold hinzu
	$Update = mysql_query("UPDATE user SET gold=gold+".$buyitem['preis']." WHERE id='".$anbieter."' LIMIT 1");
	
	//dem Verküfer eine Nachricht schicken das er sein Item los geworden ist
	$Titel = 'Item auf dem Markt verkauft';
	$Empfaenger = get_user_things($anbieter, 'name');
	$Text = "Hallo $Empfaenger,<br />du konntest erfolgreich das Item $buyitem[name] auf dem Markt für $buyitem[preis] verkaufen.";
	$Absender = 'Marktleitung';
	
	sendIGM($Titel,$Text,$Empfaenger,$Absender);
}

$ausgabe['errorcode'] = 0;
if ($buyitem['ort'] == 'schmiede' || ($buyitem['ort'] == 'inventar' && $buyitem['user_id'] == 4))
{
	$ausgabe['msg'] = "Du hast das Item ".utf8_encode($buyitem['name'])." erfolgreich für ".$buyitem['wert']." Gold gekauft.";
} else
{
	
	$ausgabe['msg'] = "Du hast das Item ".utf8_encode($buyitem['name'])." erfolgreich für ".$buyitem['preis']." Gold gekauft.";
}



echo json_encode($ausgabe);
?>
