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
	<a href="index.php?site=gluecksspiel" target="_self">Gl�cksspiel</a>
	</center>';

echo'
	Hey duda, komm mal her. Lust auf ein Spielchen?<br />
	Sieh her, ich hab hier ein paar Karten. Die erste ist aufgedeckt und die anderen nicht. Du musst einfach nur raten ob die n�chste Karte gr��er oder kleine als die vorher aufgedeckte ist.<br />
	Hast du 2 richtig bekommst du dein Einsatz zur�ck, bei 3 bekommst du das doppelte deines Einsatzes und so weiter.';
	
$limit = 10000;
$nkarte = rand(2,14);
$karte = rand(2,14);

if($karte <= 9)
{
	echo "<center><br /><img src=\"Images/sonstiges/karte0".$karte.".GIF\" /></center>";
}
else
{
	echo "<center><br /><img src=\"Images/sonstiges/karte".$karte.".GIF\" /></center>";
}

echo "<br /><br /><form action=\"index.php?site=kartenspiel\" name=\"spiel\" method=\"post\">
		<center>Einsatz: <input type=\"Einsatz\" name=\"einsatz\"><br />
		<input type=\"hidden\" name=\"nkarte\" value=\"$nkarte\" />
		<input type=\"hidden\" name=\"karte\" value=\"$karte\" />
		<INPUT TYPE=\"submit\" VALUE=\"Gr��er\" name=\"more\">
		<INPUT TYPE=\"submit\" VALUE=\"Kleiner\" name=\"lower\"></center>
</form>";

$einsatz = $_POST['einsatz'];

if($einsatz > $user['gold'])
{
	echo'
	Willst du mich �bern Tisch ziehen? Sowiel Gold hast du garnicht!';
}
elseif($einsatz > $limit)
{
	echo'
	Du bist ja ein richtiger Zocken, aber bei �ber ".$limit." Gold mach ich nicht mit';
}
elseif($einsatz > 0)
{
	$user['gold'] = $user['gold'] - $einsatz;
	$user['play'] += 1;

	function sieg()
	{
		global $nkarte;
		global $karte;
		
		if($nkarte <= 9)
		{
			echo "<center><br /><img src=\"Images/sonstiges/karte0".$karte.".GIF\" /></center>";
		}
		else
		{
			echo "<center><br /><img src=\"Images/sonstiges/karte".$karte.".GIF\" /></center>";
		}
		
		if($_POST['more'])
		{
			if($nkarte>=$karte)
			{
				$punkte = $punkte +1;
				$ergebniss = 1;
			}
			elseif($nkarte<$karte)
			{
				$ergebniss = 2;
			}
		}
		elseif($_POST['lower'])
		{
			if($nkarte<=$karte)
			{
				$punkte = $punkte +1;
				$ergebniss = 1;
			}
			elseif($nkarte>$karte)
			{
				$ergebniss = 2;
			}
		}
				
		if($ergebniss = 1)
		{
			echo'
				<INPUT TYPE=\"submit\" VALUE=\"Aufh�ren\" name=\"next\">
				<INPUT TYPE=\"submit\" VALUE=\"Weiter\" name=\"next\">';
		}
				
		if($_POST['next']=="Aufh�ren")
		{	
			$ergebniss = 3;
		}
	}
	
	$karte = $nkarte;
	$nkarte = rand(2,14);
	
	if($ergebniss = 1)
	{
		sieg();
	}
	elseif($ergebniss = 2)
	{
		$gewinn = 0;
		$user['gold'] = $user['gold'] + $gewinn;
		$user['goldlose'] += $einsatz;
		$Rand = rand(0,2);
		switch($Rand)
		{
			case 0:
			echo'
			<center><br />Vielleicht beim n�chsten mal. Deine '.$einsatz.' Gold geh�ren jetzt mir.</center>';
			break;
			
			case 1:
			echo'
			<center><br />HAHA das war wohl nix. Deine '.$einsatz.' Gold geh�ren jetzt mir.</center>';
			break;
			
			case 2:
			echo'
			<center><br />Geh lieber wieder in die Arena, die Spielerei scheint dich arm zu machen. Deine '.$einsatz.' Gold geh�ren jetzt mir.</center>';
			break;
		}
	}
	elseif($ergebniss = 3)
	{
		$gewinn = $einsatz*($punkte-1);
		$user['gold'] = $user['gold'] + $gewinn;
		$user['goldwin'] += $gewinn;
		$Rand = rand(0,2);
		switch($Rand)
		{
			case 0:
			echo'
			<center><br />Gut geraten, hast dir die '.$gewinn.' Gold redlich verdient.</center>';
			break;
			
			case 1:
			echo'
			<center><br />Fortuna ist dir wahrlich hold. Die '.$gewinn.' Gold hast du dir redlich verdient.</center>';
			break;
			
			case 2:
			echo'
			<center><br />Wenn du in der Arena auch soviel Gl�ck hast will ich nicht dein Gegner sein. Die '.$gewinn.' Gold hast du dir redlich verdient.</center>';
			break;
		}
	}
}

$UpdateSql = "UPDATE user Set gold=".$user[gold]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set goldwin=".$user[goldwin]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set goldlose=".$user[goldlose]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set play=".$user[play]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);

?>
