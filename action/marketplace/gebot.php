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
$Query = mysql_query("SELECT * FROM ausruestung WHERE id='".$_POST['item']."' AND preis>'0' AND ort='basar' LIMIT 1");
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
	$ausgabe['msg'] = "Diese Item wird von dir angeboten";
	echo json_encode($ausgabe);
	exit;
}

//der Käufer
$user = get_user_things($sid);

//jetzt gilt es den aktuellen Preis herauszufinden
$_Gebot = mysql_query("SELECT * FROM gebote WHERE item='$buyitem[id]' AND repay='n' ORDER BY preis DESC LIMIT 1");
$_Gebot = mysql_fetch_assoc($_Gebot);

//es gibt keine Bieter 
if (!$_Gebot)
{
	$buyitem['preis'] = $buyitem['preis'];
} else
{
	$buyitem['preis'] = $_Gebot['preis'];
}
/*
//Gold prüfen
if ($user['gold'] < $buyitem['preis'])
{
	$ausgabe['errorcode'] = 3;
	$ausgabe['msg'] = "Du hast nicht genug Gold";
	echo json_encode($ausgabe);
	exit;
}
*/
$summe = $_POST['summe'];
if ($_POST['how'] == 3)
{
	if (!is_numeric($summe))
	{
		$ausgabe['errorcode'] = 4;
		$ausgabe['msg'] = "Biete gebe ein Zahl ein";
		echo json_encode($ausgabe);
		exit;
	}
	
	//ist das gebot hoch genug bei how == 3?
	if ($user['gold'] < $summe)
	{
		$ausgabe['errorcode'] = 5;
		$ausgabe['msg'] = "Du hast nicht genug Gold für so ein großes Gebot";
		echo json_encode($ausgabe);
		exit;
	}
	
	$neues_gebot = $summe;
	
	$tmp_gebot = $buyitem['preis'] * 3;
	if ($tmp_gebot < $summe)
	{
		$ausgabe['errorcode'] = 6;
		$ausgabe['msg'] = "Dieses Gebot ist zu groß bitte gebe maximal $tmp_gebot Gold ein";
		echo json_encode($ausgabe);
		exit;
	}
	
	if ($buyitem['preis'] > $summe)
	{
		$ausgabe['errorcode'] = 7;
		$ausgabe['msg'] = "Dieses Gebot ist zu klein bitte gebe min. $buyitem[preis] Gold ein";
		echo json_encode($ausgabe);
		exit;
	}
	
}elseif ($_POST['how'] == 2)
{
	$neues_gebot = round($buyitem['preis'] * 1.1);
}elseif ($_POST['how'] == 1)
{
	$neues_gebot = round($buyitem['preis'] * 1.03);
}

if ($user['gold'] < $neues_gebot)
{
	$ausgabe['errorcode'] = 5;
	$ausgabe['msg'] = "Du hast nicht genug Gold für so ein großes Gebot";
	echo json_encode($ausgabe);
	exit;
}

$Time = time();
//Gebot ist OK
//alte gebote zurückzahlen
if ($_Gebot)
{
	$Gebot_back = mysql_query("UPDATE user SET gold=gold+'$_Gebot[preis]' WHERE id='$_Gebot[bieter]' LIMIT 1");
	$Gebot_back2 = mysql_query("UPDATE gebote SET repay='j' WHERE id='$_Gebot[id]' LIMIT 1");
	
	//nachricht verschicken
	$Titel = 'Du wurdest auf dem Basar überboten';
	$Empfaenger = get_user_things($_Gebot['bieter'], 'name');
	$Text = "Hallo $Empfaenger,<br />du wurdest auf dem Basar überboten, dein Gold hast du zurück bekommen! Es handelt sich um das Item ".utf8_encode($buyitem['name'])." Du kannst dieses Item leicht auf dem Basar finden in dem du im Basar nach deinen Geboten suchst. Los, sieh lieber gleich nach bevor es zu spät ist ;)<br />MfG die Basarleitung";
	$Absender = 'Basarleitung';

	sendIGM($Titel,$Text,$Empfaenger,$Absender);
}

//gebot add
$InsertSql = "INSERT INTO gebote (item,bieter,preis,repay,time) VALUES ('$buyitem[id]','$user[id]','$neues_gebot','n','$Time')";
$Insert = mysql_query($InsertSql);

//Gold abziehen
$Update = mysql_query("UPDATE user SET gold=gold-".$neues_gebot." WHERE id='".$sid."' LIMIT 1");

$ausgabe['summe'] = $neues_gebot;
$ausgabe['msg'] = "Es wurde erfolgreich ein Gebot abgegeben.";
						
echo json_encode($ausgabe);
?>
