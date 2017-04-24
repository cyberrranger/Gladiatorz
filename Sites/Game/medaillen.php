<script>
$(document).ready(function() {
    $("#dialogchangetitel").dialog({
    	bgiframe: true,
    	autoOpen: false,
    	height: 300,
    	modal: true,
    	buttons: {
    		OK: function() {
			var newtitle = $("#newtitel").val();
			
			var result = actionRequest("action/medaillen.php", 'do=titel&newtitle=' + newtitle)
				
				if (result.count)
				{	
					$("#count").text(result.count)
					$("#_newtitle").text(result.title) 
				}
    		  $(this).dialog('close');
    		},
    		Abbrechen: function() {
    			$(this).dialog('close');
    		}
    	}
    });
    $('#changetitel').click(function() {
        if (<?php echo $user["medallien"]; ?> >= 50)
        {
        	$('#dialogchangetitel').dialog('open');
        } else
        {
			alert('Nicht Genügend Medallien, du benötigst 50!')
        }
    	
    });
    
	$("#clear_moves").click(function() {
		if (<?php echo $user["medallien"]; ?> >= 100)
        {
			var result = actionRequest("action/medaillen.php", 'do=sm')
			if (result.count)
			{	
				$("#count").text(result.count) 
			}
        } else
        {
			alert('Nicht Genügend Medallien, du benötigst 100!')
        }
        
		
    });

	$("#multfights").click(function() {
			if (<?php echo $user["medallien"]; ?> >= 35)
	        {
				var result = actionRequest("action/medaillen.php", 'do=multfights')
				if (result.count)
				{	
					$("#count").text(result.count) 
					$("#multfights").text(result.buttonText) 
				}
	        } else
	        {
				alert('Nicht Genügend Medallien, du benötigst 35!')
	        }
    });

	$("#ue1").click(function() {
		var result = actionRequest("action/medaillen.php", 'do=ue&value=1')
		if (result.count)
		{	
			$("#countQIC").text(result.count) 
			$("#ue1").text(result.buttonText) 
		} else if (result.errorcode) {
			alert('Nicht Genügend QIC!')
		}
	});

	$("#ue2").click(function() {
		var result = actionRequest("action/medaillen.php", 'do=ue&value=2')
		if (result.count)
		{	
			$("#countQIC").text(result.count) 
			$("#ue2").text(result.buttonText) 
		} else if (result.errorcode) {
			alert('Nicht Genügend QIC!')
		}
	});

	$("#ue3").click(function() {
		var result = actionRequest("action/medaillen.php", 'do=ue&value=3')
		if (result.count)
		{	
			$("#countQIC").text(result.count) 
			$("#ue3").text(result.buttonText) 
		} else if (result.errorcode) {
			alert('Nicht Genügend QIC!')
		}
	});

	$("#ue4").click(function() {
		var result = actionRequest("action/medaillen.php", 'do=ue&value=4')
		if (result.count)
		{	
			$("#countQIC").text(result.count) 
			$("#ue4").text(result.buttonText) 
		} else if (result.errorcode) {
			alert('Nicht Genügend QIC!')
		}
	});

	$("#ue5").click(function() {
		var result = actionRequest("action/medaillen.php", 'do=ue&value=5')
		if (result.count)
		{	
			$("#countQIC").text(result.count) 
			$("#ue5").text(result.buttonText) 
		} else if (result.errorcode) {
			alert('Nicht Genügend QIC!')
		}
	});

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
echo '<div id="dialogchangetitel" style="display:none" title="Titel ändern">
		<p>Willst du deinen Titel wirklich ändern?</p>
		<form action="index.php?site=medaillen&do=titel" method="post">
			<input id="newtitel" name="newtitel" type="text" value="'.$user["title"].'" />
		</form>
	</div>
<center>';


echo'Du hast bereits <strong id="count">'.$User['medallien'].' </strong> Medaillen gesammelt.<br /><br />';

echo '<div class="tooltip" id="medallien_tool_1">';
echo'<p><strong><u>Eigener Titel (50 Medaillen)</u></strong><br />';
echo'Für nur 50 Medaillen kannst du dir einen Titel frei auswählen, der an verschiedenen Stellen des Spiels (z. B. im Forum und im Profil) angezeigt wird. Der Titel hebt dich optisch von deinen Mitspielern ab, ideal für alle, die gerne mal ein bisschen prahlen!</p>';
echo '</div>';

echo '<div class="tooltip" id="medallien_tool_2">';
echo'<p><strong><u>Spezial Moves zurücksetzen (100 Medaillen)</u></strong>';
echo'<br>Du hast dich total verskillt oder willst mal ein paar andere Spezial Moves ausprobieren?! Für 100 Medaillen werden alle deine Spezial Moves wieder zurück gesetzt und du kannst deine Punkte neu verteilen.<br /></p>';
echo '</div>';

if($Settings['multfights'] > time())
{
	echo '<div class="tooltip" id="medallien_tool_3">';
	echo'<p><strong><u>Mehrfachkämpfe (35 Medaillen)</u></strong><br />';
	echo'<br>Noch aktiviert bis zum '.date('d.m',$Settings['multfights']).'</p>';
	echo '</div>';
} else 
{
	echo '<div class="tooltip" id="medallien_tool_3">';
	echo'<p><strong><u>Mehrfachkämpfe (35 Medaillen)</u></strong><br />';
	echo'<br>Es ist anstrengend in der Tiergrube jeden Kampf einzeln zu machen, eine Lösung dafür sind die Mehrfachkämpfe! Statt nur einem Kampf pro Klick, machst du eine Woche lang gleich drei Kämpfe und hast mehr Zeit um beispielsweise auf dem Basar zu shoppen. Außerdem gibt es nach jeden Kampf einen kleinen Kraftbonus.</p>';
	echo '</div>';	
}
echo '<button class="changetitel" id="changetitel" onmouseout="hideTooltip();" onmouseover="initTooltip(\'medallien_tool_1\');">Titel ändern</button>';
echo "<br /><br />";
echo '<button class="clear_moves" id="clear_moves" onmouseout="hideTooltip();" onmouseover="initTooltip(\'medallien_tool_2\');">Spezial Moves zurücksetzen</button>';
echo "<br /><br />";

if($Settings['multfights'] < time())
{
	echo '<button class="multfights" id="multfights" onmouseout="hideTooltip();" onmouseover="initTooltip(\'medallien_tool_3\');">Mehrfachkämpfe aktivieren</button>';
} else 
{
	echo '<button disabled="1" class="multfights" id="multfights" onmouseout="hideTooltip();" onmouseover="initTooltip(\'medallien_tool_3\');">Mehrfachkämpfe sind aktiv ('.date('d.m',$Settings['multfights']).')</button>';
}


//Ab hier beginnt das eintauschen von QIC
echo'<br><br><HR NOSHADE><br><br>';


echo'Du hast bereits <strong id="countQIC">'.$User['QIC'].' </strong> QIC gesammelt.<br /><br />';

echo'<p><strong><u>Untereigenschaft kaufen</u></strong>';
echo "<br />";
echo '<button class="ue1" id="ue1">Schlagkraft +1 ('.($User['Schlagkraft'] + 10).' QIC)</button>';
echo "<br /><br />";
echo '<button class="ue2" id="ue2">Einstecken +1 ('.($User['Einstecken'] + 10).' QIC)</button>';
echo "<br /><br />";
echo '<button class="ue3" id="ue3">Kraftprotz +1 ('.($User['Kraftprotz'] + 10).' QIC)</button>';
echo "<br /><br />";
echo '<button class="ue4" id="ue4">Glück +1 ('.($User['Glueck'] + 10).' QIC)</button>';
echo "<br /><br />";
echo '<button class="ue5" id="ue5">Sammler +1 ('.($User['Sammler'] + 10).' QIC)</button>';
echo "<br /><br />";


$itemfaktor = getRangName($User['level'], false, true);

switch ($User['rankplace'])
{
	case "1": $itemfaktor2 = "26"; break;
	case "2": $itemfaktor2 = "24"; break;
	case "3": $itemfaktor2 = "22"; break;
	case "4": $itemfaktor2 = "21"; break;
	case "5": $itemfaktor2 = "20"; break;
	case "6": $itemfaktor2 = "19"; break;
	case "7": $itemfaktor2 = "18"; break;
	case "8": $itemfaktor2 = "17"; break;
	case "9": $itemfaktor2 = "16"; break;
	case "10": $itemfaktor2 = "15"; break;
	case "11": $itemfaktor2 = "14"; break;
	case "12": $itemfaktor2 = "13"; break;
	case "13": $itemfaktor2 = "12"; break;
	case "14": $itemfaktor2 = "11"; break;
	case "15": $itemfaktor2 = "10"; break;
	case "16": $itemfaktor2 = "9"; break;
	case "17": $itemfaktor2 = "8"; break;
	case "18": $itemfaktor2 = "7"; break;
	case "19": $itemfaktor2 = "6"; break;
	case "20": $itemfaktor2 = "5"; break;
	default: $itemfaktor2 = "0"; break;
}


if($_GET['do'] == 'Flamberge')
{
	$queryQIC = "SELECT name, COUNT(name) FROM ausruestung WHERE name LIKE '%Quest-Item-Coupon%' AND user_id='$User[id]' AND art='itemall' AND ort='inventar' GROUP BY name";
	$resultQIC = mysql_query($queryQIC) or die(mysql_error());
	$QIC = mysql_fetch_array($resultQIC);

	if ($QIC['COUNT(name)'] >= 25)
	{
		$zufallMax2 = round($User['level'] + $itemfaktor + $itemfaktor2);
		$Artefakt['off'] = round((4 * (($zufallMax2/100)+1)) * rand(95,99));
		$Artefakt['deff'] = 0;
		$Artefakt['name'] = $User['name'].'`s Flamberge';
		$Artefakt['wert'] = 1000000;

		mysql_update("ausruestung", "user_id = '4', ort = 'inventar'", "user_id = '$User[id]' AND ort = 'inventar' AND name = 'Quest-Item-Coupon' LIMIT 25");
		$SqlInsert = "INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,preis,dauer,pic,ZHW,klasse,rang) VALUES ('$user[id]','$Artefakt[name]','weapon','$Artefakt[off]','$Artefakt[deff]','50','0','0','0','0','inventar','$Artefakt[wert]','0','0','flamberge.gif','1','5','$user[rang]')";
		$tr = mysql_query($SqlInsert);

		echo'<br />Du hast dir eine Flamberge gekauft <em>('.$Artefakt['name'].', Off: '.$Artefakt['off'].')</em>!';
	}
	else
	{
		echo"Du hast leider nicht genügend QIC in deinem Inventar";
	}
}

if($_GET['do'] == 'Stahlschwert')
{
	$queryQIC = "SELECT name, COUNT(name) FROM ausruestung WHERE name LIKE '%Quest-Item-Coupon%' AND user_id='$User[id]' AND art='itemall' AND ort='inventar' GROUP BY name";
	$resultQIC = mysql_query($queryQIC) or die(mysql_error());
	$QIC = mysql_fetch_array($resultQIC);

	if ($QIC['COUNT(name)'] >= 20)
	{
		$zufallMax2 = round($User['level'] + $itemfaktor + $itemfaktor2);
		$Artefakt['off'] = round((2.6 * (($zufallMax2/100)+1)) * rand(95,99));
		$Artefakt['deff'] = 0;
		$Artefakt['name'] = $User['name'].'`s Stahlschwert';
		$Artefakt['wert'] = 1000000;

		mysql_update("ausruestung", "user_id = '4', ort = 'inventar'", "user_id = '$User[id]' AND ort = 'inventar' AND name = 'Quest-Item-Coupon' LIMIT 20");
		$SqlInsert = "INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,preis,dauer,pic,ZHW,klasse,rang) VALUES ('$user[id]','$Artefakt[name]','weapon','$Artefakt[off]','$Artefakt[deff]','50','0','0','0','0','inventar','$Artefakt[wert]','0','0','stahlschwert.gif','0','5','$user[rang]')";
		$tr = mysql_query($SqlInsert);

		echo'<br />Du hast dir ein Stahlschwert gekauft <em>('.$Artefakt['name'].', Off: '.$Artefakt['off'].')</em>!';
	}
	else
	{
		echo"Du hast leider nicht genügend QIC in deinem Inventar";
	}
}

if($_GET['do'] == 'Kurzschwert')
{
	$queryQIC = "SELECT name, COUNT(name) FROM ausruestung WHERE name LIKE '%Quest-Item-Coupon%' AND user_id='$User[id]' AND art='itemall' AND ort='inventar' GROUP BY name";
	$resultQIC = mysql_query($queryQIC) or die(mysql_error());
	$QIC = mysql_fetch_array($resultQIC);

	if ($QIC['COUNT(name)'] >= 15)
	{
		$zufallMax2 = round($User['level'] + $itemfaktor + $itemfaktor2);
		$Artefakt['off'] = round((1.15 * (($zufallMax2/100)+1)) * rand(95,99));
		$Artefakt['deff'] = 0;
		$Artefakt['name'] = $User['name'].'`s Kurzschwert';
		$Artefakt['wert'] = 700000;

		mysql_update("ausruestung", "user_id = '4', ort = 'inventar'", "user_id = '$User[id]' AND ort = 'inventar' AND name = 'Quest-Item-Coupon' LIMIT 15");
		$SqlInsert = "INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,preis,dauer,pic,ZHW,klasse,rang) VALUES ('$user[id]','$Artefakt[name]','shield','$Artefakt[off]','$Artefakt[deff]','33','33','0','0','0','inventar','$Artefakt[wert]','0','0','kurzschwert.gif','0','5','$user[rang]')";
		$tr = mysql_query($SqlInsert);

		echo'<br />Du hast dir ein Kurzschwert gekauft <em>('.$Artefakt['name'].', Off: '.$Artefakt['off'].')</em>!';
	}
	else
	{
		echo"Du hast leider nicht genügend QIC in deinem Inventar";
	}
}

if($_GET['do'] == 'Scutum')
{
	$queryQIC = "SELECT name, COUNT(name) FROM ausruestung WHERE name LIKE '%Quest-Item-Coupon%' AND user_id='$User[id]' AND art='itemall' AND ort='inventar' GROUP BY name";
	$resultQIC = mysql_query($queryQIC) or die(mysql_error());
	$QIC = mysql_fetch_array($resultQIC);

	if ($QIC['COUNT(name)'] >= 15)
	{
		$zufallMax2 = round($User['level'] + $itemfaktor + $itemfaktor2);
		$Artefakt['off'] = 0;
		$Artefakt['deff'] = round((1.4 * (($zufallMax2/100)+1)) * rand(95,99));
		$Artefakt['name'] = $User['name'].'`s Scutum';
		$Artefakt['wert'] = 700000;

		mysql_update("ausruestung", "user_id = '4', ort = 'inventar'", "user_id = '$User[id]' AND ort = 'inventar' AND name = 'Quest-Item-Coupon' LIMIT 15");
		$SqlInsert = "INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,preis,dauer,pic,ZHW,klasse,rang) VALUES ('$user[id]','$Artefakt[name]','shield','$Artefakt[off]','$Artefakt[deff]','0','50','0','0','0','inventar','$Artefakt[wert]','0','0','scutum.gif','0','5','$user[rang]')";
		$tr = mysql_query($SqlInsert);

		echo'<br />Du hast dir ein Scutum gekauft <em>('.$Artefakt['name'].', Deff: '.$Artefakt['deff'].')</em>!';
	}
	else
	{
		echo"Du hast leider nicht genügend QIC in deinem Inventar";
	}
}

//funktion für Schlagkraft
if($_GET['do'] == 'Schlagkraft')
{
	$queryQIC = "SELECT name, COUNT(name) FROM ausruestung WHERE name LIKE '%Quest-Item-Coupon%' AND user_id='$User[id]' AND art='itemall' AND ort='inventar' GROUP BY name";
	$resultQIC = mysql_query($queryQIC) or die(mysql_error());
	$QIC = mysql_fetch_array($resultQIC);

	$Preis = $User['Schlagkraft'] + 10;
	$User['Schlagkraft']++;

	if ($QIC['COUNT(name)'] >= $Preis)
	{
		echo"Du hast erfolgreich deine Schlagkraft erhöht";
		mysql_update("ausruestung", "user_id='4', ort='inventar'", "user_id='$User[id]' AND ort = 'inventar' AND name = 'Quest-Item-Coupon' LIMIT $Preis");
		$Update = mysql_query("UPDATE user SET Schlagkraft='$User[Schlagkraft]' WHERE id='$User[id]' LIMIT 1");
	}
	else
	{
		echo"Du hast leider nicht genügend QIC in deinem Inventar";
	}
}


// echo'<br><p><strong><u>Items kaufen</u></strong>';
// echo'<br />Kaufe eine Flamberge (25 QIC) <a href="?site=medaillen&do=Flamberge">Jetzt kaufen!</a>';
// echo'<br />Kaufe ein Stahlschwert (20 QIC) <a href="?site=medaillen&do=Stahlschwert">Jetzt kaufen!</a>';
// echo'<br />Kaufe eine Kurzschwert (15 QIC) <a href="?site=medaillen&do=kurzschwert">Jetzt kaufen!</a>';
// echo'<br />Kaufe eine Scutum (15 QIC) <a href="?site=medaillen&do=Scutum">Jetzt kaufen!</a>';

?>
