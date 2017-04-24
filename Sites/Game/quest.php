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
echo'<center><a href="index.php?site=quest" target="_self">Quest</a> |
<a href="index.php?site=quests" target="_self">Spezial Quests</a> |
<a href="index.php?site=tool" target="_self">Werkstatt</a>
</center><br />';

if($user['schlafen'] <= time())
{
//in der woche vom 15.3 bis 22.03 gibt es 5
  $max_quest_today = 3;

  if($_REQUEST['delete'] == 1)
  {
  	echo "<center>";
  	echo "Dieses Quest wirklich löschen? Wenn du dies machst wird dir ein Quest abgezogen!<br />";
  	echo "<a href='?site=quest&delete=y'>Ja</a><br />";
  	echo "<a href='?site=quest'>Nein</a><br />";
  	echo "</center>";
  }
  elseif($_REQUEST['delete'] == 'y')
  {
  	echo "<center>";
  	echo "Das Quest wurde gelöscht, wenn dir diese Aufgabe zu schwer war dann probier doch eine leichtere Aufgabe.<br />";
  	echo "<a href='?site=quest'>Zurück zu den Quests</a><br />";
  	echo "</center>";
  	$Update1 = mysql_query("UPDATE quest_today SET count=count+1 WHERE user_id=".$user['id']." AND date='".date('Y-m-d')."' LIMIT 1 ");
  	$Update2 = mysql_query("DELETE FROM quest_what WHERE user_id=$user[id]");
  }
  else
  {
  	//Prüfen ob der user heute schon was eingetragen hat wenn nicht eintrag erstellen
  	if(!$_REQUEST['stufe'] || $user['level'] < 20)
  	{
  		if($user['level'] >= 20)
  		{
  			$Query = @mysql_query("SELECT * FROM quest_today WHERE user_id='".$User['id']."' AND date='".date('Y-m-d')."' ORDER BY id DESC LIMIT 1");
  			$quest_today = @mysql_fetch_assoc($Query);

  			if(!$quest_today)
  			{
  				//collected spalte anlegen
  				$Query2 = @mysql_query("INSERT INTO quest_today (user_id,date) VALUES ('".$User['id']."', '".date('Y-m-d')."')"); //der aktulle quest wird aus der datenbank geholt
  				$quest_today = @mysql_fetch_assoc($Query2);
  			}

  			//aktuelles Quest herausfinden
  			$Query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$User['id']."' LIMIT 1");
  			$quest_now = @mysql_fetch_assoc($Query);

  			if($quest_today['count'] == $max_quest_today)
  			{
  				echo "<center>";
  				echo "Du hast heute schon ".$max_quest_today." Quests erfüllt.";
  				echo "</center>";
  			}elseif($quest_now['id'] != '')
  			{
  				//ist diese quest vielleicht schon erledigt?
  				if($quest_now['maxcount'] <= $quest_now['count'])
  				{
  					$Update1 = mysql_query("UPDATE quest_today SET count=count+1 WHERE user_id=".$user['id']." AND date='".date('Y-m-d')."' LIMIT 1 ");

  					echo "<center>";
  					echo "Glückwunsch für dein letztes Quest (".$quest_now['id'].") hast du folgendes bekommen:<br />";
  					echo $quest_now['wincount']." ".strtoupper($quest_now['win'])."<br />";
  					echo "<a href='index.php?site=quest'>Zurück zur Quest Übersicht</a>";
  					echo "</center>";

  					$Update2 = mysql_query("DELETE FROM quest_what WHERE user_id=$user[id]");

  					switch ($quest_now['win']){
  					case 'gold':
  						mysql_query("UPDATE ".TAB_USER." SET gold=gold+$quest_now[wincount] WHERE id='".$User['id']."' LIMIT 1");
  				        break;
  					case 'exp':
  						mysql_query("UPDATE ".TAB_USER." SET exp=exp+$quest_now[wincount] WHERE id='".$User['id']."' LIMIT 1");
  				        break;
  					case 'prestige':
  						mysql_query("UPDATE ".TAB_USER." SET prestige=prestige+$quest_now[wincount] WHERE id='".$User['id']."' LIMIT 1");
  				        break;
  					case 'meds':
  						mysql_query("UPDATE ".TAB_USER." SET medallien=medallien+$quest_now[wincount] WHERE id='".$User['id']."' LIMIT 1");
  				        break;
  					case 'qic':
  						//item erstllen
  						mysql_query("UPDATE ".TAB_USER." SET QIC=QIC+'".$quest_now['wincount']."' WHERE id='".$User['id']."' LIMIT 1");
  				       	break;
  					case 'pm':
  						mysql_query("UPDATE ".TAB_USER." SET pmspar=pmspar+$quest_now[wincount] WHERE id='".$User['id']."' LIMIT 1");
  				        break;
  					}
  					mysql_query("UPDATE ".TAB_USER." SET quest=quest+1 WHERE id='".$User['id']."' LIMIT 1");

  				}else
  				{
  					//Quest läuft noch
  					echo "<center>";
  					echo "Du hast heute schon ".$quest_today['count']." von ".$max_quest_today." Quests abgeschlossen.<br /><br />";

  					echo "Zurzeit hast du folgende Aufgabe:<br />";
  					if($quest_now['typ'] == 'people')
  					{
  						echo "Du hast schon ".$quest_now['count']." von ".$quest_now['maxcount']." Gladiatoren in der Arena geTötet.<br />";
  					}elseif($quest_now['typ'] == 'animal')
  					{
  						$Query3 = @mysql_query("SELECT * FROM tiergrube WHERE id='".$quest_now['animal_id']."'");
  						$animal = @mysql_fetch_assoc($Query3);
  						echo  "Du hast schon ".$quest_now['count']." von ".$quest_now['maxcount']." ".$animal['name']." in der Tiergrube geTötet.<br />";
  					}else
  					{
  						echo "Du hast schon ".$quest_now['count']." von ".$quest_now['maxcount']." ".strtoupper($quest_now['typ'])." gesammelt.<br />";
  					}
  					echo "Dieses Quest <a href='?site=quest&delete=1' title='wenn du dies machst kannst du heute ein quest weniger machen.'>löschen?</a><br />";
#  					echo "<b>DEBUG::(".$quest_now['id'].") du solltest für dieses Quest ".$quest_now['wincount']." ".$quest_now['win']." bekommen! </b></center>";
  				}
  			}else
  			{
  				echo "<center>";
  				if(!$quest_today['count'])
  				{
					$count = 0;
				} else
				{
					$count = $quest_today['count'];
				}
				echo "Du hast heute schon ".$count." von ".$max_quest_today." Quests abgeschlossen.<br /><br />";
				echo "<br /><br />";
				echo "<a href='?site=quest&stufe=1'>Eine <b>sehr leichte Aufgabe</b> für einen unerfahrennen Gladiator</a><br>";
				echo "<a href='?site=quest&stufe=2'>Eine <b>leichte Aufgabe</b> für einen erfahrennen Gladiator</a><br>";

				if($user['level'] > 50)echo "<a href='?site=quest&stufe=3'>Eine <b>normale Aufgabe</b> für die Standardgladiatoren</a><br>";
				if($user['level'] > 100)echo "<a href='?site=quest&stufe=4'>Und eine <b>schwere Aufgabe</b> für die Elite der Arena</a><br>";
				if($user['level'] > 150)echo "<a href='?site=quest&stufe=5'>Und eine <b>sehr schwere Aufgabe</b> für die ganz Harten der Arena</a><br>";

				echo "</center>";
			}

  		}else
  		{
  			echo "<center>";
  			echo "Erst Gladiatoren ab dem Level 20 dürfen hier Quest annehmen.";
  			echo "</center>";
  		}
  	}else
  	{
  		$Query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$User['id']."' LIMIT 1");
  		$quest_now = @mysql_fetch_assoc($Query);

  		if(!$quest_now['id']) // || $user['name'] == 'cyberrranger')
  		{
  			$this_quest = array();

  			//gültige Stufe?

  			$stufe = $_REQUEST['stufe'];

  			if($stufe > 5)
  			{
  				//wegen schummeln auf stufe 1 Zurück
  				$stufe = 1;
  			}

  			if($stufe == 5 && $user['level'] > 150)
  			{
  				$rand = mt_rand(9,10);
  				$rand_2 = mt_rand(90,100);
  				$rand_3 = mt_rand(900,1000);
  				$rand_4 = mt_rand(9000,10000);
  			}elseif($stufe == 4 && $user['level'] > 100)
  			{
  				$rand = mt_rand(7,8);
  				$rand_2 = mt_rand(70,80);
  				$rand_3 = mt_rand(700,800);
  				$rand_4 = mt_rand(7000,8000);
  			}elseif($stufe == 3 && $user['level'] > 50)
  			{
  				$rand = mt_rand(5,6);
  				$rand_2 = mt_rand(50,60);
  				$rand_3 = mt_rand(500,600);
  				$rand_4 = mt_rand(5000,6000);
  			}elseif($stufe == 2)
  			{
  				$rand = mt_rand(3,4);
  				$rand_2 = mt_rand(30,40);
  				$rand_3 = mt_rand(300,400);
  				$rand_4 = mt_rand(3000,4000);
  			}else
  			{
  				$rand = mt_rand(1,2);
  				$rand_2 = mt_rand(10,20);
  				$rand_3 = mt_rand(100,200);
  				$rand_4 = mt_rand(1000,2000);
  			}
  			//Punkt 1 welcher typ soll es werden?
  			//animal, exp, prestige, item, people

  			$typ = mt_rand(1,6);
  			switch ($typ) {
  				case 1:
  			        $this_quest['typ'] = 'animal';

  			        //Nur Tiere nehmen auf die man Zugriff hat

  			        if($User['level'] <= 25)
  					{
  						$this_quest['animal_id'] = mt_rand(1,14);
  					}
  					elseif($User['level'] <= 40)
  					{
  						$this_quest['animal_id'] = mt_rand(15,24);
  					}
  					elseif($User['level'] <= 60)
  					{
  						$this_quest['animal_id'] = mt_rand(25,34);
  					}elseif($User['level'] <= 80)
  					{
  						$this_quest['animal_id'] = mt_rand(35,44);
  					}else
  					{
						if($stufe == 5)
						{
							$this_quest['animal_id'] = mt_rand(45,50);
						} else
						{
							$this_quest['animal_id'] = mt_rand(40,45);
						}
  					}
  					 $this_quest['maxcount'] = ceil($stufe * $rand_2 / 2);
  			        break;
  				case 2:
  					$this_quest['typ'] = 'exp';
  					$this_quest['animal_id'] = 0;
  					$this_quest['maxcount'] = $stufe * ceil($rand_3 / 2);
  			        break;
  				case 5:
  					$this_quest['typ'] = 'prestige';
  					$this_quest['animal_id'] = 0;
  					$this_quest['maxcount'] = $stufe * $rand_3;
  			        break;
  				case 3:
  					$this_quest['typ'] = 'item';
  					$this_quest['animal_id'] = 0;
  					$this_quest['maxcount'] = $stufe * $rand;
  			        break;
  				case 4:
  					$this_quest['typ'] = 'people';
  					$this_quest['animal_id'] = 0;
  					$this_quest['maxcount'] = $stufe * 3;#$rand;
  			        break;
				case 6:
  					$this_quest['typ'] = 'drops';
  					$this_quest['animal_id'] = 0;
  					$this_quest['maxcount'] = $rand_2;
  			        break;
  			}


  			//Punkt 3 was gibt es als Gewinn??
  			if($this_quest['typ'] != 'exp' && $this_quest['typ'] != 'prestige')
  			{
    			$win = mt_rand(1,6);

    			switch ($win) {
  				case 1:
  					$this_quest['win'] = 'gold';
  					break;
  				case 2:
  					$this_quest['win'] = 'exp';
  					break;
  				case 3:
  					$this_quest['win'] = 'gold';
  					break;
  				case 4:
  					$this_quest['win'] = 'meds';
  					break;
  				case 5:
  					$this_quest['win'] = 'qic';
  					break;
  				case 6:
  					$this_quest['win'] = 'pm';
  					break;
    			}
  			}
  			else
  			{
        		$win = mt_rand(1,5);
    			switch ($win) {
  				case 1:
  					$this_quest['win'] = 'gold';
  					break;
  				case 2:
  					$this_quest['win'] = 'meds';
  					break;
  				case 3:
  					$this_quest['win'] = 'qic';
  					break;
  				case 4:
  					$this_quest['win'] = 'pm';
  					break;
  				case 5:
  					$this_quest['win'] = 'gold';
  					break;
  				}
  			}

  			//Punkt 4 wieviel gibt es als Gewinn?

  			switch ($this_quest['win']){
  			case 'gold':
  			switch ($this_quest['typ']) {
  					case 'animal':
						$Query = @mysql_query("SELECT * FROM tiergrube WHERE id='".$this_quest['animal_id']."' LIMIT 1");
						$this_animal = @mysql_fetch_assoc($Query);

						$this_quest['wincount'] = $this_quest['maxcount'] * $this_animal['gold'];
  				        break;
  					case 'exp':
  						$this_quest['wincount'] = $this_quest['maxcount'] * $rand_2;
  				        break;
  					case 'prestige':
  						$this_quest['wincount'] = $this_quest['maxcount'] * $rand_2;
  				        break;
  					case 'item':
  						 $this_quest['wincount'] = $this_quest['maxcount'] * $rand_4;
  				        break;
  					case 'people':
  						$this_quest['wincount'] = $this_quest['maxcount'] * $rand_4;
  				        break;
					case 'drops':
  						$this_quest['wincount'] = $this_quest['maxcount'] * $rand_3;
  				        break;
  				}
  		        break;
  			case 'exp':
  				switch ($this_quest['typ']) {
  					case 'animal':
						$Query = @mysql_query("SELECT * FROM tiergrube WHERE id='".$this_quest['animal_id']."' LIMIT 1");
						$this_animal = @mysql_fetch_assoc($Query);

						$this_quest['wincount'] = $this_quest['maxcount'] * $this_animal['exp'];
  				        break;
  					case 'prestige':
  						  $this_quest['wincount'] = ceil($this_quest['maxcount'] / 10);
  				        break;
  					case 'item':
  						 $this_quest['wincount'] = $this_quest['maxcount'] * $rand_2;
  				        break;
  					case 'people':
  						 $this_quest['wincount'] = $this_quest['maxcount'] * $rand_2;
  				        break;
					case 'drops':
  						$this_quest['wincount'] = $this_quest['maxcount'] * $rand_2;
  				        break;
  				}
  		        break;
  			case 'meds':
				if($stufe == 5)
				{
					$this_quest['wincount'] = mt_rand(25,30);
				}elseif($stufe == 4)
				{
					$this_quest['wincount'] = mt_rand(19,24);
				}elseif($stufe == 3)
				{
					$this_quest['wincount'] = mt_rand(13,18);
				}elseif($stufe == 2)
				{
					$this_quest['wincount'] = mt_rand(7,12);
				}else
				{
					$this_quest['wincount'] = mt_rand(1,6);
				}
  		        break;
  			case 'qic':
					if($stufe == 5)
					{
						$this_quest['wincount'] = mt_rand(3,5);
					}elseif($stufe == 4)
					{
						$this_quest['wincount'] = mt_rand(2,4);
					}elseif($stufe == 3)
					{
						$this_quest['wincount'] = mt_rand(2,3);
					}elseif($stufe == 2)
					{
						$this_quest['wincount'] = mt_rand(1,2);
					}else
					{
						$this_quest['wincount'] = mt_rand(1,1);
					}
  		        break;
  			case 'pm':
					if($stufe == 5)
					{
						$this_quest['wincount'] = mt_rand(21,25);
					}elseif($stufe == 4)
					{
						$this_quest['wincount'] = mt_rand(16,20);
					}elseif($stufe == 3)
					{
						$this_quest['wincount'] = mt_rand(11,15);
					}elseif($stufe == 2)
					{
						$this_quest['wincount'] = mt_rand(6,10);
					}else
					{
						$this_quest['wincount'] = mt_rand(1,5);
					}
  		        break;
  			}

  			$Query3 = @mysql_query("INSERT INTO quest_what (user_id,typ,animal_id,maxcount,win,wincount,dif) VALUES ('".$User['id']."', '".$this_quest['typ']."', '".$this_quest['animal_id']."', '".$this_quest['maxcount']."', '".$this_quest['win']."', '".$this_quest['wincount']."', '".$stufe."')");
  			#$Query_debug = @mysql_query("INSERT INTO quest_what_debug (user_id,typ,animal_id,maxcount,win,wincount,dif) VALUES ('".$User['id']."', '".$this_quest['typ']."', '".$this_quest['animal_id']."', '".$this_quest['maxcount']."', '".$this_quest['win']."', '".$this_quest['wincount']."', '".$stufe."')");
  			$quest_today = @mysql_fetch_assoc($Query3);
  			#$quest_today_2 = @mysql_fetch_assoc($Query_debug);


  			echo "<center>";
  			echo "Du hast folgendes Quest bekommen:<br />";
  			if($this_quest['typ'] == 'people')
  			{
  				echo "Töte ".$this_quest['maxcount']." Gladiatoren in der Arena.<br />";
  			}elseif($this_quest['typ'] == 'animal')
  			{
  				$Query3 = @mysql_query("SELECT * FROM tiergrube WHERE id='".$this_quest['animal_id']."'");
  				$animal = @mysql_fetch_assoc($Query3);
  				echo "Töte ".$this_quest['maxcount']." ".$animal['name']." in der Tiergrube.<br />";
  			}elseif($this_quest['typ'] == 'drops')
  			{
  				echo "Sammel ".$this_quest['maxcount']." ".strtoupper($this_quest['typ']).".<br />";
  			}else
  			{
  				echo "Sammel ".$this_quest['maxcount']." ".strtoupper($this_quest['typ']).".<br />";
  			}
  			echo "<a href='index.php?site=quest'>Zurück zur Quest Übersicht</a>";
  			echo "</center>";
  		}else
  		{
  			echo "<center>";
  			echo "Du hast bereits ein Quest.<br />";
  			echo "<a href='index.php?site=quest'>Zurück zur Quest Übersicht</a>";
  			echo "</center>";
  		}
  	}
  }
}else
{
    echo "<center>";
		echo "Schlafende Gladiatoren haben hier nichts zu suchen!<br />";
		echo "</center>";
}

?>
