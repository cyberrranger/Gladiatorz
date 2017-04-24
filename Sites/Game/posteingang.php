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
$ausklapp = $_GET["ausklapp"];

$abfrage = "SELECT * FROM nachrichten WHERE empfaenger='$user[name]' AND del_empfaenger='n'";
$select = mysql_query($abfrage);
while($loeschen = mysql_fetch_assoc($select))
{
  if($_POST["$loeschen[id]"] != "")
  {
    $kill = $_POST["$loeschen[id]"];
	mysql_update("nachrichten","del_empfaenger='j'","id='$kill' AND empfaenger='$user[name]'");
	$kills++;
  }
}

if($_POST['bericht']=="Berichte")
{
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Arenaleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Challenge'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Rangleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Duellleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Schulenoberhaupt'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Schulenleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Basarleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Gladiatorenmeister'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='admin'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Marktleitung'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Organisation'");
	mysql_update("nachrichten","del_empfaenger='j'","empfaenger='$user[name]' AND absender='Rundmail'");
}

if($kills == 1)
{
  echo"<center><b>Die Nachricht wurde gel&ouml;scht.</b></center><br>";
}
elseif($kills > 1)
{
  echo"<center><b>Die Nachrichten wurden gel&ouml;scht.</b></center><br>";
}

$check = array();

echo"<form name=\"checkform\" action=\"index.php?site=posteingang\" method=\"post\">
<table cellpadding=0 cellspacing=0 border=0 align=center width=480 style=\"border-collapse:collapse;\">

<tr>
  <th height=10 align=center class=bordered_table><font size=-2><b>Betreff</b></font></th>
  <th height=10 align=center class=bordered_table width=100><font size=-2><b>Absender</b></font></th>
  <th height=10 align=center class=bordered_table width=100><font size=-2><b>Datum</b></font></th>
  <th height=10 align=center class=bordered_table width=25>
    <input type=button value=# style=\"border:0px;background-color:#bd7900;\" onClick=\"chkAll()\">
  </th>
</tr>";

$abfrage = "SELECT * FROM nachrichten WHERE empfaenger='$user[name]' AND del_empfaenger='n' ORDER BY id DESC";
$select = mysql_query($abfrage);
while($post = mysql_fetch_assoc($select))
{
  $datum = date("H:i j.n.y",$post[datum]);
  $messages++;

  if($post[id] == $ausklapp)
  {
if ($post['titel']== "Duellbericht" OR $post['titel']== "Kampfbericht")
{
  echo"
  <tr>
    <td height=10 style=\"text-align:left;\">
	  <font size=-2><a href=index.php?site=posteingang style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	</td>
    <td height=10 class=bordered_table align=center><font size=-2>$post[absender]</font></td>
    <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
	<td height=10 class=bordered_table align=center>
	  <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox>
	</td>
  </tr>
  <tr>
    <td colspan=4 height=10 class=bordered_table style=\"text-align:left;\"><font size=-2 >".br2nl($bbcode->parse($post[text]))."<br><br>
	<a href=index.php?site=nachrichten&re=$post[id] style=\"text-decoration:none;\">
  </tr>";
}
else
{
	$new_body = htmlspecialchars($new_body);
	$new_body = br2nl($bbcode->parse($post[text]));

     echo"
  <tr>
    <td height=10 style=\"text-align:left;\">
	  <font size=-2><a href=index.php?site=posteingang style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	</td>
    <td height=10 class=bordered_table align=center><font size=-2>$post[absender]</font></td>
    <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
	<td height=10 class=bordered_table align=center>
	  <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox>
	</td>
  </tr>
  <tr>
    <td colspan=4 height=10 class=bordered_table style=\"text-align:left;\"><font size=-2>".$new_body."<br><br>
	<a href=index.php?site=nachrichten&re=$post[id] style=\"text-decoration:none;\">
	<b>Antworten</b></a></font></td>
  </tr>";
}

    if($post[gelesen] == 'n')
    {
      mysql_update("nachrichten","gelesen='j'","id='$post[id]'");
    }
  }
  elseif($post[gelesen] == 'n')
  {
    echo"
	<tr style=\"background-color:darkred;\">
      <td height=10 class=bordered_table style=\"text-align:left;\">
	    <font size=-2>
		<a href=index.php?site=posteingang&ausklapp=$post[id] style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	  </td>
      <td height=10 class=bordered_table align=center>
	    <font size=-2>$post[absender]</font></td>
      <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
      <td height=10 class=bordered_table align=center>
        <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox>
	  </td>
    </tr>";
  }
  else
  {
    echo"
	<tr>
      <td height=10 class=bordered_table style=\"text-align:left;\">
	    <font size=-2>
		<a href=index.php?site=posteingang&ausklapp=$post[id] style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	  </td>
      <td height=10 class=bordered_table align=center><font size=-2>$post[absender]</font></td>
      <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
      <td height=10 class=bordered_table align=center>
        <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox />
	  </td>
    </tr>";
  }
}

echo'</table>';

if($messages >= 1)
{
  echo"<br><center><b>L�schen:</b> <input type=submit value=Auswahl name=L�schen>
  <input type=\"submit\" value=\"Berichte\" name=\"bericht\" ></center></form>";
}

?>
