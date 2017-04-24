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
if(isset($_SESSION['id']))
{
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
	
	$Scroll=' style="overflow:auto;height:464;"';
	
	if (!$GLOBALS['conf']['activ']['shoutbox'])
	{
		$Box = '
		<div id="box_head">&nbsp;</div>
		<div id="box" style="height:500px">

		<div id="write">
		<form accept-charset="utf-8" name="sendmsg" method="post">
		<input style="width:150px;" maxlength="350" type="text" id="boxmsg" name="boxmsg" />
		&nbsp;<button style="height:30px;width:50px;" id="showok">OK</button>
		</form>
		</div>

		<div id="show"'.$Scroll.'>'.$BoxShow.'</div>
		</div><div id="box_bottom">&nbsp;</div>';

		$Smarty->assign('ShoutBox',$Box);
	}
}

?>
