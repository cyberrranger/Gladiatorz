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
if (!$_SESSION['n1'] && !$_SESSION['n2'])
{
	$_SESSION['n1'] = rand(0,19);
	$_SESSION['n2'] = rand(20,40);
}

if ($_REQUEST['ref'])
{
	$_SESSION['ref'] = $_REQUEST['ref'];
}

if (!$_SESSION['ref'])
{
	$_SESSION['ref'] = 0;
}

if ($_POST['anmelden'])
{
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

	$nick = strip_tags(trim($_POST['username']));
	$email = strip_tags(trim($_POST['email']));
	$email2 = strip_tags(trim($_POST['email2']));
	$pw = strip_tags(trim($_POST['pw']));
	$pw2 = strip_tags(trim($_POST['pw2']));
	$check = strip_tags(trim($_POST['check']));
	$_check = $_SESSION['n1'] + $_SESSION['n2'];

	unset($_SESSION['n1']);
	unset($_SESSION['n2']);

	$query_name = mysql_query("SELECT id,name FROM ".TAB_USER." WHERE name='".$nick."' LIMIT 1");
	$check_nick = mysql_fetch_assoc($query_name);

	$query_mail = mysql_query("SELECT id,mail FROM user WHERE mail='".$email."' LIMIT 1");
	$check_mail = mysql_fetch_assoc($query_mail);
	$check_mail = false;

	if(empty($nick))
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du hast keinen Benutzernamen eingegeben.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}elseif(strlen($nick) >= 36 || strlen($nick) <= 4)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Der Name deines Gladiators ist zu lang (maximal 35 Zeichen) oder zu kurz (minimal 5 Zeichen).</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}elseif($check_nick == true)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Dieser Name wird schon von einem anderen Gladiator benutzt! Du musst dir einen anderen Namen suchen.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}elseif($_check != $check)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Die von dir eingegebene Pr�fsumme ist nicht korrekt</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}elseif(empty($pw) || empty($pw2))
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du hast kein Passwort eingegeben.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}
	elseif(strlen($pw) >= 36 || strlen($pw) <= 4)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Dein Passwort ist zu lang (maximal 35 Zeichen) oder zu kurz (minimal 5 Zeichen).</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}elseif($pw != $pw2)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du musst zwei mal dasselbe Passwort eingeben, sonst wird das nichts!</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}
	elseif(empty($email))
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du hast keine E-Mail Adresse eingegeben.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}
	elseif(empty($email2))
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du hast deine E-Mail Adresse nicht wiederholt eingegeben.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}
	elseif($email != $email2)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Du musst zwei mal dieselbe E-Mail Adresse eingeben, sonst wird das nichts!</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	}
	elseif($check_mail == true)
	{
		echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
		<strong>Diese EMail-Adresse wird bereits von einem anderen Gladiator benutzt. Bitte benutze eine noch nicht belegte Adresse.</strong>
		<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
	} else
	{
		$Reg = mysql_query("INSERT INTO user (name,pw,gold,kraft,regstamp,powmod,schule,status,lonline,multi,rankplace,rang,mail,aktiv,ref,pmspar) VALUES ('".$nick."','".md5($pw)."','250','10','".time()."','10','0','user','".time()."','0','21','1','".$email."','0', '".$_SESSION['ref']."',20)");
		$reg_id = mysql_insert_id();
		
		if($Reg == true)
		{
			$Query = mysql_query("SELECT * FROM ".TAB_USER." WHERE BINARY name='".$nick."' AND pw='".md5($pw)."' LIMIT 1");
			$Login = mysql_fetch_assoc($Query);

			$mov = "INSERT INTO moves (uid) VALUES ('".$Login['id']."')";
			mysql_query($mov) or die(mysql_error());

			if(isset($_SESSION['name']))
			{
				mysql_query("UPDATE ".TAB_USER." SET multi='".($Login['multi']+100)."' WHERE id='".$Login['id']."' LIMIT 1");

				$Query = mysql_query("SELECT * FROM ".TAB_USER." WHERE BINARY name='".$_SESSION['name']."' LIMIT 1");
				$Cheater = mysql_fetch_assoc($Query);

				if($Cheater == true)
				{
					mysql_query("UPDATE ".TAB_USER." SET multi='".($Cheater['multi']+100)."' WHERE id='".$Cheater['id']."' LIMIT 1");
				}
			}

			// Einstiegs-IGM
			$timestamp = time();
			$pn_betreff = "Willkommen bei Gladiatorz!";
			$pn_text = "
Guten Tag, $nick!

Willkommen bei Gladiatorz.
Um dir den Einstieg etwas zu erleichtern m�chten wir dir kurz das Wichtigste im Spiel erkl�ren.

PowMod (PM):
Mithilfe von PM kannst du hier k�mpfen und dein Charakter skillen. Jeder siegreiche Kampf verbraucht 0.1 PM. Bei einer Niederlage kann es vorkommen, dass du mehr verbrauchst. Wenn deine PM leer sind musst du bis zur n�chsten vollen Stunde warten, dann bekommst du neue PM.
Im Gasthaus kannst du dich auch f�r wenig Gold schlafen legen und somit die Regeneration deiner PM erh�hen.

Spezial Moves:
F�r jedes neu erreichte Level bekommst du 2 Spezial Moves Punkte, diese kannst du benutzen um deine Spezial Moves auszubauen. Mit Level 30, 60 und 90 werden neue Spezial Moves freigeschalten. Viele von den Spezial Moves ben�tigen aber eine bestimmte Mindestmenge eines anderen Spezial Moves um trainiert zu werden.

Die K�mpfe:
Bei Gladiatorz gibt es viele Arten zu k�mpfen. Die Wichtigste ist der Kampf in der Tiergrube. Dort bekommst du Gold, Erfahrung und mit etwas Gl�ck neue Items.
Gegen andere Gladiatoren kannst du in der Arena und bei Duellen k�mpfen. Das Besondere bei Duellen ist das du dort keine PM verbrauchst.
Wenn dir die Tiergrube zu lahm ist, kannst du auch Prestigek�mpfe machen. Dort k�mpfst du gegen mehrere Tiere und kannst am ende des Tages Medaillen gewinnen.

Die Schulen (Clans):
In Schulen treffen sich Gladiatoren die einen �hnlichen Kampfstil haben. Durch die jeweiligen Schulentaktiken wird man dann im Kampf verst�rkt. Au�erdem bringen Schulen auch einen Bonus au�erhalb von K�mpfen, wie zum Beispiel eine h�here Regeneration deiner PM w�hrend des Schlafens.

Falls du noch Fragen hast stehen dir jederzeit User in der Shoutbox zur Verf�gung und auch unser Wiki kann einige weitere Fragen beantworten.

mfg
dein Gladiatorzteam";

			mysql_insert("nachrichten","empfaenger,absender,titel,text,datum,gelesen,del_empfaenger,del_absender","'$nick','admin','$pn_betreff','$pn_text','$timestamp','n','n','n'");

			$Absender = "noreply@gladiatorgame.de";
			$Erstellt = date("Y-m-d H:i:s");
			$Aktivierungscode = rand(1, 99999999);

			$sql = "INSERT INTO Aktivierung (Aktivierungscode, Erstellt, EMail, Aktiviert) VALUES ('$Aktivierungscode', '$Erstellt', '".$_POST['email']."', 'Nein')";
			$query = mysql_query($sql);
			$ID = mysql_insert_id();

			$mail_betreff = "Registrierung eines neuen Gladiators";
			$mail_text = "
Guten Tag, $nick!<br />
<br />
Willkommen bei Gladiatorz.<br />
<br />
Du hast soeben einen kostenlosen Account im Online-Spiel 'Gladiatorz' er�ffnet. Dies ist eine automatische Best�tigung Deiner Anmeldung! Deine Registrierungsdaten:<br />
<br />
 Login: $nick<br />
 Passwort: $pw<br />
<br />
 Um den Registrierungsprozess abzuschlie�en, klicke bitte auf den folgenden Link (oder kopiere diese URL in die Adressleiste Deines Browsers ein und dr�cke dann 'Enter') :
 ".$GLOBALS['conf']['konst']['url']."/index.php?site=aktivierung&ID=$ID&Aktivierungscode=$Aktivierungscode<br />
<br />
 Wenn Du diese Nachricht irrt�mlich erhalten hast, bitten wir um Entschuldigung.<br />
<br />
 Mit den besten Gr��en,<br />
 Das Team von Gladiatorz<br />
 ".$GLOBALS['conf']['konst']['url']."<br />
<br />
  *** ACHTUNG: Antworte bitte nicht direkt auf diese e-Mail. Diese e-Mail wurde automatisch erstellt. ***
";
			//Empf�ngeradresse setzen
			$mail->AddAddress($email2);
			$mail->IsHTML(true);
			//Betreff der Email setzen
			$mail->Subject = $mail_betreff;

			//Text der EMail setzen
			$mail->Body = $mail_text;

			if(!$mail->Send())
			{
				echo "Die E-Mail konnte nicht gesendet werden bitte wende dich an support@gladiatorgamer.de";
			}
			else
			{
				echo "Die E-Mail wurde versandt.<br />";
			}

			echo "Deine Daten wurden erfolgreich aufgenommen. Um die Registrierung abzuschlie�en, musst du dein E-Mail-Postfach abrufen und auf den Aktivierungslink klicken.";
		} else
		{
			echo'<br /><center style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">
			<strong>Ups...derzeit gibt es ein Problem mit der Datenbank! Bitte probiere es sp�ter noch einmal.</strong>
			<br /><br /><a href="?site=anmelden">Versuch es noch einmal... !</a></center>';
		}

	}
} else
{

	echo '
	<center>
		<h2 style="color:darkred;margin-top:1px;">...nur wenige Schritte trennen dich von deinem Schicksal...</h2>
		<form name="Anmelden" method="post" action="?site=anmelden">
			<table align="center" border="0">
			  <tr valign="middle" style="padding:5px;">
				 <td valign="middle" style="padding:5px;">Nickname*:</td>
				 <td valign="middle" style="padding:5px;"><input type="text" name="username" /><br /><br /></td>
				 <td valign="middle" style="padding:5px;">Was gibt '.$_SESSION['n1'].' + '.$_SESSION['n2'].'*</td>
				 <td valign="middle" style="padding:5px;"><input type="text" name="check" /><br /><br /></td>
			  </tr>
			  <tr>
				 <td valign="middle" style="padding:5px;">E-Mail*:</td>
				 <td valign="middle" style="padding:5px;"><input type="text" name="email" /><br /><br /></td>
				 <td valign="middle" style="padding:5px;">E-Mail wiederholen*:</td>
				 <td valign="middle" style="padding:5px;"><input type="text" name="email2" /><br /><br /></td>
			  </tr>
			  <tr>
				 <td valign="middle" style="padding:5px;">Passwort*:</td>
				 <td valign="middle" style="padding:5px;"><input type="password" name="pw" /><br /><br /></td>
				 <td valign="middle" style="padding:5px;">Passwort wiederholen*:</td>
				 <td valign="middle" style="padding:5px;"><input type="password" name="pw2" /><br /><br /></td>
			  </tr>
			  <tr>
			  <td valign="middle" style="padding:5px;"></td>
				 <td valign="middle" style="padding:5px;">
					 <input name="anmelden" type="submit" value="Registrierung absenden" style="height:16px;cursor:pointer;font-weight:bold;color:darkred;width:145px;" />
				 </td>
			  </tr>
			</table>
		</form>
	</center>
	';
}
?>
