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
$re = $_GET["re"];

$re_post = mysql_select("*","nachrichten","id='$re'",null,"1");

if(isset($_GET["empfaenger"]))
$re_post[absender] = $_GET["empfaenger"];

if(isset($_GET["titel"]))
$re_post[titel] = $_GET["titel"];

if(isset($_POST["text"]))
{
	$empfaenger = strip_tags($_POST["empfaenger"]);
	$titel = strip_tags($_POST["titel"]);
	$text = nl2br(strip_tags($_POST["text"]));

	if($empfaenger != "" AND $titel != "" AND $text != "")
	{
		$adresse = mysql_select("*","user","name='$empfaenger'",null,"1");

		if($adresse[name] != "")
		{
			$time = time();

			mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender","'$empfaenger','$user[name]','$titel','$text','$time','n','n','n'");

			$message = "Deine Nachricht wurde erfolgreich verschickt!";

		}
		else
		{
			$message = "Dieser User existiert nicht!";
		}
	}
	else
	{
		$message = "Bitte alle Felder ausf�llen!";
	}
}

if($re_post[titel] != "" && $re_post['titel' != 'Spielanfrage']){$re_post[titel] = "Re/".$re_post[titel];}
if($re_post[text] != ""){$re_post[text] = "\n\n||Vorherige Nachricht||\n\n".$re_post[text];}

$re_post[text] = br2nl($re_post[text]);

//eingabefelder
echo'<form action="index.php?site=nachrichten" method="post">
<div style="margin-left:60px;"><b>'.$message.'</b><br /><br />Empfänger:<br /><input value="'.$re_post[absender].'" name="empfaenger" type="text" style="width:400px;font-family:Verdana;font-size:11px;border:1px solid darkgray;padding:2px;" /><br />';

echo'<br />Überschrift:<br /><input value="'.$re_post[titel].'" name="titel" type="text" style="width:400px;font-family:Verdana;font-size:11px;border:1px solid darkgray;padding:2px;" /><br />';

echo'<br />Nachricht:<br /><textarea name="text" style="width:400px;height:180px;border:1px solid darkgray;padding:2px;font-family:Verdana;font-size:11px;" scrolling=auto wrapping=auto>'.$re_post[text].'</textarea><br />
<input type="submit" value="Absenden" /><br /><br /><a href="javascript:PopUp(\'bbcode/index.php\',\'640\',\'480\',0)">Du willst BB-Code benutzen?</a></div></form>';

//anzeige von ein paar BB-Codes
echo'<br /><br /><b>BB-Codes:</b><br /><br /><table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-left:-1px;border:0px;">
  <tr>
    <td width="170" style="border:0px;text-align:left;">[b]Text[/b]</td>
    <td style="border:0px;text-align:left;"><b>fetter Text</b></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[i]Text[/i]</td>
    <td style="border:0px;text-align:left;"><em>kursiver Text</em></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[u]Text[/u]</td>
    <td style="border:0px;text-align:left;"><u>unterstrichener Text</u></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[size=Pixel]Text[/size]</td>
    <td style="border:0px;text-align:left;"><font style="font-size:10px;">kleiner Text</font></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[font=Schrift]Text[/font]</td>
    <td style="border:0px;text-align:left;"><font style="font-family:Courier;">andere Schriftart</font></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[color=Farbe]Text[/color]</td>
    <td style="border:0px;text-align:left;"><font color="green">andere Farbe</font></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[url=test.com]Text[/url]</td>
    <td style="border:0px;text-align:left;"><a href="#">Hyperlink</a></td>
  </tr>
  <tr>
    <td style="border:0px;text-align:left;">[img]test.com/bild.jpg[/img]</td>
    <td style="border:0px;text-align:left;">Bild</td>
  </tr>
</table><br />';

?>
