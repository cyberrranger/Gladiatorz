<div class="center">
  <a href="?site=profil">Profil</a> (<a href="?site=userinfo&info=<?php echo $User['id']; ?>">eigenes</a>) |
  <a href="?site=signatur">Signatur</a> |
  <a href="?site=boxlog">BoxLog</a>
</div><br />
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
$datum = getdate();

if (!$_POST['day'])
{
	$_POST['day'] = $datum["mday"];
} else
{
	$_POST['day'] = trim($_POST['day']);

	if (!is_numeric($_POST['day']))
	{
		$_POST['day'] = $datum["day"];
	}
}

if (!$_POST['month'])
{
	$_POST['month'] = $datum["mon"];
} else
{
	$_POST['month'] = trim($_POST['month']);

	if (!is_numeric($_POST['month']))
	{
		$_POST['month'] = $datum["mon"];
	}
}

echo '<center>
	<form name="ShowLog" method="post" action="?site=boxlog">
		<p>Chatlog vom</p>&nbsp;';

		echo '<select name="day">';
			for ($i = 1;$i<=31;$i++)
			{
				$att = '';
				if (
					($i == $datum["mday"] && $_POST['day'] == $i)
					|| ($i == $_POST['day'] && $i != $datum["mday"] )
					)
				{
					$att = 'selected="selected"';
				}

				echo "<option $att value=".$i.">".$i."</option>";
			}
		echo '</select>';

		echo '<select name="month">';
			for ($i = 1;$i<=12;$i++)
			{
				switch ($i)
				{
					case 1:
						$mname = "Januar";
						break;
					case 2:
						$mname = "Februar";
						break;
					case 3:
						$mname = "M&auml;rz";
						break;
					case 4:
						$mname = "April";
						break;
					case 5:
						$mname = "Mai";
						break;
					case 6:
						$mname = "Juni";
						break;
					case 7:
						$mname = "Juli";
						break;
					case 8:
						$mname = "August";
						break;
					case 9:
						$mname = "September";
						break;
					case 10:
						$mname = "Oktober";
						break;
					case 11:
						$mname = "November";
						break;
					case 12:
						$mname = "Dezember";
						break;

				}

				$att = '';
				if (
					($i == $datum["mon"] && $_POST['month'] == $i)
					|| ($i == $_POST['month'] && $i != $datum["mon"] )
					)
				{
					$att = 'selected="selected"';
				}
				echo "<option $att value=".$i.">".$mname."</option>";
			}
		echo '</select>';

		echo '<input type="submit" value="Anzeigen" />';
	echo '</form>';
echo '</center>';

$month = $_POST['month'];
$day = $_POST['day'];
$daysOfMonth = date('t',mktime(1,1,1,$month,1,date('Y')));

if($day <= $daysOfMonth && $day >= 1 && $month >= 1 && $month <= 12)
{
	$StartTime = mktime(0,0,0,$month,$day,date('Y'));
	$EndTime = mktime(23,59,59,$month,$day,date('Y'));

	$Query = mysql_query("SELECT * FROM ".TAB_SHOUTBOX." WHERE time BETWEEN ".$StartTime." AND ".$EndTime." ORDER BY id DESC");

	while($Msg = mysql_fetch_assoc($Query))
	{
		$Query2 = mysql_query("SELECT id,name FROM ".TAB_USER." WHERE id='".$Msg['from']."' LIMIT 1");
		$Sender = mysql_fetch_assoc($Query2);

		$new_body = htmlspecialchars($Msg['msg']);
		$new_body = br2nl($bbcode->parse($new_body));

		if($Sender == false)
		{
			echo'
			<p style="font-size:9px;"><strong>
			'.$Msg['id'].': *gel&ouml;scht* ('.date('j, M H:i',$Msg['time']).')
			</strong><br />'.$new_body.'</p>';
		} else
		{
			echo'
			<p style="font-size:9px;"><strong>
			'.$Msg['id'].': <a href="?site=userinfo&info='.$Msg['from'].'" style="font-size:9px;">'.$Sender['name'].'</a>
			('.date('j, M H:i',$Msg['time']).')
			</strong><br />'.$bbcode->parse(makeSmilie(utf8_encode($new_body))).'</p>';
		}
	}
}


?>
