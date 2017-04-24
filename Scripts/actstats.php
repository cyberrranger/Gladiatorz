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
?>

<script language="javascript" type="text/javascript">
<!--//

var i=0,e=5;

var pm = <?php echo $User['powmod']; ?>;
var hp = <?php echo $User['kraft']; ?>;
var max_hp = <?php echo $User['max_kraft']; ?>;
var gold = <?php echo $User['gold']; ?>;
var exp = <?php echo $User['exp']; ?>;
var level = <?php echo $User['level']; ?>;

var pm_bowl_length = 0;
var hp_bowl_length = 0;
  
if(pm > <?php echo $GLOBALS['conf']['konst']['max_pm']; ?>)
{
  pm = <?php echo $GLOBALS['conf']['konst']['max_pm']; ?>;
}

for(i=0;i<=level;i++)
{
  e = e*(1.154-(0.0008*(level)));
}

e = <?php echo neededEXP($User['level']); ?>;
  
function Refresh()
{
  document.getElementById("pm").firstChild.nodeValue = pm;
  document.getElementById("hp").firstChild.nodeValue = hp;

  var _calc = <?php echo $GLOBALS['conf']['konst']['max_pm']; ?>*10;
  pm_bowl_length = Math.round(135*((pm/0.1)/_calc));
  hp_bowl_length = Math.round(92*((hp/(max_hp/100))/100));
  
  document.getElementById("show_pm").style.height = pm_bowl_length;
  document.getElementById("show_hp").style.height = hp_bowl_length;
  
  document.getElementById("show_pm").style.marginTop = (154-pm_bowl_length);
  document.getElementById("show_hp").style.marginTop = (133-hp_bowl_length);
  
  document.getElementById("gold").firstChild.nodeValue = gold;
  document.getElementById("level").firstChild.nodeValue = level;
  
  document.getElementById("exp_bar").style.width = Math.round(exp/(e/376))+'px';
  
  if(pm == 0)
  {
    document.getElementById("show_pm").style.visibility = 'hidden';
  }
}
  
function newHP()
{
  if(hp == max_hp)
  {
    Refresh();
    clearInterval(Interval);
	return true;
  }

  hp += 1;
  Refresh();
}
  
Refresh();
  
hp_reg = 500/<?php echo $user['hp_reg']; ?>;
  
var Interval = setInterval('newHP()',hp_reg);

//-->
</script>
