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

if($_POST['Löschen']=="Ja")
{
	$Query = mysql_query("SELECT sum(wert) FROM ausruestung WHERE user_id='$User[id]' AND ort = 'inventar' AND art != 'itemall' AND save = '0'");
	$WertGes = mysql_fetch_row($Query);
	$WertBekomm = round($WertGes[0] / 3);
	$User['gold'] = round($User['gold'] + $WertBekomm);

	mysql_update("user","gold='$user[gold]'","id='$user[id]'");
    mysql_update("ausruestung","user_id='4',ort='inventar'","user_id='$User[id]' AND ort = 'inventar' AND art != 'itemall' AND save = '0'");
	
	include"System/Variablen.php";

	echo"<center>Du hast erfolgreich alle nicht getragenen Items für <b>$WertBekomm Gold</b> verkauft!</center><br>";
}
if(!empty($_GET["item"]))
{
  $change = mysql_select("*","ausruestung","id='$_GET[item]'",null,"1");
  $auswahl = $_POST["auswahl"];
  $do = $_GET["do"];

	if($change['user_id'] != $user['id'] || $change['ort'] == "standard")
	{
		echo"<center>Entweder das ist nicht dein Item oder du kannst dieses Item nicht verändern!</center><br>";
	} else 
	{
	if($auswahl == "Ausrüsten")
	{
		//prüfen ob das ein eigenes namensitem ist
		if(strpos(utf8_encode($change['name']), $user['name']."s") === 0)
		{
			$user['rangtmp'] = $user['rang'] + 2;
			if ($change['rang'] <= $user['rangtmp'])
			{
				$user['staerke'] = 50;
				$user['geschick'] = 50;
				$user['kondition'] = 50;
				$user['charisma'] = 50;
				$user['inteligenz'] = 50;
			}
		}
		if($change['minStaerke'] > $user['staerke'] AND $change['minGeschick'] > $user['geschick'])
		{
			echo "<center>Um das Item zu tragen benötigst du $change[minStaerke] Stärke und $change[mingeschick] Geschick, du hast aber erst $user[staerke] Stärke und $user[geschick] Geschick!</center><br>";
		}
		elseif($change['minStaerke'] > $user['staerke'] AND $change['minIntelligenz'] > $user['inteligenz'])
		{
			echo "<center>Um das Item zu tragen benötigst du $change[minStaerke] Stärke und $change[minIntelligenz] Intelligenz, du hast aber erst $user[staerke] Stärke und $user[inteligenz] Intelligenz!</center><br>";
		}
		elseif($change['minStaerke'] > $user['staerke'] AND $change['minCarisma'] > $user['charisma'])
		{
			echo "<center>Um das Item zu tragen benötigst du $change[minStaerke] Stärke und $change[minCarisma] Charisma, du hast aber erst $user[staerke] Stärke und $user[charisma] Charisma!</center><br>";
		}
		elseif($change['minKondition'] > $user['kondition'] AND $change['minGeschick'] > $user['geschick'])
		{
			echo "<center>Um das Item zu tragen benötigst du $change[minKondition] Kondition und $change[mingeschick] Geschick, du hast aber erst $user[kondition] Kondition und $user[geschick] Geschick!</center><br>";
		}
		elseif($change['minStaerke'] > $user['staerke'])
		{
			echo "<center>Um das Item zu tragen benötigst du $change[minStaerke] Stärke, du hast aber erst $user[staerke] Stärke!</center><br>";
		}
		elseif($change['minGeschick'] > $user['geschick'])
		{
			echo "<center>um das Item zu tragen benötigst du $change[minGeschick] Geschick, du hast aber erst $user[geschick] Geschick!</center><br>";
		}
		elseif($change['minKondition'] > $user['kondition'])
		{
			echo "<center>um das Item zu tragen benötigst du $change[minKondition] Kondition, du hast aber erst $user[kondition] Kondition!</center><br>";
		}
		elseif($change['minCarisma'] > $user['charisma'])
		{
			echo "<center>um das Item zu tragen benötigst du $change[minCarisma] Charisma, du hast aber erst $user[charisma] Charisma!</center><br>";
		}
		elseif($change['minIntelligenz'] > $user['inteligenz'])
		{
			echo "<center>um das Item zu tragen benötigst du $change[minIntelligenz] Intelligenz, du hast aber erst $user[inteligenz] Intelligenz!</center><br>";
		}
		elseif($change['rang'] > $user['rang'])
		{
			echo "<center>um das Item zu tragen benötigst du den Rang ".getRangName2($change['rang']).", du bist aber erst ".getRangName2($user['rang'])."!</center><br>";
		}
		elseif($change['ZHW'] > 0)
		{
			$test= "UPDATE ausruestung SET ort='inventar' WHERE art='shield' AND ort='user' AND user_id='$user[id]'";
			mysql_query($test) OR die(mysql_error());
			$uneqp = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='$change[art]' AND ort='user'", null,"1");
			
			if($uneqp != false)
			{
				mysql_update("ausruestung","ort='inventar'","id='$uneqp[id]'");
			}

			mysql_update("ausruestung","ort='user'","id='$change[id]'");

			include"System/Variablen.php";

			echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> angelegt!</center><br>";
		}
		elseif($change[art] == shield)
		{
			$test="UPDATE ausruestung SET ort='inventar' WHERE art='weapon' AND ZHW='1' AND ort='user' AND user_id='$user[id]'";
			mysql_query($test) OR die(mysql_error());
			$uneqp = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='$change[art]' AND ort='user'", null,"1");
			
			if($uneqp != false)
			{
				mysql_update("ausruestung","ort='inventar'","id='$uneqp[id]'");
			}

			mysql_update("ausruestung","ort='user'","id='$change[id]'");

			include"System/Variablen.php";

			echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> angelegt!</center><br>";
		}
		elseif($change[art] == itemall)
		{
			echo'<center><b>Du kannst das nicht </b></center>';
		}
		else
		{
	  $uneqp = mysql_select("*", "ausruestung", "user_id='$user[id]' AND art='$change[art]' AND ort='user'", null,"1");
	  
      if($uneqp != false)
      {
        mysql_update("ausruestung","ort='inventar'","id='$uneqp[id]'");
      }

	  mysql_update("ausruestung","ort='user'","id='$change[id]'");

	  include"System/Variablen.php";

	  echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> angelegt!</center><br>";
	  }
	}
	elseif($auswahl == "Verkaufen")
	{
	  $zuerueck = round($change[wert] / 3);
	  $user[gold] = $user[gold] + $zuerueck;
           $dauer = (1 * 86400) + time();
           $zuerueck2 = $zuerueck * 1.5 ;

	  mysql_update("user","gold='$user[gold]'","id='$user[id]'");
           mysql_update("ausruestung","user_id='4',ort='basar',preis='$zuerueck2',dauer='$dauer'","id='$change[id]'");

	  include"System/Variablen.php";

	  echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> für <b>$zuerueck Gold</b> verkauft!</center><br>";
	}
	elseif($auswahl == "Versteigern")
	{
	  echo'<center>
	  <form name="versteigern" method="post" action="index.php?site=inventar&do=versteigern&item='.$change['id'].'">
	  Startpreis: <input type="text" maxlength="8" name="preis" style="text-align:center;">
	  <select name="dauer">
	    <option>4 Tag</option>
		<option>1 Tage</option>
		<option>2 Tage</option>
		<option>3 Tage</option>
		<option>5 Tage</option>
		<option>6 Tage</option>
		<option>7 Tage</option>
	  </select>
	  <input type="submit" value="Versteigern">
	  </form></center><br />';
	}
	elseif($auswahl == "Anbieten")
	{
	  echo'<center>
	  <form name="anbieten" method="post" action="index.php?site=inventar&do=anbieten&item='.$change['id'].'">
	  Preis: <input type="text" maxlength="8" name="apreis" style="text-align:center;">
	  <select name="adauer">
	    <option>4 Tag</option>
		<option>1 Tage</option>
		<option>2 Tage</option>
		<option>3 Tage</option>
		<option>5 Tage</option>
		<option>6 Tage</option>
		<option>7 Tage</option>
	  </select>
	  <input type="submit" value="Anbieten">
	  </form></center><br />';
	}
	elseif($auswahl == "sichern")
	{
	  mysql_update("ausruestung","save=1","id='$change[id]'");
	  echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> gesichert!</center><br>";
	}
	elseif($auswahl == "entsichern")
	{
	  mysql_update("ausruestung","save=0","id='$change[id]'");
	  echo"<center>Du hast erfolgreich <b>".utf8_encode($change[name])."</b> entsichert!</center><br>";
	}
	elseif($do == "versteigern")
	{
	  $Preis = $_POST["preis"];
	  $Dauer = substr($_POST["dauer"],0,1);

	  if($Dauer > 7 || empty($Dauer))
	  {
	    $Dauer = 7;
	  }

	  $Dauer = ($Dauer * 86400) + time();

	  if(empty($Preis))
	  {
	    echo'<center>Du musst einen Startpreis angeben!</center><br />';
	  }
	  elseif($Preis < ($change['wert']/2))
	  {
	    echo'<center>Der Startpreis muss mindestens die HÄlfte des Itemwerts betragen!</center><br />';
	  }
	  elseif($Preis >($change['wert']*2))
	  {
	    echo'<center>Dein Startpreis darf maximal das Doppelte des Itemwerts betragen!</center><br />';
	  }
	  else
	  {
	    mysql_update("ausruestung","ort='basar',preis='$Preis',dauer='$Dauer'","id='$change[id]'");
	    require('System/Variablen.php');
		echo'<center>Das Item wird jetzt auf dem Basar feilgeboten.</center><br />';
	  }
	}
	elseif($auswahl == "lagern" && $user['schule'] != 0)
	{
		$Preis = round($change['wert'] / 3);
		$Dauer = time() + 604800;
	
		$zuerueck = round($change['wert'] / 4);
		$user[gold] = $user[gold] + $zuerueck;
	
		mysql_update("user","gold='$user[gold]'","id='$user[id]'");
		mysql_update("ausruestung","ort='lager',preis='$Preis',dauer='$Dauer',schule ='$user[schule]'","id='$change[id]'");
	
		echo'<center>Das Item befindet sich nun im Schulenlager.</center><br />';
	} elseif ($do == "anbieten")
	{
	  $Preis = $_POST["apreis"];
	  $Dauer = substr($_POST["adauer"],0,1);

	  if($Dauer > 7 || empty($Dauer))
	  {
	    $Dauer = 7;
	  }

	  $Dauer = ($Dauer * 86400) + time();

	  if(empty($Preis))
	  {
	    echo'<center>Du musst einen Preis angeben!</center><br />';
	  }
	  elseif($Preis < ($change['wert']*0.66))
	  {
	    echo'<center>Der Preis muss mindestens 2 Drittel des Itemwerts betragen!</center><br />';
	  }
	  elseif($Preis >($change['wert']*2))
	  {
	    echo'<center>Dein Preis darf maximal das Doppelte des Itemwerts betragen!</center><br />';
	  }
	  else
	  {
	    mysql_update("ausruestung","ort='markt',preis='$Preis',dauer='$Dauer'","id='$change[id]'");
	    require('System/Variablen.php');
		echo'<center>Das Item wird jetzt auf dem Markt feilgeboten.</center><br />';
	  }
	}
  }
}

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
'Schilde / Zweitwaffen',
'Helme',
'Schulterrüstung',
'Körperrüstung',
'Beinrüstung',
'Umhänge',
'Gürtel',
'Handschuhe',
'Schuhe / Stiefel');

