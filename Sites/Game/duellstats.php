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
	echo'<center>
		<a href="index.php?site=duelle" target="_self">Duelle</a> |
		<a href="index.php?site=duellstats" target="_self">Duell Statistik</a> |
		<a href="index.php?site=duelle_schule" target="_self">Trainingsduelle</a>
		</center>
		<br />';
?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th>Gewinner</th>
			<th>Level</th>
			<th>Verlierer</th>
			<th>Level</th>
			<th>Einsatz</th>
			<th>Kampfbericht</th>
		</tr>
	</thead>
	<tfoot>

<?php

$seite = $_GET["seite"];

if(!isset($seite))
   {
   $seite = 1;
   }

$eintraege_pro_seite = 50;
$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite;

$Query = mysql_query("SELECT * FROM duelle_report WHERE schule = 0 ORDER BY id DESC LIMIT $start, $eintraege_pro_seite");
while($Duelle = mysql_fetch_assoc($Query))
{
  echo'
  <tr>
    <td>'.get_user_things($Duelle['winner'], 'name').'</td>
    <td>'.$Duelle['winner_level'].'</td>
    <td>'.get_user_things($Duelle['loser'], 'name').'</td>
    <td>'.$Duelle['loser_level'].'</td>
    <td>'.$Duelle['einsatz'].'</td>
    <td><a href="index.php?site=report&what=duell&id='.$Duelle['id'].'"><img src="Images/icons/scroll.png" height="20" /></a></td>
  </tr>';
}

?>

  </tfoot>
</table>

<?php

$result = mysql_query("SELECT id FROM duelle_report WHERE schule = 0");
$menge = mysql_num_rows($result);

$wieviel_seiten = $menge / $eintraege_pro_seite;

echo'
<center>
  <strong>Seite:</strong>
  '.displayPages($Seite,$wieviel_seiten,'?site=duellstats',4,'seite').'
</center><br />';
?>
