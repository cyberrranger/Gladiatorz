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
echo'<center><a href="index.php?site=quest" target="_self">Quest</a> |
<a href="index.php?site=quests" target="_self">Spezial Quests</a> |
<a href="index.php?site=tool" target="_self">Werkstatt</a>
</center><br />';

echo "Auf dieser Seite kannst du vorhanden Herausforderungen annehmen oder ablehnen. <br />Wenn du jemanden Herausfordern möchtest musst du beim betroffen Spieler auf dessen Profil.<br /><br />";

if($_REQUEST['start'])
{
	$Query = @mysql_query("SELECT * FROM open_fights WHERE id='".$_REQUEST['start']."' AND user2='".$User['id']."' AND status=0 LIMIT 1");
  	$this_chal = @mysql_fetch_assoc($Query);
  	if($this_chal)
  	{
  		$enemy = get_user_things($this_chal['user1']);
  		$enemy2 = get_user_things($this_chal['user2']);

  		include"fight2.php";
		$both = creat_fight($User['id'], $this_chal['user1']);
		$fight = make_a_new_fight($both);

		$winner = $fight['winner'];
		$looser = $fight['looser'];
		$fight_msg = $fight['msg'];
		$kampfbericht = $fight['kampfbericht'];

		echo $fight_msg;

		$turnier = mysql_query("INSERT INTO open_fights_report (`report`, `winner`, `loser`) VALUES ('".$kampfbericht."','".$winner."','".$looser."')");
		$report_id = mysql_insert_id();

		if($winner == $User['id']) // User hat gewonnen
		{
			$Update1 = mysql_query("UPDATE open_fights SET status=3 , report='".$report_id."' , winner='".$winner."' WHERE id='".$_REQUEST['start']."' LIMIT 1 ");
			$msg= "Niederlage<br />
  			Der Spieler $enemy2[name] hat deine Herausforderung angenommen. Du hast diesen Kampf verloren.
			<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=challenge&id=$report_id]hier[/url]";

			echo "<br /><br /><center>Diesen Kampf hast du gewonnen.</center><br /><br />";
		}else
		{
			$Update1 = mysql_query("UPDATE open_fights SET status=3 , report='".$report_id."' , winner='".$winner."' WHERE id='".$_REQUEST['start']."' LIMIT 1 ");
			$msg= "Glückwunsch<br />
  			Der Spieler $enemy2[name] hat deine Herausforderung angenommen. Du hast diesen Kampf gewonnen.
			<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&what=challenge&id=$report_id]hier[/url]";

			echo "<br /><br /><center>Diesen Kampf hast du verloren.</center><br /><br />";
		}

  		sendIGM('Deine Herausforderung wurde angenommen!',$msg,$enemy['name'],'Challenge');
  	}
}

if($_REQUEST['stop'])
{
	$Query = @mysql_query("SELECT * FROM open_fights WHERE id='".$_REQUEST['stop']."' AND user2='".$User['id']."' AND status=0 LIMIT 1");
  	$this_chal = @mysql_fetch_assoc($Query);
  	if($this_chal)
  	{
  		$Update1 = mysql_query("UPDATE open_fights SET status=4 WHERE id='".$_REQUEST['stop']."' LIMIT 1 ");

  		$enemy = get_user_things($this_chal['user1']);
  		$enemy2 = get_user_things($this_chal['user2']);

  		$msg= "Der Spieler $enemy2[name] hat deine Herausforderung abgelehnt.";

  		sendIGM('Deine Herausforderung wurde abgelehnt!',$msg,$enemy['name'],'Challenge');
  	}
}

//10 tage
$time = time() - 10 * 24 * 60 * 60;

//Prüfen auf aktuelle Herausforderungen
//Status 0 => neu
//Status 3 => angenommen
//Status 4 =>abgelehnt
$Query = @mysql_query("SELECT * FROM open_fights WHERE (user1='".$User['id']."' OR user2='".$User['id']."') AND time>'".$time."' ORDER BY time DESC");

echo '<table border=1>';

echo '<thead><th>Herausforderer</th><th>Verteidiger</th><th>aktiv bis:</th><th>Aktion</th></thead>';

while($chal_today = @mysql_fetch_assoc($Query))
{
	if($chal_today['user1'] == $user['id'])
	{
		$enemy1_name = $user['name'];
		$enemy1_id = $user['id'];
	}else
	{
		$enemy1_name = get_user_things($chal_today['user1'], 'name');
		$enemy1_id = $chal_today['user1'];
	}
	if($chal_today['user2'] == $user['id'])
	{
		$enemy2_name = $user['name'];
		$enemy2_id = $user['id'];
	}else
	{
		$enemy2_name = get_user_things($chal_today['user2'], 'name');
		$enemy2_id = $chal_today['user2'];
	}


	if($chal_today['user1'] == $user['id'])
	{
		$action = 'warten auf Gegner';
	}elseif($chal_today['user2'] == $user['id'])
	{
		$action = '<a href="?site=challenge&start='.$chal_today['id'].'">annehmen</a> / <a href="?site=challenge&stop='.$chal_today['id'].'">ablehnen</a>';
	}
	if($chal_today['status'] == 4)
	{
		$action = 'wurde abgelehnt';
	}elseif($chal_today['status'] == 3)
	{
		$enemy_winnwe = get_user_things($chal_today['winner']);
		$action = 'Der Gewinner ist '.$enemy_winnwe['name'].'<a href="index.php?site=report&what=challenge&id='.$chal_today['report'].'"><img src="Images/icons/scroll.png" height="20" /></a>';
	}
	echo '<tr><td><a href="?site=userinfo&info='.$enemy1_id.'">'.$enemy1_name.'</a></td><td><a href="?site=userinfo&info='.$enemy2_id.'">'.$enemy2_name.'</a></td><td>'.date("d.m.Y H:i",$chal_today['time']).'</td><td>'.$action.'</td></tr>';
	flush();
}
echo '</table>';
?>
