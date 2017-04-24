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
} else {
	$sid = $_SESSION['id'];
}

require('../System/conf.php');
require('../System/Database.php');
require('../System/new_functions.php');
require('../System/Functions.php');
require('../System/GameFunctions.php');
require('../System/JesusCryOld.php');

if ($_POST['do'] == 'move')
{
	$query = mysql_query( "SELECT * FROM moves WHERE uid=".$sid." LIMIT 1");
	$sm = mysql_fetch_assoc($query);

	$countall = $sm['kraftschlag']
	+$sm['wutschrei']
	+$sm['armor']
	+$sm['kritischer_schlag']
	+$sm['wundhieb']
	+$sm['kraftschrei']
	+$sm['block']
	+$sm['taeuschen']
	+$sm['koerperteil_abschlagen']
	+$sm['sand_werfen']
	+$sm['ausweichen']
	+$sm['todesschlag']
	+$sm['konter']
	+$sm['berserker']
	+$sm['anti_def'];
	
	$user['level'] = get_user_things($sid, 'level');
	
	$countmax = $user['level']*2;
	$count = $countmax - $countall;

	//darf geskillt werden?
	if($count <= 0) {
		$sm['error'] = 'notenoughtpm';
		echo json_encode($sm);
		exit; 
	}
	
	//darf der SPieler diese eigenschaft skillen?
	$check = array();
	$check['kraftschlag'] = true;
	$check['wutschrei'] = true;
	$check['armor'] = true;
	
	if ($user['level'] >= 30) {
		if($sm['kraftschlag'] > 19)
		{
			$check['kritischer_schlag'] = true;
		}
		if($sm['wutschrei'] > 29)
		{
			$check['kraftschrei'] = true;
		}
		if($sm['armor'] > 29)
		{
			$check['block'] = true;
			$check['taeuschen'] = true;
		}
		$check['wundhieb'] = true;
		$check['koerperteil_abschlagen'] = false;
		$check['sand_werfen'] = false;
		$check['ausweichen'] = false;
		$check['todesschlag'] = false;
		$check['konter'] = false;
		$check['berserker'] = false;
		$check['anti_def'] = false;
	} 
	
	if ($user['level'] >= 60) {
		if($sm['kritischer_schlag'] > 19 && $sm['wundhieb'] > 39)
		{
			$check['koerperteil_abschlagen'] = true;
		}
	
		if($sm['kraftschrei'] > 19)
		{
			$check['sand_werfen'] = true;
		}
		if($sm['block'] > 49)
		{
			$check['ausweichen'] = true;
		}
		$check['todesschlag'] = false;
		$check['konter'] = false;
		$check['berserker'] = false;
		$check['anti_def'] = false;	
	} 
	
	if ($user['level'] >= 90) {
		if($sm['koerperteil_abschlagen'] > 29)
		{
			$check['todesschlag'] = true;
		}
		if($sm['ausweichen'] > 49)
		{
			$check['konter'] = true;
		}
		if($sm['taeuschen'] > 49)
		{
			$check['berserker'] = true;
		}
		if($sm['sand_werfen'] > 29)
		{
			$check['anti_def'] = true;
		}
	}
	
	if(!$check[$_POST['what']]) {
		$sm['error'] = 'notenoughtpm';
		echo json_encode($sm);
		exit; 
	}
	
	//Ja es darf
	 mysql_query("UPDATE moves SET ".$_POST['what']."=".$_POST['what']."+1 WHERE uid='".$sid."' LIMIT 1");
	 $sm[$_POST['what']]++;
	 $sm['count'] = $count - 1;
	 
	echo json_encode($sm);
}
?>
