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


if ($_POST['pwvergessen'])
{
	if ($_POST['searchpw'] == '')
	{
		echo "Keine Eingabe vorgenommen";
	}

	$email = $_POST['searchpw'];
	$email = strip_tags(trim($email));

	if (strpos($email, '@') && strpos($email, '.'))
	{	//es ist eine email
		$query_name = mysql_query("SELECT id,name,mail FROM ".TAB_USER." WHERE mail='".$email."' LIMIT 1");
		$check = mysql_fetch_assoc($query_name);

	} else
	{	//es ist ein name
		$query_name = mysql_query("SELECT id,name,mail FROM ".TAB_USER." WHERE name='".$email."' LIMIT 1");
		$check = mysql_fetch_assoc($query_name);
	}

	if($check == true)
	{
		//es wurde ein Eintrag gefunden

		//neues PW
		$pw = passwort_generator();
		$pw_md5 = md5($pw);

		@mysql_query("UPDATE ".TAB_USER." SET pw = '".$pw_md5."', aktiv = 1 WHERE ID = '".$check['id']."'");

		//Klasse einbinden
		require('System/phpmailer/class.phpmailer.php');

		//Instanz von PHPMailer bilden
		$mail = new PHPMailer();

		//Absenderadresse der Email setzen
		$mail->From = $GLOBALS['conf']['mail']['from'];

		//Name des Abenders setzen
		$mail->FromName = $GLOBALS['conf']['mail']['fromName'];

		$mail->IsSMTP();
		$mail->Host = $GLOBALS['conf']['mail']['host'];
		$mail->SMTPAuth = TRUE;
		$mail->Username = $GLOBALS['conf']['mail']['username'];
		$mail->Password = $GLOBALS['conf']['mail']['password'];

		//Empf�ngeradresse setzen
		$mail->AddAddress($check['mail']);
		$mail->IsHTML(true);
		//Betreff der Email setzen
		$mail->Subject = "Passwort vergessen bei Gladiatorz";

		$mail_text = "
Hallo ".$check['name'].",<br />
<br />
herzlich willkommen bei Gladiatorz!<br />
<br />
Du hast Dein Passwort vergessen? Kein Problem!<br />
<br />
<br />
Deine Zugangsdaten lauten:<br />
<br />
---------------------------------------------------<br />
Username: ".$check['name']."<br />
Passwort: ".$pw."<br />
".$GLOBALS['conf']['konst']['url']."<br />
---------------------------------------------------<br />
<br />
Hast Du Fragen, Anregungen, Lob oder Kritik? Im Forum sind wir jederzeit gerne f�r Dich da.<br />
<br />
Wir freuen uns auf Dich!<br />
<br />
<br />
Dein Gladiatorz - Team<br />
".$GLOBALS['conf']['konst']['url']."<br />
<br />
Du hast Dich nicht bei uns registriert, sondern diese E-Mail irrt�mlich erhalten? Dann hat einer unserer Spieler (wohl versehentlich) Deine E-Mail-Adresse angegeben; daf�r m�chten wir uns in aller Form entschuldigen";
		//Text der EMail setzen
		$mail->Body = $mail_text;
		$mail->Send();
		echo "Deine Login-Daten wurden an die angegebene E-Mail-Adresse verschickt.";
	} else
	{
		echo "Zu deiner Eingabe wurde kein passender EIntrag in unserer Datenbank gefunden";
	}

} else
{

	echo '
	<center style="font-size:12px;">
		<strong>
			Du bist bei uns registriertes Mitglied, hast aber Deine Zugangsdaten verlegt oder vergessen?<br />
			<br />
			Gib hier Deinen Usernamen oder Deine E-Mail-Adresse ein und wir schicken Dir Dein Passwort erneut zu. Bei weiteren Fragen wende Dich auch jederzeit an unseren Support.<br />
			<br />
			Username oder E-Mail-Adresse<br />
			<form name="pwvergessen" action="?site=pwvergessen" method="POST">
				<input type="text" name="searchpw" />
				<input name="pwvergessen" type="submit" value="Password anfordern" style="height:16px;cursor:pointer;font-weight:bold;color:darkred;width:145px;" />
			</form>
		</strong>
	</center>';
}

function passwort_generator($laenge=8)
{
	$string = md5((string)mt_rand() . $_SERVER['REMOTE_ADDR'] . time());

	$start = rand(0,strlen($string)-$laenge);

	$password = substr($string, $start, $laenge);

	return $password;
 }
