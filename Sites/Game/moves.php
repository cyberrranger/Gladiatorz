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
$query = mysql_query( "SELECT * FROM moves WHERE uid=".$User['id']." LIMIT 1");
$sm = mysql_fetch_assoc($query);
?>
<script>
$(function() {

	updatemove('kraftschlag');
	updatemove('wutschrei');
	updatemove('armor');
	updatemove('kritischer_schlag');
	updatemove('wundhieb');
	updatemove('kraftschrei');
	updatemove('block');
	updatemove('taeuschen');
	updatemove('koerperteil_abschlagen');
	updatemove('sand_werfen');
	updatemove('ausweichen');
	updatemove('todesschlag');
	updatemove('konter');
	updatemove('berserker');
	updatemove('anti_def');
	
	//Grenze bei 100 Skills
    if (<?php echo $sm['kraftschlag'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#kraftschlag").button( "option", "disabled", true );
    }
    if (<?php echo $sm['wutschrei'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#wutschrei").button( "option", "disabled", true );
    }
    if (<?php echo $sm['armor'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#armor").button( "option", "disabled", true );
    }
    if (<?php echo $sm['kritischer_schlag'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#kritischer_schlag").button( "option", "disabled", true );
    }
    if (<?php echo $sm['wundhieb'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#wundhieb").button( "option", "disabled", true );
    }
    if (<?php echo $sm['kraftschrei'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#kraftschrei").button( "option", "disabled", true );
    }
    if (<?php echo $sm['block'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#block").button( "option", "disabled", true );
    }
    if (<?php echo $sm['taeuschen'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#taeuschen").button( "option", "disabled", true );
    }
    if (<?php echo $sm['koerperteil_abschlagen'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#koerperteil_abschlagen").button( "option", "disabled", true );
    }
    if (<?php echo $sm['sand_werfen'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#sand_werfen").button( "option", "disabled", true );
    }
    if (<?php echo $sm['ausweichen'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#ausweichen").button( "option", "disabled", true );
    }
    if (<?php echo $sm['todesschlag'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#todesschlag").button( "option", "disabled", true );
    }
    if (<?php echo $sm['konter'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#konter").button( "option", "disabled", true );
    }
    if (<?php echo $sm['berserker'];?> == 100 || <?php echo $user['schlafen'];?>  > <?php echo time();?>)
    {
    	$("#berserker").button( "option", "disabled", true );
    }
    if (<?php echo $sm['anti_def'];?> == 100 || <?php echo $user['schlafen'];?> > <?php echo time();?>)
    {
    	$("#anti_def").button( "option", "disabled", true );
    }

	function reloadProgress() {
	    //Progressbar anzeigen beim aufrufen der Seite
		$( "#progressbar_kraftschlag" ).progressbar({
			value: <?php echo $sm['kraftschlag'];?>
		});
		$( "#progressbar_wutschrei" ).progressbar({
			value: <?php echo $sm['wutschrei'];?>
		});
		$( "#progressbar_armor" ).progressbar({
			value: <?php echo $sm['armor'];?>
		});
		$( "#progressbar_kritischer_schlag" ).progressbar({
			value: <?php echo $sm['kritischer_schlag'];?>
		});
		$( "#progressbar_wundhieb" ).progressbar({
			value: <?php echo $sm['wundhieb'];?>
		});
		$( "#progressbar_kraftschrei" ).progressbar({
			value: <?php echo $sm['kraftschrei'];?>
		});
		$( "#progressbar_block" ).progressbar({
			value: <?php echo $sm['block'];?>
		});
		$( "#progressbar_taeuschen" ).progressbar({
			value: <?php echo $sm['taeuschen'];?>
		});
		$( "#progressbar_koerperteil_abschlagen" ).progressbar({
			value: <?php echo $sm['koerperteil_abschlagen'];?>
		});
		$( "#progressbar_sand_werfen" ).progressbar({
			value: <?php echo $sm['sand_werfen'];?>
		});
		$( "#progressbar_ausweichen" ).progressbar({
			value: <?php echo $sm['ausweichen'];?>
		});
		$( "#progressbar_todesschlag" ).progressbar({
			value: <?php echo $sm['todesschlag'];?>
		});
		$( "#progressbar_konter" ).progressbar({
			value: <?php echo $sm['konter'];?>
		});
		$( "#progressbar_berserker" ).progressbar({
			value: <?php echo $sm['berserker'];?>
		});
		$( "#progressbar_anti_def" ).progressbar({
			value: <?php echo $sm['anti_def'];?>
		});
	}
	reloadProgress();
});
</script>

<?php
$countall = $sm['kraftschlag']
+$sm['wutschrei']
+$sm['armor']
+$sm['kritischer_schlag']
+$sm['wundhieb']
+$sm['kraftschrei']
+$sm['block']
+$sm['taeuschen']
+$sm['koerperteil_abschlagen']
+$sm['sand_werfen']
+$sm['ausweichen']
+$sm['todesschlag']
+$sm['konter']
+$sm['berserker']
+$sm['anti_def'];

$countmax = $user['level']*2;
$count = $countmax - $countall;

$short = array();
$short['char']['0'] = "50% mehr Schaden";
$short['char']['1'] = "Vor lauter Wut dringt ein kräftiger Schrei aus dir, deine Offensiv- und Defensivwerte steigen.";
$short['char']['2'] = "33% weniger Schaden";
$short['char']['3'] = "150% mehr Schaden";
$short['char']['4'] = "100% mehr Schaden + Bluten";
$short['char']['5'] = "Bündle mithilfe deiner mentalen Kräfte all deinen Schmerz in einen Schrei und lass ihn heraus. Du wirst sehen, dass es dir danach körperlich wieder besser geht (stellt deine Kraft wieder her).";
$short['char']['6'] = "66% weniger Schaden";
$short['char']['7'] = "Täusche deinen Gegner mit unsinnigen Bewegungen, so dass er dich nicht mehr voll treffen kann (75% weniger Schaden) und Überrasche ihn mit einem direkten Gegenschlag.";
$short['char']['8'] = "300% mehr Schaden";
$short['char']['9'] = "Zu den fiesesten Tricks in der Arena gehört das gezielte werfen mit Sand in die Augen deines Gegners. Dies ist nicht besonders nobel aber der Zweck heiligt die Mittel.";
$short['char']['10'] = "Weiche dem Schlag deines Gegners geschickt aus.";
$short['char']['11'] = "Der ultimative Angriff; es gab noch keinen Gegner der diesen Überlebt hat.";
$short['char']['12'] = "Blocke den Angriff deines Gegners (66% weniger Schaden) und treff ihn in einem direkten Gegenangriff mit einem Kritischen Schlag (150% mehr Schaden).";
$short['char']['13'] = "Raste beim Kraftschlag deines Gegners so aus, dass du auf ihn stürmst und auf ihn eindrischt.";
$short['char']['14'] = "Durch intensive Studien hast du erkannt wo alle Schwachstellen in der Rüstung sind. Nutze diese und greif deinen gegner ohne Abzug seiner Def an.";

$short['check']['0'] = true;
$short['check']['1'] = true;
$short['check']['2'] = true;

if ($user['level'] >= 30) {
	if($sm['kraftschlag'] > 19)
	{
		$short['check']['3'] = true;
	}
	$short['check']['4'] = true;
	if($sm['wutschrei'] > 29)
	{
		$short['check']['5'] = true;
	}
	if($sm['armor'] > 29)
	{
		$short['check']['6'] = true;
		$short['check']['7'] = true;
	}
	
	$short['check']['8'] = false;
	$short['check']['9'] = false;
	$short['check']['10'] = false;
	$short['check']['11'] = false;
	$short['check']['12'] = false;
	$short['check']['13'] = false;
	$short['check']['14'] = false;
} 

if ($user['level'] >= 60) {
	if($sm['kritischer_schlag'] > 19 && $sm['wundhieb'] > 39)
	{
		$short['check']['8'] = true;
	}
	if($sm['kraftschrei'] > 19)
	{
		$short['check']['9'] = true;
	}
	if($sm['block'] > 49)
	{
		$short['check']['10'] = true;
	}
	$short['check']['11'] = false;
	$short['check']['12'] = false;
	$short['check']['13'] = false;
	$short['check']['14'] = false;	
} 

if ($user['level'] >= 90) {
	if($sm['koerperteil_abschlagen'] > 29)
	{
		$short['check']['11'] = true;
	}
	if($sm['ausweichen'] > 49)
	{
		$short['check']['12'] = true;
	}
	if($sm['taeuschen'] > 49)
	{
		$short['check']['13'] = true;
	}
	if($sm['sand_werfen'] > 29)
	{
		$short['check']['14'] = true;
	}
}

echo '<div class="center">Du kannst noch <strong><div id="smskillpoints">'.$count.'</div> Spezial Move-Punkte</strong> benutzen, um zu trainieren!</div><br />';

		
echo '<center><b><u>Spezial Moves:</u></b></center><br />
<table width=480>
  <tr>
    <th>Eigenschaft</th>
	<th width=25>Stufe</td>
	<th width=202>Fortschritt</td>
	<th width=150>Trainieren</td>
  </tr>';

foreach($short['char'] as $key => $val)
{
	if (!$short['check'][$key]) {
		continue;
	}
	$skillbar = '<div id="progressbar_'.$moves[$key].'" style="height:10px;width:250px;"></div>';

	if($count > 0) {
		$button = "<button style='height:40px;width:40px;' id='".$moves[$key]."'>+</button>";
		$bool = false;
	} else {
		$button = "<button style='height:40px;width:40px;' id='".$moves[$key]."'>+</button>";
		$bool = true;
	}

	echo "<tr height=30>
		<td class=bordered_table align=center>$moves_text[$key]<a href=\"#\" target=\"_self\"
		onmouseover=\"this.T_TITLE='$moves_text[$key]';return escape('$val')\"><span class=\"help\">(?)</span></a></td>
		<td class=bordered_table align=center id='_".$moves[$key]."'>".$sm[$moves[$key]]."</td>
		<td class=bordered_table style=text-align:left;>$skillbar</td>
		<td class=bordered_table align=center>".$button."</td>
		</tr>";
	
	if ($bool) {
		echo "<script>$(function() { $(\"#".$moves[$key]."\").button( \"option\", \"disabled\", true );});</script>";	
	}
}

echo"</table>";
?>
<div class="center">
<a href="http://wiki.gladiatorgame.de/Spezial+Moves.html" target="_blank">Skillbaum</a>
</div>
