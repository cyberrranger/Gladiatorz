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

echo"<center><a href=\"index.php?site=spielersuche\" target=\"_self\">Spielersuche</a> | 
<a href=\"index.php?site=gegnersuche\" target=\"_self\">Gegnersuche</a> |
<a href=\"index.php?site=artisuche\" target=\"_self\">Artefaktsuche</a> |
<a href=\"index.php?site=allysuche\" target=\"_self\">Schulensuche</a> |
<a href=\"index.php?site=duellsuche\" target=\"_self\">Duellsuche</a>
</center><br />";

if(!empty($_POST["suchen"]))
{
  $rw = substr($_POST["suchen"],0,strlen($_POST["suchen"])-1);
  
  echo"<center><b>Suchergebnisse:</b></center><br /><div style=\"width:300px;text-align:left;margin-left:160px;\">";
  
  $user['tstats'] = $user['staerke'] + $user['geschick'] + $user['kondition'] + $user['charisma'] + $user['inteligenz'];
  $user['tfert'] = $user['waffenkunde'] + $user['ausweichen'] + $user['taktik'] + $user['zweiwaffenkampf'] + $user['heilkunde'];
  
  $EqpWeapon = get_equipment($user[id], 'weapon');
  $EqpArmor = get_equipment($user[id], 'armor');
  $EqpShield = get_equipment($user[id], 'shield');
	
  $EqpWeapon['off'] = $EqpWeapon['off']  + (($EqpWeapon['off']/100)*$user[waffenkunde]);
  $EqpShield['off'] = $EqpShield['off']  + (($EqpShield['off']/100)*$user[zweiwaffenkampf]);
	
  $user[off] = $EqpWeapon['off'] + $EqpArmor['off'] + $EqpShield['off'];
  $user[deff] = $EqpWeapon['deff'] + $EqpArmor['deff'] + $EqpShield['deff'];
  
  $replayer = array();
  
  $sql = "SELECT * FROM user WHERE name != '$user[name]'";
  $query = mysql_query($sql);
  while($erg = mysql_fetch_assoc($query))
  {
    $statsok = false;
	$fertsok = false;
	$offok = false;
	$deffok = false;
  
    $EqpWeapon = get_equipment($erg[id], 'weapon');
    $EqpArmor = get_equipment($erg[id], 'armor');
    $EqpShield = get_equipment($erg[id], 'shield');
	
    $EqpWeapon['off'] = $EqpWeapon['off']  + (($EqpWeapon['off']/100)*$erg[waffenkunde]);
    $EqpShield['off'] = $EqpShield['off']  + (($EqpShield['off']/100)*$erg[zweiwaffenkampf]);
	
    $off = $EqpWeapon['off'] + $EqpArmor['off'] + $EqpShield['off'];
    $deff = $EqpWeapon['deff'] + $EqpArmor['deff'] + $EqpShield['deff'];
  
    $tstats = $erg['staerke'] + $erg['geschick'] + $erg['kondition'] + $erg['charisma'] + $erg['inteligenz']; 
	$tfert = $erg['waffenkunde'] + $erg['ausweichen'] + $erg['taktik'] + $erg['zweiwaffenkampf'] + $erg['heilkunde'];  
  
    if(($tstats*((100-$rw)/100)) < $user['tstats'] && $user['tstats'] < ($tstats*((100+$rw)/100)))
	{
	  $statsok = true;
	}
	
	if(($tfert*((100-$rw)/100)) < $user['tfert'] && $user['tfert'] < ($tfert*((100+$rw)/100)))
	{
	  $fertsok = true;
	}
	
	if(($off*((100-$rw)/100)) < $user['off'] && $user['off'] < ($off*((100+$rw)/100)))
	{
	  $offok = true;
	}
	
	if(($deff*((100-$rw)/100)) < $user['deff'] && $user['deff'] < ($deff*((100+$rw)/100)))
	{
	  $deffok = true;
	}
	
	if($statsok == true && $fertsok == true && $offok == true && $deffok == true)
	{
	  echo"<li><a href=index.php?site=userinfo&info=$erg[id]>$erg[name]</a></li>";
	}
  }
  echo"</div>";
}
else
{
  echo"<center>Finde ebenb�rtige Gegner!</center><br>";
  echo"<center><form name=such method=post action=index.php?site=gegnersuche>
  Reichweite&nbsp;<select name=suchen><option>50%</option><option>20%</option><option>10%</option><option>5%</option></select>
  &nbsp;<input type=submit value=Go!></form></center>";
}

?>
