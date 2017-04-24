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
function IMG($absender,$empfaenger,$titel,$text)
{
	$timestamp = time();

	$text = "Hallo $empfaenger,
	<br />
	$text
	<br />MfG die $absender";

	mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender","'$empfaenger','$absender','$titel','$text','$timestamp','n','n','n'");
}

error_reporting(E_ALL);

class AllyClass
{
	function schularray($id)
	{
		$AllySql = "SELECT * FROM ".TABALLY." WHERE id='$id' LIMIT 1";
		$AllyQuery = mysql_query($AllySql);
		$Ally = mysql_fetch_assoc($AllyQuery);

		return $Ally;
	}
	
	function deleteSchule($id)
	{
		//Forum löschen
		mysql_query("DELETE FROM ".TABALLY." WHERE id='".$id."' LIMIT 1");
		$this->deleteForum($id);
	}
	
	function deleteForum($id)
	{
		//Forum löschen
		mysql_query("DELETE FROM ".TAB_FORUMS." WHERE id='".$id."' LIMIT 1");
		
		$Query2 = mysql_query("SELECT * FROM ".TAB_TOPICS." WHERE forums_id = ".$id);
	
		while($forum2 = mysql_fetch_assoc($Query2))
		{
			//topic löschen
			mysql_query("DELETE FROM ".TAB_TOPICS." WHERE id='".$forum2['id']."' LIMIT 1");
			
			//antworten löschen
			mysql_query("DELETE FROM ".TAB_ANSWERS." WHERE topics_id='".$forum2['id']."'");
		}
	}

	function ListAllys()
	{
		global $user,$User;
		$Ally = array();
		$Loop = 0;

        $Query = mysql_query("SELECT * FROM ".TABALLY);
		while($AllyTemp = mysql_fetch_assoc($Query))
		{
			$MemberSql = "SELECT count(id) FROM ".TABUSER." WHERE schule='$AllyTemp[id]'";
			$MemberQuery = mysql_query($MemberSql);
			$Member = mysql_fetch_row($MemberQuery);

			// Wenn es keine Mitglieder gibt Forum und schule löschen
			if($Member[0] == 0)
			{
				$this->deleteSchule($AllyTemp['id']);
				continue;
			}
			
			//Hirachie aufbauen bzw. prüfen
			$Boss = get_user_things($AllyTemp['boss'], 'id');
			$Vize = get_user_things($AllyTemp['vize'], 'id');
			$diplo = get_user_things($AllyTemp['diplo'], 'id');
			
			// wenn es keinen Boss gibt nach dem Vize suchen
			if (!$Boss)
			{
				if (!$Vize)
				{
					//wenn es keinen Boss gibt nach dem diplo suchen
					if (!$diplo)
					{
						// ?? wir haben keinen boss, vize oder diplo ??
						$AllyTemp['boss'] = 0;
						$AllyTemp['vize'] = 0;
						$AllyTemp['diplo'] = 0;
					} else
					{
						$AllyTemp['boss'] = $diplo['id'];
						$AllyTemp['vize'] = 0;
						$AllyTemp['diplo'] = 0;
					}
				} else
				{
					//es gibt einen Vize, diesen zum Boss machen
					$AllyTemp['boss'] = $vize['id'];
					
					if (!$diplo)
					{
						$AllyTemp['vize'] = 0;
					} else
					{
						$AllyTemp['vize'] = $diplo['id'];
					}
					$AllyTemp['diplo'] = 0;
				}
				
				mysql_query("UPDATE ".TABALLY." Set vize='".$AllyTemp['vize']."', boss='".$AllyTemp['boss']."' WHERE id='".$AllyTemp['id']."' LIMIT 1");
			}
			
			//Punkte einer Schule berechnen
			//gesamtpunkte ermitteln, gesamtpunkte durch zahl der member teilen
			
			$Ally[$Loop] = $AllyTemp;
			$Ally[$Loop]['member'] = $Member[0];
			$Ally[$Loop]['punkte'] = $this->AllyPoints($AllyTemp['id']);
			$Ally[$Loop]['punktedurch'] = round($Ally[$Loop]['punkte'] / $Ally[$Loop]['member']);
			
			$Loop++;
		}
		echo'<table cellpadding="0" cellspacing="0" border="0" width="500" id="allytable" align="center">';
	
		echo '<tr height="19">
				<td bgcolor="#b87b00" width="50"><strong>Platz</strong></td>
				<td bgcolor="#b87b00"><strong>Name</strong></td>
				<td bgcolor="#b87b00" width="50"><strong>K&uuml;rzel</strong></td>
				<td bgcolor="#b87b00" width="50"><strong>Member</strong></td>
				<td bgcolor="#b87b00" width="90"><strong>Boss</strong></td>
				<td bgcolor="#b87b00" width="50"><strong>Punkte</strong></td>
				<td bgcolor="#b87b00" width="50"><strong>Schnitt</strong></td>
			</tr>';
			
		if (is_array($Ally) && sizeof($Ally) != 0) {
			$Ally = array_reverse(ArrayKeysort($Ally, 'punkte'));

			$allyrang = 0;
	
			foreach($Ally as $GiveOut)
			{
				$allyrang = $allyrang + 1;
				
				$Boss = get_user_things($GiveOut['boss'], 'name');
				
				echo '<tr height="19" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';">
						<td width="50"><strong>'.$allyrang.'</strong></td>
						<td><a href="index.php?site=schulen&sub='.$GiveOut['id'].'info" target="_self">'.utf8_encode($GiveOut['name']).'</a></td>
						<td width="50">'.utf8_encode($GiveOut['kuerzel']).'</td>
						<td width="50">'.$GiveOut['member'].'</td>
						<td width="90">'.$Boss.'</td>
						<td width="50">'.$GiveOut['punkte'].'</td>
						<td width="50">'.$GiveOut['punktedurch'].'</td>
					</tr>';
	        }
		}
		echo'</table>';
	}

	//Einer Schule beitreten
	function EnterAlly($AllyJoin)
	{
		global $user,$User;
		echo'<center>';

		if($AllyJoin != false)
		{
			$Ally = $this->schularray($AllyJoin);
			
			echo 'Du bewirbst dich momentan bei der Schule: '.$Ally['name'];
			echo '<p><a href="index.php?site=schulen&sub=enter&add=out" target="_self">Bewerbung zur&uuml;ckziehen</a></p>';
		} else
		{
			echo "Du hast dich noch nicht bei einer Schule beworben! Um dich bei einer Schule zu bewerben, geh auf die Infoseite der Schule (&uuml;ber die Schulenliste) und bewirb dich mit dem Link am Ende der Seite.";
		}
		echo'</center>';
	}

	function searchGebet($id)
	{
		$time = time();
		$Query = mysql_query("SELECT * FROM ally_gebet WHERE schule='$id' AND time>= '$time' LIMIT 1");
		$Gebet = mysql_fetch_assoc($Query);
		return $Gebet;
	}
	
