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
<a href="index.php?site=gasthaus" target="_self">Schankraum</a> |
<a href="index.php?site=chat" target="_self">Chat</a>
(<a href="javascript:PopUp(\'Sites/Game/chat.php\',540,408,0);" target="_self">im PopUp</a>) |
<a href="index.php?site=schlafraum" target="_self">Schlafraum</a> |
<a href="index.php?site=gluecksspiel" target="_self">Glücksspiel</a>
</center>';

echo'
<br /><b>Lust auf ein Spielchen?</b><br>
Wähle 4 mal je eine Zahl zwischen 1 und 8 aus.<br>
Hast du 1 davon richtig, bekommst du deinen Einsatz zurück<br>
Hast du 2 davon richtig gewinnst du deinen Einsatz<br>
Hast du 3 davon richtig gewinnst du 1x deinen Einsatz<br>
Hast du 4 davon richtig, gewinnst du 2x deinen Einsatz.<br>
Hast du 5 davon richtig, gewinnst du 5x deinen Einsatz.<br>
Hast du alle 6 richtig, gewinnst du 8x deinen Einsatz.<br><br><br>';

echo'
<form action="index.php?site=zahlenspiel" method="post">
Einsatz: <input type="text" size="6" maxlength="6" name="einsatz"><br>
1.Zahl: <select name="zahl1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br>
2.Zahl: <select name="zahl2">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br>
3.Zahl: <select name="zahl3">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br>
4.Zahl: <select name="zahl4">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br>
5.Zahl: <select name="zahl5">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br>
6.Zahl: <select name="zahl6">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select><br><br>
<input type="submit" value="Spielen" name="submit">
</form>';

$ein=$_POST['einsatz'];
$z1=$_POST['zahl1'];
$z2=$_POST['zahl2'];
$z3=$_POST['zahl3'];
$z4=$_POST['zahl4'];
$z5=$_POST['zahl5'];
$z6=$_POST['zahl6'];

$zu1=rand(1,8);
$zu2=rand(1,8);
$zu3=rand(1,8);
$zu4=rand(1,8);
$zu5=rand(1,8);
$zu6=rand(1,8);

