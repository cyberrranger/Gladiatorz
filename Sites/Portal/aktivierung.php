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
if($_REQUEST['ID'] && $_REQUEST['Aktivierungscode'])
{
    $_REQUEST['ID']               = mysql_real_escape_string($_REQUEST['ID']);
    $_REQUEST['Aktivierungscode'] = mysql_real_escape_string($_REQUEST['Aktivierungscode']);

    $ResultPointer = mysql_query("SELECT ID FROM Aktivierung WHERE ID = '".$_REQUEST['ID']."' AND Aktivierungscode = '".$_REQUEST['Aktivierungscode']."'");

    if(mysql_num_rows($ResultPointer) > 0)
    {
        @mysql_query("UPDATE Aktivierung SET Aktiviert = 'Ja' WHERE ID = '".$_REQUEST['ID']."'"); //das tut schonmal
		$Query = mysql_query("SELECT * FROM Aktivierung WHERE ID = '".$_REQUEST['ID']."' LIMIT 1");
		$Aktiviert = mysql_fetch_assoc($Query);

		$Query2 = mysql_query("SELECT * FROM user WHERE mail = '".$Aktiviert['EMail']."'");
		$AktiviertUser = mysql_fetch_assoc($Query2);

		@mysql_query("UPDATE user SET aktiv='1' WHERE id='".$AktiviertUser['id']."'");
        echo "Herzlichen Gl�ckwunsch,<br /><br /> die Registrierung ist erfolgreich abgeschlossen und du kannst dich mit deine Benutzernamen sowie dem Passwort einloggen. Vielen Dank f�r deine Registrierung und viel Spa� in der Welt der Gladiatoren!";
    }
}
?>
