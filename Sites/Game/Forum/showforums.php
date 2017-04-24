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

echo'
<div id="forum" style="font-size:10px;border:0px;margin-bottom:5px;">
  <a href="index.php?site=forum" target="_self">Gladiatorz Forum</a>
</div>';

echo'
<table id="forum" width="100%" border="1" cellspacing="0" cellpadding="0">
<thead><tr>
  <th class="headline" width="260" colspan="2"><strong>Foren</strong></th>
  <th class="headline" width="40"><strong>Topics</strong></th>
  <th class="headline" width="40"><strong>Posts</strong></th>
  <th class="headline" width="160"><strong>letzter Beitrag</strong></th>
</tr></thead>
<tr><td class="group" colspan="5" style="text-align:left;"><strong>Gladiatorz - das Spiel</strong></td></tr>';

$Sql = "SELECT * FROM ".TABFORUMS." WHERE type='normal' ORDER BY id";
$Query = mysql_query($Sql);
while($Forums = mysql_fetch_assoc($Query))
{
  $Img = Read($Forums['id']);

  echo'
  <tr>
    <td class="forums" width="25" align="center"><img src="Images/Forum/'.$Img.'thread.gif" border="0" /></td>
    <td class="forums" style="text-align:left;">&nbsp;<a style="font-weight:bold;" href="index.php?site=forum&showforum='.$Forums['id'].'" target="_self">'.$Forums['name'].'</a></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getTopicsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getPostsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;font-size:9px;text-align:right;">'.getLastPostByForum($Forums['id']).'</td>
  </tr>';
}

echo'<tr><td height="24" class="group" colspan="5" style="text-align:left;"><strong>Gladiatorz - die Schulen</strong></td></tr>';

$Sql = "SELECT * FROM ".TABFORUMS." WHERE type='allyout' ORDER BY id";
$Query = mysql_query($Sql);
while($Forums = mysql_fetch_assoc($Query))
{
  $Img = Read($Forums['id']);

   echo'
  <tr>
    <td class="forums" width="25" height="25" align="center" style="padding:0px;"><img src="Images/Forum/'.$Img.'thread.gif" border="0" style="margin:0px;" /></td>
    <td class="forums" style="text-align:left;">&nbsp;<a style="font-weight:bold;" href="index.php?site=forum&showforum='.$Forums['id'].'" target="_self">'.$Forums['name'].'</a></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getTopicsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getPostsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;font-size:9px;text-align:right;">'.getLastPostByForum($Forums['id']).'</td>
  </tr>';
}

if($user['schule'] != 0)
{
  echo'<tr><td height="24" class="group" colspan="5" style="text-align:left;"><strong>Gladiatorz - Pinnwand deiner Schule</strong></td></tr>';

  if(isMod() && $a==1)
  {
    $Sql = "SELECT * FROM ".TABFORUMS." WHERE type='allyin'";
  }
  else
  {
    $Sql = "SELECT * FROM ".TABFORUMS." WHERE type='allyin' AND name='$schule[name]' LIMIT 1";
  }
  
  $Query = mysql_query($Sql);
  while($Forums = mysql_fetch_assoc($Query))
  {
    $Img = Read($Forums['id']);
  
   echo'
  <tr>
    <td class="forums" width="25" align="center"><img src="Images/Forum/'.$Img.'thread.gif" border="0" /></td>
    <td class="forums" style="text-align:left;">&nbsp;<a style="font-weight:bold;" href="index.php?site=forum&showforum='.$Forums['id'].'" target="_self">'.$Forums['name'].'</a></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getTopicsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;"><center>'.getPostsByForum($Forums['id']).'</center></td>
	<td class="forums" width="15" style="font-weight:100;font-size:9px;text-align:right;">'.getLastPostByForum($Forums['id']).'</td>
  </tr>';
  }
}

echo'</table>';

function Read($Forum)
{
  global $user;

  $TSql = "SELECT id FROM forum_topics WHERE forums_id='$Forum' ORDER BY time DESC";
  $TQuery = mysql_query($TSql);
  while($T = mysql_fetch_assoc($TQuery))
  {
    $RSql = "SELECT time FROM forum_read WHERE topic='$T[id]' AND user='$user[id]'";
    $RQuery = mysql_query($RSql);
    $R = mysql_fetch_assoc($RQuery);
	
	if($R == false) // never read
	{
	  $Img = 'new';
	  return $Img;
	}
	else
	{
	  $ASql = "SELECT time FROM forum_answers WHERE topics_id='$T[id]' ORDER BY id DESC LIMIT 1";
      $AQuery = mysql_query($ASql);
      $A = mysql_fetch_assoc($AQuery);
	  
	  if($A['time'] > $R['time']) // read before but theres new stuff
	  {
	    $Img = 'new';
		return $Img;
	  }
	  else // user is up to time :D
	  {
	    $Img = 'old';
	  }
	}
  }
  
  $Img = 'old';
  return $Img;
}

$Query = mysql_query("SELECT count(id) FROM ".TAB_TOPICS);
$Topics = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM ".TAB_ANSWERS);
$Answers = mysql_fetch_row($Query);

$Query = mysql_query("SELECT sum(hits) FROM ".TAB_TOPICS);
$Hits = mysql_fetch_array($Query);

echo'<br /><center><em style="font-size:11px;">Insgesamt: '.$Topics[0].' Topics und '.$Answers[0].' Posts und '.$Hits[0].' Hits</em></center><br />';

?>
