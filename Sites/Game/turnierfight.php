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

if ($user['name'] != 'admin')
{
	exit;
}

include"class/Turnier.php";

$Turnier = new Turnier();
$tid = $_REQUEST['id'];
$calctiefe = $Turnier->calctiefe($tid);

//include fight.php
include"fight2.php";

//functionen
function Tround($_fight, $round, $calctiefe, $runde = 0)
{
	$_Turnier = new Turnier();
	$next = time() + (24*60*60);
	$next = date("Y-m-d H:i:s", $next);
	$absender = 'Organisation';
	foreach ($_fight as $f => $v)
	{
		$user1 = $round[$v[0]]['userid'];
		$user2 = $round[$v[1]]['userid'];

		if ($user1 == NULL)
		{
			$user1 = 4;
		}
		if ($user2 == NULL)
		{
			$user2 = 4;
		}

		$both = creat_fight($user1, $user2);
		$fight = make_a_new_fight($both);

		$winner = $fight['winner'];
		$looser = $fight['looser'];

		$empf_win = get_user_things($winner, 'name');
		$empf_los = get_user_things($looser, 'name');

		$reportid = $_Turnier->writefightreport($fight['kampfbericht'], $winner, $looser);

		if ($runde == $calctiefe)
		{
			$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' LIMIT 1");
			$thisgold = mysql_fetch_assoc($Query);

			$firstgold = round(($thisgold['gold'] / 3) * 2);
			$secondgold = round(($thisgold['gold'] / 3) * 1);

			//Finalle
			$titel_win = "Finale gewonnnen";
			$titel_los = "Finale verloren";

			$exp_win = 20;
			$exp_los = 10;

			$text_win = "Gl&uuml;ckwunsch, du hast gegen $empf_los gewonnen, und dadurch $firstgold Gold und $exp_win Exp gewonnen.";
			$text_los = "Du hast in der letzten Runde gegen $empf_win verloren, du hast hierf&uuml;r $secondgold Gold und $exp_los Exp gewonnen..";

			$Update = mysql_query("UPDATE user SET gold=gold+'".$firstgold."' WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET gold=gold+'".$secondgold."' WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE user SET exp=exp+'".$exp_win."' WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET exp=exp+'".$exp_los."' WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE user SET turnierpoints=turnierpoints+10 WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET turnierpoints=turnierpoints+5 WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE user SET turnier=0 WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET turnier=0 WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE turnier SET winner='".$empf_win."' WHERE id='".$_REQUEST['id']."' LIMIT 1");

		} else
		{
			//normale Runde
			$titel_win = "Runde weiter";
			$titel_los = "Runde verloren";

			$exp_win = 10;
			$exp_los = 5;

			$text_win = "Gl&uuml;ckwunsch, du hast gegen ".$empf_los." gewonnen daf&uuml;r hast du $exp_win Exp erhalten und bist eine Runde weiter.";
			$text_los = "Du hast es nicht geschafft ".$empf_win." zu besiegen, daf&uuml;r hast du aber $exp_los Exp erhalten und bist aus dem Turnier ausgeschieden";

			$Update = mysql_query("UPDATE user SET exp=exp+'".$exp_win."' WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET exp=exp+'".$exp_los."' WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE user SET turnierpoints=turnierpoints+2 WHERE id='".$winner."' LIMIT 1");
			$Update = mysql_query("UPDATE user SET turnierpoints=turnierpoints+1 WHERE id='".$looser."' LIMIT 1");

			$Update = mysql_query("UPDATE user SET turnier=0 WHERE id='".$looser."' LIMIT 1");

		}

		$text_win .= "<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&id=$reportid]hier[/url]";
		$text_los .= "<br />Denn Kampfbericht findest du [url=".$GLOBALS['conf']['konst']['url']."/index.php?site=report&id=$reportid]hier[/url]";
		send_igm($titel_win,$text_win,$empf_win,$absender);
		send_igm($titel_los,$text_los,$empf_los,$absender);
		$Update = mysql_query("UPDATE turnier SET date='".$next."' WHERE id='".$_REQUEST['id']."' LIMIT 1");

		if ($user1 == $winner)
		{
			$round[$f] = $round[$v[0]];
			$turnier = mysql_query("INSERT INTO turnier_run (`turnierid`, `userid`, `place`, `report_id`) VALUES ('".$_REQUEST['id']."','".$user1."','".$f."','".$reportid."')");
		} else
		{
			$round[$f] = $round[$v[1]];
			$turnier = mysql_query("INSERT INTO turnier_run (`turnierid`, `userid`, `place`, `report_id`) VALUES ('".$_REQUEST['id']."','".$user2."','".$f."','".$reportid."')");
		}
	}

	return $round;
}

//aktuelles Turnier zusammenbasteln
$round = array();
foreach ($Turnier->placeListe($tid) as $t)
{
	$round[$t['place']] = $t;
}

