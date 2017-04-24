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

	header('content-type: text/html; charset=utf-8');

	// Sessions aktivieren
	session_start();
	error_reporting(E_ALL^E_NOTICE^E_DEPRECATED);
	srand(microtime()*1000000);
	ini_set('max_execution_time',20);
	date_default_timezone_set('Europe/Berlin');

	// Include Sites bestimmen

	if(isset($_GET['site']) && !empty($_GET['site']))
	{
		$Site = $_GET['site'];
	}
	else
	{
		if(isset($_SESSION['id']) && !empty($_SESSION['id'])) // User ist eingeloggt
		{
			$Site = 'uebersicht';
		} else
		{
			$Site = 'portal';
		}
	}

	if(isset($_SESSION['id']) && !empty($_SESSION['id'])) // User ist eingeloggt
	{

		$Template = 'game';
	}
	else
	{
		$Template = 'portal';
	}

	$SP = 'Sites/'.ucwords($Template).'/'.$Site.'.php';

	$FileSP = @fopen($SP,'r');
	if($FileSP == false)
	{
		if(isset($_SESSION['id']))
		{
			$SP = 'Sites/'.ucwords($Template).'/uebersicht.php';
			$FileSP = @fopen($SP,'r');
			@fclose($FileSP);
		} else
		{
			$SP = 'Sites/'.ucwords($Template).'/portal.php';
			$FileSP = @fopen($SP,'r');
			@fclose($FileSP);
		}
	} else {
		@fclose($FileSP);
	}
	
	require('System/include.php');

	# Datenbank Konstanten definieren
	define('TAB_IPS'		,'ips'			);
	define('TAB_ANIMALARENA','tiergrube'	);
	define('TAB_USER'     	,'user'         );
	define('TAB_ITEMS'    	,'ausruestung'  );
	define('TAB_FORUMS'   	,'forum_forums' );
	define('TAB_TOPICS'   	,'forum_topics' );
	define('TAB_ANSWERS'  	,'forum_answers');
	define('TAB_READ'     	,'forum_read'   );
	define('TAB_NPCS'     	,'rangnpcs'     );
	define('TAB_MESSAGES' 	,'nachrichten'  );
	define('TAB_SETTINGS' 	,'settings'     );
	define('TAB_TIPPS'   	,'tipps'        );
	define('TAB_SHOUTBOX'   ,'shoutbox'     );

	if(isset($_SESSION['id']))
	{
		// Konstanten definieren
		define('TABUSER'		,'user'			);
		define('TABALLY'		,'ally_schule'	);
		define('TABALLYBUILD'	,'settings_ally_geleande');
		define('TABFORUMS'		,'forum_forums'	);
		define('TABTOPICS'		,'forum_topics'	);
		define('TABANSWERS'		,'forum_answers');

		define('FORUM_NEWS_ID'   ,'1' 			);
		define('FORUM_ARCHIVE_ID','46'			);

		require('System/include_online.php');
		
		// Adminbereich absichern
		if(substr($Site,0,5) == 'admin' && !isMod()) exit;

		//Update Downtime
		if($GLOBALS['conf']['gladi_down'] && $user['status'] != 'admin')
		{
			echo "Hallo ".$user['name'].", wie bereits angek&uuml;ndigt haben wir das Spiel f&uuml;r ein paar Stunden offline genommen, um &Auml;nderungen am System durchzuf&uuml;hren. Des Weiteren werden wir ebenfalls in dieser Zeit das neue Update aufspielen und testen. Wir danken also f&uuml;r eure Geduld.<br><br>Euer Gladiatorz-Team";
			exit;
		}
		
		$User['max_kraft'] = get_health($User['id']);

		if($User['max_kraft'] < 100)
		{
			$User['max_kraft']  = 100;
		}

		$User['hp_reg'] = floor(5 + $User['heilkunde']);

		if(time() - $User['kraftupdate'] >= 1)
		{
			$User['kraft'] = $User['kraft'] + ((time() - $User['kraftupdate']) * $User['hp_reg']);
			$User['kraftupdate'] = time();

			if($User['kraft'] > $User['max_kraft'])
			{
				$User['kraft'] = $User['max_kraft'];
			}
			mysql_query("UPDATE user SET kraft='".$User['kraft']."',kraftupdate='".$User['kraftupdate']."' WHERE id='".$User['id']."'");
		}
	}

 //Ip-Script
