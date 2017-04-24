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

$abfrage = "SELECT * FROM nachrichten WHERE absender='$user[name]' AND del_absender='n'";
$select = mysql_query($abfrage);
while($loeschen = mysql_fetch_assoc($select))
{
  if($_POST["$loeschen[id]"] != "")
  {
    $kill = $_POST["$loeschen[id]"];
	mysql_update("nachrichten","del_absender='j'","id='$kill' AND absender='$user[name]'");
	$kills++;
  }
}

if($kills == 1)
{
  echo"<center><b>Die Nachricht wurde gel�scht.</b></center><br>";
}
elseif($kills > 1)
{
  echo"<center><b>Die Nachrichten wurden gel�scht.</b></center><br>";
}

$check = array();

echo"<form name=\"checkform\" action=\"index.php?site=postausgang\" method=\"post\">
<table cellpadding=0 cellspacing=0 border=0 align=center width=480 style=\"border-collapse:collapse;\">

<tr>
  <th height=10 align=center class=bordered_table><font size=-2><b>Betreff</b></font></th>
  <th height=10 align=center class=bordered_table width=100><font size=-2><b>Empf�nger</b></font></th>
  <th height=10 align=center class=bordered_table width=100><font size=-2><b>Datum</b></font></th>
  <th height=10 align=center class=bordered_table width=25>
    <input type=button value=# style=\"border:0px;background-color:#bd7900;\" onClick=\"chkAll()\">
  </th>
</tr>";

$abfrage = "SELECT * FROM nachrichten WHERE absender='$user[name]' AND del_absender='n' ORDER BY id DESC";
$select = mysql_query($abfrage);
while($post = mysql_fetch_assoc($select))
{
  $datum = date("H:i j.n.y",$post[datum]);
  $messages++;

  if($post[id] == $ausklapp)
  {
  echo"
  <tr>
    <td height=10 class=bordered_table style=\"text-align:left;\">
	  <font size=-2><a href=index.php?site=postausgang style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	</td>
    <td height=10 class=bordered_table align=center><font size=-2>$post[empfaenger]</font></td>
    <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
	<td height=10 class=bordered_table align=center>
	  <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox>
	</td>
  </tr>
  <tr>
    <td colspan=4 height=10 class=bordered_table style=\"text-align:left;\"><font size=-2>".br2nl($bbcode->parse($post[text]))."</font></td>
  </tr>";
  }
  else
  {
    echo"
	<tr>
      <td height=10 class=bordered_table style=\"text-align:left;\">
	    <font size=-2>
		<a href=index.php?site=postausgang&ausklapp=$post[id] style=\"text-decoration:none;font-size:10px;\">$post[titel]</a></font>
	  </td>
      <td height=10 class=bordered_table align=center><font size=-2>$post[empfaenger]</font></td>
      <td height=10 class=bordered_table align=center><font size=-2>$datum</font></td>
      <td height=10 class=bordered_table align=center>
        <input style=\"height:12px;width:12px;\" name=$post[id] value=$post[id] type=checkbox>
	  </td>
    </tr>";
  }
}

echo'</table>';

if($messages >= 1)
{
  echo"<br><center><input type=submit value=L�schen></center></form>";
}

?>
