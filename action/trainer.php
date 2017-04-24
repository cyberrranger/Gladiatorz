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

if ($_POST['do'] == 'train1')
{
	$thisuser = get_user_things($sid);
	
	if ($thisuser['schlafen'] > time()) {
		$thisuser['error'] = 'notenoughtpm';
		echo json_encode($thisuser);
		exit; 
	}
	
	//Schule
	if($thisuser['schule'] != 0) {
		$query = mysql_query("SELECT * FROM ally_schule WHERE id='".$thisuser['schule']."' LIMIT 1");
		$schule = mysql_fetch_assoc($query);
	}
  
	$kosten = calcTraincost($thisuser, $schule);
	
	//gibt es genügend PM?
	if ($thisuser['powmod'] >= $kosten['pm']
		&& $thisuser['gold'] >= $kosten['gold'] ) {
		$thisuser[$_REQUEST['what']]++;

		if ($thisuser[$_REQUEST['what']] == 51) {
			$thisuser[$_REQUEST['what']] = 50;
			$thisuser['error'] = 'notenoughtpm';
			echo json_encode($thisuser);
			exit;
		}
		
		$thisuser['powmod'] = $thisuser['powmod'] - $kosten['pm'];
		$thisuser['gold'] = $thisuser['gold'] - $kosten['gold'];
		
		$Update = mysql_query("UPDATE user SET ".$_REQUEST['what']."=".$_REQUEST['what']."+1,powmod = '".$thisuser['powmod']."',gold = '".$thisuser['gold']."', pmspar = '".$thisuser['pmspar']."'  WHERE id='".$thisuser['id']."' LIMIT 1");

		$thisuser['what'] = $thisuser[$_REQUEST['what']];

		$tmp = calcTraincost($thisuser, $schule);
		$thisuser['tmp'] = $tmp['pm'];
		$thisuser['tmp2'] = $tmp['gold'];

		if ($thisuser['powmod'] < $tmp['pm']
			&& $thisuser['gold'] < $tmp['gold']) {
			$thisuser['error'] = 'notenoughtpm';
		}
	} else {
		$thisuser['error'] = 'notenoughtpm';
	}

	echo json_encode($thisuser);
}
?>
