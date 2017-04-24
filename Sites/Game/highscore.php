<br />
<center>
  <form name="FormHighscore" method="post" action="index.php?site=highscore">
    <select name="order" onchange="FormHighscore.submit()">
    <option value="1">Punkte</option>
	<option value="2">Prestige</option>
	<option value="3">Medaillen</option>
	<option value="4">Exp</option>
	<option value="5">Level</option>
	<option value="6">Gold</option>
	<option value="7">PowMod</option>
	<option value="8">Brutalität</option>
    <option value="9">Quests</option>
    <option value="10">Arenarang</option>
	<option value="36">Turnier</option>
	<option value="">----------</option>
	<option value="11">Stärke</option>
	<option value="12">Geschick</option>
	<option value="13">Kondition</option>
	<option value="14">Charisma</option>
	<option value="15">Intelligenz</option>
	<option value="16">Eigenschaften</option>
	<option value="50">Untereigenschaften</option>
	<option value="49">Spezial Moves</option>
	<option value="">----------</option>
	<option value="17">Waffenkunde</option>
	<option value="18">Ausweichen</option>
	<option value="19">Taktik</option>
	<option value="20">2-Waffenkampf</option>
	<option value="21">Heilkunde</option>
	<option value="46">Schildkunde</option>
	<option value="22">Fertigkeiten</option>
	<option value="">----------</option>
	<option value="23">Tierkämpfe (gew.)</option>
	<option value="24">Tierkämpfe (verl.)</option>
	<option value="25">Tierkämpfe (Punkte)</option>
	<option value="">----------</option>
	<option value="26">Arenakämpfe (gew.)</option>
	<option value="27">Arenakämpfe (verl.)</option>
	<option value="28">Arenakämpfe (Punkte)</option>
	<option value="">----------</option>
	<option value="29">Prestigekämpfe (gew.)</option>
	<option value="30">Prestigekämpfe (verl.)</option>
	<option value="31">Prestigekämpfe (Punkte)</option>
	<option value="">----------</option>
	<option value="32">Duelle (gew.)</option>
	<option value="33">Duelle (verl.)</option>
	<option value="34">Duelle (Punkte)</option>
	<option value="">----------</option>
	<option value="43">Rangkämpfe (gew.)</option>
	<option value="44">Rangkämpfe (verl.)</option>
	<option value="45">Rangkämpfe (Punkte)</option>
	<option value="">----------</option>
	<option value="48">Maximale deff</option>
	<option value="47">Maximale off</option>
	<option value="">----------</option>
	<option value="35">Player ID</option>
	<option value="37">Dabei seit...</option>
	<option value="38">zuletzt Online</option>
    </select>
	&nbsp;<input type="submit" value="Ordnen">
  </form>
</center><br />

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
//aktuelle minute beachten
$min = date('i');
if($min<=29)
{
	$tabell_name = 'highscore';
	$tabell_name_2 = 'highscore30';
}else
{
	$tabell_name = 'highscore30';
	$tabell_name_2 = 'highscore';
}

$Page = $_GET['page'];

if(!isset($Page))
{
  $Page = 1;
}

if(isset($_GET['sort']))
{
  $_POST['order'] = $_GET['sort'];
}

$Start = ($Page * $GLOBALS['conf']['vars']['highscore_size']) - $GLOBALS['conf']['vars']['highscore_size'];


