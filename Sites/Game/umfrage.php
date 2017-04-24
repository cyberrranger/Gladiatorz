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
if (!$GLOBALS['conf']['activ']['umfragen'])
{
	$time = time();
	$Sql = "SELECT * FROM Umfrage WHERE zeit >= '$time' LIMIT 1";
	$Query = mysql_query($Sql);
	$Umfrage = mysql_fetch_assoc($Query);

	echo' <center>Hallo <STRONG>'.$User['name'].'</STRONG><br><br> Bitte nimm auch du Teil an unserer aktuellen Umfrage.<br><br><STRONG>'.$Umfrage['frage'].'</STRONG><br><br></center>';

	//Hier wird die stimme in der DB gespeichert
	if(isset($_POST['antwort']) && !empty($_POST['antwort']) && $User['contest'] == 0)
	{
		$antwort = $_POST['antwort'];
		
		switch($antwort)
		{
			case 1:
			$stimme = 'stimmen1';
			$zahl = $Umfrage['stimmen1']+1;
			break;
			
			case 2:
			$stimme = 'stimmen2';
			$zahl = $Umfrage['stimmen2']+1;
			break;
			
			case 3:
			$stimme = 'stimmen3';
			$zahl = $Umfrage['stimmen3']+1;
			break;
			
			case 4:
			$stimme = 'stimmen4';
			$zahl = $Umfrage['stimmen4']+1;
			break;
			
			case 5:
			$stimme = 'stimmen5';
			$zahl = $Umfrage['stimmen5']+1;
			break;
		}
			
		$UpdateSql = "UPDATE Umfrage Set $stimme='$zahl' WHERE id='$Umfrage[id]' LIMIT 1";
		$UpdateQuery = mysql_query($UpdateSql);
		$UpdateSql2 = "UPDATE user Set contest='$antwort' WHERE id='$user[id]' LIMIT 1";
		$UpdateQuery2 = mysql_query($UpdateSql2);
		
		echo' <center>Vielen dank f�r Deine Teilnahme an der Umfrage<center>';
		
		echo'<META HTTP-EQUIV="Refresh" CONTENT="3"; URL="index.php?site=umfrage">';
		
		echo'<A HREF="index.php?site=umfrage">Du wirst sofort weitergeleitet</A>';
	}
	elseif(isset($_POST['antwort']) && !empty($_POST['antwort']) && $User['contest'] >= 1)
	{
		echo' Du hast bereits an dieser Umfrage Teilgenommen ';
	}
	elseif($User['contest'] == 0) //Die Umfrage ansich, erscheint nur wenn man noch nicht teilgenommen hat
	{
		echo'
		<form name="umfrage" action="index.php?site=umfrage" method="post"><br />
		<table cellpadding="0" cellspacing="0" border="0" width="450" align="center">
		<tr>
		<th colspan="2">Antwort</th>
		</tr>
		<tr>
			<td width="20" align="center"><input name="antwort" type="radio" value="1" /></td>
			<td align="center">'.$Umfrage['antwort1'].'</td>
		</tr>
		<tr>
			<td width="20" align="center"><input name="antwort" type="radio" value="2" /></td>
			<td align="center">'.$Umfrage['antwort2'].'</td>
		</tr>';
		
		if($Umfrage['antworten'] >=3) 
		{
			echo'
			<tr>
				<td width="20" align="center"><input name="antwort" type="radio" value="3" /></td>
				<td align="center">'.$Umfrage['antwort3'].'</td>
			</tr>';
		}
		if($Umfrage['antworten'] >=4) 
		{
			echo'
			<tr>
				<td width="20" align="center"><input name="antwort" type="radio" value="4" /></td>
				<td align="center">'.$Umfrage['antwort4'].'</td>
			</tr>';
		}
		if($Umfrage['antworten'] ==5)
		{
			echo'
			<tr>
				<td width="20" align="center"><input name="antwort" type="radio" value="5" /></td>
				<td align="center">'.$Umfrage['antwort5'].'</td>
			</tr>';
		}
		echo'
		</table><br />
		<input type="submit" value="Abstimmen" />
		</form></center>';
	}
	else //Die anzeige der Ergebnisse der Umfrage, erscheint wenn man bereits abgestimmt hat
	{
		If($Umfrage['zeit'] < time()) //wenn zurzeit keine Umfrage stattfindet eine Meldung ausgeben
		{
			echo'
			<br><center><b>Zurzeit findet keine Umfrage statt</b></center><br><br>';
		}
		else
		{
			echo'
			<table cellpadding="0" cellspacing="0" border="0" width="450" align="center">
			<tr>
				<th>Antwort</th>
				<th width="50" align="center">Stimmen</th>
			</tr>
			<tr>
				<td>'.$Umfrage['antwort1'].'</td>
				<td width="50" align="center">'.$Umfrage['stimmen1'].'</td>
			</tr>
			<tr>
				<td>'.$Umfrage['antwort2'].'</td>
				<td width="50" align="center">'.$Umfrage['stimmen2'].'</td>
			</tr>';
			
			if($Umfrage['antworten'] >=3) 
			{
				echo'
				<tr>
					<td>'.$Umfrage['antwort3'].'</td>
					<td width="50" align="center">'.$Umfrage['stimmen3'].'</td>
				</tr>';
			}
			if($Umfrage['antworten'] >=4) 
			{
				echo'
				<tr>
					<td>'.$Umfrage['antwort4'].'</td>
					<td width="50" align="center">'.$Umfrage['stimmen4'].'</td>
				</tr>';
			}
			if($Umfrage['antworten'] ==5) 
			{
				echo'
				<tr>
					<td>'.$Umfrage['antwort5'].'</td>
					<td width="50" align="center">'.$Umfrage['stimmen5'].'</td>
				</tr>';
			}
			
			//Abschluss der Tabelle
			echo'
			</table><br />';
		}
		$schleife = 1;
		
		echo'
		<br><br><center><b>�ltere Umfragen</b></center><br><br>';
		
		while($schleife < 3)
		{
			$id = $Umfrage['id'] - $schleife;
			$Sql2 = "SELECT * FROM Umfrage WHERE id = '$id' ";
			$Query2 = mysql_query($Sql2);
			$Umfrage2 = mysql_fetch_assoc($Query2);
		
			echo'<center>Frage:<b> '.$Umfrage2['frage'].'</b></center><br>';
			echo'
			<table cellpadding="0" cellspacing="0" border="0" width="450" align="center">
			<tr>
				<th>Antwort</th>
				<th width="50" align="center">Stimmen</th>
			</tr>
			<tr>
				<td>'.$Umfrage2['antwort1'].'</td>
				<td width="50" align="center">'.$Umfrage2['stimmen1'].'</td>
			</tr>
			<tr>
				<td>'.$Umfrage2['antwort2'].'</td>
				<td width="50" align="center">'.$Umfrage2['stimmen2'].'</td>
			</tr>';
			
			if($Umfrage2['antworten'] >=3) 
			{
				echo'
				<tr>
					<td>'.$Umfrage2['antwort3'].'</td>
					<td width="50" align="center">'.$Umfrage2['stimmen3'].'</td>
				</tr>';
			}
			if($Umfrage2['antworten'] >=4) 
			{
				echo'
				<tr>
					<td>'.$Umfrage2['antwort4'].'</td>
					<td width="50" align="center">'.$Umfrage2['stimmen4'].'</td>
				</tr>';
			}
			if($Umfrage2['antworten'] ==5) 
			{
				echo'
				<tr>
					<td>'.$Umfrage2['antwort5'].'</td>
					<td width="50" align="center">'.$Umfrage2['stimmen5'].'</td>
				</tr>';
			}
			echo'
			</table><br />';
			$schleife++;
		}
	}
}
else
{
	echo'<center><b>Umfragen derzeit deaktiviert</b></center>';
}
?>
