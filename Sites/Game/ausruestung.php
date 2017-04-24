<center>
  <a href="index.php?site=eigenschaften" target="_self">Eigenschaften</a> | 
  <a href="index.php?site=ausruestung" target="_self">Ausr�stung</a>
</center><br />

<center>Hier siehst du die Ausr�stung deines Charakters...</center><br />

<table width="500">
  <tr>
    <th width="100"><b>Art</b></th>
    <th colspan="2"><b>Ausr�stung</b></th>
	<th width="30" style="padding:0px;"><img src="Images/OldStuff/weapon.jpg" /></th>
	<th width="30" style="padding:0px;"><img src="Images/OldStuff/armor.jpg" /></th>
  </tr>

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

$ItemTitle = array(
'weapon',
'shield',
'head',
'shoulder',
'armor',
'lowbody',
'cape',
'belt',
'gloves',
'foots');

$ItemName = array(
'Waffen',
'Schild/ZW',
'Helme',
'Schulterr�stung',
'K�rperr�stung',
'Beinr�stung',
'Umh�nge',
'G�rtel',
'Handschuhe',
'Schuhe/Stiefel');

for($i=0;$i<10;$i++)
{
  $Item = getEquipment($User['id'],$ItemTitle[$i]);

  echo'
  <tr height="32">
    <td width="100" style="font-size:11px;">'.$ItemName[$i].'</td>
    <td width="30" style="padding:0px;"><img src="Images/Items/'.$Item['pic'].'" /></td>
    <td>'.$Item['name'].'</td>
    <td width="25" style="font-size:11px;">'.$Item['off'].'</td>
    <td width="25" style="font-size:11px;">'.$Item['deff'].'</td>
  </tr>';
  
  $AllOff += $Item['off'];
  $AllDef += $Item['deff'];
}

echo'
<tr bgcolor="darkred">
  <td width="100"><strong>gesamt</strong></td>
  <td colspan="2"></td>
  <td width="25" style="font-size:11px;"><strong>'.$AllOff.'</strong></td>
  <td width="25" style="font-size:11px;"><strong>'.$AllDef.'</strong></td>
</tr>';

?>

</table>
