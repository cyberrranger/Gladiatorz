<script>
$(function() {
	updatetrain('waffenkunde');
	updatetrain('ausweichen');
	updatetrain('taktik');
	updatetrain('zweiwaffenkampf');
	updatetrain('heilkunde');
	updatetrain('schildkunde');

	//Grenze bei 100 Skills
    if (<?php echo $user['waffenkunde'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#waffenkunde").button( "option", "disabled", true );
    }
    if (<?php echo $user['ausweichen'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#ausweichen").button( "option", "disabled", true );
    }
    if (<?php echo $user['taktik'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#taktik").button( "option", "disabled", true );
    }
    if (<?php echo $user['zweiwaffenkampf'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>0)
    {
    	$("#zweiwaffenkampf").button( "option", "disabled", true );
    }
    if (<?php echo $user['heilkunde'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#heilkunde").button( "option", "disabled", true );
    }
    if (<?php echo $user['schildkunde'];?> == 50 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#schildkunde").button( "option", "disabled", true );
    }

	function reloadProgress() {
	    //Progressbar anzeigen beim aufrufen der Seite
		$( "#progressbar_waffenkunde" ).progressbar({
			value: <?php echo $user['waffenkunde']*2;?>
		});
	
		$( "#progressbar_ausweichen" ).progressbar({
			value: <?php echo $user['ausweichen']*2;?>
		});
	
		$( "#progressbar_taktik" ).progressbar({
			value: <?php echo $user['taktik']*2;?>
		});
	
		$( "#progressbar_zweiwaffenkampf" ).progressbar({
			value: <?php echo $user['zweiwaffenkampf']*2;?>
		});
	
		$( "#progressbar_heilkunde" ).progressbar({
			value: <?php echo $user['heilkunde']*2;?>
		});

		$( "#progressbar_schildkunde" ).progressbar({
			value: <?php echo $user['schildkunde']*2;?>
		});
	}
	reloadProgress();
});
</script>
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

//Tooltips
$short = array();
$short['char_1']['0'] = 'Newbie Help: <b><font color=green>wichtig</font></b><br /><br />Erhöht deinen Schaden mit einer Hauptwaffe.';
$short['char_1']['1'] = 'Newbie Help: <b><font color=green>nützlich</font></b><br /><br />Verbessert jeden defensiven Spezialmove.';
$short['char_1']['2'] = 'Newbie Help: <b><font color=darkgreen>sehr wichtig</font></b><br /><br />Verbessert jeden offensiven Spezialmove.';
$short['char_1']['3'] = 'Newbie Help: <b><font color=gray>interessant</font></b><br /><br />Erhöht deinen Schaden mit einer Zweitwaffe.';
$short['char_1']['4'] = 'Newbie Help: <b><font color=gray>hilfreich</font></b><br /><br />Verbessert jeden Support Spezialmove.<br />Erhöht deine Kraft und deren Regeneration.';
$short['char_1']['5'] = 'Newbie Help: <b><font color=gray>interessant</font></b><br /><br />Erhöht die Verteidigung deines Schildes.';

$kosten = calcTraincost($user, $schule);
$count_skill = $user[waffenkunde] + $user[ausweichen] + $user[taktik] + $user[zweiwaffenkampf] + $user[heilkunde] + $user[schildkunde];

echo "<center>Hier kannst du deine Fähigkeiten trainieren...</center><br>";

echo'<center style="font-size:10px;"><b>Maximale Fertigkeiten:</b> '.$count_skill.'/'.$kosten['max'].'</center><br />';

echo '</table><br /><center><b><u>Fähigkeiten:</u></b></center><br />
	<table width=480>
	<tr>
	    <th>Fähigkeit</th>
		<th width=25>Stufe</td>
		<th width=202>Fortschritt</td>
		<th width=150>Trainieren</td>
	  </tr>';

foreach($short['char_1'] as $key => $val)
{
	$skillbar = '<div id="progressbar_'.$faehigkeiten[$key].'" style="height:10px;width:250px;"></div>';
	$gold = $kosten['gold'];
	$cost = $kosten['pm'];
	if($user['powmod'] >= $cost && $user['gold'] >= $gold && $user[$faehigkeiten[$key]] < $GLOBALS['conf']['konst']['max_faehigkeiten']) {
		$button = "<button title=\"$cost PM $gold Gold\" style='height:40px;width:40px;' id='".$faehigkeiten[$key]."'>+</button>";
	} else {
		$button = "<button title=\"$cost PM $gold Gold\" style='height:40px;width:40px;' id='".$faehigkeiten[$key]."'>+</button>";
	}

	echo "<tr height=30>
		<td class=bordered_table align=center>$faehigkeiten_text[$key]<a href=\"#\" target=\"_self\"
		onmouseover=\"this.T_TITLE='$faehigkeiten_text[$key]';return escape('$val')\"><span class=\"help\">(?)</span></a></td>
		<td class=bordered_table align=center id='_".$faehigkeiten[$key]."'>".$user[$faehigkeiten[$key]]."</td>
		<td class=bordered_table style=text-align:left;>$skillbar</td>
		<td class=bordered_table align=center>".$button."</td>
		</tr>";
}
echo"</table>";
?>
