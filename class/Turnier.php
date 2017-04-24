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
class Turnier
{
	function makeUebersicht($user)
	{
		$query = @mysql_query("SELECT * FROM turnier");

		echo "<center>";
		while($turnier = @mysql_fetch_assoc($query))
		{

			if (
				($turnier['teilnehmer'] == 8 && $turnier['round'] != 3)
				|| ($turnier['teilnehmer'] == 16 && $turnier['round'] != 4)
				|| ($turnier['teilnehmer'] == 32 && $turnier['round'] != 5)
			)
			{
				if ($line)
				{
					echo "<hr />";
				}
				echo "<br />";
				echo "Startgebühr: ".$turnier['startgold']."<br />";
				echo "Teilnehmer: (".$turnier['place']."/".$turnier['teilnehmer'].")<br />";
				echo "min. Level: ".$turnier['minlvl']."<br />";
				echo "max. Level: ".$turnier['maxlvl']."<br />";
				#echo "Wann geht es los?: ".$turnier['date']."<br />";
				//button zum Teilnehmen wenn Bedingungen erf�llt sind vorhanden ist
				if ($this->checkConditions($user, $turnier) && $turnier['round'] == 0)
				{
					echo "<br />";
					echo '<form name="turnier" method="POST" action="index.php?site=turnier">';
					echo '<input type="hidden" name="this_turnier_id" value="'.$turnier['id'].'">';
					echo '<input type="submit" name="this_turnier" value="Teilnehmen">';
					echo '</form>';
				}
				if ($user['turnier'] == $turnier['id'])
				{
					echo 'Du nimmst hier teil';
					echo "<br />";
				}
				if ($turnier['teilnehmer'] == $turnier['place'] || $turnier['round'] != 0)
				{
					echo 'Dieses Tunier befindet sich in der <b>'.$turnier['round'].' Runde</b>, die nächste Runde startet am <b>'.$turnier['date'].'</b>';
					echo "<br />";
				}
				echo "<br />";

				echo '<form name="givTurnier" method="post" action="index.php?site=turnieruebersicht">
						<input type="hidden" name="id" value="'.$turnier['id'].'" />
					<input type="submit" value="Tunierbaum">
				</form>';

				// runden durchf�hren
				if (($user['name'] == 'cyberrranger' || $user['name'] == 'salamander') && ($turnier['place'] == $turnier['teilnehmer'] || $turnier['round'] != 0))
				{
					echo "<br /><br />";
					if ($turnier['round'] == 0)
					echo "<a href='index.php?site=turnierfight&id=".$turnier['id']."&round=1'>Runde 1</a><br />";
					if ($turnier['round'] == 1)
					echo "<a href='index.php?site=turnierfight&id=".$turnier['id']."&round=2'>Runde 2</a><br />";
					if ($turnier['round'] == 2)
					echo "<a href='index.php?site=turnierfight&id=".$turnier['id']."&round=3'>Runde 3</a><br />";
					if (($turnier['teilnehmer'] == 16 || $turnier['teilnehmer'] == 32) && $turnier['round'] == 3)
					echo "<a href='index.php?site=turnierfight&id=".$turnier['id']."&round=4'>Runde 4</a><br />";
					if ($turnier['teilnehmer'] == 32 && $turnier['round'] == 4)
					echo "<a href='index.php?site=turnierfight&id=".$turnier['id']."&round=5'>Runde 5</a><br />";
				}
				echo "<br />";
				$line = true;
			}
		}
		echo "</center>";
	}

	function idListe($withwinner = 0)
	{
		$query = @mysql_query("SELECT * FROM turnier");

		$id = array();
		while($turnier = @mysql_fetch_assoc($query))
		{
			if ($withwinner)
			{
				$id[$turnier['id']] = $turnier['winner'];
			} else
			{
				$id[] = $turnier['id'];
			}
		}

		return $id;
	}

	function placeListe($tid, $user = false)
	{
		$query = @mysql_query("SELECT * FROM turnier_run WHERE turnierid=".$tid." ORDER by place ASC");

		$round = array();
		while ($p = mysql_fetch_assoc($query))
		{
			if ($user)
			{
				$round[] = $p['userid'];
			} else
			{
				$round[$p['place']] = $p;
			}
		}

		return $round;
	}

	function ifTurnier($tid)
	{
		$thisround = mysql_fetch_assoc(mysql_query("SELECT * FROM turnier WHERE id='".$tid."' LIMIT 1"));
		return $thisround;
	}

	function registerForm($user, $this_turnier_id)
	{
		//User m�chte an einem Tunier teilnehmen
		$sql = "SELECT * FROM turnier WHERE id=".$this_turnier_id." LIMIT 1";
		$query = mysql_query($sql);
		$thisturnier = mysql_fetch_assoc($query);

		if ($this->checkConditions($user, $thisturnier))
		{
			$this->registerUser($user, $thisturnier);
			return "<b>Du wurdest erfolgreich f�r dieses Turnier angemeldet.</b><br /><br /><br />";

		} else
		{
			return "<b>Du darfst hier nicht teilnehmen</b><br /><br /><br />";
		}

	}

	function calctiefe($tid)
	{
		$thisround = mysql_fetch_assoc(mysql_query("SELECT * FROM turnier WHERE id='".$tid."' LIMIT 1"));

		if ($thisround['teilnehmer'] == 8)
		{
			return 3;
		}

		if ($thisround['teilnehmer'] == 16)
		{
			return 4;
		}

		if ($thisround['teilnehmer'] == 32)
		{
			return 5;
		}
	}

