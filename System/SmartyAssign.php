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

// Smarty Template Engine einbinden
$Smarty = new Smarty();

$Smarty->template_dir = 'Templates';
$Smarty->compile_dir = 'Smarty/templates_c';
$Smarty->cache_dir = 'Smarty/cache';
$Smarty->config_dir = 'Smarty/configs';


// Template Assignments
$Smarty->assign('Title', 'Gladiatorgame.de - Gladiatorz das Browsergame mit den antiken Recken der Arena!');
$Smarty->assign('PlayerOnline',countPlayer());
$Smarty->assign('MetaDate',date("Y-m-d",time()));
$Smarty->assign('User',$user);
$Smarty->assign('Msg',$Messages[0]);

// ShoutBox
require('Scripts/shoutbox.php');

// Tooltips
if($Template == 'game')
{
  foreach($Tooltips as $Key => $Text)
  {
    $Headline = str_replace('_',' ',substr($Key,3));
    $Tip = 'onmouseover="this.T_TITLE=\''.$Headline.'\';return escape(\''.$Text.'\');"';
    $Smarty->assign($Key,$Tip);
  }
}


// Variablen
$Smarty->assign('CPlayer',countPlayer());
$Smarty->assign('username',$User['name']);

// Templates anzeigen
$Smarty->display($Template.'.tpl');

// PHP Code includen (keine errors ausgeben?!?! FIX?!?!)
error_reporting(E_ALL^E_NOTICE);
require($SP);

if($Template == 'game')
{
	$rangupdate = $levelupdate = false;
	$NoodForLvl = neededEXP($User['level']);
		
	if($User['exp'] >= $NoodForLvl)
	{
		$new_exp = $User['exp']-$NoodForLvl;
		
		$User['level']++;
		$Update = mysql_query("UPDATE ".TAB_USER." SET level='".$User['level']."',exp='".$new_exp."', arenarang=arenarang+2 WHERE id='".$User['id']."' LIMIT 1");
		
		$levelupdate = true;
	}

	// Rang des User festlegen
	if(getRangName($User['level'], true) != $User['rang'])
	{
		$User['rang'] = getRangName($User['level'], true);
		$Update = mysql_query("UPDATE ".TAB_USER." Set rang='".$User['rang']."', rankplace='21' WHERE id='".$User['id']."' LIMIT 1");
		
		$rangupdate = true;
		
	}
	
	if (!$rangupdate && $levelupdate)
	{
		echo '<div id="dialog_level" style="display:none" title="Level up!">
			<p>Dein Gladiator hat Level '.$User['level'].' erreicht!</p>
		</div>';
	}
	
	if ($rangupdate && $levelupdate)
	{
		echo '<div id="dialog_rang" style="display:none" title="Rang aufgestiegen!">
			<p>Dein Gladiator hat mit dem Level '.$User['level'].' den Rang '.getRangName($User['level']).' erreicht!<br /> Vergess nicht in der Rangarena deinen aktuellen Platz zu erhöhen.</p>
		</div>';
	}
}

if($Template == 'game') @include('Scripts/actstats.php'); 

$Smarty->display($Template.'_footer.tpl');

?>