$ipadresse = getip();

$query = "SELECT * FROM ips WHERE user = '".$user['name']."' ORDER BY Datum DESC LIMIT 1";
$result = mysql_query($query) or die(mysql_error());
$IPuser = mysql_fetch_array($result);

$query2 = "SELECT * FROM ips WHERE user = '".$user['name']."' AND ip !='".$IPuser['ip']."' ORDER BY Datum ASC LIMIT 1";
$result2 = mysql_query($query2) or die(mysql_error());
$IPuser2 = mysql_fetch_array($result2);

$query3 = "SELECT * FROM ips WHERE user = '".$user['name']."' AND ip !='".$IPuser['ip']."' AND ip !='".$IPuser2['ip']."' ORDER BY Datum ASC LIMIT 1";
$result3 = mysql_query($query3) or die(mysql_error());
$IPuser3 = mysql_fetch_array($result3);

	if (!empty($user['name']))
	{
		$date = date('Y-m-d H:i:s');
		$date2 = date('Y-m-d');


		if($IPuser['ip'] == $ipadresse)
		{
			$sql = "UPDATE ips SET Datum = '".$date."' WHERE id='".$IPuser['id']."'";
			mysql_query($sql) OR die(mysql_error());
		}
		elseif($IPuser2['ip'] == $ipadresse)
		{
			$sql = "UPDATE ips SET Datum = '".$date."' WHERE user = '".$user['name']."'";
			mysql_query($sql) OR die(mysql_error());
		}
		elseif($IPuser3['ip'] == $ipadresse)
		{
			$sql = "UPDATE ips SET Datum = '".$date."' WHERE user = '".$user['name']."'";
			mysql_query($sql) OR die(mysql_error());
		}
		else
		{
			$sql = "INSERT INTO ips (IP, user, Datum) VALUES ('".$ipadresse."','".$user['name']."','".$date."' )";
	        	mysql_query($sql) OR die(mysql_error());
		}

		$sql4 = "DELETE FROM ips WHERE DATE_SUB('".$date."', INTERVAL 4320 MINUTE) > Datum";
		mysql_query($sql4) OR die(mysql_error());


		//auf multi pr�fen
		$query_multi = "SELECT * FROM ips WHERE user != '".$user['name']."' AND ip ='".$IPuser['ip']."' ORDER BY id ASC LIMIT 1";
		$result_multi = mysql_query($query_multi) or die(mysql_error());
		$multi = mysql_fetch_array($result_multi);
		//es wurde ein Eintrag gefunden
		if($multi['user'])
		{
			//nun pr�fen ob dies ein sitter eintrag ist
			$query_sitting = "SELECT * FROM sitting WHERE sitter = '".$user['name']."' AND user ='".$multi['user']."' ORDER BY id ASC LIMIT 1";
			$result_sitting = mysql_query($query_sitting) or die(mysql_error());

			$sitting = mysql_fetch_array($result_sitting);

			if($sitting['start'] < $date2 && $sitting['ende'] > $date2)
			{
			} else
			{
				$sql_multi = "INSERT INTO multiverdacht (user, ip, ipfromuser, date) VALUES ('".$user['name']."','".$IPuser['ip']."','".$multi['user']."','".$date."' )";
			   	mysql_query($sql_multi) OR die(mysql_error());
			}
		}
	}

	if($User['bann'] >= time())
	{
		echo'Dein Account wurde gesperrt.<br />F&uuml;r weitere informationen melde dich per E-Mail an admin@gladiatorgame.de oder per ICQ an 335-917-841';
		exit;
	}
	if ($User['schlafen'] <= time()) {
		$User['schlafen'] = 0;
	}

	// Smarty
	require('Smarty/Smarty.class.php');
	require('System/SmartyAssign.php');
	// Walterzorn JS Bibliothek
	echo'<script src="System/Tooltips.js" language="javascript" type="text/javascript"></script>';

	// Die dreifaltige Endigung
	mysql_close();
	if(!exit())

?>
