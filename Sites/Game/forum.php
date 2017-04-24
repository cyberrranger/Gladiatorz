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
<center style="margin-bottom:5px;">
<a href="index.php?site=forum" target="_self">Forum</a> |
<a href="index.php?site=topicsuche" target="_self">Topicsuche</a> | 
<a href="index.php?site=inhaltsuche" target="_self">Inhaltsuche</a> |
</center>';

require('System/ForumFunctions.php');

if(!isset($_GET['showforum']) && !isset($_GET['showtopic']) && !isset($_GET['smforum']) && !isset($_GET['smtopic']))
{
  require('Forum/showforums.php');
}
elseif(isset($_GET['showforum']) && !empty($_GET['showforum']))
{
  require('Forum/showtopics.php');
}
elseif(isset($_GET['smforum']) || isset($_GET['smtopic']))
{
  require('Forum/sendmessage.php');
}
else
{
  require('Forum/showanswers.php');
}


if ($_REQUEST['clear'] && isMod())
{
	$Query = mysql_query("SELECT * FROM forum_forums");

	$array = array();
	while($forum = mysql_fetch_assoc($Query))
	{
		$array[] = $forum['id'];
	}
	
	$Query2 = mysql_query("SELECT * FROM forum_topics");
	
	$count = 0;
	while($forum2 = mysql_fetch_assoc($Query2))
	{
		if (!in_array($forum2['forums_id'], $array))
		{
			$count++;
			echo $forum2['id']."<br />";
			#mysql_query("DELETE FROM ".TAB_TOPICS." WHERE id='".$forum2['id']."' LIMIT 1");
  			#mysql_query("DELETE FROM ".TAB_ANSWERS." WHERE topics_id='".$forum2['id']."'");
		}
	}
	
	echo $count." Topics entfernt";
	
	
}

$user['lforum'] = time();

$UpSql = "UPDATE user Set lforum='$user[lforum]' WHERE id='$user[id]' LIMIT 1";

?>