if(isset($_POST['submit']))
{
	if($user['gold'] < $ein)
	{
		echo'
		<br />Willst du mich übern Tisch ziehen? Sowiel Gold hast du garnicht!';
	}
	elseif($ein > 10000)
	{
		echo'
		<br />Du bist ja ein richtiger Zocken, aber bei über 10000 Gold mach ich nicht mit';
	}
	elseif($ein > 0)
	{
		$user['gold'] = $user['gold'] - $ein;
		$user['play'] += 1;

		echo "<br />Deine Zahlen sind: <b>$z1</b>, <b>$z2</b>, <b>$z3</b>, <b>$z4</b>, <b>$z5</b> und <b>$z6</b><br>
		Die Gewinnerzahlen sind: <b>$zu1</b>, <b>$zu2</b>, <b>$zu3</b>, <b>$zu4</b>, <b>$zu5</b> und <b>$zu6</b>.<br>";

		if($z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6)
		{
			$gewinn= $ein*9;
			$user['gold'] = $user['gold'] + $gewinn;
			$user['goldwin'] = $user['goldwin']+ - $ein;
			echo "Du hast alle <b>6</b> Zahlen richtig und Gewinnst somit <b>$gewinn</b>!";
		}
		elseif($z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 OR $z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 AND $z6==$zu6 OR $z1==$zu1 AND $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6 OR $z1==$zu1 AND $z2==$zu2 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6 OR $z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z5==$zu5 AND $z6==$zu6)
		{
			$gewinn= $ein*6;
			$user['gold'] = $user['gold'] + $gewinn;
			$user['goldwin'] = $user['goldwin']+ $gewinn - $ein;
			echo "Du hast <b>5</b> Zahlen richtig und Gewinnst somit <b>$gewinn</b>!";
		}
		elseif($z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 OR $z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z5==$zu5 OR $z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 AND $z6==$zu6 OR $z1==$zu1 AND $z4==$zu4 AND $z3==$zu3 AND $z5==$zu5 OR $z1==$zu1 AND $z4==$zu4 AND $z3==$zu3 AND $z6==$zu6 OR $z1==$zu1 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6 OR $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 OR $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 AND $z6==$zu6 OR $z2==$zu2 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6 OR $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6)
		{
			$gewinn= $ein*4;
			$user['gold'] = $user['gold'] + $gewinn;
			$user['goldwin'] = $user['goldwin']+ $gewinn - $ein;
			echo "Du hast <b>4</b> Zahlen richtig und Gewinnst somit <b>$gewinn</b>!";
		}
		elseif($z1==$zu1 AND $z2==$zu2 AND $z3==$zu3 OR $z1==$zu1 AND $z2==$zu2 AND $z4==$zu4 OR $z1==$zu1 AND $z2==$zu2 AND $z5==$zu5 OR $z1==$zu1 AND $z2==$zu2 AND $z6==$zu6 OR $z1==$zu1 AND $z3==$zu3 AND $z4==$zu4 OR $z1==$zu1 AND $z3==$zu3 AND $z5==$zu5 OR $z1==$zu1 AND $z3==$zu3 AND $z6==$zu6 OR $z1==$zu1 AND $z4==$zu4 AND $z5==$zu5 OR $z1==$zu1 AND $z4==$zu4 AND $z6==$zu6 OR $z1==$zu1 AND $z5==$zu5 AND $z6==$zu6 OR $z2==$zu2 AND $z3==$zu3 AND $z4==$zu4 OR $z2==$zu2 AND $z3==$zu3 AND $z5==$zu5 OR $z2==$zu2 AND $z3==$zu3 AND $z6==$zu6 OR $z2==$zu2 AND $z4==$zu4 AND $z5==$zu5 OR $z2==$zu2 AND $z4==$zu4 AND $z6==$zu6 OR $z2==$zu2 AND $z5==$zu5 AND $z6==$zu6 OR $z3==$zu3 AND $z4==$zu4 AND $z5==$zu5 OR $z3==$zu3 AND $z4==$zu4 AND $z6==$zu6 OR $z3==$zu3 AND $z5==$zu5 AND $z6==$zu6 OR $z4==$zu4 AND $z5==$zu5 AND $z6==$zu6)
		{
			$gewinn= $ein*3;
			$user['gold'] = $user['gold'] + $gewinn;
			$user['goldwin'] = $user['goldwin']+ $gewinn - $ein;
			echo "Du hast <b>3</b> Zahlen richtig und Gewinnst somit <b>$gewinn</b>!";
		}
		elseif($z1==$zu1 AND $z2==$zu2 OR $z1==$zu1 AND $z3==$zu3 OR $z1==$zu1 AND $z4==$zu4 OR $z1==$zu1 AND $z5==$zu5 OR $z1==$zu1 AND $z6==$zu6 OR $z2==$zu2 AND $z3==$zu3 OR $z2==$zu2 AND $z4==$zu4 OR $z2==$zu2 AND $z5==$zu5 OR $z2==$zu2 AND $z6==$zu6 OR $z3==$zu3 AND $z4==$zu4 OR $z3==$zu3 AND $z5==$zu5 OR $z3==$zu3 AND $z6==$zu6 OR $z4==$zu4 AND $z5==$zu5 OR $z4==$zu4 AND $z6==$zu6 OR $z5==$zu5 AND $z6==$zu6)
		{
			$gewinn= $ein*2;
			$user['gold'] = $user['gold'] + $gewinn;
			$user['goldwin'] = $user['goldwin']+ $gewinn - $ein;
			echo "Du hast <b>2</b> Zahlen richtig und Gewinnst somit <b>$gewinn</b>!";
		}
		elseif($z1==$zu1 OR $z2==$zu2 OR $z3==$zu3 OR $z4==$zu4 OR $z5==$zu5 OR $z6==$zu6)
		{
			echo "Du hast <b>1</b> Zahl richtig und bekommst deinen Einsatz zurück.";
			$user['gold'] = $user['gold'] + $ein;
		}
		else
		{
			echo'
			Tja, leider falsch geraten, deine '.$ein.' Gold sind jetzt mein';
			$user['goldlose'] += $gold;
		}
			
	$UpdateSql = "UPDATE user Set gold=".$user[gold]." WHERE id=".$user[id]." LIMIT 1";
	$UpdateQuery = mysql_query($UpdateSql);
	$UpdateSql = "UPDATE user Set goldwin=".$user[goldwin]." WHERE id=".$user[id]." LIMIT 1";
	$UpdateQuery = mysql_query($UpdateSql);
	$UpdateSql = "UPDATE user Set goldlose=".$user[goldlose]." WHERE id=".$user[id]." LIMIT 1";
	$UpdateQuery = mysql_query($UpdateSql);
	$UpdateSql = "UPDATE user Set play=".$user[play]." WHERE id=".$user[id]." LIMIT 1";
	$UpdateQuery = mysql_query($UpdateSql);
	}
}
?>
