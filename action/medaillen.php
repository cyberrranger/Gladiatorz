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
session_start();

if(!isset($_SESSION['id']))
{
	exit;
}
else
{
	$sid = $_SESSION['id'];
}

require('../System/conf.php');
require('../System/Database.php');
require('../System/new_functions.php');

$thisuser = get_user_things($sid);

if ($_POST['do'] == 'sm')
{
	//SM zurücksetzten
	if($thisuser['medallien'] >= 100)
	{	
		$Update = mysql_query("UPDATE user SET medallien=medallien-100 WHERE id='".$thisuser['id']."' LIMIT 1");
		$Update2 = mysql_query("UPDATE moves SET kraftschlag='0' , wutschrei='0' , armor='0' , kritischer_schlag='0' , wundhieb='0' , kraftschrei='0' , block='0' , taeuschen='0' , koerperteil_abschlagen='0' , sand_werfen='0' , ausweichen='0' , todesschlag='0' , konter='0' , berserker='0' WHERE uid='".$thisuser['id']."' LIMIT 1");
		
		$thisuser['medallien'] = $thisuser["medallien"] - 100;
		$thisuser['count'] = $thisuser["medallien"];
		$thisuser['errorcode'] = 0;
	} else
	{
		$thisuser['errorcode'] = 1;
	}
}

if ($_POST['do'] == 'ue') {
	
	//QIC kosten berechnen
	$cost = 10;
	switch($_POST['value']) {
		case 1:
			$cost = $cost + $thisuser['Schlagkraft'];
			break;
		case 2:
			$cost = $cost + $thisuser['Einstecken'];
			break;
		case 3:
			$cost = $cost + $thisuser['Kraftprotz'];
			break;
		case 4:
			$cost = $cost + $thisuser['Glueck'];
			break;
		case 5:
			$cost = $cost + $thisuser['Sammler'];
			break;
	}
	
	if ($thisuser['QIC']<$cost) {
	$thisuser['errorcode'] = 1;
		echo json_encode($thisuser);
		exit;
	}
	
	$Update = mysql_query("UPDATE user SET QIC=QIC-'".$cost."' WHERE id='".$sid."' LIMIT 1");
	
	//es sind genug QIC da
	switch($_POST['value']) {
		case 1:
			$Update2 = mysql_query("UPDATE user SET Schlagkraft=Schlagkraft+1 WHERE id='".$sid."' LIMIT 1");
			$buttonText = "Schlagkraft +1 (".($cost + 1)." QIC)";
			break;
		case 2:
			$Update2 = mysql_query("UPDATE user SET Einstecken=Einstecken+1 WHERE id='".$sid."' LIMIT 1");
			$buttonText = "Einstecken +1 (".($cost + 1)." QIC)";
			break;
		case 3:
			$Update2 = mysql_query("UPDATE user SET Kraftprotz=Kraftprotz+1 WHERE id='".$sid."' LIMIT 1");
			$buttonText = "Kraftprotz +1 (".($cost + 1)." QIC)";
			break;
		case 4:
			$Update2 = mysql_query("UPDATE user SET Glueck=Glueck+1 WHERE id='".$sid."' LIMIT 1");
			$buttonText = "Glueck +1 (".($cost + 1)." QIC)";
			break;
		case 5:
			$Update2 = mysql_query("UPDATE user SET Sammler=Sammler+1 WHERE id='".$sid."' LIMIT 1");
			$buttonText = "Sammler +1 (".($cost + 1)." QIC)";
			break;
	}
	
	$thisuser['count'] = $thisuser['QIC']-$cost;
	$thisuser['buttonText'] = $buttonText;
	echo json_encode($thisuser);
	exit;
}


if ($_POST['do'] == 'titel')
{
	//funktion um den Titem zu ändern
	if($thisuser['medallien'] >= 50)
	{	
		if(isset($_REQUEST["newtitle"]) && $_REQUEST["newtitle"] != $thisuser['title'])
		{
			$Update = mysql_query("UPDATE user SET title='".$_REQUEST['newtitle']."' WHERE id='".$thisuser['id']."' LIMIT 1");
			$Update2 = mysql_query("UPDATE user SET medallien=medallien-50 WHERE id='".$thisuser['id']."' LIMIT 1");
			
			$thisuser['title'] = $_REQUEST["newtitle"];
			$thisuser['medallien'] = $thisuser['medallien'] - 50;
			$thisuser['count'] = $thisuser["medallien"];
			$thisuser['errorcode'] = 0;
		}
	} else
	{
		$thisuser['errorcode'] = 1;
	}
}

//funktion um 3fach Kampf zu aktivieren
if($_POST['do'] == 'multfights')
{
	if($thisuser['medallien'] >= 35)
	{
		$Update = mysql_query("UPDATE user SET medallien=medallien-35 WHERE id='".$sid."' LIMIT 1");
		$Update2 = mysql_query("UPDATE settings SET multfights='".(time()+604800)."' WHERE user_id='".$sid."' LIMIT 1");
		
		$thisuser['medallien'] = $thisuser['medallien'] - 35;
		$Settings['multfights'] = time()+604800;
		$thisuser['count'] = $thisuser["medallien"];
		$thisuser['buttonText'] = "mehrfachkämpfe siind aktiv";
		$thisuser['errorcode'] = 0;
	}
	else
	{
		$thisuser['errorcode'] = 1;
	}
}

echo json_encode($thisuser);
?>
