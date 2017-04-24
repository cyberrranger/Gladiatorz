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

$seite = $_GET["seite"];

if(!isset($seite))
{
  $seite = 1;
}

$eintraege_pro_seite = 20;
$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite;

$Forum = $_GET['showforum'];

$ForumSql = "SELECT * FROM forum_forums WHERE id='$Forum' LIMIT 1";
$ForumQuery = mysql_query($ForumSql);
$Forum = mysql_fetch_assoc($ForumQuery);

if($Forum['type'] == 'allyin' && $schule['name'] != $Forum['name'] && !isMod())exit;

echo'
<div id="forum" style="font-size:12px;border:0px;margin-bottom:5px;">
  <a href="index.php?site=forum" target="_self">Gladiatorz Forum</a> |
  <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">'.$Forum['name'].'</a>
</div>';

echo'
<table id="forum" width="100%" border="1" cellspacing="0" cellpadding="0">
<thead><tr>
  <th class="headline" colspan="2"><strong>Thema</strong></th>
  <th class="headline" width="40"><strong>Hits</strong></th>
  <th class="headline" width="120"><strong>letzter Beitrag</strong></th>
</tr></thead>';

$Important = array();
$NewTopics = array();
$OldTopics = array();

//$Query = mysql_query("SELECT * FROM ".TAB_TOPICS." WHERE forums_id='$Forum[id]' ORDER BY important ASC, id DESC");
//while($Topics = mysql_fetch_assoc($Query))
$Query = mysql_query("SELECT DISTINCT a.id,a.forums_id,a.name,b.topics_id,a.important FROM ".TAB_TOPICS." AS a,".TAB_ANSWERS." AS b WHERE a.forums_id='".$Forum['id']."' AND b.topics_id=a.id ORDER BY b.time DESC LIMIT $start, $eintraege_pro_seite");
while($Topics = mysql_fetch_array($Query))
{
  if($Topics[4] == 'j')
  {
	continue;
  }

  $Query2 = mysql_query("SELECT time FROM ".TAB_ANSWERS." WHERE topics_id='".$Topics[0]."' ORDER BY id DESC LIMIT 1");
  $Answer = mysql_fetch_assoc($Query2);

  $Query3 = mysql_query("SELECT * FROM ".TAB_READ." WHERE topic='".$Topics[0]."' ORDER BY id DESC LIMIT 1");
  $Read = mysql_fetch_assoc($Query3);

  if($Read['time'] < $Answer['time'])
  {
    $NewTopics[] = $Topics[0];
  }
  else
  {
    $OldTopics[] = $Topics[0];
  }
}

$Query = mysql_query("SELECT * FROM ".TAB_TOPICS." WHERE forums_id='".$Forum['id']."' AND important='j'");
while($Imp = mysql_fetch_assoc($Query))
{
  $Important[] = $Imp['id'];
}

$AllTopics = array_merge($Important,$NewTopics,$OldTopics);

foreach($AllTopics as $Topic)
{
  $Query = mysql_query("SELECT * FROM ".TAB_TOPICS." WHERE id='".$Topic."' LIMIT 1");
  $Topics = mysql_fetch_assoc($Query);

  $Img = Read($Topics['id']);

  if(strlen($Topics['name']) > 36)
  {
    $Topics['name'] = substr($Topics['name'],0,36).'...';
  }

  if($Topics['important'] == 'j')
  {
    $Important = '<font color="#e36363"><strong>Wichtig</strong></font> ';
  }
  else
  {
    $Important = null;
  }

  $CSql = "SELECT count(id) FROM forum_answers WHERE topics_id='$Topics[id]'";
  $CQ = mysql_query($CSql);
  $Posts = mysql_fetch_row($CQ);

  $Posts = $Posts[0];

  $Query2 = mysql_query("SELECT id,user FROM ".TAB_ANSWERS." WHERE topics_id='".$Topics['id']."' ORDER BY id DESC LIMIT 1");
  $Answer = mysql_fetch_assoc($Query2);

  $SqlUser = "SELECT * FROM user WHERE id='$Answer[user]' LIMIT 1";
  $QueryUser = mysql_query($SqlUser);
  $Author = mysql_fetch_assoc($QueryUser);

  $result = mysql_query("SELECT id FROM forum_answers WHERE topics_id='$Topics[id]'");
  $menge2 = mysql_num_rows($result);
  $antw_seiten = ceil($menge2/10);

  if($Topics['close'] == 1)
  {
    $Close = '[closed]';
  }
  else
  {
    $Close = '';
  }

  echo'
  <tr>
    <td class="forums" width="25" align="center"><img src="Images/Forum/'.$Img.'thread.gif" border="0" /></td>
    <td class="forums" style="text-align:left;">&nbsp;'.$Important.'<a href="index.php?site=forum&showtopic='.$Topics['id'].'&seite='.$antw_seiten.'#unten" target="_self">'.$Topics['name'].' ('.$Posts.') '.$Close.'</a></td>
	<td class="forums" align="center" style="font-weight:100;">'.$Topics['hits'].'</td>
	<td class="forums" style="font-weight:100;font-size:9px;text-align:right;">'.getLastPostByTopic($Topics['id']).'</td>
  </tr>';//<center>von <strong>'.$Author['name'].'</strong><br />'.date('d.m.Y, H:i',$Topics['time']).'</center>
}

  //<br /><font style="font-size:9px;font-weight:100;">von <a href="index.php?site=nachrichten&empfaenger='.$Author['name'].'" target="_self" style="color:blue;text-decoration:none;">'.$Author['name'].'</a> am '.date('d.m.Y, H:i',$Topics['time']).'</font>

echo'</table><div class="forums" style="margin-top:5px;padding-bottom:5px;">';

if($Forum['id'] != FORUM_ARCHIVE_ID && $Forum['id'] != FORUM_NEWS_ID || isMod($User['id']))
{
  echo'<a href="index.php?site=forum&smforum='.$Forum['id'].'" target="_self">Neuer Beitrag</a> | ';
}

$result = mysql_query("SELECT id FROM ".TAB_TOPICS." WHERE forums_id='".$Forum['id']."'");
$menge = mysql_num_rows($result);

$wieviel_seiten = ceil($menge / $eintraege_pro_seite);

echo'Seiten: '.displayPages($seite,$wieviel_seiten,$url="index.php?site=forum&showforum=".$Forum['id'],$anzahl=4,$get_name="seite").'</div>';

function Read($Topic)
{
  global $user;

  $TSql = "SELECT id FROM forum_topics WHERE id='$Topic' LIMIT 1";
  $TQuery = mysql_query($TSql);
  $T = mysql_fetch_assoc($TQuery);

  $RSql = "SELECT time FROM forum_read WHERE topic='$T[id]' AND user='$user[id]' LIMIT 1";
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

  $Img = 'old';
  return $Img;
}

?>