// die einzelnen Gruppen gegen einander antreten lassen
if ($_REQUEST['round'] == 1)
{
	$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' AND round = 0 LIMIT 1");
	$thisround = mysql_fetch_assoc($Query);

	if ($thisround)
	{
		if ($calctiefe == 3)
		{
			$_fight = array(
				9 => array(1,2),
				11 => array(3,4),
				13 => array(5,6),
				15 => array(7,8)
			);
		}
		if ($calctiefe == 4)
		{
			$_fight = array(
				17 => array(1,2),
				19 => array(3,4),
				21 => array(5,6),
				23 => array(7,8),
				25 => array(9,10),
				27 => array(11,12),
				29 => array(13,14),
				31 => array(15,16)
			);
		}
		if ($calctiefe == 5)
		{
			$_fight = array(
				33 => array(1,2),
				35 => array(3,4),
				37 => array(5,6),
				39 => array(7,8),
				41 => array(9,10),
				43 => array(11,12),
				45 => array(13,14),
				47 => array(15,16),
				49 => array(17,18),
				51 => array(19,20),
				53 => array(21,22),
				55 => array(23,24),
				57 => array(25,26),
				59 => array(27,28),
				61 => array(29,30),
				63 => array(31,32)
			);
		}

		$round = Tround($_fight, $round, $calctiefe, 1);
		$Update = mysql_query("UPDATE turnier SET round=round+1 WHERE id='".$_REQUEST['id']."' LIMIT 1");
	} else
	{
		echo "Diese runde wurde bereits durchgef&uuml;hrt oder ist noch nicht an der Reihe";
	}
}

if ($_REQUEST['round'] == 2)
{
	$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' AND round = 1 LIMIT 1");
	$thisround = mysql_fetch_assoc($Query);

	if ($thisround)
	{
		if ($calctiefe == 3)
		{
			$_fight = array(
				17 => array(9,11),
				21 => array(13,15)
			);
		}

		if ($calctiefe == 4)
		{
			$_fight = array(
				33 => array(17,19),
				37 => array(21,23),
				41 => array(25,27),
				45 => array(29,31)
			);
		}

		if ($calctiefe == 5)
		{
			$_fight = array(
				65 => array(33,35),
				69 => array(37,39),
				73 => array(41,43),
				77 => array(45,47),
				81 => array(49,51),
				85 => array(53,55),
				89 => array(57,59),
				93 => array(61,63)
			);
		}

		$round = Tround($_fight, $round, $calctiefe, 2);
		$Update = mysql_query("UPDATE turnier SET round=round+1 WHERE id='".$_REQUEST['id']."' LIMIT 1");
	} else
	{
		echo "Diese runde wurde bereits durchgef&uuml;hrt oder ist noch nicht an der Reihe";
	}
}

if ($_REQUEST['round'] == 3)
{
	$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' AND round = 2 LIMIT 1");
	$thisround = mysql_fetch_assoc($Query);

	if ($thisround)
	{
		if ($calctiefe == 3)
		{
			$_fight = array(
				25 => array(17,21)
			);
		}

		if ($calctiefe == 4)
		{
			$_fight = array(
				49 => array(33,37),
				57 => array(41,45)
			);
		}

		if ($calctiefe == 5)
		{
			$_fight = array(
				97 => array(65,69),
				105 => array(73,77),
				113 => array(81,85),
				121 => array(89,93)
			);
		}

		$round = Tround($_fight, $round, $calctiefe, 3);
		$Update = mysql_query("UPDATE turnier SET round=round+1 WHERE id='".$_REQUEST['id']."' LIMIT 1");
	} else
	{
		echo "Diese runde wurde bereits durchgef&uuml;hrt oder ist noch nicht an der Reihe";
	}
}

if ($_REQUEST['round'] == 4)
{
	$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' AND round = 3 LIMIT 1");
	$thisround = mysql_fetch_assoc($Query);

	if ($thisround)
	{
		if ($calctiefe == 4)
		{
			$_fight = array(
				65 => array(49,57)
			);
		}

		if ($calctiefe == 5)
		{
			$_fight = array(
				129 => array(97,105),
				145 => array(113,121)
			);
		}

		$round = Tround($_fight, $round, $calctiefe, 4);
		$Update = mysql_query("UPDATE turnier SET round=round+1 WHERE id='".$_REQUEST['id']."' LIMIT 1");
	} else
	{
		echo "Diese runde wurde bereits durchgef&uuml;hrt oder ist noch nicht an der Reihe";
	}
}

if ($_REQUEST['round'] == 5)
{
	$Query = mysql_query("SELECT * FROM turnier WHERE id='".$_REQUEST['id']."' AND round = 4 LIMIT 1");
	$thisround = mysql_fetch_assoc($Query);

	if ($thisround)
	{
		if ($calctiefe == 5)
		{
			$_fight = array(
				161 => array(129,145)
			);
		}

		$round = Tround($_fight, $round, $calctiefe, 5);
		$Update = mysql_query("UPDATE turnier SET round=round+1 WHERE id='".$_REQUEST['id']."' LIMIT 1");
	} else
	{
		echo "Diese runde wurde bereits durchgef&uuml;hrt oder ist noch nicht an der Reihe";
	}
}


echo '
	<br />
	<center>
		<form name="givTurnier" method="post" action="index.php?site=turnieruebersicht">
			<select name="id">';
			foreach ($Turnier->idListe() as $id)
			{
					echo '<option value="'.$id.'">'.$id.'</option>';
			}

			echo '</select>
			&nbsp;
			<input type="submit" value="Suchen">
		</form>
	</center><br />';

if ($tid)
{
	if ($Turnier->ifTurnier($tid))
	{
		echo $Turnier->makeTabelle($Turnier->placeListe($tid), $calctiefe, $tid);
	}
}
?>