	function AllyInfoscreen($ID)
	{
		global $user,$User;

		$Ally = $this->schularray($ID);

		if($Ally != false)
		{
        	$Gebet = $this->searchGebet($ID);
        	
			if(substr($Ally['pic'],0,7) != 'http://' && $Ally['pic'] != '')
	        {
				$Ally['pic'] = 'http://'.$Ally['pic'];
			}
	
			if($Ally['pic'] == '')
			{
				$Ally['pic'] = 'Images/Design/pic.gif';
			}
	
			if($Ally['descr'] == '')
			{
				$Ally['descr'] = 'Es existiert noch keine Beschreibung dieser Schule.';
			}
		
			switch($Ally['tactic'])
			{
				case 0: $tac="<em>Keine</em><br /><br />Manchmal ist keine Taktik immer noch die beste Taktik! Bringt weder Vorteile noch Nachteile.";break;
				case 1: $tac="<em>Hau nei!</em><br /><br />Nur wer richtig zuhauen kann gewinnt auch einen Kampf. Diese Taktik gew&Auml;hrt dir 18% mehr Off auf deine Hauptwaffe und einen um 5 Punkte verbesserten Todesschlag, daf&uuml;r hast du aber auch nurnoch 75% deiner Def!";break;
				case 2: $tac="<em>Un&uuml;berwindware Verteidigung</em><br /><br />Nutze deine R&uuml;stung optimal und die Schl&auml;ge deines Gegners prallen an dir ab. Diese Taktik bringt 20% mehr Def und erh&ouml;ht deine Taktik um 5, daf&uuml;r kannst du aber auch nurnoch 82% deiner Off nutzen!";break;
				case 3: $tac="<em>2 Waffen sind besser als eine!</em><br /><br />&uuml;berw&auml;ltige deine Gegner indem du sie gleich mit 2 Waffen angreifst. Diese Taktik erh&ouml;ht die Off deiner Zweitwaffe um 50% und bringt dir 5 Punkte mehr im kritischen Schlag, einziger nachteil sind deine um 25% veringerten Def!";break;
				case 4: $tac="<em>Schrei dich an die Macht!</em><br /><br />Wer braucht Schon spezielle Schl&auml;ge, taktisch klug ist es auch den Status des Gegners zu schw&Auml;chen. Diese Taktik erh&ouml;ht Wutschrei, Kraftschrei, Sand werfen und Anti-Defense um 15 Punkte, verringert aber jeden anderen Move um 5 Punkte!";break;
				case 5: $tac="<em>Seine St&auml;rke ist meine St&auml;rke!</em><br /><br />Verwende die St&auml;rke deines Gegners um ihn dir vom Hals zu schaffen. Diese Takik erh&ouml;ht die Wahrscheinlichkeit und St&auml;rke von Konter, T&Auml;uschen und Berserker enorm!";break;
				case 6: $tac="<em>Schild hoch!</em><br /><br />Verstck dich hinter deinen Schild und greif deinen Gegner immer dann an wenn er grad nicht aupasst. Diese Taktik erh&ouml;ht die Def deines Schildes um 33% und gibt dir 3 Punkte in Ausweichen, allerdings sind Zweitwaffen nurnoch halb so wirkungsvoll und dein allgemeiner Waffenschaden sinkt um 10% wenn du ein Schild tr&auml;gst!";break;
			}

			
			switch($Gebet['effekt'])
			{
				case 1: $geb="<em>Von Mars gesegnet!</em><br /><br />Der Gott des zerst&ouml;rerischen Krieges und der Schlachten (bringt 10% mehr Off und Deff).";break;
				case 2: $geb="<em>Von Jupiter gesegnet!</em><br /><br />Unserem G&ouml;ttervater (Alle Spezial Moves +2).";break;
				case 3: $geb="<em>Von Merkur gesegnet!</em><br /><br />Dem Gott des Handels (Schmiedepreise sinken um 15%).";break;
				case 4: $geb="<em>Von Minerva gesegnet!</em><br /><br />Die G&ouml;ttin der Weisheit (Intelligenz steigt um 5).";break;
				case 5: $geb="<em>Von Venus gesegnet!</em><br /><br />Die G&ouml;ttin der Liebe und Sch&ouml;nheit (Charisma steigt um 5).";break;
				case 6: $geb="<em>Von Herkules gesegnet!</em><br /><br />Dem Schirmherr der Sportst&auml;tten und Pal&auml;ste (Belohnung in der Arena steigt um 25%).";break;
				case 7: $geb="<em>Von Fortuna gesegnet!</em><br /><br />Die G&ouml;ttin des gl&uuml;cks (Droprate steigt im 5%).";break;
				default: $geb="<em>Von keinem Gott gesegnet!</em><br /><br />Derzeit kein Bonus.";break;
			}

			$bbcode = new StringParser_BBCode();
			$bbcode->addFilter (STRINGPARSER_FILTER_PRE, 'convertlinebreaks');
			$bbcode->addParser ('list', 'bbcode_stripcontents');

			$bbcode->addCode ('b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>'),
							  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
			$bbcode->addCode ('u', 'simple_replace', null, array ('start_tag' => '<u>', 'end_tag' => '</u>'),
							  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
			$bbcode->addCode ('i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>'),
							  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
			$bbcode->addCode ('url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default'),
							  'link', array ('listitem', 'block', 'inline'), array ('link'));
			$bbcode->addCode ('link', 'callback_replace_single', 'do_bbcode_url', array (),
							  'link', array ('listitem', 'block', 'inline'), array ('link'));
			$bbcode->addCode ('img', 'usecontent', 'do_bbcode_img', array (),
							  'image', array ('listitem', 'block', 'inline', 'link'), array ());
			$bbcode->addCode ('bild', 'usecontent', 'do_bbcode_img', array (),
							  'image', array ('listitem', 'block', 'inline', 'link'), array ());
			$bbcode->setOccurrenceType ('img', 'image');
			$bbcode->setOccurrenceType ('bild', 'image');
			$bbcode->setMaxOccurrences ('image', 2);
			$bbcode->addCode ('list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>'),
							  'list', array ('block', 'listitem'), array ());
			$bbcode->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'),
							  'listitem', array ('list'), array ());
			$bbcode->addCode ('color', 'usecontent?', 'bbcode_color', array ('usecontent_param' => 'default'), 'link', array ('block', 'inline'), array ('link'));
			$bbcode->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
			$bbcode->setCodeFlag ('*', 'paragraphs', true);
			$bbcode->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
			$bbcode->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
			$bbcode->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
			$bbcode->setRootParagraphHandling (true);
			
			echo '<center>
				<strong style="font-size:28px;">'.$Ally['name'].'</strong>
				<br /><img src="'.$Ally['pic'].'" border="0" width="320" height="260" style="margin-top:10px;" />
				<p><strong>Schulenbeschreibung:</strong>
 				<br /><br />'.$bbcode->parse($Ally['descr']).'</p>
				<br /><strong>Wars:</strong><br /><br />';

				$Wars = explode(',',$Ally['war']);
				
				if (is_array($Wars))
				{
					foreach($Wars as $War)
					{
						if($War=='Array')continue;
						
						$Al = $this->schularray($War);

						$Enemy = explode(',',$Al['war']);

						if(array_search($War,$Enemy) === false)
						{
							if ($Al['name'])
							{
								echo '<a href="?site=schulen&sub='.$Al[id].'info" target="_self">'.$Al[name].'</a><br />';
							}
						}
					}
				} else
				{
					echo 'Keine Kriege!<br />';
				}
				
				if(count($Wars) == 1)
				{
					echo 'Keine Kriege!<br />';
				}

                echo'<br /><strong>Schulenkasse:</strong><br /><br />
	                '.$Ally['gold'].' Gold<br />'.$Ally['medallien'].' Medaillen<br /><br />
	                <font size="-2">'.$Ally['taxpercent'].'% Steuersatz</font><br /><br />
	                <strong>Taktik:</strong><br />
	                <div style="width:320px;">'.$tac.'
					<br /><br /><strong>Gebet:</strong><br />
	                <div style="width:320px;">'.$geb.'<br /></div>
	                <p><strong>Member:</strong></p>
          		</center>';

				$AllyMember = array();
				$AllyMemberID = 0;
				$MemberSql = "SELECT * FROM ".TABUSER." WHERE schule='$Ally[id]'";
				$MemberQuery = mysql_query($MemberSql);
				
				while($Member = mysql_fetch_assoc($MemberQuery))
				{
					$AllyMember[$AllyMemberID]['punkte'] = get_points($Member['id']);
					$AllyMember[$AllyMemberID]['id'] = $Member['id'];
					$AllyMember[$AllyMemberID]['lonline'] = $Member['lonline'];
					$AllyMember[$AllyMemberID]['name'] = $Member['name'];

					$AllyMemberID++;
				}

				$AllyMember = ArrayKeysort($AllyMember,'punkte');
				$AllyMember = array_reverse($AllyMember);

				echo '<table cellpadding="0" cellspacing="0" border="0" align="center" id="allytable" width="410">';

				echo '<tr>
						<td bgcolor="#b87b00" width="40"><strong>Platz</strong></td>
						<td bgcolor="#b87b00" width="150"><strong>Name</strong></td>
						<td bgcolor="#b87b00" width="40"><strong>Punkte</strong></td>
						<td bgcolor="#b87b00" width="40"><strong>Status</strong></td>
						<td bgcolor="#b87b00" width="100"><strong>letzte mal on?</strong></td>
					</tr>';

			$platz = 1;

			foreach($AllyMember as $Member)
			{
				if($Member['id'] == $Ally['boss'])
                {
					$Member['title'] .= ' <font style="font-size:9px;">(Oberhaupt)</font>';
                } elseif($Member['id'] == $Ally['vize'])
                {
					$Member['title'] .= ' <font style="font-size:9px;">(Vize)</font>';
                } elseif($Member['id'] == $Ally['diplo'])
				{
					$Member['title'] .= ' <font style="font-size:9px;">(Diplomat)</font>';
				}

				if($Member['lonline'] > time()-180)
                {
					$Member['status'] .= ' <font color="#31c32a">on</font>';
				} else
				{
					$Member['status'] .= ' <font color="#e36363">off</font>';
				}

				$Member2['lonline'] = date('H:i d.m.y',$Member['lonline']);

				echo '<tr onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';">
						<td>'.$platz.'</td>
						<td><a href="index.php?site=userinfo&info='.$Member['id'].'" target="_self">'.$Member['name'].'</a>'.$Member['title'].'</td>
						<td>'.$Member['punkte'].'</td>
						<td>'.$Member['status'].'</td>
						<td>'.$Member2['lonline'].'</td>';
				
				if (
				$user['id'] == $Ally['vize'] 
				&& $Member['id'] != $Ally['boss'] 
				&& $Member['id'] != $Ally['vize'] || $user['id'] == $Ally['diplo'] 
				&& $Member['id'] != $Ally['boss'] 
				&& $Member['id'] != $Ally['vize'] 
				&& $Member['id'] != $Ally['diplo'] || $user['id'] == $Ally['boss'] 
				&& $Member['id'] != $Ally['boss'])
				{
					echo'<td><a href="?site=schulen&sub=kick&who='.$Member['id'].'" target="_self">X</a></td>';
				}

				$platz = $platz + 1;

				echo'</tr>';

			}