$ausgabe['tool'] = '';
for($i=0;$i<10;$i++)
{
  echo'<center><strong><u>'.$ItemName[$i].':</u></strong></center><br />';
  echo'
  <table width="500">
    <thead>
    <tr>
      <th width="32" style="padding:0px;">&nbsp;</td>
      <th>Name</th>
      <th width="30" style="padding:0px;"><img src="Images/Smith/weapon.jpg" /></th>
      <th width="30" style="padding:0px;"><img src="Images/Smith/armor.jpg" /></th>
      <th width="80">Wert</th>
      <th width="140">Aktion</th>
    </tr>
    </thead><tfoot>';

  $EQP = getEquipment($User['id'],$ItemTitle[$i]);
  $thisitem = findItem($EQP['art'], $user['id']);

  $Itemword = '<b>Off:</b> '.$EQP['off'].' ('.$thisitem['off'].')<br /><b>Deff:</b> '.$EQP['deff'].' ('.$thisitem['deff'].')<br /><br /><b>Wert: </b>'.$EQP['wert'].'<br /><br /><b>min. Stärke: </b>'.$EQP['minStaerke'].' ('.$user['staerke'].')<br /><b>min. Geschick: </b>'.$EQP['minGeschick'].' ('.$user['geschick'].')<br /><b>min. Kondition: </b>'.$EQP['minKondition'].' ('.$user['kondition'].')<br /><b>min. Charisma: </b>'.$EQP['minCarisma'].' ('.$user['charisma'].')<br /><b>min. Intelligenz: </b>'.$EQP['minIntelligenz'].' ('.$user['inteligenz'].')<br /><b>min. Rang: </b>'.getRangName2($EQP['rang']).' ['.$EQP['rang'].'] ('.getRangName2($user['rang']).' ['.$user['rang'].'])<br /><br />';
  
  $ausgabe['tool'] = $ausgabe['tool'].'<div class="tooltip" id="'.$EQP['id'].'"><b>'.utf8_encode($EQP['name']).'</b><p>'.$Itemword.'</p></div>';
 
  echo'
  <tr bgcolor="darkred">
    <td style="padding:0px;"><img onmouseout="hideTooltip();" onmouseover="initTooltip(\''.$EQP['id'].'\');" src="Images/Items/'.$EQP['pic'].'" /></td>
    <td style="font-size:10px;">'.utf8_encode($EQP['name']).'</td>
    <td style="font-size:10px;">'.$EQP['off'].'</td>
    <td style="font-size:10px;">'.$EQP['deff'].'</td>
    <td style="font-size:10px;">'.$EQP['wert'].'<br />('.round($EQP['wert']/3).')</td>
    <td width="140">
	<form method="post" action="?site=inventar&item='.$EQP['id'].'">
	  <select name="auswahl" style="width:80px;height:18px;font-size:10px;">
           <option>-----</option>
         <option>Verkaufen</option>
           <option>Versteigern</option>
		   <option>Anbieten</option>
	  </select>
	  <input type="submit" value="Los" style="width:40px;height:18px;font-size:10px;" />
	</form>
	</td>
  </tr>';

  $Query = mysql_query("SELECT * FROM ".TAB_ITEMS." WHERE user_id='".$User['id']."' AND ort='inventar' AND art='".$ItemTitle[$i]."'");
  while($Items = mysql_fetch_assoc($Query))
  {
	
  	$EQP = getEquipment($User['id'],$ItemTitle[$i]);
  	
  	$Itemword = '<b>Off:</b> '.$Items['off'].' ('.$thisitem['off'].')<br /><b>Deff:</b> '.$Items['deff'].' ('.$thisitem['deff'].')<br /><br /><b>Wert: </b>'.$Items['wert'].'<br /><br /><b>min. Stärke: </b>'.$Items['minStaerke'].' ('.$user['staerke'].')<br /><b>min. Geschick: </b>'.$Items['minGeschick'].' ('.$user['geschick'].')<br /><b>min. Kondition: </b>'.$Items['minKondition'].' ('.$user['kondition'].')<br /><b>min. Charisma: </b>'.$Items['minCarisma'].' ('.$user['charisma'].')<br /><b>min. Intelligenz: </b>'.$Items['minIntelligenz'].' ('.$user['inteligenz'].')<br /><b>min. Rang: </b>'.getRangName2($Items['rang']).' ['.$Items['rang'].'] ('.getRangName2($user['rang']).' ['.$user['rang'].'])<br /><br />';
  	
  	$ausgabe['tool'] = $ausgabe['tool'].'<div class="tooltip" id="'.$Items['id'].'"><b>'.utf8_encode($Items['name']).'</b><p>'.$Itemword.'</p></div>';
  	
    echo'
    <tr>';
      if($Items['save'] == 0){ echo' <td style="padding:0px;"><img onmouseout="hideTooltip();" onmouseover="initTooltip(\''.$Items['id'].'\');" src="Images/Items/'.$Items['pic'].'" /></td>';}
	  else{ echo'<td style="padding:0px;"><img onmouseout="hideTooltip();" onmouseover="initTooltip(\''.$Items['id'].'\');" src="Images/Items/schloss.gif" /></td>';}
	  
	  echo'<td style="font-size:10px;">'.utf8_encode($Items['name']).'</td>';
	  
      echo'<td style="font-size:10px;">'.$Items['off'].'</td>
      <td style="font-size:10px;">'.$Items['deff'].'</td>
      <td style="font-size:10px;">'.$Items['wert'].'<br />('.round($Items['wert']/3).')</td>
      <td width="140">';
      
      flush();
	 
      
      $_tmp = true;
      
      
      	if(
	  	($Items['minStaerke'] > $user['staerke'] 
	  	|| $Items['minGeschick'] > $user['geschick'] 
	  	|| $Items['minIntelligenz'] > $user['inteligenz'] 
	  	|| $Items['minCarisma'] > $user['charisma'] 
	  	|| $Items['minKondition'] > $user['kondition']
	  	|| $Items['rang'] > $user['rang']))
	  	{
	  		$_tmp = false;
	  	}
	  	
      	if (strpos(utf8_encode($Items['name']), $user['name']."s") === 0)
      	{
      		$user['rangtmp'] = $user['rang'] + 2;
	      	if ($Items['rang'] <= $user['rangtmp'])
	      	{
	      		$_tmp = true;
	      	}
      	}
		
	  	if(!$_tmp)
	  	{
		 	 echo'	<form method="post" action="?site=inventar&item='.$Items['id'].'">
				<select name="auswahl" style="width:80px;height:18px;font-size:10px;">';
				If($Items['save'] != 0)
				{
					echo '<option>entsichern</option>';
				}
				echo '<option>Verkaufen</option>
				<option>Versteigern</option>
				<option>Anbieten</option> ';
			  	if ($user['schule'] != 0)
			  	{
					echo "<option>lagern</option>";
			  	}
			  	If($Items['save'] == 0)
				{
					echo '<option>sichern</option>';
				}
				echo '</select>
		    <input type="submit" value="Los" style="width:40px;height:18px;font-size:10px;" />
		  </form>';
	  }else
	  {
	  echo'	<form method="post" action="?site=inventar&item='.$Items['id'].'">
			<select name="auswahl" style="width:80px;height:18px;font-size:10px;">';
			If($Items['save'] != 0)
			{
				echo '<option>entsichern</option>';
			}
			echo '<option>Verkaufen</option>
			<option>Ausrüsten</option>
			<option>Versteigern</option>
			<option>Anbieten</option>';
		  	if ($user['schule'] != 0)
		  	{
				echo "<option>lagern</option>";
		  	}
			If($Items['save'] == 0)
			{
				echo '<option>sichern</option>';
			}
			echo '</select>
	    <input type="submit" value="Los" style="width:40px;height:18px;font-size:10px;" />
	  </form>';
	  }		  

	  echo'
	  </td>
    </tr>';
  }

  echo'</tfoot></table><br />';
  

}
echo"<form name=\"alles\" action=\"index.php?site=inventar\" method=\"post\"><br><center>Alle nicht getragenen Items verkaufen: <input type=\"submit\" value=\"Ja\" name=\"Löschen\" ></form>";

echo $ausgabe['tool'];

function findItem($art, $sid)
{
	$Query = mysql_query("SELECT * FROM ausruestung WHERE user_id='".$sid."' AND ort = 'user' AND art='".$art."' LIMIT 1");
	$useritem = mysql_fetch_assoc($Query);

	$array = array();
	if ($useritem)
	{
		$array['off'] = $useritem['off'];
		$array['deff'] = $useritem['deff'];

	} else
	{
		$array['off'] = 0;
		$array['deff'] = 0;
	}


	return $array;
}
?>
