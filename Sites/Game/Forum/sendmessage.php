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
if(isset($_GET['smforum']))
{
$Forum = $_GET['smforum'];

if($Forum == FORUM_NEWS_ID && !isMod() || $Forum == FORUM_ARCHIVE_ID && !isMod()) exit;

$ForumSql = "SELECT * FROM forum_forums WHERE id='$Forum' LIMIT 1";
$ForumQuery = mysql_query($ForumSql);
$Forum = mysql_fetch_assoc($ForumQuery);

echo'
<div id="forum" style="font-size:10px;border:0px;margin-bottom:5px;margin-left:60px;">
  <a href="index.php?site=forum" target="_self">Gladiatorz Forum</a> /
  <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">'.$Forum['name'].'</a>
</div>';
}
elseif(isset($_GET['smtopic']))
{
$Topic = $_GET['smtopic'];

$TopicSql = "SELECT * FROM forum_topics WHERE id='$Topic' LIMIT 1";
$TopicQuery = mysql_query($TopicSql);
$Topic = mysql_fetch_assoc($TopicQuery);

if($Topic['close'] == 1) exit;

$Forum = $Topic['forums_id'];

$ForumSql = "SELECT * FROM forum_forums WHERE id='$Forum' LIMIT 1";
$ForumQuery = mysql_query($ForumSql);
$Forum = mysql_fetch_assoc($ForumQuery);

echo'
<div id="forum" style="font-size:10px;border:0px;margin-bottom:5px;font-weight:bold;margin-left:60px;">
  <a href="index.php?site=forum" target="_self">Gladiatorz Forum</a> /
  <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">'.$Forum['name'].'</a> /
  <a href="index.php?site=forum&showtopic='.$Topic['id'].'" target="_self">'.$Topic['name'].'</a>
</div>';
}

echo'<center><div class="forums">';

if(isset($_POST['text']) && isset($_GET['smtopic'])) // neuer post
{
  $Text = nl2br(strip_tags(trim($_POST['text'])));

  if(strlen($Text) >= 1)
  {
    $Sql = "INSERT INTO forum_answers (topics_id,user,time,message) VALUES ('$_GET[smtopic]','$user[id]',UNIX_TIMESTAMP(),'$Text')";
	$Query = mysql_query($Sql);

	if($Query != false)
	{
	  $KnowSql = "SELECT * FROM forum_read WHERE user='$user[id]' AND topic='$Topic[id]' LIMIT 1";
	  $KnowQuery = mysql_query($KnowSql);
	  $Know = mysql_fetch_assoc($KnowQuery);
	  $t = time()+10;
	  if($Know == false) // new
	  {
	    mysql_query("INSERT INTO forum_read (user,time,topic) VALUES ('$user[id]','$t','$Topic[id]')");
	  }
	  else // up...
	  {
	    mysql_query("UPDATE forum_read Set time='$t' WHERE id='$Know[id]'");
	  }

	  $result = mysql_query("SELECT id FROM forum_answers WHERE topics_id='$Topic[id]'");
      $menge2 = mysql_num_rows($result);
      $antw_seiten = ceil($menge2/10);

      echo'Beitrag hinzugef&uuml;gt <a href="index.php?site=forum&showtopic='.$_GET['smtopic'].'&seite='.$antw_seiten.'#unten" target="_self">zum Beitrag</a> oder <a href="index.php?site=forum&showforum='.$Forum['id'].'" target="_self">zum Forum</a>';
	}
	else
	{
	  echo'Datenbankfehler!!!';
	}
  }
  else
  {
    echo'Text zu kurz';
  }
}
elseif(isset($_POST['text']) && isset($_GET['smforum']) && isset($_POST['headline'])) // neues topic
{
  $Text = nl2br(strip_tags(trim($_POST['text'])));
  $Headline = strip_tags(trim($_POST['headline']));

  if(strlen($Text) >= 1)
  {
    $time = time();

    $Sql = "INSERT INTO forum_topics (forums_id,creator,time,name,hits,important) VALUES ('$_GET[smforum]','$user[id]','".$time."','$Headline','0','n')";
	$Query = mysql_query($Sql);

	$Sql = "SELECT * FROM forum_topics WHERE forums_id='$_GET[smforum]' AND time='".$time."' AND creator='$user[id]' AND name='$Headline' ORDER BY id DESC LIMIT 1";
	$Query = mysql_query($Sql);
	$topic = mysql_fetch_assoc($Query);

	$Sql = "INSERT INTO forum_answers (topics_id,user,time,message) VALUES ('$topic[id]','$user[id]',UNIX_TIMESTAMP(),'$Text')";
	$Query = mysql_query($Sql);

	if($Query != false)
	{
      echo'<br /><br /><br />Beitrag hinzugef&uuml;gt <a href="index.php?site=forum&showtopic='.$topic['id'].'#unten" target="_self">weiter</a>';
	}
	else
	{
	  echo'Datenbankfehler!!!';
	}
  }
  else
  {
    echo'Text zu kurz';
  }
}
else
{
  if(isset($_GET['smforum']))
  {
    $FormVar = 'smforum='.$_GET['smforum'];
  }
  else
  {
    $FormVar = 'smtopic='.$_GET['smtopic'];
  }

  echo'<form method="post" name="sendmess" action="index.php?site=forum&'.$FormVar.'"><div style="text-align:left;margin-left:60px;padding-bottom:5px;">';

  if(isset($_GET['smforum']))
  {
    echo'<br />&Uuml;berschrift:<br /><input name="headline" type="text" style="width:400px;font-family:Verdana;font-size:11px;border:1px solid darkgray;padding:2px;" /><br />';
  }

  echo'<br />Nachricht:<br /><textarea name="text" style="width:400px;height:180px;border:1px solid darkgray;padding:2px;font-family:Verdana;font-size:11px;"></textarea><br />
  <input type="submit" value="Antworten" />';//<input type="submit" value="Vorschau" />

  echo'<br /><br /><a href="javascript:PopUp(\'bbcode/index.php\',\'640\',\'480\',0)"><strong>Du willst BB-Code benutzen?</strong></a><br /><br /><span style="font-size:11px;"><!--<strong>Achtung!</strong> Links m�ssen so angegeben werden: <em>[url=http://www.link.com]Linktext[/url]</em></span>--></div></form>';
}

echo'</div></center>';

?>
