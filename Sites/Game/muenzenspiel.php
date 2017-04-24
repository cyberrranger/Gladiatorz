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

echo "<strong>Na, lust auf ein kleines Spielchen?</strong><br />
Ich werf die Münze nach oben und wenn du richtig räts was oben liegt wenn sie auf dem Boden aufschlägt hast du gewonnen.<br />
";

if($_GET['play']==1){
	$limit = 10000;
	$gold = (int)$_POST['einsatz'];
	if($_POST['coin']=="Kopf") $coin = 0;
	else $coin =1;

	if($gold>$user['gold']){
		echo "Du hast nicht Soviel Gold, <strong>Betrüger</strong>.";
	}elseif($gold > $limit){
		echo "Du bist ja ein richtiger Zocken, aber bei über ".$limit." Gold mach ich nicht mit";
	}elseif($gold > 0){
		$zufall= rand(0,1);

		// 0 = Kopf; 1 = Zahl
		// bildname: coin-0.gif bzw. coin-1.gif
		echo "<center><br /><img src=\"Images/sonstiges/coin-".$zufall.".gif\" /></center>";

		if($coin == $zufall){
			$user['gold'] += $gold;
                         $user['goldwin'] += $gold;
                         $user['play'] += 1;
			echo "<center><br />Gut geraten, hast dir die ".$gold." Gold redlich verdient.</center>";
		}else{
			$user['gold'] -= $gold;
                         $user['goldlose'] += $gold;
                         $user['play'] += 1;
			echo "<center><br />Vielleicht beim nächsten mal. Deine ".$gold." Gold gehören jetzt mir.</center>";
		}
	}else{
		echo "<br /><center>Hau ab wenn du nicht spielen willst.</center>";
	}
}
echo "<br /><br /><form action=\"index.php?site=muenzenspiel&play=1\" name=\"spiel\" method=\"post\">
		<center>Einsatz: <input type=\"text\" name=\"einsatz\"><br />
		Wahl:    <input type=\"submit\" value=\"Kopf\" name=\"coin\">
		<input type=\"submit\" value=\"Zahl\" name=\"coin\"></center>
</form>";

$UpdateSql = "UPDATE user Set gold=".$user[gold]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set goldwin=".$user[goldwin]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set goldlose=".$user[goldlose]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);
$UpdateSql = "UPDATE user Set play=".$user[play]." WHERE id=".$user[id]." LIMIT 1";
$UpdateQuery = mysql_query($UpdateSql);

?>
