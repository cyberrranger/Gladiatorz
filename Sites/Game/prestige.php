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
if(isset($_POST['pet']) && isset($_POST['anz'])) // Kampf :D
{
	if(time() < $user['schlafen'])
	{
		echo"<center><b>Du ruhst dich gerade aus und kannst daher nicht an den Prestigekämpfen teilzunehmen!</b><br><br>
		<a href=index.php?site=prestige>weiter</a></center>";
	}

	else if($user['powmod'] <= 0)
	{
		echo"<center><b>Du hast nicht genügend PowMod um an den Kämpfen teilzunehmen!</b><br><br>
		<a href=index.php?site=prestige>weiter</a></center>";
	}

	else // nu aba Kampf ^^
	{
		if($_POST['anz'] < 2)
		{
			$_POST['anz'] = 2;
		}

		if($_POST['anz'] > 10)
		{
			$_POST['anz'] = 10;
		}

		if($_POST['pet'] < 1 || $_POST['pet'] > 37)
		{
			echo'Eingabefehler! : '.$_POST['pet'];
			exit;
		}

		include'fights.php';
		$Battle = new Battle();

		$GroupOne = array();
		$GroupOne[1] = new Fighter();
		$GroupOne[1]->getAbilitys($user['id']);

		$GroupTwo = array();

		$Loop = 1;
		while($Loop <= $_POST['anz'])
		{
			$GroupTwo[$Loop] = new Animal();
			$GroupTwo[$Loop]->getAbilitys($_POST['pet']);

			$Loop++;
		}

		$Return = $Battle->newBattle($GroupOne,$GroupTwo);

		echo $Return['show'].'<br />';

		$Sql = "SELECT * FROM tiergrube WHERE id='$_POST[pet]' LIMIT 1";
		$Select = mysql_query($Sql);
		$Animal = mysql_fetch_assoc($Select);

		if($Return['winner'] == 1) // Player
		{
			$user['powmod'] -= 0.1;
			$NewPrestige = round(((($user['charisma'] * 2) / 100) + 1) * ($Animal['prestige'] * $_POST['anz']));
			$user['prestige'] += $NewPrestige;
			$user['prest_kills_win']++;
			$EXP = 10 * ($User['level'] /100 );
			if($EXP < 3) { $EXP = 3;}
			$user['exp'] += round($EXP);

		# Wenn der User ein Quest hat auffülle
		$Query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$User['id']."' LIMIT 1");
		$quest_now = @mysql_fetch_assoc($Query);
		
		if($quest_now['typ'] == 'exp')
		{
			mysql_query("UPDATE quest_what SET count=count+".$EXP." WHERE user_id='".$User['id']."' LIMIT 1");
		}
		
		if($quest_now['typ'] == 'prestige')
		{
			mysql_query("UPDATE quest_what SET count=count+".$NewPrestige." WHERE user_id='".$User['id']."' LIMIT 1");
		}

		
			echo'<center>Durch den Sieg über deine Gegner hast du dir '.$NewPrestige.' Prestige und '.round($EXP).' EXP verdient! Außerdem bist du leicht erschöpft.<br /><a href="index.php?site=prestige" target="_self"><h2>zurück</h2></a></center><br />';
		}
		else // Enemy
		{
			$user['powmod'] -= 0.5;
			//$user['prestige'] += ((($user['charisma'] * 2) / 100) + 1) * ($Animal['prestige'] * $_POST['anz']);
			$user['prest_kills_lost']++;
			$user['exp']+=1;

			# Wenn der User ein Quest hat auffülle
			$Query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$User['id']."' LIMIT 1");
			$quest_now = @mysql_fetch_assoc($Query);
			
			if($quest_now['typ'] == 'exp')
			{
				mysql_query("UPDATE quest_what SET count=count+1 WHERE user_id='".$User['id']."' LIMIT 1");
			}

			echo'<center>Durch deine Niederlage bist du stark erschöpft hast aber 1 EXP verdient.<br />
			<a href="index.php?site=prestige" target="_self"><h2>zurück</h2></a></center><br />';
		}

		$user['kraft'] = $user['kraft'] - ($user['kraft'] - $Return[$user['id']]);

		$UpdateSql = "UPDATE user Set kraft='$user[kraft]',powmod='$user[powmod]', exp='$user[exp]', prestige='$user[prestige]', prest_kills_win='$user[prest_kills_win]', prest_kills_lost='$user[prest_kills_lost]' WHERE id='$user[id]' LIMIT 1";

		mysql_query($UpdateSql);
	}
}
else
{
	echo'
	<center><font style=font-size:14px;><u><strong>Prestige-Arena:</u></strong></font><br><br>
	<form method="post" action="index.php?site=prestige">
	<center>
    Als du die Arena betrittst, kommt dir ein muskulöser Mann entgegen.<br /><br />
    <em>Hallo <b>'.$user['name'].'</b>, mein Name ist Polyxéni - der Zwingermeister. 
    <br />Gegen welche und wieviele Tiere möchtest du deine Kräfte messen?</em><br /><br />    
    <b>Dein Kampf:</b>
    <select name="anz">
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
    </select>
    <select name="pet">
		<option value="5">Hirsch</option>
		<option value="8">Schakal</option>
		<option value="12">Strauß</option>
		<option value="17">Luchs</option>
		<option value="20">Keiler</option>
		<option value="23">Wolf</option>
		<option value="26">Leopard</option>
		<option value="29">Schwarzbär</option>
		<option value="32">Komodowaran</option>
		<option value="35">Nil-Krokodil</option>
		<option value="38">Elefant</option>
    </select>
    <input style="border:1px solid black;background-color:#eeeeee;" type="submit" value="Los gehts!" />
	</center>
	</form>';
  
	$Loop = 1;
	$Sql = "SELECT * FROM user WHERE prestige >= '0' ORDER BY prestige DESC,id ASC";
	$Query = mysql_query($Sql);
	while($rang = mysql_fetch_assoc($Query))
	{
		if($rang['id'] == $user['id'])
		{
			$PrestRang = $Loop;
			break;
		}
		else
		{
			$Loop++;
		}
	}

	echo'<br /><center style="font-size:10px;"><b>Prestige:</b> '.$user['prestige'].' <b>Platz:</b> '.$PrestRang.'</center>';
}

?>