	function calcteilnehmer($tid)
	{
		$thisround = mysql_fetch_assoc(mysql_query("SELECT * FROM turnier WHERE id='".$tid."' LIMIT 1"));

		return $thisround['teilnehmer'];
	}

	function makeTabelle($array, $tiefe, $tid)
	{
		if (($tiefe < 1) || ($tiefe >10))
		{
			return false;
		}

		$rows = 1 << $tiefe;
		$result= ' <table border="1" style="border: 1px solid; color: #D3C1A2;padding: 5px;" cols="'.$tiefe.'" rows="'.$rows.'"> ';

		for ($row = 0; $row < $rows; $row++)
		{
			$result .= '<tr style="border: 1px solid; color: #D3C1A2;padding: 5px;"> ';
			for($col=0;$col<$tiefe+1;$col++)
			{
				$rowspan='';
				$raster = 1 << $col;
				$feld = $rows * $col + $row;
				if ($col>0) $rowspan=' rowspan="'.$raster.'" ';

				if(($col==0) || (($feld % $raster) == 0))
				{
					$thisfeld = $feld + 1;

					if ($array[$thisfeld])
					{
						$name = get_user_things($array[$thisfeld]['userid'],'name');
						$result .= '<td style="border: 1px solid;color: #D3C1A2;padding: 5px;" '.$rowspan.' ><a href="/index.php?site=userinfo&info='.$array[$thisfeld]['userid'].'"><b>'.$name.'</b></a>';

						if ($array[$thisfeld]['report_id'])
						{
							$result .= ' <a href="index.php?site=report&id='.$array[$thisfeld]['report_id'].'"><img src="Images/icons/scroll.png" height="20" /></a>';
						}

						$result .= '</td> ';
					} else
					{
						if ($thisfeld <= $this->calcteilnehmer($tid))
						{
							$result .= '<td style="border: 1px solid;color: #D3C1A2;padding: 5px;"'.$rowspan.' >noch frei</td> ';
						} else
						{
							$result .= '<td style="border: 1px solid;color: #D3C1A2;padding: 5px;width:50px;"'.$rowspan.' >&nbsp;</td> ';
						}
					}
				}
			}
			$result .= '</tr> ';
		}
		$result .= '</table> ';
		return $result;
	}

	function writefightreport ($report, $winner, $loser)
	{
		$turnier = mysql_query("INSERT INTO turnier_report (`report`, `winner`, `loser`) VALUES ('".$report."','".$winner."','".$loser."')");
		$report_id = mysql_insert_id();
		return $report_id;
	}

	private function registerUser($u, $t)
	{
		//startplatz herausfinden
		$place = false;
		while ($place == false)
		{
			$_place = rand(1, $t['teilnehmer']);
			//pr�fen ob dieser platz bereits belegt ist
			$InfoSql = "SELECT * FROM turnier_run WHERE turnierid=".$t['id']." AND place=".$_place." LIMIT 1";
			$InfoQuery = mysql_query($InfoSql);
			$__place = mysql_fetch_assoc($InfoQuery);

			if ($__place)
			{
				$place = false;
			} else
			{
				$place = $_place;
			}
		}

		if ($place)
		{
			$turnier = mysql_query("INSERT INTO turnier_run (`turnierid`, `userid`, `place`) VALUES ('".$t['id']."','".$u['id']."','".$place."')");

			//gold abziehen
			$Update = mysql_query("UPDATE user SET gold=gold-".$t['startgold']." WHERE id='".$u['id']."' LIMIT 1");

			//tunier bei user registerUser
			$Update = mysql_query("UPDATE user SET turnier=".$t['id']." WHERE id='".$u['id']."' LIMIT 1");

			$d = getdate();
			$d['mday'] = $d['mday'] + 1;
			$date = $d['year']."-".$d['mon']."-".$d['mday']." ".$d['hours'].":".$d['minutes'].":".$d[''].":"."00";

			//tunierplatz belegen
			$Update = mysql_query("UPDATE turnier SET place=place+1 WHERE id='".$t['id']."' LIMIT 1");
			$Update = mysql_query("UPDATE turnier SET date='".$date."' WHERE id='".$t['id']."' LIMIT 1");

			//gold hinzuf�gen
			$Update = mysql_query("UPDATE turnier SET gold=gold+".$t['startgold']." WHERE id='".$t['id']."' LIMIT 1");

			$t['place'] = $t['place'] + 1;
			if ($t['teilnehmer'] == $t['place'])
			{
				//turnier ist voll rundmail raussenden
				$array = $this->placeListe($t['id'], $user = true);
				$this->roundmail($array);
			}

		} else
		{
			return "Fehler beim anmelden zu diesem Turnier";
		}

	}

	private function checkConditions($u, $t)
	{
		if ($t['startgold'] > $u['gold'])
		{
			return false;
		}

		if ($u['level'] < $t['minlvl'])
		{
			return false;
		}

		if ($u['level'] > $t['maxlvl'])
		{
			return false;
		}

		if ($t['place'] >= $t['teilnehmer'])
		{
			return false;
		}

		if ($u['turnier'] != 0)
		{
			return false;
		}

		return true;
	}

	private function roundmail($array)
	{
		$absender = 'Organisation';
		$text = "Soeben wurde der letzte freie Platz des aktuellen Turniers belegt, dieses startet somit in 24 Stunden.";
		$titel = "Turnier Start";
		foreach ($array as $id)
		{
			$empfaenger = get_user_things($id,'name');
			send_igm($titel,$text,$empfaenger,$absender);
		}
	}
}
?>
