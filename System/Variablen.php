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

// Userdaten auslesen
$user = mysql_select("*", "user", "id = '$_SESSION[id]'", null, null);

// Varname Fix, seit Weihnachts Edition
$User =& $user;
$user =& $User;

// PowMod des Users Überprüfen
if(isset($_SESSION["id"]) && $_GET["hash"] == null)
{
	if($user['powmod'] < 0)
	{
		$user['powmod'] = 0;
		mysql_update("user","powmod='$user[powmod]'","id='$_SESSION[id]'");
	} elseif($user[powmod] > $GLOBALS['conf']['konst']['max_pm'])
	{
		$user['powmod'] = $GLOBALS['conf']['konst']['max_pm'];
		mysql_update("user","powmod='$user[powmod]'","id='$_SESSION[id]'");
	}

	$timestamp = time();
	mysql_update("user","lonline='$timestamp'","name='$_SESSION[name]'");
}

// Schule des Users auslesen
if(strstr($user['schule'],'j') != '')
{
	$User['AllyJoin'] = substr(strstr($user['schule'],'j'),1);
	$user['schule'] = 0;
} else
{
	$User['AllyJoin'] = false;
}

if($user['schule'] != 0)
{
	$schuleSql = "SELECT * FROM ally_schule WHERE id='$user[schule]' LIMIT 1";
	$schuleQuery = mysql_query($schuleSql);
	$schule = mysql_fetch_assoc($schuleQuery);
}

$UserAlly = $schule;

$Sql = "SELECT COUNT(id) FROM nachrichten WHERE gelesen='n' AND del_empfaenger = 'n' AND empfaenger = '".$user['name']."'";
$Query = mysql_query($Sql);
$Messages = mysql_fetch_row($Query);

$Query = mysql_query("SELECT * FROM settings WHERE user_id='".$User['id']."' LIMIT 1");
$Settings = mysql_fetch_assoc($Query);
if($Settings == false)
{
	$Insert = mysql_query("INSERT INTO ".TAB_SETTINGS." (user_id) VALUES ('".$User['id']."')");
	$Query = @mysql_query("SELECT * FROM ".TAB_SETTINGS." WHERE user_id='".$User['id']."' LIMIT 1");
	$Settings = @mysql_fetch_assoc($Query);
}

?>
