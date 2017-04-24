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
# Willkommensnachricht

echo'<div class="center">Willkommen <strong>'.getRangName($User['level']).' '.$User['name'].'!</strong></div><br />';

/*
 * Tipps / Events / Umfrage / Tunier anzeigen
 */

showTipp();
showEvent();
#showUmfrage($User['contest']);
#showTunier($User['tunier']);
checkChallenge($User['id']);

showMSG($Messages[0]);
showSchlafen($User['schlafen']);

# Falls der User Allychef oder Vize ist &auml;ber neue Aufnahmeanfragen informieren

if($User['id'] == $UserAlly['boss'] || $User['id'] == $UserAlly['vize'])
{
  $Query = mysql_query("SELECT count(id) FROM user WHERE schule='0j".$UserAlly['id']."'");
  $Count = mysql_fetch_row($Query);

  if($Count[0] >= 1)
  {
	echo'
    <div class="center">
      <strong>
	    <a href="?site=schulen&sub=ver">&raquo; '.$Count[0].' neue(r) Bewerber warten auf die Aufnahme in deine Schule! &laquo;</a>
	  </strong>
    </div>';
  }
}

# Anzeige der neuesten Newsbeitr&auml;ge im Forum

echo'
<div class="center"><strong>Neue Newsbeitr&auml;ge</strong></div><br />
<table id="noborder" width="80%" border="0" cellpadding="0" cellspacing="0" align="center">';

$Query = mysql_query("SELECT * FROM forum_topics WHERE forums_id='1'  ORDER BY id DESC LIMIT 3");
while($NewTopics = mysql_fetch_assoc($Query))
{
  $Query2 = mysql_query("SELECT count(id) FROM forum_answers WHERE topics_id='".$NewTopics['id']."'");
  $Posts = mysql_fetch_row($Query2);

  echo'
  <tr style="border:0px;">
    <td width="80" style="border:0px;">'.date('d.m, H:i',$NewTopics['time']).'</td>
    <td style="border:0px;">
	  <a href="?site=forum&showtopic='.$NewTopics['id'].'" style="display:block;">'.$NewTopics['name'].' ('.$Posts[0].')</a>
	</td>
  </tr>';
}

echo'</table><br />';

# Anzeigen der neuesten Forenbeitr&auml;gen (keine News!)

echo'
<div class="center"><strong>Neue Forenbeitr&auml;ge</strong></div><br />
<table id="noborder" width="80%" border="0" cellpadding="0" cellspacing="0" align="center">';

$Query = mysql_query("SELECT * FROM forum_topics WHERE forums_id!='1' && forums_id<='9' OR forums_id = 11 ORDER BY id DESC LIMIT 7");
while($NewTopics = mysql_fetch_assoc($Query))
{
  $Query2 = mysql_query("SELECT count(id) FROM forum_answers WHERE topics_id='".$NewTopics['id']."'");
  $Posts = mysql_fetch_row($Query2);

  echo'
  <tr style="border:0px;">
    <td width="80" style="border:0px;">'.date('d.m, H:i',$NewTopics['time']).'</td>
    <td style="border:0px;">
	  <a href="?site=forum&showtopic='.$NewTopics['id'].'" style="display:block;">'.$NewTopics['name'].' ('.$Posts[0].')</a>
	</td>
  </tr>';
}

echo'</table><br />';


# Anzeigen der neuesten Pinnwandbeitr&auml;ge

  $AllySql = "SELECT * FROM ".TABALLY." WHERE id='$user[schule]' LIMIT 1";
  $AllyQuery = mysql_query($AllySql);
  $UserAlly = mysql_fetch_assoc($AllyQuery);

  $PinnSql = "SELECT * FROM ".TABFORUMS." WHERE type='allyin' AND name='$UserAlly[name]' LIMIT 1";
  $PinnQuery = mysql_query($PinnSql);
  $Pinn = mysql_fetch_assoc($PinnQuery);


$schuleid=$Pinn['id'];
if ($User['schule']!=0)
{
	echo'
	<div class="center"><strong>Neue Pinnwandbeitr&auml;ge</strong></div><br />
	<table id="noborder" width="80%" border="0" cellpadding="0" cellspacing="0" align="center">';

	$Query = mysql_query("SELECT * FROM forum_topics WHERE forums_id='$schuleid' ORDER BY id DESC LIMIT 3");
	while($NewTopics = mysql_fetch_assoc($Query))
	{
	  $Query2 = mysql_query("SELECT count(id) FROM forum_answers WHERE topics_id='".$NewTopics['id']."'");
	  $Posts = mysql_fetch_row($Query2);

	  echo'
	  <tr style="border:0px;">
	    <td width="80" style="border:0px;">'.date('d.m, H:i',$NewTopics['time']).'</td>
	    <td style="border:0px;">
		  <a href="?site=forum&showtopic='.$NewTopics['id'].'" style="display:block;">'.$NewTopics['name'].' ('.$Posts[0].')</a>
		</td>
	  </tr>';
	}

	echo'</table><br />';
}
//teamforum

if($User['status'] != 'user')
{
echo'
<div class="center"><strong>Neues im <a href=?site=forum&showforum=10>Teamforum</a></strong></div><br />
<table id="noborder" width="80%" border="0" cellpadding="0" cellspacing="0" align="center">';

$mod = mysql_query("SELECT * FROM forum_topics WHERE forums_id='10' ORDER BY id DESC LIMIT 3");
while($NewTopics = mysql_fetch_assoc($mod))
{
  $mod2 = mysql_query("SELECT count(id) FROM forum_answers WHERE topics_id='".$NewTopics['id']."'");
  $Posts = mysql_fetch_row($mod2);

  echo'
  <tr style="border:0px;">
    <td width="80" style="border:0px;">'.date('d.m, H:i',$NewTopics['time']).'</td>
    <td style="border:0px;">
	  <a href="?site=forum&showtopic='.$NewTopics['id'].'" style="display:block;">'.$NewTopics['name'].' ('.$Posts[0].')</a>
	</td>
  </tr>';
}
echo'</table><br />';
}

 /*
  * Ausgabe des Teams
  */

foreach($GLOBALS['conf']['_team'] as $gTeam => $key)
{
	echo "<div align='center'><strong>$gTeam</strong>";
	foreach($key as $k)
	{
		$thisTeamMember = mysql_fetch_assoc(mysql_query("SELECT id,name,lonline FROM user WHERE name='".$k."';"));

		if($thisTeamMember['lonline'] > time() - $GLOBALS['conf']['vars']['min_online_time'])
		{
			$color = $GLOBALS['conf']['vars']['team_off_color'];
		}
		else
		{
			$color = $GLOBALS['conf']['vars']['alert_color'];
		}

		echo '<a href="?site=nachrichten&titel=Spielanfrage&empfaenger='.$k.'" style="color:'.$color.';">'.$k.'</a> ';
	}
	echo "</div>";
}


if($user['status'] != user)
{
	echo'<br><center><a href="?site=admin_seite">Adminmen&uuml;</a></center>';
}


?>
