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

function IMGrund($absender,$empfaenger,$titel,$text)
{
  $timestamp = time();

  mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender"
  ,"'$empfaenger','$absender','$titel',
  '$text','$timestamp','n','n','n'");
}


echo'
<center>
<a href="index.php?site=schulen&sub=list" target="_self">Liste</a> |
<a href="index.php?site=schulen&sub='.$UserAlly['id'].'info" target="_self">'.$UserAlly['name'].'</a> |
<a href="index.php?site=forum&showforum='.$Pinn['id'].'" target="_self">Pinnwand</a> |
<a href="index.php?site=schulen&sub=gel" target="_self">Gel&auml;nde</a>';

if($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'])
{
	echo' | <a href="index.php?site=schulen&sub=gebet" target="_self">Gebetsstätte</a>';
}

if($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'] || $user['id'] == $UserAlly['diplo'])
{
	echo' | <a href="index.php?site=rundmail" target="_self">Rundmail</a>';
	echo' | <a href="index.php?site=schulen&sub=ver" target="_self">Verwaltung</a>';
}


if(isset($_POST["text"]))
{
	$titel = strip_tags($_POST["titel"]);
	$text = nl2br(strip_tags($_POST["text"]));

	if($titel != "" AND $text != "")
	{
		$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
		while($empfaenger = mysql_fetch_assoc($Query))
		{
			IMGrund('Rundmail',$empfaenger['name'],$titel,$text);
		}
	}
	else
	{
		$message = "Bitte alle Felder ausfüllen!";
	}
}

echo'<form action="index.php?site=rundmail" name="mail" method="post">';
echo'<div style="margin-left:60px;"><br><br><b>'.$message.'</b><br /><br />Überschrift:<br /><input name="titel" type="text" style="width:400px;font-family:Verdana;font-size:11px;border:1px solid darkgray;padding:2px;" /><br />';

echo'<br />Nachricht:<br /><textarea name="text" style="width:400px;height:180px;border:1px solid darkgray;padding:2px;font-family:Verdana;font-size:11px;" scrolling=auto wrapping=auto></textarea><br />
<input type="submit" value="Absenden" /><br /></div></form>';

?>
