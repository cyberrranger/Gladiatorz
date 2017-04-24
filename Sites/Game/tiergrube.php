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
$Query = @mysql_query("SELECT * FROM collected WHERE user_id='".$User['id']."' ORDER BY id DESC LIMIT 1"); //der aktulle quest wird aus der datenbank geholt
$collected = @mysql_fetch_assoc($Query);

if(!$collected)
{
	//collected spalte anlegen
	$Query2 = @mysql_query("INSERT INTO collected (user_id) VALUES ('".$User['id']."')"); //der aktulle quest wird aus der datenbank geholt
	$collected = @mysql_fetch_assoc($Query2);
}
if($_GET['kill'])
{
	require('fights.php');
	$Battle = new Battle();

	if($_GET['triple'] == 'y' && $Settings['multfights'] > time())
	{
		$Mult = 3;
	}
	elseif($_GET['triple'] == 'j' && $user['status'] == "admin")
	{
		$Mult = 10;
	}
	else
	{
		$Mult = 1;
	}

	$Fehler = array();

	$Sql = "SELECT * FROM tiergrube WHERE id='$_GET[kill]' LIMIT 1";
	$Query = mysql_query($Sql);
	$Tier = mysql_fetch_assoc($Query);

	while($Mult > 0)
	{
		if($user['schlafen'] > time())
		{
			$Fehler[] = 'Du kannst nicht kämpfen während du schläfst!';
		}

		if($user['powmod'] == 0)
		{
			$Fehler[] = 'Du hast nicht genügend PowMod!';
		}

		if(!$_GET['kill'])
		{
			$Fehler[] = 'Du musst ein Tier auswählen!';
		}

		if($User['level'] <= 25)
		{
			if($Tier['id'] > 14)
			{
				echo'Falsches Tier!.';
				exit;
			}
		}
		elseif($User['level'] <= 40)
		{
			if($Tier['id'] < 15 || $Tier['id'] > 25)
			{
				echo'Falsches Tier!.';
				exit;
			}
		}
		elseif($User['level'] <= 60)
		{
			if($Tier['id'] < 25 || $Tier['id'] > 35)
			{
				echo'Falsches Tier!.';
				exit;
			}
		}
		elseif($User['level'] <= 80)
		{
			if($Tier['id'] < 35 || $Tier['id'] > 45)
			{
				echo'Falsches Tier!.';
				exit;
			}
		} else {
			if($Tier['id'] < 40 || $Tier['id'] > 100)
			{
				echo'Falsches Tier!.';
				exit;
			}

		}


		if(count($Fehler) == 0 && $user['kraft'] > 0) // Der eigentliche Kampf
		{
			$GroupOne = array();
			$GroupOne[1] = new Fighter();
			$GroupOne[1]->getAbilitys($user['id']);

			$GroupTwo = array();
			$GroupTwo[1] = new Animal();
			$GroupTwo[1]->getAbilitys($_GET['kill']);

			$Return = $Battle->newBattle($GroupOne,$GroupTwo);

			echo $Return['show'].'<br />';

			if($Return['winner'] == 1) // Spieler gewinnt
			{
				if($user['schule'] != 0)
				{
					$T = explode('|',$schule['area']);
					$T = $T[5];

					if($T == 2)
					{
						$user['powmod'] = $user['powmod'] - 0.1;
						$Bonus = $Tier['exp']+2;
						$user['exp'] = $user['exp'] + $Tier['exp'] + 2;
						$user['kraft'] = $user['kraft'] - ($user['kraft'] - $Return[$user['id']]);
						$user['tier_kills_win']++;
					}
					elseif($T == 1)
					{
						$user['powmod'] = $user['powmod'] - 0.1;
						$Bonus = $Tier['exp']+1;
						$user['exp'] = $user['exp'] + $Tier['exp'] + 1;
						$user['kraft'] = $user['kraft'] - ($user['kraft'] - $Return[$user['id']]);
						$user['tier_kills_win']++;
					}
					else
					{
						$user['powmod'] = $user['powmod'] - 0.1;
						$user['exp'] = $user['exp'] + $Tier['exp'];
						$user['kraft'] = $user['kraft'] - ($user['kraft'] - $Return[$user['id']]);
						$user['tier_kills_win']++;
						$Bonus = $Tier['exp'];
					}
				}
				else
				{
					$user['powmod'] = $user['powmod'] - 0.1;
					$user['exp'] = $user['exp'] + $Tier['exp'];
					$user['kraft'] = $user['kraft'] - ($user['kraft'] - $Return[$user['id']]);
					$user['tier_kills_win']++;
					$Bonus = $Tier['exp'];
				}



		# Wenn der User ein Quest hat auffülle
		$Query = @mysql_query("SELECT * FROM quest_what WHERE user_id='".$User['id']."' LIMIT 1");
		$quest_now = @mysql_fetch_assoc($Query);

		if($quest_now['typ'] == 'exp')
		{
			mysql_query("UPDATE quest_what SET count=count+".$Bonus." WHERE user_id='".$User['id']."' LIMIT 1");
		}
				if($user['schule'] == 0)
				{
					$user['gold'] = $user['gold'] + $Tier['gold'];
					$MoreMoney = $Tier['gold'];
					$wech=0;
					$Allystring = '';
				}
				elseif(get_points($user['id']) >= $schule['taxprotect'] && $schule['taxpercent'] != 0)
				{
					if($T == 2)
					{
						$MoreMoney = round($Tier['gold']*1.20);
						$Bonusgold = round($Tier['gold']*1.20);
					}
					elseif($T == 1)
					{
						$MoreMoney = round($Tier['gold']*1.10);
						$Bonusgold = round($Tier['gold']*1.10);
					}
					else
					{
						$MoreMoney = $Tier['gold'];
						$Bonusgold = $Tier['gold'];
					}

					if((($Bonusgold/100)*$schule['taxpercent']) > 1 && $schule['taxpercent'] > 0)
					{
						$wech = round((($Bonusgold/100)*$schule['taxpercent']));
						$MoreMoney -= $wech;
						$schule['gold'] += $wech;
					}
					else
					{
						$wech = 1;
						$MoreMoney = $MoreMoney - 1;
						$schule['gold']++;
					}

					$user['gold'] = $user['gold'] + $MoreMoney;
					$UpSSql = "UPDATE ".TABALLY." Set gold='$schule[gold]' WHERE id='$schule[id]' LIMIT 1";
					$UpS = mysql_query($UpSSql);

					$Allystring = '<br />('.$wech.' Gold geht in die Schulenkasse)';
				}
				else
				{
					if($T == 2)
					{
						$Bonusgold = round($Tier['gold']*1.20);
					}
					elseif($T == 1)
					{
						$Bonusgold = round($Tier['gold']*1.10);
					}
					else
					{
						$Bonusgold = $Tier['gold'];
					}

					$user['gold'] = $user['gold'] + $Bonusgold;
					$MoreMoney = $Bonusgold;
					$wech=0;
					$Allystring = '<br />(du bist von der Schulenzahlung befreit)';
				}

				echo'<center>Nach dem Kampf erhälst du '.($MoreMoney).' Gold, '.$Bonus.' Erfahrung und bist leicht erschöpft. '.$Allystring.'</center>';

				# Wenn der User ein Quest hat auffüllen

				if($quest_now['typ'] == 'animal' && $quest_now['animal_id'] == $Tier['id'])
				{
					mysql_query("UPDATE quest_what SET count=count+1 WHERE user_id='".$User['id']."' LIMIT 1");
				}

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

				// bekommt der User ein Arti?

				$eventzeit = time();
				$Event = @mysql_query("SELECT * FROM event WHERE dauer >= '".$eventzeit."' LIMIT 1");
				$Event = @mysql_fetch_assoc($Event);

				$gRND = round(20 + ($User['Glueck']/2));

				if($Event['event'] == 9)
				{
					$gRND += 10;
				}

				if($Gebet['time'] > $time)
				{
					if($Gebet['effekt'] = 7)
					{
						$gRND += 5;
					}
				}

				$Artefakt = null;
				$RND = rand(0,100);//0,100
				if($RND <= $gRND)
				{
	          		# Wenn der User ein Quest hat auffüllen
					if($quest_now['typ'] == 'item')
					{
						mysql_query("UPDATE quest_what SET count=count+1 WHERE user_id='".$User['id']."' LIMIT 1");
					}

					$Arti = rand(0,100);

					if($Arti <= $Tier['arti1'])
					{
						$Query = mysql_query("SELECT * FROM ausruestung WHERE ort='schmiede' AND user_id='0' AND klasse='$Tier[klasse1]' ORDER BY RAND() LIMIT 1");
					}
					elseif($Arti <= $Tier['arti2'])
					{
						$Query = mysql_query("SELECT * FROM ausruestung WHERE ort='schmiede' AND user_id='0' AND klasse='$Tier[klasse2]' ORDER BY RAND() LIMIT 1");
					}
					else
					{
						$Query = mysql_query("SELECT * FROM ausruestung WHERE ort='schmiede' AND user_id='0' AND klasse='$Tier[klasse3]' ORDER BY RAND() LIMIT 1");
					}
					$Artefakt = mysql_fetch_assoc($Query);

					$Sql2 = "SELECT * FROM appendix ORDER BY rand() LIMIT 1";
					$Query2 = mysql_query($Sql2);
					$Randappendix = mysql_fetch_array($Query2);

					$Sql3 = "SELECT * FROM user ORDER BY rand() LIMIT 1";
					$Query3 = mysql_query($Sql3);
					$Name = mysql_fetch_array($Query3);

					$minArti1 = round(25+$itemfaktor+$itemfaktor2);
					$minArti2 = round(15+$itemfaktor+$itemfaktor2);
					$maxArti1 = round($User['level']+$itemfaktor+$itemfaktor2);
					$maxArti2 = round(($User['level']/2)+$itemfaktor+$itemfaktor2);

					if($minArti1 <= $maxArti1*0.8)
					{
						$minArti1 = $maxArti1*0.8;
					}
					if($minArti2 <= $maxArti2*0.8)
					{
						$minArti2 = $maxArti2*0.8;
					}

					// es ist weihnachten der $Artefakt['name'] muss manipuliert werden
					/*
					switch ($Artefakt['art'])
					{
						case "weapon":
							$Artefakt['name'] = "Zuckerstange";
							$Artefakt['pic'] = "zuckerstange.gif";
							break;
						case "shield":
							$Artefakt['name'] = "Christbaumkugeln";
							$Artefakt['pic'] = "christbaumkugel.gif"; break;
						case "head":
							$Artefakt['name'] = "Weihnachtsmann Mütze";
							$Artefakt['pic'] = "bommel.gif"; break;
						case "shoulder":
							$Artefakt['name'] = "Lametta";
							$Artefakt['pic'] = "lametta.gif"; break;
						case "armor":
							$Artefakt['name'] = "Weihnachtsmann Gewand";
							$Artefakt['pic'] = "mantel.gif";  break;
						case "lowbody":
							$Artefakt['name'] = "Weihnachtsmann Hose";
							$Artefakt['pic'] = "mantel.gif";  break;
						case "cape":
							$Artefakt['name'] = "Geschenkesack";
							$Artefakt['pic'] = "sack.gif";  break;
						case "belt":
							$Artefakt['name'] = "Leder Gürtel";
							$Artefakt['pic'] = "lederguertel.gif"; break;
						case "gloves":
							$Artefakt['name'] = "Fäustlinge";
							$Artefakt['pic'] = "fauustlinge.gif"; break;
						case "foots":
							$Artefakt['name'] = "Schneeschuhe";
							$Artefakt['pic'] = "schneeschuhe.gif"; break;
					}

					if ($Artefakt['ZHW'] == 1)
					{
						$Artefakt['name'] = $Artefakt['name']." (ZWH)";
					}
					*/
					/*if($Tier['id'] >= '40')
					{
						$geb = rand(1,4);

						if($geb == 1)
						{
							$Artefakt['name'] = 'Backup';
							$Artefakt['off'] = 0;
							$Artefakt['deff'] = rand($User['level'],$User['level']*2);
							$Artefakt['wert'] = $Artefakt['deff']*1000;
							$Artefakt['art'] = 'armor';
							$Artefakt['minGeschick'] = $User['geschick'];
						}
						elseif($geb == 2)
						{
							$Artefakt['name'] = 'Programmiercode';
							$Artefakt['off'] = rand($User['kondition']/3,$User['kondition']/2);
							$Artefakt['deff'] = rand($User['level']/2,$User['level']);
							$Artefakt['wert'] = $Artefakt['deff']*1000;
							$Artefakt['art'] = 'head';
							$Artefakt['minKondition'] = $User['kondition'];
						}
						elseif($geb == 3)
						{
							$Artefakt['name'] = 'Bugs';
							$Artefakt['off'] = rand($User['staerke']/3,$User['staerke']/2);
							$Artefakt['deff'] = rand($User['level']/2,$User['level']);
							$Artefakt['wert'] = $Artefakt['deff']*1000;
							$Artefakt['art'] = 'foots';
							$Artefakt['minStaerke'] = $User['staerke'];
							$Artefakt['minIntelligenz'] = $User['inteligenz'];
						}
						elseif($geb == 4)
						{
							$Artefakt['name'] = 'Kaffeetassen';
							$Artefakt['off'] = rand($User['inteligenz']/3,$User['inteligenz']/2);
							$Artefakt['deff'] = rand($User['level']/4,$User['level']/2);
							$Artefakt['wert'] = $Artefakt['deff']*1000;
							$Artefakt['art'] = 'gloves';
							$Artefakt['minIntelligenz'] = $User['inteligenz'];
						}
					}
					else
					{*/
						$rangrand = round(40 + ($User['Sammler']/3));
						if($Event['event'] == 10)
						{
							$rangrand += 10;
						}

						if($User['rankplace'] < 21 && $User['rankplace'] > 0 && rand(0,100) < $rangrand) // Rangarti
						{
							$Artefakt['off'] = ceil($Artefakt['off'] * ((rand($minArti1,$maxArti1) / 100)+1));
							$Artefakt['deff'] = ceil($Artefakt['deff'] * ((rand($minArti1,$maxArti1) / 100)+1));
							$Artefakt['name'] = 'Rangkampf '.$Artefakt['name'];
							$Artefakt['wert'] = ceil($Artefakt['wert'] * ((rand(50,150) / 100)+1));
							$Artefakt['typ'] = 'rang';
						}
						elseif(rand(0,100) < 50) // normales Arti
						{
							$Artefakt['off'] = ceil($Artefakt['off'] * ((rand($minArti2,$maxArti2) / 100)+1));
							$Artefakt['deff'] = ceil($Artefakt['deff'] * ((rand($minArti2,$maxArti2) / 100)+1));
							$Artefakt['name'] = $Artefakt['name'].' ' .$Randappendix['name'];
							$Artefakt['wert'] = ceil($Artefakt['wert'] * ((rand(50,120) / 100)+1));
							$Artefakt['typ'] = 'normal';
						}
						else //Namens Arti
						{
							$Artefakt['off'] = round($Artefakt['off'] * ((rand($minArti2,$maxArti2) / 100)+1));
							$Artefakt['deff'] = round($Artefakt['deff'] * ((rand($minArti2,$maxArti2) / 100)+1));
							$Artefakt['name'] = $Name['name'].'s '.$Artefakt['name'];
							$Artefakt['wert'] = round($Artefakt['wert'] * ((rand(50,120) / 100)+1));
							$Artefakt['typ'] = 'name';
						}
						
						
						if (rand(1,200) == 1)
						{
							$Artefakt['off'] = round($Artefakt['off'] * ((125 - getRangName($User['level'], true) ) / 100));
							$Artefakt['deff'] = round($Artefakt['deff'] * ((125 - getRangName($User['level'], true) ) / 100));
							$Artefakt['wert'] = round($Artefakt['wert'] * ((125 - getRangName($User['level'], true) ) / 100));
						}
					//}

					//rang eines items -2 bis +0
					$rang = rand($User['rang']-2, $User['rang']+0);	
					
					if ($rang == $User['rang']) {
						//alles bleibts wie es ist
					} else if($rang == $User['rang'] + 1) {
						$Artefakt['off'] = round($Artefakt['off'] * 1.1);
						$Artefakt['deff'] = round($Artefakt['deff'] * 1.1);
						$Artefakt['wert'] = round($Artefakt['wert'] * 1.1);
						
					} else if($rang == $User['rang'] - 2) {
						$Artefakt['off'] = round($Artefakt['off'] * 0.8);
						$Artefakt['deff'] = round($Artefakt['deff'] * 0.8);
						$Artefakt['wert'] = round($Artefakt['wert'] * 0.8);
					} else if($rang == $User['rang'] - 1) {
						$Artefakt['off'] = round($Artefakt['off'] * 0.9);
						$Artefakt['deff'] = round($Artefakt['deff'] * 0.9);
						$Artefakt['wert'] = round($Artefakt['wert'] * 0.9);
					}
						
					$SqlInsert = "INSERT INTO ausruestung (user_id,name,art,off,deff,minStaerke,minGeschick,minKondition,minCarisma,minIntelligenz,ort,wert,preis,dauer,pic,ZHW,klasse,rang,typ) VALUES ('$user[id]','$Artefakt[name]','$Artefakt[art]','$Artefakt[off]','$Artefakt[deff]','$Artefakt[minStaerke]','$Artefakt[minGeschick]','$Artefakt[minKondition]','$Artefakt[minCarisma]','$Artefakt[minIntelligenz]','inventar','$Artefakt[wert]','0','0','$Artefakt[pic]','$Artefakt[ZHW]','$Artefakt[klasse]','$rang','$Artefakt[typ]')";
					$tr = mysql_query($SqlInsert);

					echo'<br /><center>Du hattest Glück, nach dem Kampf hast du ein <strong>Artefakt gefunden</strong> <em>('.$Artefakt['name'].', Off: '.$Artefakt['off'].', Def: '.$Artefakt['deff'].')</em>!</center>';
				}


				//Gibt es etwas extra?
				// Weihnachtsevent 2010 vom 1.12 bis 24.12
				if (time() > 1291158000 && time() < 1293231599)
				{
					$rand = rand(0, 15);
					if ($rand < 4)
					{
						// gibts nichts
					} else
					{
						switch($rand)
						{
							case ($rand < 6):
								$_collected = "collected_name_9";
								$whatcol = 9;
								break;
							case ($rand < 10):
								$_collected = "collected_name_10";
								$whatcol = 10;
								break;
							case ($rand < 13):
								$_collected = "collected_name_11";
								$whatcol = 11;
								break;
							case ($rand < 15):
								$_collected = "collected_name_12";
								$whatcol = 12;
								break;
							case ($rand < 16):
								$_collected = "collected_name_13";
								$whatcol = 13;
								break;
						}

						$collected[$_collected] = $collected[$_collected] + 1;
						mysql_query("UPDATE collected SET ".$_collected."=".$_collected."+1 WHERE user_id='$user[id]' LIMIT 1");
						echo'<br /><center>Das Tier hat ein '.collection_name($whatcol).' verloren, insgesamt hast du nun schon '.$collected[$_collected].' '.collection_name($whatcol).' gesammelt.</center>';

					}
				} else
				{

					$fell = rand(1,3);
					if($fell == 2)
					{
						$collected['collected_name_1'] = $collected['collected_name_1'] + 1;
						mysql_query("UPDATE collected SET collected_name_1='$collected[collected_name_1]' WHERE user_id='$user[id]' LIMIT 1");
						echo'<br /><center>Das Tier hat ein Fell verloren, insgesamt hast du nun schon '.$collected['collected_name_1'].' Felle gesammelt.</center>';
						$drop = true;

					}else
					{
						$zahn = rand(1,6);
						if($zahn == 6)
						{
							$collected['collected_name_2'] = $collected['collected_name_2'] + 1;
							mysql_query("UPDATE collected SET collected_name_2='$collected[collected_name_2]' WHERE user_id='$user[id]' LIMIT 1");
							echo'<br /><center>Das Tier hat ein Zahn verloren, insgesamt hast du nun schon '.$collected['collected_name_2'].' Zähne gesammelt.</center>';
							$drop = true;

						}else
						{
							$kralle = rand(1,12);

							if($kralle == 12)
							{
								$collected['collected_name_3'] = $collected['collected_name_3'] + 1;
								mysql_query("UPDATE collected SET collected_name_3='$collected[collected_name_3]' WHERE user_id='$user[id]' LIMIT 1");
								echo'<br /><center>Das Tier hat eine Kralle verloren, insgesamt hast du nun schon '.$collected['collected_name_3'].' Krallen gesammelt.</center>';
								$drop = true;
							}else
							{
								$feder = rand(1,3);
								if($feder == 2)
								{
									$collected['collected_name_4'] = $collected['collected_name_4'] + 1;
									mysql_query("UPDATE collected SET collected_name_4='$collected[collected_name_4]' WHERE user_id='$user[id]' LIMIT 1");
									echo'<br /><center>Das Tier hat eine Feder verloren, insgesamt hast du nun schon '.$collected['collected_name_4'].' Federn gesammelt.</center>';
									$drop = true;
								}else
								{
									$stock = rand(1,5);
									if($stock == 2)
									{
										$collected['collected_name_5'] = $collected['collected_name_5'] + 1;
										mysql_query("UPDATE collected SET collected_name_5='$collected[collected_name_5]' WHERE user_id='$user[id]' LIMIT 1");
										echo'<br /><center>Auf dem Boden der Arena hast du einen Stock gefunden, insgesamt hast du nun schon '.$collected['collected_name_5'].' Stöcke gesammelt.</center>';
										$drop = true;
									}else
									{
										$stein = rand(1,5);
										if($stein == 2)
										{
											$collected['collected_name_6'] = $collected['collected_name_6'] + 1;
											mysql_query("UPDATE collected SET collected_name_6='$collected[collected_name_6]' WHERE user_id='$user[id]' LIMIT 1");
											echo'<br /><center>Auf dem Boden der Arena hast du einen spitzen Stein gefunden, insgesamt hast du nun schon '.$collected['collected_name_6'].' Steine gesammelt.</center>';
											$drop = true;
										}
									}
								}
							}
						}
					}

					if ($drop)
					{
						# Wenn der User ein Quest hat auffüllen
						if($quest_now['typ'] == 'drops')
						{
							mysql_query("UPDATE quest_what SET count=count+1 WHERE user_id='".$User['id']."' LIMIT 1");
						}
					}
				}
			}else // Tier gewinnt
			{
				$user['powmod'] = $user['powmod'] - 0.3;
				$user['kraft'] = 0;
				$user['tier_kills_lost']++;

				echo'<center>Nach dem Kampf bist du stark erschöpft.</center>';
			}

			$UpdateSql = "UPDATE user Set powmod='$user[powmod]',gold='$user[gold]',exp='$user[exp]',kraft='$user[kraft]', tier_kills_win='$user[tier_kills_win]', tier_kills_lost='$user[tier_kills_lost]' WHERE id='$user[id]' LIMIT 1";
			$UpdateQuery = mysql_query($UpdateSql);

			if($Mult > 1)
			{
				echo'<br /><hr /><br />';
			}
		}
		elseif($user['kraft'] <= 0)
		{
			echo'<center><b>Du wurdest besiegt!</b></center><br />';
			break;
		}
		else
		{
			echo'<center><b>'.$Fehler[0].'</b></center><br />';
			break;
		}

		if($Settings['multfights'] > time())
		{
			$max_kraft = ($User['staerke'] * 50) + ($User['kondition'] * 120) + ($User['geschick'] * 5) + ($User['heilkunde'] * 30) + ($User['level'] * 50) + ($User['Kraftprotz'] * 50);
			$User['kraft'] = $User['kraft'] + ($User['staerke'] * 25) + ($User['kondition'] * 60) + ($User['geschick'] * 5) + ($User['heilkunde'] * 15) + ($User['level'] * 25) + ($User['Kraftprotz'] * 25);
			if($User['kraft'] > $max_kraft)
			{
				$User['kraft'] = $max_kraft;
			}
			mysql_update("user","kraft='$user[kraft]'","name='$user[name]'");
		}
		if($user['status'] == "admin")
		{
			$user['gold'] -= 300;
			$User['kraft'] = ($User['staerke'] * 50) + ($User['kondition'] * 120) + ($User['geschick'] * 5) + ($User['heilkunde'] * 30) + ($User['level'] * 50) + ($User['Kraftprotz'] * 50);
			mysql_update("user","gold='$user[gold]',kraft='$user[kraft]'","name='$user[name]'");
		}
		$Mult = $Mult - 1;
	}

	echo'<center style="margin-top:10px;font-weight:bold;"><a style="font-size:24px;" href="index.php?site=tiergrube" target="_self">zurück</a></center>';

	if($user['powmod'] <= 0)
	{
		$user['powmod'] = 0;
	}
} else
{
	echo' <center><font style=font-size:14px;><u><strong>Tiergrube:</u></strong></font><br><br>';

	echo'<table cellpadding="0" cellspacing="0" width="480" border="0" align="center">';

	echo'
	<tr style="background-color:#b87b00;">
	<td height="10" width="140" class="border" align="center"><strong>Name</strong></td>
	<td height="10" width="30" class="border" align="center" style="padding:0px;"><img src="Images/OldStuff/weapon.jpg" border="0" title="Schaden"></td>
	<td height="10" width="30" class="border" align="center" style="padding:0px;"><img src="Images/OldStuff/armor.jpg" border="0" title="Rüstung"></td>
	<td height="10" width="30" class="border" align="center" style="padding:0px;"><img src="Images/OldStuff/health.gif" border="0" title="Leben"></td>
	<td height="10" width="110" class="border" align="center"><strong style="font-size:11px;">Belohnung | EXP</strong></td>
	<td colspan="2" height="10" width="90" align="center"><strong>Aktion</strong></td>
	</tr>';

	if($User['level'] <= 25)
	{
		$Sql = "SELECT * FROM tiergrube WHERE id BETWEEN '1' AND '14' ORDER BY ID";
		$Select = mysql_query($Sql);
	}
	elseif($User['level'] <= 40)
	{
		$Sql = "SELECT * FROM tiergrube WHERE id BETWEEN '15' AND '24' ORDER BY ID";
		$Select = mysql_query($Sql);
	}
	elseif($User['level'] <= 60)
	{
		$Sql = "SELECT * FROM tiergrube WHERE id BETWEEN '25' AND '34' ORDER BY ID";
		$Select = mysql_query($Sql);
	}
	elseif($User['level'] <= 80)
	{
		$Sql = "SELECT * FROM tiergrube WHERE id BETWEEN '35' AND '45' ORDER BY ID";
		$Select = mysql_query($Sql);
	}
	else
	{
		$Sql = "SELECT * FROM tiergrube WHERE id BETWEEN '40' AND '100' ORDER BY ID";
		$Select = mysql_query($Sql);
	}

  while($Animals = mysql_fetch_assoc($Select))
  {
	if($user['schule'] != 0)
	{
		$T = explode('|',$schule['area']);
		$T = $T[5];

		if($T == 2)
		{
			$Animals['gold'] = round($Animals['gold']*1.20);
			$Animals['exp'] = $Animals['exp']+2;
		}
		elseif($T == 1)
		{
			$Animals['gold'] = round($Animals['gold']*1.10);
			$Animals['exp'] = $Animals['exp']+1;
		}
		else
		{
			$Animals['gold'] = $Animals['gold'];
			$Animals['exp'] = $Animals['exp'];
		}
	}

		echo'
		<tr>
		  <td height="10" class="border" align="center">'.utf8_encode($Animals['name']).'</td>
		  <td height="10" class="border" align="center" style="font-size:11px;">'.$Animals['attack'].'</td>
		  <td height="10" class="border" align="center" style="font-size:11px;">'.$Animals['armor'].'</td>
		  <td height="10" class="border" align="center" style="font-size:11px;padding:0px;">'.$Animals['health'].'</td>
		  <td height="10" class="border" align="center">'.$Animals['gold'].' | '.$Animals['exp'].'</td>';


	if($user['powmod'] >= 0.1)
	{
		if($user['powmod'] >= 1 && $user['status'] == "admin")
	  {
	    echo'
	    <td height="10" width="10" class="border" align="center">
          <a href="index.php?site=tiergrube&kill='.$Animals['id'].'&triple=j" target="_self">10x</a>
	    </td>';
	  }
	  if($user['powmod'] >= 0.3 && $Settings['multfights'] > time())
	  {
	    echo'
	    <td height="10" width="10" class="border" align="center">
          <a href="index.php?site=tiergrube&kill='.$Animals['id'].'&triple=y" target="_self">3x</a>
	    </td>';
	  }

	  echo'
	  <td height="10" width="80" class="border" align="center">
        <a href="index.php?site=tiergrube&kill='.$Animals['id'].'&triple=n" target="_self">Attacke!</a>
	  </td>';
	}
	else
	{
	  echo'<td height="10" width="80" class="border" align="center" style="height:21px;">---</td>';
	}

	echo'</tr>';
  }

  echo'</table>';
}
?>
