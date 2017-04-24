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
if(!isMod())
{
	exit;
}

if($_REQUEST['do'] == 'new')
{
	$i=mysql_query(
		"INSERT INTO sitting (user,sitter,start,ende,text) 
			VALUES 
				('".$_REQUEST['user']."','".$_REQUEST['sitter']."','".$_REQUEST['start']."','".$_REQUEST['ende']."','".$_REQUEST['text']."')");
  
	if($i!=false)
	{
		echo'Eingef�gt :-)<br /><br />';
	}
}



$sql = "SELECT * FROM sitting";
$result = mysql_query($sql) or die(mysql_error());

echo"
<table border=1>
<tr>
<th>Username</th>
<th>Sittername</th>
<th>Start</th>
<th>Ende</th>
<th>Beschreibung</th>
</tr>";

while ($ausgabe = mysql_fetch_array ($result))
{
	echo"
	<tr>
	<td>".$ausgabe['user']."</td>
	<td>".$ausgabe['sitter']."</td>
	<td>".$ausgabe['start']."</td>
	<td>".$ausgabe['ende']."</td>
	<td>".$ausgabe['text']."</td>
	</tr>";
}
echo"</table><br><br><br>";

echo "Neuer Eintrag:<br /><br />";

echo "<h3>Achtung bitte unbedingt auf das Datum achten Jahr-Monat-Tag wenn der Tag oder monat nur eine Stelle hat mit 0 ausf�llen 2010-04-31</h3>";
echo "<form name='newsitter' method='post' action='?site=sitting&do=new'>";

echo "Username:<br />";
echo "<input name='user' /><br />";
echo "Sittername:<br />";
echo "<input name='sitter' /><br />";
echo "Start (Jahr-Monat-Tag):<br />";
echo "<input name='start' /><br />";
echo "Ende (Jahr-Monat-Tag):<br />";
echo "<input name='ende' /><br />";
echo "Beschreibung:<br />";
echo "<input name='text' /><br />";

echo "<input type='submit' value='Einf�gen' /></form>";


?>
