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
function getTopicsByForum($ForumID)
{
  $Query = mysql_query("SELECT count(id) FROM ".TAB_TOPICS." WHERE forums_id='".$ForumID."'");
  $Row = mysql_fetch_row($Query);

  return $Row[0];
}

function getPostsByForum($ForumID)
{
  $Posts = 0;

  $Query = mysql_query("SELECT a.id,a.forums_id,b.id,b.topics_id FROM ".TAB_TOPICS." AS a,".TAB_ANSWERS." AS b WHERE a.forums_id='".$ForumID."' AND b.topics_id=a.id");

  while($Row = mysql_fetch_assoc($Query)) $Posts++;

  return $Posts;
}

function getLastPostByForum($ForumID)
{
  $Query = mysql_query("SELECT a.id,a.forums_id,b.id,b.topics_id,b.time FROM ".TAB_TOPICS." AS a,".TAB_ANSWERS." AS b WHERE a.forums_id='".$ForumID."' AND b.topics_id=a.id ORDER BY b.time DESC LIMIT 1");

  $LastPost = mysql_fetch_array($Query);

  if($LastPost == true)
  {
  $TopicID = $LastPost[0];
  $PostID = $LastPost[2];

  //echo $TopicID.'|'.$PostID.'<br>';

  $Query = mysql_query("SELECT * FROM ".TAB_TOPICS." WHERE id='".$TopicID."' LIMIT 1");
  $Topic = mysql_fetch_assoc($Query);

  $Query = mysql_query("SELECT * FROM ".TAB_ANSWERS." WHERE id='".$PostID."' LIMIT 1");
  $Post = mysql_fetch_assoc($Query);

  $Query = mysql_query("SELECT id,name FROM user WHERE id='".$Post['user']."' LIMIT 1");
  $User = mysql_fetch_assoc($Query);

  if($User == false)
  {
    $User['name'] = '*R.I.P.*';
  }
  else
  {
    $User['name'] = '<a href="?site=userinfo&info='.$User['id'].'" style="font-size:11px;">'.$User['name'].'</a>';
  }

  if(strlen($Topic['name']) > 30) $Topic['name'] = substr($Topic['name'],0,27).'...';

  return '<strong>'.$Topic['name'].'</strong><br />'.Stamp2Date($Post['time']).'<br />'.'von '.$User['name'];
  }
  else
  {
    return '<p>keiner</p><br />';
  }
}

function getCreatorByTopic($Creator)
{
  $Query = mysql_query("SELECT id,name FROM user WHERE id='".$Creator."' LIMIT 1");
  $User = mysql_fetch_assoc($Query);

  return $User['name'];
}

function getAnswersByTopic($TopicID)
{
  $Query = mysql_query("SELECT count(id) FROM ".TAB_ANSWERS." WHERE topics_id='".$TopicID."'");
  $Row = mysql_fetch_row($Query);

  return $Row[0];
}

function getLastPostByTopic($TopicID)
{
  $Query = mysql_query("SELECT * FROM ".TAB_ANSWERS." WHERE topics_id='".$TopicID."' ORDER BY time DESC LIMIT 1");
  $Answer = mysql_fetch_assoc($Query);

  $Query = mysql_query("SELECT id,name FROM user WHERE id='".$Answer['user']."' LIMIT 1");
  $User = mysql_fetch_assoc($Query);

  if($User == false)
  {
    $User['name'] = '*R.I.P.*';
  }
  else
  {
    $User['name'] = '<a href="?site=userinfo&info='.$User['id'].'" style="font-size:9px;">'.$User['name'].'</a>';
  }

  return Stamp2Date($Answer['time']).'<br />'.'von '.$User['name'];
}

function Stamp2Date($Stamp)
{
  if(date('d_m_Y',$Stamp) == date('d_m_Y',(time()-86400))) // Gestern
  {
    $Date = 'Gestern - '.date('H:i', $Stamp);
  }
  elseif(date('d_m_Y',$Stamp) == date('d_m_Y',time())) // Heute
  {
    $Date = 'Heute - '.date('H:i', $Stamp);
  }
  else // Datum
  {
    $Date = date("d.m.Y - H:i", $Stamp);
  }

  return $Date;
}
?>
