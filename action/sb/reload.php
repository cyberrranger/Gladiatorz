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


if(isset($_POST['msg']) && isset($_POST['user']))
{
	$msg = $_POST['msg'];
	
	$msg = htmlentities($msg, ENT_QUOTES, "UTF-8");
	$msg = trim($msg);
	$msg = strip_tags($msg);
	
	if(!empty($msg) && strlen($msg) <= 400)
	{
		mysql_query("INSERT INTO shoutbox (`time`,`from`,`fromname`,`msg`) VALUES ('".time()."','".$sid."','".$_POST['user']."','".$msg."')");
	}
}

$msg = '';
$BoxShow = '';

$query = mysql_query("SELECT * FROM shoutbox ORDER BY id DESC LIMIT 25");
while($msg = mysql_fetch_assoc($query))
{	
	$msg['msg'] = makeSmilie($msg['msg']);

	$BoxShow .= '
	<strong>
	<a href="?site=userinfo&info='.$msg['from'].'">'.$msg['fromname'].'</a>
	('.date('j.m H:i',$msg['time']).')
	</strong><br />'.$bbcode->parse(utf8_encode($msg['msg']));
}
echo json_encode(array('text' => $BoxShow));
?>
