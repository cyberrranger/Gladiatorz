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
if(isset($_GET['info']))
{
  $ID = $_GET['info'];

  $InfoSql = "SELECT * FROM ".TABUSER." WHERE id='$ID' LIMIT 1";
  $InfoQuery = mysql_query($InfoSql);
  $Info = mysql_fetch_assoc($InfoQuery);

	if($_REQUEST['this_user'])
	{
		//5 Tage
		$time = 5*24*60*60 + time();

		$Query = @mysql_query("INSERT INTO open_fights (user1,user2,time,status) VALUES ('".$User['id']."','".$Info['id']."','".$time."',0)");
	  	$this_chal = @mysql_fetch_assoc($Query);
	  	echo "Du hast diesen Spieler erfolgreich herausgefordert.";

	  	$msg= "Der Spieler $User[name] hat dich herausgefordert.";

	  	sendIGM('Neue Herausforderung!',$msg,$Info['name'],'Challenge');
	}

  $SchuleSql = "SELECT kuerzel FROM ally_schule WHERE id='$Info[schule]' LIMIT 1";
  $SchuleQuery = mysql_query($SchuleSql);
  $Schule = mysql_fetch_assoc($SchuleQuery);

  if(substr($Info['avatar'],0,7) != 'http://' && $Info['avatar'] != '')
  {
    $Info['avatar'] = 'http://'.$Info['avatar'];
  }

  if($Info['avatar'] == '')
  {
    $Info['avatar'] = 'Images/Design/pic.gif';
  }

  if($Info != false)
  {
    echo'<center><strong style="font-size:24px;">Profil von '.$Info['title'].' '.$Info['name'].'</strong></center><br />';

    $Rang = getRangName($Info['level']);

    $PostsSql = "SELECT count(id) FROM forum_answers WHERE user='$Info[id]'";
    $PostsQuery = mysql_query($PostsSql);
    $Posts = mysql_fetch_row($PostsQuery);


    if($Info['descr'] == '')
	{
	  $Info['descr'] = '[center]'.$Info['name'].' hat keine Beschreibung seines Characters hinterlassen.[/center]';
	}

	if ($Info['sex'] == 'm') {
		$Info['sex'] = 'M&auml;nnlich';
	} elseif ($Info['sex'] == 'w') {
		$Info['sex'] = 'Weiblich';
	} else {
		$Info['sex'] = '---';
	}
	
    echo'
	<table cellpadding="0" cellspacing="0" border="0" class="tab" width="500" align="center">
	<tr valign="top">
	  <td width="160" height="130" style="padding:0px;"><img src="'.$Info['avatar'].'" border="0" width="160" height="160" /></td>
	  <td rowspan="2" style="text-align:left;padding:3px;">'.$bbcode->parse(br2nl($Info[descr])).'</td>
	</tr>
	<tr>
	  <td>
	    <strong>'.$Info['name'].'</strong><br />
	    '.$Posts[0].' Posts
             <br />
             <strong>'.$Title.'</strong><br />
             <br />
			<strong>Rang:</strong>
			'.$Rang.'<br /><br />'.$Info['ppl_kills_win'].' Kills<br /><br />
			<em style="font-size:12px;">'.get_points($Info['id']).' Punkte</em><br /><br />
			<strong>Level:</strong>  <br />
			'.$Info['level'].'<br /><br />
			<strong>Schule:</strong>  <br />
			'.utf8_encode($Schule['kuerzel']).'<br /><br />
			<strong>Real Life Name:</strong>  <br />
			'.utf8_encode($Info['rlname']).'<br /><br />
			<strong>Geschlecht:</strong>   <br />
			'.utf8_encode($Info['sex']).'<br /> <br />
			<strong>Website/Blog:</strong> <br />
			'.utf8_encode($Info['website']).'<br /> <br />
			<strong>ICQ-Nummer:</strong> <br />
			'.$Info['icq'].'<br />    <br />
	  </td>
	</tr>
	</table>';

	$AuszeichnungSql = 'SELECT * FROM auszeichnung WHERE UID='.$ID.'';
	$AuszeichnungQuery = mysql_query($AuszeichnungSql);
	$Auszeichnung = mysql_fetch_assoc($AuszeichnungQuery);

	if(!empty($Auszeichnung))
	{
		echo '<table width="500">
		<tr>
		<th colspan="2">Auszeichnungen</th>
		</tr>';

		$AuszeichnungSql2 = 'SELECT * FROM auszeichnung WHERE UID='.$ID.'';
		$AuszeichnungQuery2 = mysql_query($AuszeichnungSql2);
		while($Auszeichnung2 = mysql_fetch_assoc($AuszeichnungQuery2))
		{
			echo'<tr>
			<td><img src="'.$Auszeichnung2['img'].'" width="80" height="80"></td>
			<td>'.$Auszeichnung2['Text'].'</td>
			</tr>';
		}
	}
	echo'</table>';
	echo'<br /><center><strong style="font-size:14px;">'.$Info['name'].'\'s Ausrüstung</strong></center><br />';
	echo "<table width=\"500\">";
	echo "<tr>";
	echo "<th width= \"100\"><b>Art</b></th>";
	echo "<th colspan=\"2\"><b>Ausrüstung</b></th>";
	echo "<th width=\"30\" style=\"padding:0px;\"><img src=\"Images/OldStuff/weapon.jpg\" /></th>";
	echo "<th width=\"30\" style=\"padding:0px;\"><img src=\"Images/OldStuff/armor.jpg\" /></th>";
	echo "</tr>";

$ItemTitle = array(
'weapon',
'shield',
'head',
'shoulder',
'armor',
'lowbody',
'cape',
'belt',
'gloves',
'foots');

$ItemName = array(
'Waffen',
'Schild/ZW',
'Helme',
'Schulterrüstung',
'Körperrüstung',
'Beinrüstung',
'Umhänge',
'Gürtel',
'Handschuhe',
'Schuhe/Stiefel');

for($i=0;$i<10;$i++)
{
  $Item = getEquipment($Info['id'],$ItemTitle[$i]);

  echo'
  <tr height="32">
    <td width="100" style="font-size:11px;">'.$ItemName[$i].'</td>
    <td width="30" style="padding:0px;"><img src="Images/Items/'.$Item['pic'].'" /></td>
    <td>'.utf8_encode($Item['name']).'</td>
    <td width="25" style="font-size:11px;">'.$Item['off'].'</td>
    <td width="25" style="font-size:11px;">'.$Item['deff'].'</td>
  </tr>';

  $AllOff += $Item['off'];
  $AllDef += $Item['deff'];
}

echo'
<tr bgcolor="darkred">
  <td width="100"><strong>gesamt</strong></td>
  <td colspan="2"></td>
  <td width="25" style="font-size:11px;"><strong>'.$AllOff.'</strong></td>
  <td width="25" style="font-size:11px;"><strong>'.$AllDef.'</strong></td>
</tr>';


echo "</table>";

	echo'<br /><center><a href="index.php?site=nachrichten&empfaenger='.$Info['name'].'" target="_self">'.$Info['name'].' anschreiben</a></center>';
  }
  else
  {
    echo'<center><strong>Diesen User gibt es nicht!</strong></center>';
  }
}
else
{
  echo'<center><strong>Link nicht korrekt!</strong></center>';
}
if($User['id'] != $Info['id'])
{
	// Herrausforderung aussprechen
	echo '<br />
		<form name=challenge method=post action=index.php?site=userinfo&info='.$Info['id'].'>
			<input type=submit name="this_user" value="Diesen Spieler herausfordern?">
		</form>';
}
?>
