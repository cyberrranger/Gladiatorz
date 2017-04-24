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
echo "<center>";
echo "<a href='index.php?site=search&what=player' target='_self'>Spielersuche</a> | ";
echo "<a href='index.php?site=search&what=arti' target='_self'>Artefaktsuche</a> | ";
echo "<a href='index.php?site=search&what=ally' target='_self'>Schulensuche</a> ";
echo "</center><br />";

if(!empty($_REQUEST["suchen"]))
{
	$suche = strtolower($_POST["suchen"]);
	$suche2 = strtolower($_POST["suchen2"]);
	
	$count = 0;
	$treffer = array();
	
	switch($_REQUEST['what'])
	{
		case 'player':
			$sql = "SELECT * FROM user WHERE name LIKE '%$suche%'";
			break;
		case 'arti':
			$sql = "SELECT * FROM ausruestung WHERE name LIKE '$user[name]%'";
			break;
		case 'ally':
			$sql = "SELECT * FROM ally_schule WHERE name LIKE '%$suche%'";
			break;
	}
	
	$query = mysql_query($sql);
	
	while($ergebniss = mysql_fetch_assoc($query))
	{
		$treffer[] = $ergebniss;
		$count++;
	}
	echo "<div style=\"width:500px;text-align:left;margin-left:30px;\">";
	
	echo"<center><b>Suchergebnisse:</b></center><br />";
	
	if ($count == 0)
	{
		echo "Es wurden keine Treffer gefunden";
	} elseif ($count == 1)
	{
		echo "Es wurde 1 Treffer gefunden";
	} else 
	{
		echo "Es wurden $count Treffer gefunden";
	}	
	
	echo "<br /><br />";
	
	if($count != 0)
	{
		echo "<ul>";
		switch($_REQUEST['what'])
		{
			case 'player':
				foreach($treffer AS $key)
				{
					echo"<li><a href='index.php?site=userinfo&info=$key[id]'>$key[name]</a></li>";
				}
				break;
			case 'arti':
				foreach($treffer AS $key)
				{
					if($key['basar'] > 0)
					{
						echo '<li>'.$key['name'].' | Basar</li>';
					} else
					{
						$usrsql = "SELECT * FROM user WHERE id='$key[user_id]'";
						$usrquery = mysql_query($usrsql);
						$usr = mysql_fetch_assoc($usrquery);
						
						if($usr!=false)
						{
							echo '<li>'.utf8_encode($key['name']).' | <a href="index.php?site=userinfo&info='.$usr['id'].'" target="_self">'.$usr['name'].'</a></li>';
						}
					}
				}
				break;
			case 'ally':
				foreach($treffer AS $key)
				{
					echo "<li><a href='index.php?site=schulen&sub=$key[id]info'>".utf8_encode($key['name'])."</a></li>";
				}
				break;
		}
		echo "</ul>";
	}
	echo "</div>";
	
} else 
{
	switch($_REQUEST['what'])
	{
		case 'player':
			$search_question = 'Hier kannst du schauen, ob es Spieler mit einem bestimmten Namen gibt ...';
			$search_form = "<center><form name='such' method='post' action='index.php?site=search&what=player'><input name='suchen' type='text' />&nbsp;<input type='submit' value='Suchen' /></form></center>";
			break;
		case 'arti':
			$search_question = 'Finde Artefakte die zu deinem Set gehören!';
			$search_form = "<center><form name='such' method='post' action='index.php?site=search&what=arti'><input name='suchen' type='submit' value='Go!' /></form></center>";
			break;
		case 'ally':
			$search_question = 'Hier kannst du schauen, ob es Schulen mit einem bestimmten Namen gibt ...';
			$search_form = "<center><form name='such' method='post' action='index.php?site=search&what=ally'><input name='suchen' type='text' />&nbsp;<input type='submit' value='Suchen' /></form></center>";
			break;
	}
	
	echo "<center>".$search_question."</center>";
	echo "<br /><br />";
	echo $search_form;
}
?>