			echo'</table>';

			$cmembersql = "SELECT count(id) FROM ".TABUSER." WHERE schule='$Ally[id]'";
			$cmemberq=mysql_query($cmembersql);
			$fe = mysql_fetch_row($cmemberq);
          
			$gelaendesql = "SELECT * FROM ally_schule WHERE id='$Ally[id]'";
			$gelaendeq = mysql_query($gelaendesql);
			$gelaende = mysql_fetch_row($gelaendeq);

			$T = explode('|',$gelaende['area']);
			$T = $T[0];

			if($User['AllyJoin'] == false && $user['schule'] == 0)
			{
				if($fe[0] < $Ally['maxmember'] || $Ally['maxmember'] == 0)
                {
					if(get_points($user['id']) >= $Ally['minpoints'])
					{
						echo '<br /><center><a href="index.php?site=schulen&sub=enter&add='.$Ally['id'].'" target="_self">Bei dieser Schule bewerben</a></center>';
					} else
					{
						echo '<br /><center>Du hast nicht gen&uuml;gend Punkte um aufgenommen zu werden<br />(es fehlen '.($Ally['minpoints']-get_points($user['id'])).' Pkt.)</center>';
					}
				} else
				{
					echo '<br /><center>Diese Schule nimmt momentan keine Member mehr auf.</center>';
				}
			} elseif($user['schule'] == $Ally['id'])
			{
				echo '<br /><center><a href="index.php?site=schulen&sub=leave" target="_self">Schule verlassen</a></center>';
			}
		} else
		{
			echo'<center><strong>Diese Allianz gibt es nicht!</strong></center>';
		}
	}

	function NewAlly()
	{
		global $user,$User;

		if($User['AllyJoin'] == false && $user['schule'] == 0)
		{
			$Replace = array(';',',','#',' ','        ');
			$_POST['name'] = str_replace($Replace,'',strip_tags(trim($_POST['name'])));
			$_POST['kuerzel'] = str_replace($Replace,'',strip_tags(trim($_POST['kuerzel'])));

			if(!empty($_POST['name']) && !empty($_POST['kuerzel']))
			{
				if($user['gold'] >= 50000 && $user['medallien'] >= 10)
				{
					if(strlen(strip_tags(trim($_POST['name']))) < 31 && strlen(strip_tags(trim($_POST['kuerzel']))) < 6)
					{
						$_POST['name'] = strip_tags(trim($_POST['name']));
						$_POST['kuerzel'] = strip_tags(trim($_POST['kuerzel']));

						$OldSql = "SELECT id,name FROM ".TABALLY." WHERE name='$_POST[name]' OR kuerzel='$_POST[kuerzel]' LIMIT 1";
                        $OldQuery = @mysql_query($OldSql);
                        $Old = @mysql_fetch_assoc($OldQuery);

                        if($Old == false)
                        {
                        	$NewSql2 = "INSERT INTO ".TABFORUMS." (name,type) VALUES ('$_POST[name]','allyin')";
                          	$NewQuery2 = mysql_query($NewSql2);
                          	$new_id = mysql_insert_id();
                          	
              				$NewSql = "INSERT INTO ".TABALLY." (name,kuerzel,pic,descr,vize,boss,diplo,gold,medallien,'privat') VALUES ('$_POST[name]','$_POST[kuerzel]','','','0','$user[id]','0','0','0','$new_id')";
                          	$NewQuery = mysql_query($NewSql);
                          	$new_id2 = mysql_insert_id();

                          	if($NewQuery != false && $NewQuery2 != false)
                          	{
                            	$user['schule'] = $Sel['id'];
                            	$user['gold'] -= 50000;
                            	$user['medallien'] -= 10;

								$UpSql = "UPDATE user Set schule='$new_id2',gold='$user[gold]',medallien='$user[medallien]' WHERE id='$user[id]' LIMIT 1";
								$UpQuery = mysql_query($UpSql);

								$NewSql3 = "INSERT INTO ally_gebet (schule,effekt,time) VALUES ('$new_id2','0','0')";
								$NewQuery3 = mysql_query($NewSql3);

								echo '<center><strong>Schule erfolgreich gegr&uuml;ndet!</strong><br /><br /><a href="index.php?site=schulen" target="_self">weiter</a></center>';
							}
                        } else
						{
							echo'<center><strong>Es gibt bereits eine Schule mit diesem Namen/K&uuml;rzel!</strong></center>';
						}
					} else
					{
						echo '<center><strong>Name/K&uuml;rzel zu lang!</strong></center>';
					}
				} else
				{
					echo '<center><strong>Du hast nicht gen&uuml;gend Gold und/oder Medallien!</strong></center>';
				}
			} else
			{
				echo '<center><font color="red">Achtung! Es kostet 50000 Gold und 10 Medallien um eine Schule zu gr&uuml;nden.</font><br /><br />
					<form name="newally" action="index.php?site=schulen&sub=new" method="post">
	                <strong>Name:</strong><br />
	                <input type="text" name="name" maxlength="30" /><br />
	                <font size="-2">(max. 30 Zeichen)</font><br /><br />
	                <strong>K&uuml;rzel:</strong><br />
	                <input type="text" name="kuerzel" maxlength="5" /><br />
	                <font size="-2">(max. 5 Zeichen)</font><br /><br />
	                <input type="submit" value="Gr&uuml;nden!" />
	                </form>';
			}
        } else
		{
			echo '<center><strong>Du kannst keine Schule gr&uuml;nden w&Auml;rend du dich bei einer bewirbst oder bereits einer beigetreten bist!</strong></center>';
		}
	}

  function AllyLand()
  {
    global $user,$UserAlly,$BuildDescr;

        $Area = explode('|',$UserAlly['area']);

        if(isset($_GET['ausbau']))
        {
          //if($user['id'] != $UserAlly['boss'] && $user['id'] != $UserAlly['vize'])
          //die("<center><strong>Du hast nicht die n&ouml;tige Befugniss!</strong></center>");

          $AusbauSql = "SELECT * FROM ".TABALLYBUILD." WHERE id='$_GET[ausbau]' ORDER BY id";
          $AusbauQuery = mysql_query($AusbauSql);
          $Ausbau = mysql_fetch_assoc($AusbauQuery);

          if($Ausbau != false)
          {
            $Cost = explode('|',$Ausbau['cost']);

            $Up = $_GET['ausbau']-1;
            if($Area[$Up] == 0)
                {
                  $Goldkosten = $Cost[0];
                  $Medkosten = $Cost[1];
                }
                elseif($Area[$Up] == 1)
                {
                  $Goldkosten = $Cost[0]*2;
                  $Medkosten = $Cost[1]*2;
                }
                else
                {
                  echo'<center><strong>Das Geb&Auml;ude ist schon auf der h&ouml;chsten Stufe!</strong></center>';
                  exit;
                }

            if($UserAlly['gold'] >= $Goldkosten && $UserAlly['medallien'] >= $Medkosten)
                {
                  // schulen gold/med abziehen
                  $UserAlly['gold'] -= $Goldkosten;
                  $UserAlly['medallien'] -= $Medkosten;
                  // schulen area + uppen
                  $Area[$Up]++;
                  $NewArea = implode('|',$Area);

                  $UpSchoolSql = "UPDATE ".TABALLY." Set gold='$UserAlly[gold]',medallien='$UserAlly[medallien]',area='$NewArea' WHERE id='$UserAlly[id]' LIMIT 1";
                  $UpSchool = mysql_query($UpSchoolSql);

                  echo'<center><strong>Geb&Auml;ude erfolgreich ausgebaut.</strong></center>';
                }
                else
                {
                  echo'<center><strong>Deine Schule besitzt nicht gen&uuml;gend Mittel (Gold, Medallien)!</strong></center>';
                }
          }
          else
          {
            echo'<center><strong>Diese Geb&Auml;udeart gibt es nicht!</strong></center>';
          }

          echo'<br />';
        }

    echo'
        <table cellpadding="0" cellspacing="0" border="0" width="440" align="center" id="allytable">
        <tr>
          <td bgcolor="#b87b00"><strong>Bild</strong></td>
          <td bgcolor="#b87b00"><strong>Name/Beschreibung/Ausbau</strong></td>
        </tr>';

        $Loop = 0;
        $BuildSql = "SELECT * FROM ".TABALLYBUILD." ORDER BY id";
        $BuildQuery = mysql_query($BuildSql);
        while($Build = mysql_fetch_assoc($BuildQuery))
        {
          if($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'] && $Area[$Loop] < 2)
          {
            $BuildCost = explode('|',$Build['cost']);

                if($Area[$Loop] == 0)
                {
                  $Ausbau = '<a href="index.php?site=schulen&sub=gel&ausbau='.$Build['id'].'" target="_self">
                  (Ausbauen f&uuml;r '.$BuildCost[0].' Gold und '.$BuildCost[1].' Medallien)</a>';
                }
                elseif($Area[$Loop] == 1)
                {
                  $Ausbau = '<a href="index.php?site=schulen&sub=gel&ausbau='.$Build['id'].'" target="_self">
                  (Ausbauen f&uuml;r '.(2*($BuildCost[0])).' Gold und '.(2*($BuildCost[1])).' Medaillen)</a>';
                }
                else
                {
                  $Ausbau = '';
                }
          }
          else
          {
            $Ausbau = '';
          }

          if($Area[$Loop] == 0)
          {
            $Head = 'leeres Grundst&uuml;ck';
                $Descr = 'Hier k&ouml;nnte folgendes Geb&Auml;ude stehen: '.$Build['first'];
          }
          elseif($Area[$Loop] == 1)
          {
            $Head = $Build['first'];
                $Descr = $Build['descrfirst'];
          }
          else
          {
            $Head = $Build['second'];
                $Descr = $Build['descrsecond'];
          }

          echo'
          <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';">
            <td width="60" height="60"style="padding:0px;">
                  <img src="Images/AllyBuildings/nopic.gif" border="0" width="60" height="60" />
                </td>
            <td style="text-align:left;"><strong>'.$Head.'</strong> '.$Ausbau.'<br /><div style="margin-top:4px;">'.$Descr.'</div></td>
          </tr>';

          $Loop++;
        }

        echo'</table>';
  }

  function AllyGebet()
  {
        global $user, $UserAlly;

        $GebetSql = "SELECT * FROM ally_gebet WHERE schule='$UserAlly[id]' LIMIT 1";
        $GebetQuery = mysql_query($GebetSql);
        $gebet = mysql_fetch_assoc($GebetQuery);

        $time = time();

        if($time >= $gebet['time'])
        {

                echo'<center>Die m&auml;chtigen G&ouml;tter <br /><br /> Sch&ouml;pfer alles Lebens und Herrscher &uuml;ber die Natur <br /><br /> sind gegen&uuml;ber Schulen, die ihnen Opfergaben bieten und zu ihnen beten, sehr wohlgesonnen.<br \>
                F&uuml;r nur<strong>200.000 Gold</strong>und<strong>100 Medaillen</strong>unterst&uuml;tzen sie auch dich und deine Gladiatoren f&uuml;r drei Tage.<br \><br \>
                Deine Schule hat zur Zeit<strong>'.$UserAlly['gold'].' Gold</strong>und<strong>'.$UserAlly['medallien'].' Medaillen</strong>in ihrer Schatzkammer.<br \><br \></center>';
                echo '<table><form aktion="index.php?site=schulen&sub=gebet" name="gebet" method="post">
                 <tr><td bgcolor="#b87b00"><strong>G&ouml;tter</strong></td><td bgcolor="#b87b00"><strong>Beten</strong></td></tr>
				<tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zum gro&szlig;en Mars</b><br>der Gott des zerst&ouml;rerischen Krieges und der Schlachten<br><i>(bringt 10% mehr Off und Deff)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Mars beten" name="gebet"></td></tr>
                <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zum gro&szlig;en Jupiter</b><br>unserem G&ouml;ttervater <br><i>(Alle Spezial Moves +2)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Jupiter beten" name="gebet"></td></tr>
        <!--        <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zum gro&szlig;en Merkur</b><br>dem Gott des Handels <br><i>(Schmiedpreise sinken um 15%)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Merkur beten" name="gebet"></td></tr> -->
                <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zur gro&szlig;en Minerva</b><br>die G&ouml;ttin der Weisheit <br><i>(Intelligenz steigt um 5)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Minerva beten" name="gebet"></td></tr>
                <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zur gro&szlig;en Venus</b><br>die G&ouml;ttin der Liebe und Sch&ouml;nheit <br><i>(Charisma steigt um 5)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Venus beten" name="gebet"></td></tr>
                <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zum gro&szlig;en Hercules</b><br>dem Beschirmer der Sportst&auml;tten und Pal&auml;ste <br><i>(Belohnung in der Arena steigt um 25%)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Herkules beten" name="gebet"></td></tr>
                <tr valign="top" onmouseover="this.style.backgroundColor=\'darkred\';" onmouseout="this.style.backgroundColor=\'\';"><td style="text-align:left;"><b>Bete zur gro&szlig;en Fortuna</b><br>die G&ouml;ttin des gl&uuml;cks <br><i>(Droprate steigt um 5%)</i><br></td><td style="text-align:right;"><input type="submit" value="Zu Fortuna beten" name="gebet"></td></tr>
				</form></table>';

                if($_POST['gebet'] == "Zu Mars beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='1' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zum gro&szlig;en Mars gesprochen.
																	Mars wird dich f&uuml;r die n&auml;chsten 3 Tage im Kampf St&auml;rken!');
								}
                        }
                        else
                        {
                                echo' <br \><center>Deine Schule hat nicht gen&uuml;gend Opfergaben</center>';
                        }
                }
                elseif($_POST['gebet'] == "Zu Jupiter beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='2' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zum gro&szlig;en Jupiter gesprochen.
																	Jupiter wird dich f&uuml;r die n&auml;chsten 3 Tage mit verbesserten Techniken segnen!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben';
                        }
                }
                elseif($_POST['gebet'] == "Zu Merkur beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='3' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zum gro&szlig;en Merkur gesprochen.
																	Merkur sorgt daf&uuml;r, dass die n&auml;chsten 3 Tage die Schmiede f&uuml;r dich g&uuml;nstiger ist!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben';
                        }
                }
                elseif($_POST['gebet'] == "Zu Minerva beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='4' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zur gro&szlig;en Minerva gesprochen.
																	Minerva wird f&uuml;r die n&auml;chsten 3 Tage deine Intelligenz im Kampf erh&ouml;hen!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben';
                        }
                }
                elseif($_POST['gebet'] == "Zu Venus beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='5' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zur gro&szlig;en Venus gesprochen.
																	Venus wird dich f&uuml;r die n&auml;chsten 3 Tage im Kampf charismatischer machen!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben';
                        }
                }
                elseif($_POST['gebet'] == "Zu Herkules beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='6' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zum gro&szlig;en Herkules gesprochen.
																	Herkules wird dich f&uuml;r die n&auml;chsten 3 Tage im Kampf h&ouml;her belohnen!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben';
                        }
                }
				elseif($_POST['gebet'] == "Zu Fortuna beten")
                {
                        if($UserAlly['gold'] >=200000 AND $UserAlly['medallien'] >=100)
                        {
                                $gebet['time'] = (time() + 259200);
                                $UserAlly['gold'] = $UserAlly['gold'] - 200000;
                                $UserAlly['medallien'] -= 100;

                                $timeSql = "UPDATE ally_gebet Set time='$gebet[time]' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $timeQuery = mysql_query($timeSql);
                                $UpSql = "UPDATE ally_gebet Set effekt='7' WHERE schule='$UserAlly[id]' LIMIT 1";
                                $UpQuery = mysql_query($UpSql);
                                $goldSql = "UPDATE ally_schule Set gold='$UserAlly[gold]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $goldQuery = mysql_query($goldSql);
                                $medSql = "UPDATE ally_schule Set medallien='$UserAlly[medallien]' WHERE id='$UserAlly[id]' LIMIT 1";
                                $medQuery = mysql_query($medSql);

                                echo' <br \><center>Deine Gebete wurden erh&ouml;rt</center>';

								$Query = mysql_query("SELECT * FROM user WHERE schule='$UserAlly[id]'");
								while($empf = mysql_fetch_assoc($Query))
								{
									IMG('Schulenleitung',$empf['name'],'Ein neues Gebet','Deine Schule hat soeben ein Gebet zur gro&szlig;en Fortuna gesprochen.
																	Fortuna wird die n&auml;chsten 3 Tage dein Findegl&uuml;ck erh&ouml;hen!');
								}
                        }
                        else
                        {
                                echo' Deine Schule hat nicht gen&uuml;gend Opfergaben.';
                        }
                }
        }
        else
        {
                if($gebet['effekt'] == 1)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Mars';
                }
                elseif($gebet['effekt'] == 2)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Jupiter';
                }
                elseif($gebet['effekt'] == 3)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Merkur';
                }
                elseif($gebet['effekt'] == 4)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Minerva';
                }
                elseif($gebet['effekt'] == 5)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Venus';
                }
                elseif($gebet['effekt'] == 6)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Hercules';
                }
				elseif($gebet['effekt'] == 7)
                {
                        echo' Deine Schule steht derzeit unter dem Schutz von Fortuna';
                }
                else
                {
                        echo' Deine Schule hat noch nie zu einem Gott gebetet';
                }
        }
  }

  function AllyOptions()
  {
    global $user,$schule;

    $Error = array();
        $Success = array();

    if(isset($_POST['name'])) // name,K&uuml;rzel,descr
        {
          $UpString = array();

          if(!blank($_POST['name']))
          {
            $_POST['name'] = strip_tags(trim($_POST['name']));

            if(strlen(blank($_POST['name'])) > 30)
                {
                  $Error[] = 'Neuer Name zu lang (max. 30 Zeichen)';
                }
                elseif(blank($_POST['name']))
                {
                  $Error[] = 'Neuer Name zu kurz';
                }
                elseif($_POST['name'] == $schule['name'])
                {
                  $Success[] = 'Name bleibt wie er ist...';
                }
                else
                {
                  $Sql = "SELECT count(id) FROM forum_forums WHERE id='$schule[public]' AND type='allyout' LIMIT 1";
                  $Query = @mysql_query($Sql);
                  $Fetch = @mysql_fetch_row($Query);

                  if($Fetch[0] >= 1)
                  {
					$UpSql = "UPDATE forum_forums Set name='$_POST[name]' WHERE name='$schule[name]' AND type='allyout' LIMIT 1";
					$Up = mysql_query($UpSql);
                  }

                  $UpSql = "UPDATE forum_forums Set name='$_POST[name]' WHERE id='$schule[private]' AND type='allyin' LIMIT 1";
                  $Up = mysql_query($UpSql);

              $UpString[] = "name='$_POST[name]'";
                  $Success[] = 'Name erfolgreich ge&Auml;ndert';
                }
          }

          if(!blank($_POST['kuerzel']))
          {
            $_POST['kuerzel'] = strip_tags(trim($_POST['kuerzel']));

            if(strlen(blank($_POST['kuerzel'])) > 5)
                {
                  $Error[] = 'Neues K&uuml;rzel zu lang (max. 5 Zeichen)';
                }
                elseif(blank($_POST['kuerzel']))
                {
                  $Error[] = 'Neues K&uuml;rzel zu kurz';
                }
                elseif($_POST['kuerzel'] == $schule['kuerzel'])
                {
                  $Success[] = 'K&uuml;rzel bleibt wie es ist...';
                }
                else
                {
              $UpString[] = "kuerzel='$_POST[kuerzel]'";
                  $Success[] = 'K&uuml;rzel erfolgreich ge&Auml;ndert';
                }
          }

          if(isset($_POST['descr']))
          {
            $_POST['descr'] = nl2br(strip_tags($_POST['descr'],'<b><u><i>'));

            if(strlen(blank($_POST['descr'])) > 1500)
                {
                  $Error[] = 'Neue Beschreibung zu lang (max. 1500 Zeichen)';
                }
                elseif(blank($_POST['descr']))
                {
                  $Error[] = 'Neue Beschreibung zu kurz';
                }
                elseif($_POST['descr'] == $schule['descr'])
                {
                  $Success[] = 'Beschreibung bleibt wie sie ist...';
                }
                else
                {
              $UpString[] = "descr='$_POST[descr]'";
                  $Success[] = 'Beschreibung erfolgreich ge&Auml;ndert';
                }
          }

          $UpString = implode(' AND ',$UpString);
          $UpSql = "UPDATE ".TABALLY." Set $UpString WHERE id='$schule[id]' LIMIT 1";
          $UpQuery = mysql_query($UpSql);
        }
        elseif(isset($_POST['pic']))
        {
          $_POST['pic'] = strip_tags(trim($_POST['pic']));

          if(substr($_POST['pic'],0,7) != 'http://')
      {
                $Error[] = 'Ung&uuml;ltige Webadresse (http:// vergessen)';
          }
          else
          {
                $Success[] = 'Schulenbild erfolgreich ge&Auml;ndert.';
          }

          $UpSql = "UPDATE ".TABALLY." Set pic='$_POST[pic]' WHERE id='$schule[id]' LIMIT 1";
          $UpQuery = mysql_query($UpSql);
        }
        elseif(isset($_POST['join']))
        {
          $usr = $_POST['join'];

          $Sql = "SELECT id,name,schule FROM ".TABUSER." WHERE id='$usr' LIMIT 1";
          $Query = mysql_query($Sql);
          $Join = mysql_fetch_assoc($Query);

          if($Join != false && substr($Join['schule'],2) == $user['schule'])
          {
            $Success[] = $Join['name'].' wurde in deine Schule aufgenommen';

                $UpSql = "UPDATE ".TABUSER." Set schule='$user[schule]' WHERE id='$Join[id]' LIMIT 1";
            $UpQuery = mysql_query($UpSql);

                $Text = 'das Oberhaupt der Schule hat entschieden, dass du w&uuml;rdig genug bist um in die Schule aufgenommen zu werden.';

                send_igm('Du wurdest in die Schule '.$schule['name'].' aufgenommen',$Text,$Join['name'],'admin');

          }
          else
          {
            $Error[] = 'Fehler. Bitte wende dich an den Admin!';
          }
        }
        elseif(isset($_GET['for']) && !empty($_GET['for']))
        {
        	var_dump($schule);
          if($schule['medallien'] >= 500)
          {
            $Sql = "SELECT count(id) FROM forum_forums WHERE name='$schule[name]' AND type='allyout'";
                $Query = mysql_query($Sql);
                $Fetch = mysql_fetch_row($Query);

                if($Fetch[0] == 0)
                {
                  $schule['medallien'] -= 500;

                  $InSql = "INSERT INTO forum_forums (name,type) VALUES ('$schule[name]','allyout')";
                  $In = mysql_query($InSql);
                  $IN_id = mysql_insert_id();

                  $UpSSql = "UPDATE ".TABALLY." Set medallien='$schule[medallien]', public='$IN_id' WHERE id='$schule[id]' LIMIT 1";
                  $UpSQuery = mysql_query($UpSSql);
                  
                  $Success[] = 'Schulenforum erfolgreich angelegt.';
                } else
                {
                  $Error[] = 'Deine Schule besitzt bereits ein Forum!';
                }
          }
          else
          {
            $Error[] = 'Deine Schule besitzt nicht gen&uuml;gend Medaillen.';
          }
        }
        elseif(isset($_POST['vize']))
        {
          $usr = $_POST['vize'];

          $Sql = "SELECT id,name,schule FROM ".TABUSER." WHERE name='$usr' LIMIT 1";
          $Query = mysql_query($Sql);
          $Vize = mysql_fetch_assoc($Query);

          if($Vize != false && $Vize['schule'] == $user['schule'] && $Vize['id'] != $schule['boss'])
          {
            if($Vize['id'] == $schule['vize'])
                {
              $Success[] = $Vize['name'].' bleibt Vize';
                }
                else
                {
                  $Success[] = $Vize['name'].' wurde als neuer Vize bestimmt';

                  $UpSql = "UPDATE ".TABALLY." Set vize='$Vize[id]' WHERE id='$schule[id]' LIMIT 1";
              $UpQuery = mysql_query($UpSql);
                }
          }
          else
          {
            $Error[] = 'Fehler. Bitte wende dich an den Admin!';
          }
        }


             elseif(isset($_POST['diplo']))
        {
          $usr2 = $_POST['diplo'];

          $Sql = "SELECT id,name,schule FROM ".TABUSER." WHERE name='$usr2' LIMIT 1";
          $Query = mysql_query($Sql);
          $diplo = mysql_fetch_assoc($Query);

          if($diplo != false && $diplo['schule'] == $user['schule'] && $diplo['id'] != $schule['boss'])
          {
            if($diplo['id'] == $schule['diplo'])
                {
              $Success[] = $diplo['name'].' bleibt diplomat';
                }
                else
                {
                  $Success[] = $diplo['name'].' wurde als neuer Diplomat bestimmt';

              $UpSql = "UPDATE ".TABALLY." Set diplo='$diplo[id]' WHERE id='$schule[id]' LIMIT 1";
              $UpQuery = mysql_query($UpSql);
                }
          }
          else
          {
            $Error[] = 'Fehler. Bitte wende dich an den Admin!';
          }
        }



        elseif(isset($_POST['taxpercent']) && isset($_POST['taxprotect']))
        {
          $_POST['taxpercent'] = strip_tags(trim($_POST['taxpercent']));
          $_POST['taxprotect'] = strip_tags(trim($_POST['taxprotect']));

          $perc = $_POST['taxpercent'];
      $prot = $_POST['taxprotect'];

          if($perc >= 0 && $perc < 21 && is_numeric($perc))
          {
                $Success[] = 'Steuersatz erfolgreich ge&Auml;ndert';

                $UpSql = "UPDATE ".TABALLY." Set taxpercent='$perc' WHERE id='$schule[id]' LIMIT 1";
            $UpQuery = mysql_query($UpSql);
          }
          else
          {
                $Error[] = 'Ein zu hoher/niedriger Steuersatz wurde gew&Auml;hlt';
          }

          if($prot >= 0 && is_numeric($prot))
          {
                $Success[] = 'Newb Befreiung erfolgreich ge&Auml;ndert';

                $UpSql = "UPDATE ".TABALLY." Set taxprotect='$prot' WHERE id='$schule[id]' LIMIT 1";
            $UpQuery = mysql_query($UpSql);
          }
          else
          {
                $Error[] = 'keine g&uuml;ltige Punktzahl angegeben';
      }
        }
        elseif(isset($_POST['minp']) && isset($_POST['maxm']))
        {
          $minp = strip_tags($_POST['minp']);
      $maxm = strip_tags($_POST['maxm']);

          if($minp >= 0 && is_numeric($minp))
          {
            if($minp != $schule['minpoints'])
                {
                  $Success[] = 'min. Punktzahl f&uuml;r Member ge&Auml;ndert';

                  $UpSql = "UPDATE ".TABALLY." Set minpoints='$minp' WHERE id='$schule[id]' LIMIT 1";
              $UpQuery = mysql_query($UpSql);
                }
                else
                {
                  $Success[] = 'min. Punktzahl f&uuml;r Member bleibt so';
                }
          }
          else
          {
                $Error[] = 'min. Punktzahl ist falsch';
          }

          if($maxm >= 0 && is_numeric($maxm))
          {
          $gelaendesql = "SELECT area FROM ally_schule WHERE id='$schule[id]'";
          $gelaendeq = mysql_query($gelaendesql);
          $gelaende = mysql_fetch_array($gelaendeq);

          $T = explode("|",$gelaende['area']);
          $T = $T[4];

         	if($maxm == 0)
          	{
           		$Error[] = 'keine g&uuml;ltige max. Memberzahl angegeben.';

				//$UpSql = "UPDATE ".TABALLY." Set maxmember='1' WHERE id='$schule[id]' LIMIT 1";
				//$UpQuery = mysql_query($UpSql);
          	}
			elseif($maxm >50)
            {
              	$Error[] = 'die max. Memberzahl ist ung&uuml;ltig.';
           	}
			else
			{
                if($T == 2)
                {
                    if($maxm != $schule['maxmember'] AND $maxm <=50)
                        {
                          	$Success[] = 'max. Memberzahl ver&Auml;ndert';

                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='$maxm' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                        else
                        {
                          $Success[] = 'Die max. Memberzahl wurde ver&Auml;ndert.';

                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='50' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                }
                elseif($T == 1)
                {
                    if($maxm != $schule['maxmember'] AND $maxm <=25)
                        {
                          $Success[] = 'max. Memberzahl ver&Auml;ndert';

                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='$maxm' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                        else
                        {
                          $Success[] = 'max. Memberzahl wurde gesetzt.';

                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='25' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                }
                elseif($T == 0)
                {
                    if($maxm != $schule['maxmember'] AND $maxm <=15)
                        {
                          $Success[] = 'max. Memberzahl ver&Auml;ndert';

                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='$maxm' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                        else
                        {
                          $Success[] = 'Memberzahl auf das Maximum gesetzt.';

						  echo "123".$T."qweqwe".$gelaende['area'];


                          	$UpSql = "UPDATE ".TABALLY." Set maxmember='15' WHERE id='$schule[id]' LIMIT 1";
                      		$UpQuery = mysql_query($UpSql);
                        }
                }
                else
                {
                  	$Error[] = 'keine g&uuml;ltige max. Memberzahl angegeben.';
                }
			}

        }
        }
        elseif(isset($_POST['ally']) && $_POST['choose'])
        {
          foreach($_POST['ally'] as $Allys)
          {
            //echo $Allys.',';
                $schule['war'] = explode(',',$schule['war']);

                $AllyName = $this->SingleAlly($Allys,'id,name');
                $AllyName = $AllyName['name'];

                switch($_POST['choose'])
                {
                  case 1: // neutral

                  if(array_search($Allys,$schule['war'])) // found
                  {
                    $Success[] = 'der Krieg mit der Schule '.$AllyName.' wurde beendet.';
                        // HIER MACHNE
                        // HIER MACHNE
                        // HIER MACHNE
                        // HIER MACHNE ----> ENTFERNEN ARRAYELEMENT MIT SCHULEN ID (fremde)

                        foreach($schule['war'] as $Key => $Search)
                        {
                          if($Search == $Allys)
                          {
                            $KeyBack = $Key;
                                break;
                          }
                        }

                        unset($schule['war'][$KeyBack]);
                        $schule['war'] = implode(',',$schule['war']);

                        $UpWarSql = "UPDATE ".TABALLY." Set war='$schule[war]' WHERE id='$schule[id]' LIMIT 1";
                    $UpWar = mysql_query($UpWarSql);

                  }
                  else
                  {
                    $Success[] = 'es herscht kein Krieg mit der Schule '.$AllyName;
                  }

                  break;

                  case 2: // war

                  if(!array_search($Allys,$schule['war'])) // not found
                  {
                    $schule['war'] = implode(',',$schule['war']);
                    $schule['war'].=','.$Allys;

                        $UpWarSql = "UPDATE ".TABALLY." Set war='$schule[war]' WHERE id='$schule[id]' LIMIT 1";
                    $UpWar = mysql_query($UpWarSql);

                        $Success[] = 'es wurde erfolgreich eine Kriegserkl&Auml;rung an die Schule '.$AllyName.' versandt.';

                        // MESSAGE boss+vize + enemy ally boss/vize
                  }
                  else
                  {
                    $Success[] = 'es herrscht bereits Kriegsstimmung mit der Schule '.$AllyName;
                  }

                  break;

                  default: $Error='falsche Men&uuml;auswahl'; break;
                }
          }
        }
        elseif(isset($_POST['taktik']) && is_numeric($_POST['taktik']) && $_POST['taktik'] >= 0 && $_POST['taktik'] <= 6)
        {
          if($_POST['taktik'] != $schule['tactic'])
          {
            $Success[] = 'Schulentaktik erfolgreich ge&Auml;ndert';

            $UpSql = "UPDATE ".TABALLY." Set tactic='$_POST[taktik]' WHERE id='$schule[id]' LIMIT 1";
            $UpQuery = mysql_query($UpSql);
          }
          else
          {
            $Success[] = 'Schulentaktik unver&Auml;ndert';
          }
        }
        else
        {
          switch($schule['tactic'])
          {
            case 0: $g = 'keine'; break;
                case 1: $g = 'Hau nei!'; break;
                case 2: $g = 'Un&uuml;berwindware Verteidigung'; break;
                case 3: $g = '2 Waffen sind besser als eine!'; break;
				case 4: $g = 'Schrei dich an die Macht!';break;
				case 5: $g = 'Seine St&auml;rke ist meine St&auml;rke!';break;
				case 6: $g = 'Schild hoch!';break;
          }

          $Descr = br2nl($schule['descr']);

          echo'
          <center><form name="allgemeines" action="" method="post">
            <strong>Name</strong><br />
                <input name="name" maxlength="30" type="text" value="'.$schule['name'].'" /><br /><br />
                <strong>K&uuml;rzel</strong><br />
                <input name="kuerzel" maxlength="5" type="text" value="'.$schule['kuerzel'].'" /><br /><br />
                <strong>Beschreibung</strong><br />
                <textarea name="descr" style="width:500px;height:200px;font-size:11px;padding:1px;">'.$Descr.'</textarea><br /><br />
                <input type="submit" value="&Auml;ndern" />
                </form>
                <hr width="80%" />
                <form action="" name="picchange" method="post">
                <strong>Schulenbild</strong><br />
                  <input type="text" name="pic" value="'.$schule['pic'].'" style="width:300px;" /><br /><br />
                  <input type="submit" value="&Auml;ndern" />
                </form>
                <hr width="80%" />
                <form name="join" action="" method="post">
                <strong>Bewerber</strong><br /><br />';

                $Joinpath = '0j'.$user['schule'];
                $Sql = "SELECT id,name,schule FROM ".TABUSER." WHERE binary schule='$Joinpath'";
                $Query = mysql_query($Sql);
                while($Join = mysql_fetch_assoc($Query))
                {
                  echo $Join['name'].' ('.get_points($Join['id']).' Punkte) <input type="radio" name="join" value="'.$Join['id'].'" /><br />';
                }

                echo'<br /><br /><input type="submit" value="Zulassen" /></form><hr width="80%" />
                <font size="-2"><a href="index.php?site=schulen&sub=ver&for=j" target="_self">&Ouml;ffentliches Forum erwerben (500 Medallien)</a><br />
                <div style="width:320px;">
                Ein Unterforum im Gladiatorz Forum auf das alle Spieler Zugriff haben und das von dir moderiert wird.</div></font>
                <hr width="80%" />';

                if($user['id'] == $schule['boss'])
                {

                echo'<form method="post" action="" name="vize">
                <strong>Vize</strong><br /><br />
                <select name="vize">';

                $Arr = $this->AllyMember($user['schule'],$schule['boss']);

                foreach($Arr as $ausgabe)
            {
                  echo '<option>'.$ausgabe.'</option>';
            }

                echo'</select> <input type="submit" value="&Auml;ndern" /><br />
                <div style="width:320px;"><font size="-2">Ein Vize &uuml;bernimmt dein Amt und deine Aufgaben, wenn du mal nicht da bist. Aber auch w&auml;hrend deiner Anwesenheit, hat der Vize Zugriff auf viele Verwaltungsm&ouml;glichkeiten.</font></div>
                </form><hr width="80%" />';

                }




                 if($user['id'] == $schule['boss'])
                {

                echo'<form method="post" action="" name="diplo">
                <strong>Diplomat</strong><br /><br />
                <select name="diplo">';

                $Arr = $this->AllyMember($user['schule'],$schule['boss']);

                foreach($Arr as $ausgabe2)
            {
                  echo '<option>'.$ausgabe2.'</option>';
            }

                echo'</select> <input type="submit" value="&Auml;ndern" /><br />
                <div style="width:320px;"><font size="-2">Ein Diplomat &uuml;bernimmt dein Amt und deine Aufgaben, wenn du mal nicht da bist. Aber auch w&auml;hrend deiner Anwesenheit, hat der Diplomat Zugriff auf viele Verwaltungsm&ouml;glichkeiten.</font></div>
                </form><hr width="80%" />';

                }

                echo'
                <form method="post" action="" name="steuer">
                <strong>Steuern</strong><br /><br />
                Satz (0-20%)<br /><input type="text" name="taxpercent" maxlength="2" value="'.$schule['taxpercent'].'" /><br /><br />
                Newb-Berfreiung (Pkt.)<br /><input type="text" name="taxprotect" maxlength="6" value="'.$schule['taxprotect'].'" /><br /><br />
                <input type="submit" value="&Auml;ndern" />
                </form>
                <hr width="80%" />
                <form method="post" action="" name="member">
                <strong>Aufnahme</strong><br /><br />
                min. Punkte<br /><input type="text" name="minp" maxlength="6" value="'.$schule['minpoints'].'" /><br /><br />
                max. Member<br /><input type="text" name="maxm" maxlength="3" value="'.$schule['maxmember'].'" /><br /><br />
                <input type="submit" value="&Auml;ndern" />
                </form>
                <hr width="80%" />
                <form method="post" action="" name="allywar">
                <strong>B&uuml;ndnisse/Kriege</strong><br /><br /></center>
                <div style="margin-left:160px;width:14px;height:14px;background-color:lightgray;float:left;border:1px solid black;margin-right:10px;"></div>Neutral<br /><br />
                <div style="border:1px solid black;margin-right:10px;margin-left:160px;width:14px;height:14px;background-color:yellow;float:left;"></div>Kriegsangebot (von euch)<br /><br />
                <div style="border:1px solid black;margin-right:10px;margin-left:160px;width:14px;height:14px;background-color:darkorange;float:left;"></div>Kriegsangebot (von denen)<br /><br />
                <div style="border:1px solid black;margin-right:10px;margin-left:160px;width:14px;height:14px;background-color:red;float:left;"></div>Krieg<br /><br /><center>
                <select multiple name="ally[]" style="font-size:12px;height:160px;">'.$this->AllyNames($schule['id']).'</select><br /><br />
                <input name="choose" type="radio" value="1" />Neutral
                <input name="choose" type="radio" value="2" />Krieg<br /><br />
                <input type="submit" value="&Auml;ndern" />
                </form>
                <hr width="80%" />
                <form method="post" action="" name="taktik">
                <strong>Taktik</strong><br />
                <font style="font-size:10px;color:darkgray;">Aktuell: '.$g.'</font><br /><br />
                &Auml;ndern: <select name="taktik" id="tac" onchange="changeTac()">
                  <option value="0">keine</option>
                  <option value="1">Hau nei!</option>
                  <option value="2">Un&uuml;berwindware Verteidigung!</option>
                  <option value="3">2 Waffen sind besser als eine!</option>
				  <option value="4">Schrei dich an die Macht!</option>
				  <option value="5">Seine St&auml;rke ist meine St&auml;rke!</option>
				  <option value="6">Schild hoch!</option>
                </select> <input type="submit" value="Go" />
                <p id="tactext" style="font-size:10px;width:200px;color:darkgray;">...working...</p>
                </form>
          </center>
          <script>changeTac()</script>';
        }

        if(count($Error) > 0 || count($Success) > 0)
        {
          echo'<center><strong>';

          foreach($Success as $GiveOut)
          {
            echo'<font color="#31c32a">'.$GiveOut.'</font><br /><br />';
          }

          foreach($Error as $GiveOut)
          {
            echo'<font color="#e36363">'.$GiveOut.'</font><br />';
          }

          echo'</strong><a href="index.php?site=schulen&sub=ver" target="_self">zur&uuml;ck</a></center>';
        }
  }

  // Hilfsmethoden
  function AllyPoints($AllyID)
  {
  	$Sql = "SELECT * FROM highscore WHERE schule='$AllyID'";
  	$Query = mysql_query($Sql);
  	while($User = mysql_fetch_assoc($Query))
	{
		$Points = $Points + $User['points'];
	}

	if($Points > 0)
	{
		return $Points;
	}
  }

  function LeaveAlly($ID)
  {
    // user finden
        $UserSql = "SELECT * FROM ".TABUSER." WHERE id='$ID' LIMIT 1";
        $UserQuery = mysql_query($UserSql);
        $User = mysql_fetch_assoc($UserQuery);

    $Schule = $User['schule'];

        // ally finden
    $AllySql = "SELECT * FROM ".TABALLY." WHERE id='$Schule' LIMIT 1";
        $AllyQuery = mysql_query($AllySql);
        $Ally = mysql_fetch_assoc($AllyQuery);

    // user entschulen
        $UserUpSql = "UPDATE ".TABUSER." Set schule='0' WHERE id='$ID' LIMIT 1";
        $UserUp = mysql_query($UserUpSql);

        if($UserUp == true && $user['id'] == $ID)
        {
          echo'<center><strong>Du hast deine Schule erfolgreich verlassen.</strong></center>';
        }

        // wenn user 1 mann ally -> l&ouml;schen
        $MemberSql = "SELECT count(id) FROM ".TABUSER." WHERE schule='$Schule'";
        $MemberQuery = mysql_query($MemberSql);
        $Member = mysql_fetch_row($MemberQuery);

        // jetzt aufl&ouml;sen... (ally+forum)
        if($Member[0] == 0)
        {
          $DelForumSql = "DELETE FROM ".TABFORUMS." WHERE binary name='$Ally[name]' LIMIT 2";
          $DelForum = mysql_query($DelForumSql);

          $DelAllySql = "DELETE FROM ".TABALLY." WHERE id='$Schule' LIMIT 1";
          $DelAlly = mysql_query($DelAllySql);

          if($user['id'] == $ID)
          {
            echo'<br /><center><strong>Deine Schule wurde aufgel&ouml;st.</strong></center>';
          }
        }
        elseif($User['id'] == $Ally['boss'])
        {
          if($Ally['vize'] != 0) // vize zum boss machen
          {
            $AllyUpSql = "UPDATE ".TABALLY." Set vize='0',boss='$Ally[vize]' WHERE id='$Schule' LIMIT 1";
                $AllyUp = mysql_query($AllyUpSql);
          }
          else // user mit der niedrigsten id zum boss machen
          {
            $HighestUserSql = "SELECT id,name,schule FROM ".TABUSER." WHERE schule='$Schule' ORDER BY id LIMIT 1";
                $HighestUserQuery = mysql_query($HighestUserSql);
                $HighestUser = mysql_fetch_assoc($HighestUserQuery);

                $AllyUpSql = "UPDATE ".TABALLY." Set boss='$HighestUser[id]' WHERE id='$Schule' LIMIT 1";
                $AllyUp = mysql_query($AllyUpSql);
          }
        }
        elseif($User['id'] == $Ally['vize'])
        {
          $AllyUpSql = "UPDATE ".TABALLY." Set vize='0' WHERE id='$Schule' LIMIT 1";
          $AllyUp = mysql_query($AllyUpSql);
        }
  }

  function AllyMember($ID,$Boss,$Request='name')
  {
    $RequestArray = array();

    $Sql = "SELECT id,$Request FROM ".TABUSER." WHERE schule='$ID'";
        $Query = mysql_query($Sql);
        while($User = mysql_fetch_assoc($Query))
        {
          if($User['id'] != $Boss)
          {
            $RequestArray[] = $User[$Request];
          }
        }

        return $RequestArray;
  }

  function AllyNames($Not)
  {
    $Return = null;

        $OwnAllySql = "SELECT id,name,war FROM ".TABALLY."  WHERE id = '$Not' LIMIT 1";
        $OwnAllyQuery = mysql_query($OwnAllySql);
        $OwnAlly = mysql_fetch_assoc($OwnAllyQuery);

        $OwnAlly['war'] = explode(',',$OwnAlly['war']);

    $Sql = "SELECT id,name,war FROM ".TABALLY."  WHERE id != '$Not' ORDER BY name";
        $Query = mysql_query($Sql);
        while($Allys = mysql_fetch_assoc($Query))
        {
          $Allys['war'] = explode(',',$Allys['war']);

          if(!array_search($Not,$Allys['war'])) // not found (neutral)
          {
            if(!array_search($Allys['id'],$OwnAlly['war'])) // rl not found (rl neutral)
                {
                  $Color = 'silver';
                }
                else // war offer from me
                {
                  $Color = 'yellow';
                }
          }
          else // war offer from enemy ally or WAR
          {
            if(!array_search($Allys['id'],$OwnAlly['war'])) // war offer from enemy
                {
                  $Color = 'darkorange';
                }
                else // WAR
                {
                  $Color = 'red';
                }
          }

          $Return .= '<option style="background-color:'.$Color.';" value="'.$Allys['id'].'">'.$Allys['name'].'</option>';
        }

        return $Return;
  }

  function isWar($ID) // enemy ally
  {
    global $user,$schule;


        $Sql = "SELECT id,name,war FROM ".TABALLY." WHERE id='$ID' LIMIT 1";
        $Query = mysql_query($Sql);
        $Ally = mysql_fetch_assoc($Query);

        if($Ally == true)
        {
          $AllyWar = explode(',',$Ally['war']);
          $Wars = explode(',',$schule['war']);
          //print_r($schule['war']);

          if(array_search($schule['id'],$AllyWar))
          {
            if(array_search($Ally['id'],$Wars)) // war
                {
                  return true;
                }
                else
                {
                  return false;
                }
          }
        }
        else
        {
          return false;
        }
  }

  function AllyNews()
  {
    global $user,$schule;

        $Joinpath = '0j'.$user['schule'];

    $Sql = "SELECT count(id) FROM ".TABUSER." WHERE binary schule='$Joinpath'";
        $Query = mysql_query($Sql);
        $Bewerber = mysql_fetch_row($Query);

    $Sql = "SELECT * FROM forum_forums WHERE type='allyin' AND name='$schule[name]'";
        $Query = mysql_query($Sql);
        $AllyIn = mysql_fetch_assoc($Query);

    $Sql = "SELECT * FROM forum_forums WHERE type='allyout' AND name='$schule[name]'";
        $Query = @mysql_query($Sql);
        $AllyOut = @mysql_fetch_assoc($Query);

        if($AllyOut != false)
        {
          $aout = '- '.checkPosts($AllyOut['id'],$user['lforum']).' neue Posts im &ouml;ffentlichen Forum<br />';
        }

    $Return = null;

        $schule['war'] = explode(',',$schule['war']);

        $enemys = 0;

    $Sql = "SELECT id,name,war FROM ".TABALLY."  WHERE id != '$schule[id]' ORDER BY name";
        $Query = mysql_query($Sql);
        while($Allys = mysql_fetch_assoc($Query))
        {
          $Allys['war'] = explode(',',$Allys['war']);

          if(array_search($schule['id'],$Allys['war'])) // not found (neutral)
          {
        if(array_search($Allys['id'],$schule['war'])) // war offer from enemy
                {
                  $Color = 'darkorange';
                  $enemys++;
                }
          }
        }

    echo'
        <font style="font-size:11px;">
        <em>Seit deiner letzten Anwesenheit im Spiel/Forum:</em><br />
    - '.checkPosts($AllyIn['id'],$user['lforum']).' neue Pinnwand Posts<br />
    '.$aout.'
    - '.$Bewerber[0].' neue Bewerbungen<br />
    - '.$enemys.' Kriege
        </font>';
  }

  function SingleAlly($ID,$Request='*')
  {
    $Sql = "SELECT $Request FROM ".TABALLY." WHERE id='$ID'";
        $Query = mysql_query($Sql);
        $Return = mysql_fetch_assoc($Query);

        return $Return;
  }

  function KickUser($ID)
  {
    global $User,$schule;

        $KickSql = "SELECT * FROM ".TABUSER." WHERE id='$ID'";
        $KickQuery = mysql_query($KickSql);
        $Kick = mysql_fetch_assoc($KickQuery);

        if($User['id'] == $schule['vize'] && $ID != $schule['boss'] && $Kick['schule'] == $User['schule'] || $User['id'] == $schule['boss'] && $ID != $schule['boss'] && $Kick['schule'] == $User['schule'])
        {
          if($Kick != false)
          {
            $this->LeaveAlly($ID);

                $Text = 'dein Allianzoberhaupt ('.$user['name'].') hat soeben entschieden, dass du aus der Schule versto&szlig;en wirst.
                Jetzt bist du ohne eine Schule und kannst dein gl&uuml;ck woanders versuchen.';

                send_igm('Du wurdest aus deiner Schule versto&szlig;en',$Text,$Kick['name'],'admin');

            echo'<center><strong>User erfolgreich gekickt.</strong></center>';
          }
          else
          {
            echo'<center><strong>Dieser User existiert nicht.</strong></center>';
          }
        }
        else
        {
          echo'<center><strong>Du hast nicht die Rechte User aus deiner Schule zu werfen.</strong></center>';
        }
  }
}

?>

<script language="javascript" type="text/javascript">
<!--

function changeTac()
{
  var tac = document.getElementById("tac").value;

  if(tac=="0")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Manchmal ist keine Taktik immer noch die beste Taktik! Bringt weder Vorteile noch Nachteile.";
  }
  else if(tac=="1")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Nur wer richtig zuhauen kann gewinnt auch einen Kampf. Diese Taktik gew&Auml;hrt dir 18% mehr Off auf deine Hauptwaffe und einen um 5 Punkte verbesserten Todesschlag, daf&uuml;r hast du aber auch nurnoch 75% deiner Def.";
  }
  else if(tac=="2")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Nutze deine R&uuml;stung optimal und die Schl&auml;ge deines Gegners prallen an dir ab. Diese Taktik bringt 20% mehr Def und erh&ouml;ht deine Taktik um 5, daf&uuml;r kannst du aber auch nurnoch 82% deiner Off nutzen.";
  }
  else if(tac=="3")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "&uuml;berw&auml;ltige deine Gegner indem du sie gleich mit 2 Waffen angreifst. Diese Taktik erh&ouml;ht die Off deiner Zweitwaffe um 50% und bringt dir 5 Punkte mehr im kritischen Schlag, einziger nachteil sind deine um 20% veringerten Def.";
  }
  else if(tac=="4")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Wer braucht Schon spezielle Schl&auml;ge, taktisch klug ist es auch den Status des Gegners zu schw&Auml;chen. Diese Taktik erh&ouml;ht Wutschrei, Kraftschrei, Sand werfen und Anti-Defense um 15 Punkte, verringert aber jeden anderen Move um 5 Punkte";
  }
  else if(tac=="5")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Verwende die St&auml;rke deines Gegners um ihn dir vom Hals zu schaffen. Diese Takik erh&ouml;ht die Wahrscheinlichkeit und St&auml;rke von Konter, T&Auml;uschen und Berserker enorm!";
  }
  else if(tac=="6")
  {
    document.getElementById("tactext").firstChild.nodeValue
        = "Verstck dich hinter deinen Schild und greif deinen Gegner immer dann an wenn er grad nicht aupasst. Diese Taktik erh&ouml;ht die Def deines Schildes um 33% und gibt dir 3 Punkte in Ausweichen, allerdings sind Zweitwaffen nurnoch halb so wirkungsvoll und dein allgemeiner Waffenschaden sinkt um 10% wenn du ein Schild tr&auml;gst!";
  }
}

//-->
</script>