if(!empty($_POST['order']) && is_numeric($_POST['order']) && $_POST['order'] >= 1 && $_POST['order'] <= 50)
{
  $Sort = $_POST['order'];

	if($Sort == 1)
	{
	  	$HeadName = 'Punkte';
	    $RowName = 'points';

	    $Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
	    LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 2)
	{
	    $HeadName = 'Prestige';
		$RowName = 'prestige';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

  if($Sort == 3)
  {
		$HeadName = 'Medaillen';
		$RowName = 'meds';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

  if($Sort == 4)
  {
    	$HeadName = 'Erfahrungspkt.';
		$RowName = 'exp';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

  if($Sort == 5)
  {
		$HeadName = 'Level';
		$RowName = 'level';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

  if($Sort == 6) // Gold
  {
		$HeadName = 'Gold';
		$RowName = 'gold';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

  if($Sort == 7)
  {
    	$HeadName = 'PowMod';
		$RowName = 'pm';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 8)
	{
    	$HeadName = 'Brutalität';
		$RowName = 'brutality';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

 	if($Sort == 9) // Gewonnen Quests
    {
    	$HeadName = 'Quests';
		$RowName = 'Quest';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

   if($Sort == 10) // Arenarang
   {
		$HeadName = 'Arenarang';
		$RowName = 'arenarang';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }

	if($Sort == 11)
	{
	    $HeadName = 'Stärke';
		$RowName = 'char_1';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 12)
	{
	    $HeadName = 'Geschick';
		$RowName = 'char_2';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 13)
	{
	    $HeadName = 'Kondition';
		$RowName = 'char_3';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 14)
	{
	    $HeadName = 'Charisma';
		$RowName = 'char_4';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 15)
	{
	    $HeadName = 'Intelligenz';
		$RowName = 'char_5';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 16)
	{
	    $HeadName = 'Eigenschaften';
		$RowName = 'char_all';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 50)
	{
	    $HeadName = 'Untereigenschaften';
		$RowName = 'char2_all';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 17)
	{
	    $HeadName = 'Waffenkunde';
		$RowName = 'trainer_1';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 18)
	{
	    $HeadName = 'Ausweichen';
		$RowName = 'trainer_2';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 19)
	{
	    $HeadName = 'Taktik';
		$RowName = 'trainer_3';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 20)
	{
	    $HeadName = '2-Waffenkampf';
		$RowName = 'trainer_4';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 21)
	{
	    $HeadName = 'Heilkunde';
		$RowName = 'trainer_5';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 46)
	{
	    $HeadName = 'Schildkunde';
		$RowName = 'trainer_6';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 22)
	{
	    $HeadName = 'Fertigkeiten';
		$RowName = 'trainer_all';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	//Tiere
	if($Sort == 23)
	{
	    $HeadName = 'Tierkämpfe (gew.)';
		$RowName = 'tier_win';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 24)
	{
	    $HeadName = 'Tierkämpfe (verl.)';
		$RowName = 'tier_lost';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 25)
	{
	    $HeadName = 'Tierkämpfe (Punkte)';
		$RowName = 'tier_points';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	//Arena
	if($Sort == 26)
	{
	    $HeadName = 'Arenakämpfe (gew.)';
		$RowName = 'arena_win';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 27)
	{
	    $HeadName = 'Arenakämpfe (verl.)';
		$RowName = 'arena_lost';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 28)
	{
	    $HeadName = 'Arenakämpfe (Punkte)';
		$RowName = 'arena_points';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	//Prestige
	if($Sort == 29)
	{
	    $HeadName = 'Prestigekämpfe (gew.)';
		$RowName = 'prestige_win';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 30)
	{
	    $HeadName = 'Prestigekämpfe (verl.)';
		$RowName = 'prestige_lost';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 31)
	{
	    $HeadName = 'Prestigekämpfe (Punkte)';
		$RowName = 'prestige_points';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	//Duelle
	if($Sort == 32)
	{
	    $HeadName = 'Duelle (gew.)';
		$RowName = 'duell_win';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 33)
	{
	    $HeadName = 'Duelle (verl.)';
		$RowName = 'duell_lost';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 34)
	{
	    $HeadName = 'Duelle (Punkte)';
		$RowName = 'duell_points';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	//Rangkämpfe
	if($Sort == 43)
	{
	    $HeadName = 'Rangkämpfe (gew.)';
		$RowName = 'rang_win';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 44)
	{
	    $HeadName = 'Rangkämpfe (verl.)';
		$RowName = 'rang_lost';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}

	if($Sort == 45)
	{
	    $HeadName = 'Rangkämpfe (Punkte)';
		$RowName = 'rang_points';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	//Player ID
	if($Sort == 35)
	{
	    $HeadName = 'Player ID';
		$RowName = 'user_id';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." ASC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 37)
	{
	    $HeadName = 'Dabei seit...';
		$RowName = 'regstamp';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." ASC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 38)
	{
	    $HeadName = 'zuletzt Online...';
		$RowName = 'lonline';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 49)
	{
	  $HeadName = 'Spezial Moves';
		$RowName = 'move_all';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	/*
	 * Todo
	 * 'Gold (Gewonnen)'
	 * 'Schnittgold'
	 * 'Gesetzt'
	 * 	<option value="40">Gold Gl�cksspiel</option>
	 *	<option value="41">Spiele Gl�ckspiel</option>
	 *  <option value="42">Schnitt Gl�ckspiel</option>
	 */

	if($Sort == 47)
	{
	  $HeadName = 'Maximal Off';
		$RowName = 'off';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 48)
	{
	  $HeadName = 'Maximal Deff';
		$RowName = 'deff';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
	}
	if($Sort == 36)
   {
		$HeadName = 'Turnier';
		$RowName = 'turnierpoints';

		$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC
		LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
  }
}
else
{
	$HeadName = 'Punkte';
    $RowName = 'points';

    $Query = "
	SELECT user_id,name,schule,points FROM ".$tabell_name."
	ORDER BY ".$RowName." DESC,regstamp ASC
    LIMIT ".$Start.",".$GLOBALS['conf']['vars']['highscore_size'];
}

echo'
<table width="90%" id="tab">
  <thead>
    <tr>
      <th width="40">Platz</th>
      <th>Name</th>
      <th width="50">Schule</th>
      <th width="100">'.$HeadName.'</th>
      <th width="45">Status</th>
    </tr>
  </thead>
  <tfoot>';

$Platz = $Start;


$UQuery = mysql_query($Query);
while($Users = @mysql_fetch_assoc($UQuery))
{
  $Platz++;

  $sql = "SELECT lonline FROM user WHERE id='$Users[user_id]' LIMIT 1";
  $online = mysql_query($sql);
  $online_user = mysql_fetch_assoc($online);

  if($online_user['lonline'] > time()-300)
  {
    $Status = '<strong style="color:'.$GLOBALS['conf']['vars']['success_color'].';">on</strong>';
  }
  elseif($online_user['lonline'] > time()-560)
  {
    $Status = '<strong style="color: yellow;">off</strong>';
  }
  else
  {
    $Status = '<strong style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">off</strong>';
  }


  if($User['id'] == $Users['user_id'])
  {
    $BGColor = $GLOBALS['conf']['vars']['myself_color'];
  }
  elseif($User['schule'] == $Users['schule'])
  {
    $BGColor = $GLOBALS['conf']['vars']['friends_color'];
  }
  else
  {
    $BGColor = false;
  }

  if(!empty($Users['schule']))
  {
    $SchoolQuery = mysql_query("SELECT * FROM ".TABALLY." WHERE id='".$Users['schule']."' LIMIT 1");
    $School = mysql_fetch_assoc($SchoolQuery);

	if($School == true)
	{
	  $ShowSchool = '<a href="?site=schulen&sub='.$School['id'].'info">'.$School['kuerzel'].'</a>';
	}
	else
	{
	  $ShowSchool = '-';
	}
  }
  else
  {
    $ShowSchool = '-';
  }

  if($Sort == 37)
  {
  	$anmeldedatum = $Users[$RowName];
  	$anmeldetimestamp = strtotime($anmeldedatum);

  	$differenz = time()-$anmeldetimestamp;
  	$differenz = $differenz/86400;
    $Users[$RowName] = date('d.m.Y',$Users[$RowName]."(".ceil($differenz)." Tage)");
  }

  if($Sort == 38)
  {
    $Users[$RowName] = date('H:i d.m.y',$Users[$RowName]);
  }

    $old_points_query = mysql_query("SELECT * FROM ".$tabell_name_2." WHERE user_id='".$Users['user_id']."' LIMIT 1");
    $old_points = mysql_fetch_assoc($old_points_query);

	if ($Sort != 38 && $Sort != 37)
	{
		$diff = $Users[$RowName] - $old_points[$RowName];
		if($diff < 0)
		{
			$diff = '&nbsp;<strong style="color:red;">('.$diff.')</strong>';
		}elseif($diff > 0)
		{
		  $diff = '&nbsp;<strong style="color:'.$GLOBALS['conf']['vars']['success_color'].';">(+'.$diff.')</strong>';
		}else
		{
		  $diff = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	} else
	{
		$diff = '&nbsp;';
	}

	$eintrag = '<td>'.$Users[$RowName].$diff.'</td>';

  echo'
  <tr
   bgcolor="'.$BGColor.'"
   onmouseover="this.style.backgroundColor=\''.$GLOBALS['conf']['vars']['hover_color'].'\'"
   onmouseout="this.style.backgroundColor=\''.$BGColor.'\'">
    <td><strong>'.$Platz.'</strong></td>
    <td><a href="?site=userinfo&info='.$Users['user_id'].'">'.$Users['name'].'</a></td>
    <td>'.$ShowSchool.'</td>
    '.$eintrag.'
    <td>'.$Status.'</td>
  </tr>';
  if($Users['user_id'] == $User['id'])$PPlatz = $Platz;
}

echo'
  </tfoot>
</table><br />';

if($PPlatz)
{
	echo'
	<center>
	Du befindest dich auf
	<a href="?site=highscore&sort='.$_POST['order'].'&page='.ceil($PPlatz/$GLOBALS['conf']['vars']['highscore_size']).'">Platz '.$PPlatz.'</a>!
	</center><br />';
}else
{
	//user ist auf einem versteckten Platz
	$Query = "
		SELECT user_id,name,schule,".$RowName." FROM ".$tabell_name."
		ORDER BY ".$RowName." DESC,regstamp ASC";

	$PPlatz = 0;
	$UQuery = mysql_query($Query);
	while($Users = @mysql_fetch_assoc($UQuery))
	{
		$PPlatz++;
		if($Users['user_id'] == $User['id'])
		{
			break;
		}

	}
	echo'
	<center>
	Du befindest dich auf
	<a href="?site=highscore&sort='.$_POST['order'].'&page='.ceil($PPlatz/$GLOBALS['conf']['vars']['highscore_size']).'">Platz '.$PPlatz.'</a>!
	</center><br />';
}

$Query = mysql_query("SELECT id FROM ".$tabell_name." ");
$Count = mysql_num_rows($Query);

$AmmountPages = ceil($Count / $GLOBALS['conf']['vars']['highscore_size']);

echo'
<center>
  <strong>Seite:</strong>
  '.displayPages($Page,$AmmountPages,'?site=highscore&sort='.$_POST['order'].'',4,'page').'
</center><br />';

?>
