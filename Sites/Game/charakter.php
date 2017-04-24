<script>
$(function() {
	//PM Sparen
	$("#savepm").click(function() {
		var result = actionRequest("action/charakter.php", 'do=savepm')
		if (result)
		{	
			$("#savepm").text('Vorhandene PM sparen (' + result.pmspar + ')')
		}
	});

	if (<?php echo $user['schlafen'];?> > <?php echo time();?>) {
		$("#savepm").button( "option", "disabled", true );
	}
	    
	//Char1 Werte update
	updatechar1('staerke');
	updatechar1('geschick');
	updatechar1('kondition');
	updatechar1('charisma');
	updatechar1('inteligenz');

	//Char2 Werte update
	updatechar2('Schlagkraft');
	updatechar2('Einstecken');
	updatechar2('Kraftprotz');
	updatechar2('Glueck');
	updatechar2('Sammler');

	//Grenze bei 50 Skills
    if (<?php echo $user['staerke'];?> == 50 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#staerke").button( "option", "disabled", true );
    }
    if (<?php echo $user['geschick'];?> == 50 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#geschick").button( "option", "disabled", true );
    }
    if (<?php echo $user['kondition'];?> == 50 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#kondition").button( "option", "disabled", true );
    }
    if (<?php echo $user['charisma'];?> == 50 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#charisma").button( "option", "disabled", true );
    }
    if (<?php echo $user['inteligenz'];?> == 50 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#inteligenz").button( "option", "disabled", true );
    }

	//Grenze bei 100 Skills
    if (<?php echo $user['Schlagkraft'];?> == 100 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#Schlagkraft").button( "option", "disabled", true );
    }
    if (<?php echo $user['Einstecken'];?> == 100 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#Einstecken").button( "option", "disabled", true );
    }
    if (<?php echo $user['Kraftprotz'];?> == 100 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#Kraftprotz").button( "option", "disabled", true );
    }
    if (<?php echo $user['Glueck'];?> == 100 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#Glueck").button( "option", "disabled", true );
    }
    if (<?php echo $user['Sammler'];?> == 100 || <?php echo $user['schlafen'];?> != 0)
    {
    	$("#Sammler").button( "option", "disabled", true );
    }

	function reloadProgress() {
	    //Progressbar anzeigen beim aufrufen der Seite
		$( "#progressbar_staerke" ).progressbar({
			value: <?php echo $user['staerke']*2;?>
		});
	
		$( "#progressbar_geschick" ).progressbar({
			value: <?php echo $user['geschick']*2;?>
		});
	
		$( "#progressbar_kondition" ).progressbar({
			value: <?php echo $user['kondition']*2;?>
		});
	
		$( "#progressbar_charisma" ).progressbar({
			value: <?php echo $user['charisma']*2;?>
		});
	
		$( "#progressbar_inteligenz" ).progressbar({
			value: <?php echo $user['inteligenz']*2;?>
		});
	
		$( "#progressbar_Schlagkraft" ).progressbar({
			value: <?php echo $user['Schlagkraft'];?>
		});
	
		$( "#progressbar_Einstecken" ).progressbar({
			value: <?php echo $user['Einstecken'];?>
		});
	
		$( "#progressbar_Kraftprotz" ).progressbar({
			value: <?php echo $user['Kraftprotz'];?>
		});
	
		$( "#progressbar_Glueck" ).progressbar({
			value: <?php echo $user['Glueck'];?>
		});
	
		$( "#progressbar_Sammler" ).progressbar({
			value: <?php echo $user['Sammler'];?>
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
$short = array();
$short['char_1']['0'] = 'Newbie Help: <b><font color=green>n&uuml;tzlich</font></b><br /><br />Ein starker Gladiator kann viel mehr Kraft in seinen Angriff stecken und deshalb sind seine Attacken sehr m&auml;chtig. Außerdem stirbt ein starker Gladiator nicht so leicht wegen ein paar Wunden, seine Lebenskraft ist wirklich enorm. (St&auml;rke erh&ouml;ht Schaden und Kraft)';
$short['char_1']['1'] = 'Newbie Help: <b><font color=darkgreen>sehr wichtig</font></b><br /><br />Dein Geschick ist sehr wichtig F&uuml;r sogut wie jeden Speziam Move. Als kleinen Nebeneffeckt erh&ouml;ht Geschick auch etwas die Kraft deines Gladiators. (H&ouml;here wahrscheinlichkeit auf Spezial Moves und erh&ouml;ht Kraft)';
$short['char_1']['2'] = 'Newbie Help: <b><font color=green>wichtig</font></b><br /><br />Ein anderes Wort f&uuml;r Kondition ist Ausdauer und welcher K&auml;mpfer kommt schon ohne Ausdauer zu Rande? Kondition sorgt vorallem für das Überleben des Gladiators im Kampf. Deine Fähigkeit einen Angriff zu blocken und deine R&uuml;stung erfolgreich einzusetzen ist fast ausschlieslich von deiner Kondition abhängig! Kondition erhöht auch die Kraft, sogar um einiges mehr als Stärke. Wer nicht auf Kondition setzt stirbt immer schneller als die anderen.';
$short['char_1']['3'] = 'Newbie Help: <b><font color=gray>interessant</font></b><br /><br />Charisma bringt dir im Kampf sogut wie garnichts. Der einzige Einfluss von Charisma auf das Kampfsystem ist das du eine gr&ouml;ßere Chance auf einen Kraftschrei hast und dem Gegner öfter Sand in die Augen wirfst. Der eigentliche Vorteil von Charisma ist das man mit hohem Charisma mehr Prestige bei Prestigekämpfen bekommt. Wer oft und gerne in der Prestigearena um Medallien kämpft könnte ein paar Punkte Charisma gut vertragen.';
$short['char_1']['4'] = 'Newbie Help: <b><font color=red>nutzlos</font></b><br /><br />Intelligenz ist f&uuml;r die meisten Arena Neulinge ziemlich nutzlos und sollte erst gesteigert werden wenn man es mit starken Gegnern aufnehmen muss. Intelligenz bestimmt Effektivität und Häufigkeit von Brainattacks (Wutschrei, Sand werfen).';

$short['char_2']['0'] = 'Schlagkraft ist die Unterfertigkeit von St&auml;rke. Wer seine Schlagkraft erh&auml;ht kann wesentlich mehr Schaden anrichten, um genau zu sein 5 Schaden pro Punkt';
$short['char_2']['1'] = 'Einstecken ist die Unterfertigkeit von Geschick. Wer mehr einstekcne kann bekommt auch gleich weniger Schaden, um genau zu sein 4 Schaden pro Punkt';
$short['char_2']['2'] = 'Kraftprotz ist die Unterfertigkeit von Kondition. Ein wahrer Kraftprotz nimmt sein Schaden hin ohne mit der Wimper zu zucken. F&uuml;r jeden ausgebauten Punkt in Kraftprotz erh&auml;lts du 50 Kraft';
$short['char_2']['3'] = 'Glück Ist die Unterfertigkeit von Charisma. Je h&ouml;her dein Gl&uuml;ck ist desto häufiger wird du auch Artefakte in der Tiergrube finden. Die Droprate steigt um 0.5% pro Punkt';
$short['char_2']['4'] = 'Sammler ist die Unterfertigkeit von Intelligenz. Ein wahrer Sammler gibt sich nicht mit einfachen Artefakten zu frieden, er will nur das Beste vom Besten. F&uuml;r jeden ausgebauten Punkt steigt die Wahrscheinlichkeit für ein Rangkampfitem um 0.33%.';

echo "<center>Hier kannst du die Eigenschaften deines Charakters verbessern...</center><br>";

echo '<center><b><u>Eigenschaften:</u></b></center><br />
<table width=480>
  <tr>
    <th>Eigenschaft</th>
	<th width=25>Stufe</td>
	<th width=202>Fortschritt</td>
	<th width=150>Trainieren</td>
  </tr>';

foreach($short['char_1'] as $key => $val)
{
	$pm_kosten = calcEcost($user[$eigenschaften[$key]]);
	$skillbar = '<div id="progressbar_'.$eigenschaften[$key].'" style="height:10px;width:250px;"></div>';

	if(($user['powmod'] + $user['pmspar']) >= $pm_kosten && $user[$eigenschaften[$key]] < $GLOBALS['conf']['konst']['max_eigenschaften']) {
		$button = "<button title=\"$pm_kosten PM\" style='height:40px;width:40px;' id='".$eigenschaften[$key]."'>+</button>";
	} else {
		$button = "<button title=\"Kostet $pm_kosten PowMod\" style='height:40px;width:40px;' id='".$eigenschaften[$key]."'>+</button>";
	}
	

	echo "<tr height=30>
		<td class=bordered_table align=center>$eigenschaften_text[$key]<a href=\"#\" target=\"_self\"
		onmouseover=\"this.T_TITLE='$eigenschaften_text[$key]';return escape('$val')\"><span class=\"help\">(?)</span></a></td>
		<td class=bordered_table align=center id='_".$eigenschaften[$key]."'>".$user[$eigenschaften[$key]]."</td>
		<td class=bordered_table style=text-align:left;>$skillbar</td>
		<td class=bordered_table align=center>".$button."</td>
		</tr>";
}


echo '</table><br /><center><b><u>Untereigenschaften:</u></b></center><br />
	<table width=480>
	<tr>
	    <th>Eigenschaft</th>
		<th width=25>Stufe</td>
		<th width=202>Fortschritt</td>
		<th width=150>Trainieren</td>
	  </tr>';

foreach($short['char_2'] as $key => $val)
{
	$cost = calcUEcost($user[$ueigenschaften[$key]], $ueigenschaften[$key]);
	$gold = $cost['gold'];
	$pm = $cost['pm'];
	$skillbar = '<div id="progressbar_'.$ueigenschaften[$key].'" style="height:10px;width:250px;"></div>';
	
	if(($user['powmod'] + $User['pmspar']) >= $pm && $user['gold'] >= $gold)
	{
		$button = "<button title='$pm PM<br /> $gold Gold' style='height:40px;width:40px;' id='".$ueigenschaften[$key]."'>+</button>";
	} else {
		$button = "<button tooltip=\"dfsdf\" title=\"Kostet $pm PowMod<br /> $gold Gold\" style='height:40px;width:40px;' id='".$ueigenschaften[$key]."'>+</button>";
	}
	
	
   echo"
  <tr height=30>
    <td class=bordered_table align=center>$ueigenschaften[$key]<a href=\"#\" target=\"_self\"
	onmouseover=\"this.T_TITLE='$ueigenschaften[$key]';return escape('$val')\"><span class=\"help\">(?)</span></a></td>
	<td class=bordered_table align=center id='_".$ueigenschaften[$key]."'>".$user[$ueigenschaften[$key]]."</td>
	<td class=bordered_table style=text-align:left;>$skillbar</td>
	<td class=bordered_table align=center>$button</td>
  </tr>";
}

echo"</table>";

echo "<center><br /><br /><button title='Hiermit werden alle deine derzeit vorhanden PM gespart, diese gesparten PMs können nur noch zum Skillen deines Charakters verwendet werde.' style='height:35px;width:400px;' id='savepm'>Vorhandene PM sparen (".$User['pmspar'].")</button></center>";
?>
