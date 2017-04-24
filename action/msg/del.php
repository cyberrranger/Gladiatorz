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

require('../../System/conf.php');
require('../../System/Database.php');
require('../../System/new_functions.php');
require('../../System/Functions.php');
require('../../System/GameFunctions.php');
require('../../System/JesusCryOld.php');
if ($_POST['do'] == 'del' && is_numeric($_POST['what'])) {

	$id = $_POST['what'];
	$igm = mysql_fetch_assoc(mysql_query("SELECT * FROM igm WHERE id ='".$id."' LIMIT 1"));
	if ($igm) {
		$to = $igm['touser'];
	} else {
		return false;
	}

	if ($sid != $to) {
		return false;
	}

	mysql_delete("igm","id='$id'");

	$status['result'] = "OK";
	echo json_encode($status);
}
?>
