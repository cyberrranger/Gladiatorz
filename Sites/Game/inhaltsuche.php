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
<a href="index.php?site=forum" target="_self">Forum</a> |
<a href="index.php?site=topicsuche" target="_self">Topicsuche</a> |
<a href="index.php?site=inhaltsuche" target="_self">Inhaltsuche</a> |
</center>';

if(!empty($_POST["suchen"]))
{
  $suche = strtolower($_POST["suchen"]);
  $links = array();

  $finds = 0;
  $sql = "SELECT * FROM forum_answers WHERE message LIKE '%$suche%'";
  $query = mysql_query($sql);
  while($ergebniss = mysql_fetch_assoc($query))
  {
   $links[$ergebniss[topics_id]] = $ergebniss[topics_id];
   $finds = $finds + 1;
  }

  if($finds == 0)
  {
    echo"<center>Es wurde kein entsprechender Eintrag gefunden.</center>";
  }
  else
  {
    echo"<center><b>Suchergebnisse:</b></center><br /><div style=\"width:250px;text-align:left;margin-left:160px;\">";

	foreach($links AS $key => $value)
	{
	  echo"<li><a href=index.php?site=forum&showtopic=$key>$value</a></li>";
	}

	echo"</div>";
  }
}
else
{
echo"<center>Hier kannst du schauen, ob es eine Forum Nachricht mit folgendem Inhalt gibt ...<br>Vorsicht zeigt alle Ergebnisse an auch welche in fremden Schulforen sind.<br> <h3>Noch nicht fertig funktionsf�hig.</h3></center><br>";
  echo"<center><form name=such method=post action=index.php?site=inhaltsuche>


<input name=suchen type=text>&nbsp;<input type=submit value=Suchen></form></center>";
}
?>
