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
if($_GET["hash"] == "asdaf355235DDAd")
{
	require('../System/conf.php');
	require('../System/Database.php');
	require('../System/GameFunctions.php');

	// PowMod hinzuf�gen
	$Query = mysql_query("SELECT regstamp,powmod,name,id,aktiv FROM user WHERE aktiv=1");
	$count = 0;

	echo "PM nachf�llen<br />";
	while ($User = mysql_fetch_assoc($Query))
	{
		$Newbtime = $User['regstamp'] + 1814400; // 3 Wochen ist man Neuling...

		if ($Newbtime > time()) // Neulinge bekommen 2x soviel PowMod
		{
			if(isEvent(7))
			{
				$User['powmod'] = $User['powmod'] + 2;
			} else {
				$User['powmod'] = $User['powmod'] + 1;
			}
			
			$count++;
			
			echo $count."->".$User['name']." frischling<br>";
			
		} else {
			
			if(isEvent(7))
			{
				$User['powmod'] = $User['powmod'] + 1;
			} else {
				$User['powmod'] = $User['powmod'] + 0.5;
			}
			
			$count++;
			
			echo $count."->".$User['name']." alter hase<br>";
		}
		
		// Maximal PowMod begrenzen
		if ($User['powmod'] >= $GLOBALS['conf']['konst']['max_pm'])
		{
			$User['powmod'] = $GLOBALS['conf']['konst']['max_pm']; 
		}
		
		mysql_query("UPDATE user SET powmod='".$User['powmod']."' WHERE id='".$User['id']."' LIMIT 1");
	}
	
	echo "2 Wochen alte Nachrichten l�schen<br />";
	// veraltete, gelesenen Nachrichten l�schen (2 Wochen)
	mysql_query("DELETE FROM nachrichten WHERE del_empfaenger='j' AND del_absender='j' OR datum<'".(time()-1209600)."'");
	         
	
	// Finish-Message ausgeben
	echo '<br />Die Datei cronjob.php wurde erfolgreich ausgef�hrt.';
	
} else {
	echo "kein Zutritt!!!";
}
?>
