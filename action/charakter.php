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
require('../System/Functions.php');

$thisuser = get_user_things($sid);

if ($_POST['do'] == 'char1')
{
	if ($thisuser['schlafen'] > time())
	{
		$thisuser['error'] = 'notenoughtpm';
		echo json_encode($thisuser);
		exit; 
	}
	
	$pm_kosten = calcEcost($thisuser[$_REQUEST['what']]);

	//gibt es genügend PM?
	if (($thisuser['pmspar'] + $thisuser['powmod']) >= $pm_kosten)
	{
		$thisuser[$_REQUEST['what']]++;

		if ($thisuser[$_REQUEST['what']] == 51)
		{
			$thisuser[$_REQUEST['what']] = 50;
			$thisuser['error'] = 'notenoughtpm';
			echo json_encode($thisuser);
			exit;
		}
		if ($thisuser['pmspar'] >=  $pm_kosten)
		{
			$thisuser['pmspar'] = $thisuser['pmspar'] - $pm_kosten;
			$pm_kosten = 0;
		} else
		{
			$pm_kosten = $pm_kosten - $thisuser['pmspar'];
			$thisuser['pmspar'] = 0;
		}

		$thisuser['powmod'] = $thisuser['powmod'] - $pm_kosten;

		$Update = mysql_query("UPDATE user SET ".$_REQUEST['what']."=".$_REQUEST['what']."+1,powmod = '".$thisuser['powmod']."', pmspar = '".$thisuser['pmspar']."'  WHERE id='".$thisuser['id']."' LIMIT 1");

		$thisuser['what'] = $thisuser[$_REQUEST['what']];
		$thisuser['tmp'] = calcEcost($thisuser[$_REQUEST['what']]);

		if (($thisuser['pmspar'] + $thisuser['powmod']) < $thisuser['tmp'])
		{
			$thisuser['error'] = 'notenoughtpm';
		}

	} else
	{
		$thisuser['error'] = 'notenoughtpm';
	}

	echo json_encode($thisuser);
}

if ($_POST['do'] == 'char2')
{
	if ($thisuser['schlafen'] > time())
	{
		$thisuser['error'] = 'notenoughtpm';
		echo json_encode($thisuser);
		exit; 
	}
	
	$kosten = calcUEcost($thisuser[$_REQUEST['what']], $_REQUEST['what']);

	//gibt es genügend PM?
	if (($thisuser['pmspar'] + $thisuser['powmod']) >= $kosten['pm']
		&& $thisuser['gold'] >= $kosten['gold']
	)
	{
		$thisuser[$_REQUEST['what']]++;

		if ($thisuser[$_REQUEST['what']] == 101)
		{
			$thisuser[$_REQUEST['what']] = 100;
			$thisuser['error'] = 'notenoughtpm';
			echo json_encode($thisuser);
			exit;
		}

		if ($thisuser['pmspar'] >=  $kosten['pm'])
		{
			$thisuser['pmspar'] = $thisuser['pmspar'] - $kosten['pm'];
			$kosten['pm'] = 0;
		} else
		{
			$kosten['pm'] = $kosten['pm'] - $thisuser['pmspar'];
			$thisuser['pmspar'] = 0;
		}

		$thisuser['powmod'] = $thisuser['powmod'] - $kosten['pm'];

		$thisuser['gold'] = $thisuser['gold'] - $kosten['gold'];


		$Update = mysql_query("UPDATE user SET ".$_REQUEST['what']."=".$_REQUEST['what']."+1,powmod = '".$thisuser['powmod']."',gold = '".$thisuser['gold']."', pmspar = '".$thisuser['pmspar']."'  WHERE id='".$thisuser['id']."' LIMIT 1");

		$thisuser['what'] = $thisuser[$_REQUEST['what']];

		$tmp = calcUEcost($thisuser[$_REQUEST['what']], $_REQUEST['what']);

		$thisuser['tmp'] = $tmp['pm'];
		$thisuser['tmp2'] = $tmp['gold'];

		if (($thisuser['pmspar'] + $thisuser['powmod']) < $tmp['pm']
			&& $thisuser['gold'] < $tmp['gold']
		)
		{
			$thisuser['error'] = 'notenoughtpm';
		}

	} else
	{
		$thisuser['error'] = 'notenoughtpm';
	}

	echo json_encode($thisuser);
}


if ($_POST['do'] == 'savepm')
{

	$thisuser['pmspar'] = $thisuser['pmspar'] + $thisuser['powmod'];
	$thisuser['powmod'] = 0;

	$Update = mysql_query("UPDATE user SET powmod = '".$thisuser['powmod']."', pmspar = '".$thisuser['pmspar']."'  WHERE id='".$thisuser['id']."' LIMIT 1");

	echo json_encode($thisuser);
}
?>
