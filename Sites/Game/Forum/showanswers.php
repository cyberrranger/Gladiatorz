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

$eintraege_pro_seite = 10;
$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite;

$Topic = $_GET['showtopic'];

$TopicSql = "SELECT * FROM forum_topics WHERE id='$Topic' LIMIT 1";
$TopicQuery = mysql_query($TopicSql);
$Topic = mysql_fetch_assoc($TopicQuery);

if(!$Topic)exit;

if($_GET['do']=='archive' && isMod())
{
  $Update = mysql_query("UPDATE ".TAB_TOPICS." SET forums_id='".FORUM_ARCHIVE_ID."' WHERE id='".$Topic['id']."' LIMIT 1");

  if($Update != false)
  {
    echo'<center>Topic erfolgreich archiviert! <a href="?site=forum&showforum='.$Topic['forums_id'].'">weiter</a></center>';
  }
  else
  {
    echo'<center>Datenbankfehler! Bitte dem admin melden. <a href="?site=forum&showforum='.$Topic['forums_id'].'">weiter</a></center></center>';
  }
}
elseif($_GET['do']=='important' && isMod())
{
  mysql_query("UPDATE ".TAB_TOPICS." SET important='j' WHERE id='".$Topic['id']."' LIMIT 1");

  echo'<center>Topic erfolgreich wichtig gemacht! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
}
elseif($_GET['do']=='unimportant' && isMod())
{
  mysql_query("UPDATE ".TAB_TOPICS." SET important='n' WHERE id='".$Topic['id']."' LIMIT 1");

  echo'<center>Topic erfolgreich unwichtig gemacht! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
}
elseif($_GET['do']=='close' && isMod())
{
  mysql_query("UPDATE ".TAB_TOPICS." SET close='1' WHERE id='".$Topic['id']."' LIMIT 1");

  echo'<center>Topic erfolgreich geclosed! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
}
elseif($_GET['do']=='open' && isMod())
{
  mysql_query("UPDATE ".TAB_TOPICS." SET close='0' WHERE id='".$Topic['id']."' LIMIT 1");

  echo'<center>Topic erfolgreich ge&ouml;ffnet! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
}
elseif($_GET['do']=='del' && isMod())
{
  mysql_query("DELETE FROM ".TAB_TOPICS." WHERE id='".$Topic['id']."' LIMIT 1");
  mysql_query("DELETE FROM ".TAB_ANSWERS." WHERE topics_id='".$Topic['id']."'");

  echo'<center>Topic erfolgreich gel&ouml;scht! <a href="?site=forum&showforum='.$Topic['forums_id'].'">zum Forum</a></center>';
}
elseif(!empty($_GET['del_a']) && is_numeric($_GET['del_a']))
{
  $Query = mysql_query("SELECT * FROM ".TAB_ANSWERS." WHERE id='".$_GET['del_a']."' LIMIT 1");
  $Del = mysql_fetch_assoc($Query);

  if(isMod() || $User['id'] == $Del['user'])
  {
    mysql_query("DELETE FROM ".TAB_ANSWERS." WHERE id='".$_GET['del_a']."' LIMIT 1");

	$CSql = "SELECT count(id) FROM forum_answers WHERE topics_id='".$Del['topics_id']."'";
    $CQ = mysql_query($CSql);
    $TMPPosts = mysql_fetch_row($CQ);

    $TMPPosts = $TMPPosts[0];

	if($TMPPosts <= 0)
	{
	  mysql_query("DELETE FROM forum_topics WHERE id='".$Del['topics_id']."' LIMIT 1");
	  $DELTOP = true;
	}

	if($DELTOP == true)
	{
	  echo'<center>Topic erfolgreich gel&ouml;scht! <a href="?site=forum&showforum='.$Topic['forums_id'].'">zum Forum</a></center>';
	}
	else
	{
	  echo'<center>Beitrag erfolgreich gel&ouml;scht! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
	}
  }
}
elseif(!empty($_GET['edit_a']) && is_numeric($_GET['edit_a']))
{
  $Query = mysql_query("SELECT * FROM ".TAB_ANSWERS." WHERE id='".$_GET['edit_a']."' LIMIT 1");
  $Edit = mysql_fetch_assoc($Query);

  if(isMod() || $User['id'] == $Edit['user'])
  {
    if(!empty($_POST['message']))
	{
	  $Message = strip_tags(trim($_POST['message']));
	}

    if(!empty($Message))
	{
	  mysql_query("UPDATE ".TAB_ANSWERS." SET message='".nl2br($Message)."' WHERE id='".$_GET['edit_a']."' LIMIT 1");

	  echo'<center>Beitrag erfolgreich ge&auml;ndert! <a href="?site=forum&showtopic='.$Topic['id'].'">zur&uuml;ck</a></center>';
	}
	else
	{
      echo'
	  <center>
	  <form name="EditAnswer" method="post" action=""?site=forum&showtopic='.$Topic['id'].'&edit_a='.$Answers['id'].'"">
	    <textarea style="width:80%;height:180px;" name="message">'.br2nl($Edit['message']).'</textarea><br />
	    <input type="submit" value="Beitrag editieren" />
	  </form>
	  </center>';
	}
  }
}
else
{

$Topic['hits']++;

$UpTopic = mysql_query("UPDATE forum_topics Set hits='$Topic[hits]' WHERE id='$Topic[id]' LIMIT 1");

$KnowSql = "SELECT * FROM forum_read WHERE user='$user[id]' AND topic='$Topic[id]' LIMIT 1";
$KnowQuery = mysql_query($KnowSql);
$Know = mysql_fetch_assoc($KnowQuery);
$t = time();
if($Know == false) // new
{
  mysql_query("INSERT INTO forum_read (user,time,topic) VALUES ('$user[id]','$t','$Topic[id]')");
}
else // up...
{
  mysql_query("UPDATE forum_read Set time='$t' WHERE id='$Know[id]'");
}

$Forum = $Topic['forums_id'];

$ForumSql = "SELECT * FROM forum_forums WHERE id='$Forum' LIMIT 1";
$ForumQuery = mysql_query($ForumSql);
$Forum = mysql_fetch_assoc($ForumQuery);

if($Forum['type'] == 'allyin' && $schule['name'] != $Forum['name'] && !isMod())exit;

echo'
<div id="forum" style="border:0px;margin-bottom:5px;">
  <a href="index.php?site=forum" target="_self" name="oben">Gladiatorz Forum</a> |
  <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">'.$Forum['name'].'</a> |
  '.$Topic['name'].'
</div>';

//<a href="index.php?site=forum&showtopic='.$Topic['id'].'#unten" target="_self">'.$Topic['name'].'</a>

echo'
<table id="forum" width="100%" border="1" cellspacing="0" cellpadding="0">
<thead><tr>
  <th class="headline"><strong>Autor</strong></th>
  <th class="headline"><strong>Beitrag</strong></th>
</tr></thead>';

$Count = 0;

$Sql = "SELECT * FROM forum_answers WHERE topics_id='$Topic[id]' ORDER BY id ASC LIMIT $start, $eintraege_pro_seite";
$Query = mysql_query($Sql);
while($Answers = mysql_fetch_assoc($Query))
{
  $Count++;

  $SqlUser = "SELECT * FROM user WHERE id='$Answers[user]' LIMIT 1";
  $QueryUser = mysql_query($SqlUser);
  $Author = mysql_fetch_assoc($QueryUser);

  $Title = $Author['title'];

  if($Author['name'] == 'admin')
  {
    $Title = '*Admin*';
  }

  if($Author['name'] == 'Janun' || $Author['name'] == 'Lloyd')
  {
    $Title = '*Mod*';
  }

  if($Author == false)
  {
    $Author = array();
	$Author['name'] = '*gel&ouml;scht*';
  }

  $Rang = getRangName($Author['level']);//user_rang($Author['ppl_kills_win'],1);

  $PostsSql = "SELECT count(id) FROM forum_answers WHERE user='$Author[id]'";
  $PostsQuery = mysql_query($PostsSql);
  $Posts = mysql_fetch_row($PostsQuery);

  if(date('d.m.Y',time()) == date('d.m.Y',$Answers['time']))
  {
    $Date = '<strong>Heute</strong>'.date(', H:i',$Answers['time']);
  }
  else
  {
    $Date = date('d.m.Y, H:i',$Answers['time']);
  }

  $SchoolSql = "SELECT id,name,kuerzel FROM ally_schule WHERE id='$Author[schule]' LIMIT 1";
  $SchoolQuery = mysql_query($SchoolSql);
  $School = mysql_fetch_assoc($SchoolQuery);

  if($School == true)
  {
    $SString = '| Mitglied der <a style="font-size:11px;" href="index.php?site=schulen&sub='.$School['id'].'info" target="_self">'.$School['kuerzel'].'</a> ';
  }
  else
  {
    $SString = '';
  }

  echo'
  <tr valign="top" height="120">
    <td class="forums" width="120">
	  <b>'.$Author['name'].'</b><br />';

	  if($Author['name'] != '*gel&ouml;scht*')
	  {
	  echo'<font style="font-size:11px;">'.$Posts[0].' Posts</font>
	  <div style="font-size:10px;"><p>Titel:<br /><font style="font-weight:100;">'.$Title.'</font></p>
	  <br />Rang:<br /><font style="font-weight:100;">'.$Rang.'<br />'.$Author['ppl_kills_win'].' Kills</font></div>';
	  }
	echo'</td>
    <td class="forums" style="font-weight:100;text-align:left;font-size:11px;" valign="top">'.$bbcode->parse($Answers['message']).'</td>
  </tr>
  <tr height="18"><td class="forums" style="font-size:8px;" align="center">'.$Date.'</td>
  <td class="forums" style="font-size:11px;">';
  if($Author['name'] != '*gel&ouml;scht*')
	  {
  echo'<a style="font-size:11px;" href="index.php?site=nachrichten&empfaenger='.$Author['name'].'" target="_self">IGM senden</a> '.$SString.'| <a style="font-size:11px;" href="index.php?site=userinfo&info='.$Author['id'].'" target="_self">Userinfo</a>';
  }

  if(isMod() || $Author['id'] == $User['id'])
  {
    echo' <span style="font-size:10px;">(<a href="?site=forum&showtopic='.$Topic['id'].'&edit_a='.$Answers['id'].'" style="font-size:10px;">&Auml;ndern</a> | <a href="?site=forum&showtopic='.$Topic['id'].'&del_a='.$Answers['id'].'" style="font-size:10px;">L&ouml;schen</a>)</span>';
  }

  echo'</td></tr>';
}

if($Topic['close'] == 1)
{
  echo'<tr><td colspan="2"><h2>Topic closed!</h2></td></tr>';
}

$result = mysql_query("SELECT id FROM forum_answers WHERE topics_id='$Topic[id]'");
$menge = mysql_num_rows($result);

$wieviel_seiten = ceil($menge / $eintraege_pro_seite);

echo'</table><div class="forums" style="margin-top:5px;padding-bottom:5px;">
  <a href="index.php?site=forum" target="_self" name="oben">Gladiatorz Forum</a> |
  <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">'.$Forum['name'].'</a> | ';

if($Topic['close'] == 0)
{
  echo'<a name="unten" href="index.php?site=forum&smtopic='.$Topic['id'].'" target="_self">Antworten</a> | ';
  }
  else
  {
    echo'Beitrag geclosed! | ';
  }

  echo'<a href="#oben" target="_self">Nach oben</a> | Seiten: '.displayPages($seite,$wieviel_seiten,$url="index.php?site=forum&showtopic=".$Topic['id'],$anzahl=4,$get_name="seite");

  if(isMod())
  {
    echo'<br /><br /><span style="font-size:9px;font-weight:bold;">Administrative Optionen:<ul style="margin-left:16px;"><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=archive" target="_self" style="font-size:9px;">Archivieren</a></li><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=close" style="font-size:9px;">Closen</a></li><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=open" style="font-size:9px;">&Ouml;ffnen</a></li><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=del" style="font-size:9px;">L&ouml;schen</a></li><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=important" style="font-size:9px;">wichtig machen</a></li><li>
    <a href="?site=forum&showtopic='.$Topic['id'].'&do=unimportant" style="font-size:9px;">unwichtig machen</a></li></ul></span>';
  }

  echo'</div>';
}
?>
