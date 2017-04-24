<div class="center">
  <a href="?site=profil">Profil</a> (<a href="?site=userinfo&info=<?php echo $User['id']; ?>">eigenes</a>) |
  <a href="?site=signatur">Signatur</a> |
  <a href="?site=boxlog">BoxLog</a>
</div><br />
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
echo'<center>Im Profil kannst du deine Accountdaten ver&auml;ndern.</center><br />';

if($_POST["password_old"] != "" OR $_POST["password_new"] != "")
{
  $pw_old = $_POST["password_old"];
  $pw_new = $_POST["password_new"];

  if($pw_old == "" OR $pw_new == "")
  {
    echo"<center><b>Bitte alle PW-Felder ausf&uuml;llen!</b></center><br />";
  }
  else
  {
    if(md5($pw_old) != $user[pw])
	{
	  echo"<center><b>Altes Password inkorrekt!</b></center><br />";
	}
	else
	{
	  $pw_new = md5($pw_new);
	  mysql_update("user","pw='$pw_new'","name='$user[name]'");

	  echo"<center><b>Password erfolgreich ge&auml;ndert!</b></center><br />";
	}
  }
}

if(isset($_POST["mail"]) AND $_POST["mail"] != $user[mail])
{
  mysql_update("user","mail='$_POST[mail]'","name='$user[name]'");
  echo"<center><b>Mail erfolgreich ge&auml;ndert!</b></center><br />";

  $user[mail] = $_POST["mail"];
}



if(isset($_POST["rlname"]) AND $_POST["rlname"] != $user[rlname])
{
  mysql_update("user","rlname='$_POST[rlname]'","name='$user[name]'");
  echo"<center><b>Realname erfolgreich ge&auml;ndert!</b></center><br />";

  $user[rlname] = $_POST["rlname"];
}
if(isset($_POST["sex"]) AND $_POST["sex"] != $user[sex])
{
  mysql_update("user","sex='$_POST[sex]'","name='$user[name]'");
  echo"<center><b>Geschlecht erfolgreich ge&auml;ndert!</b></center><br />";

  $user[sex] = $_POST["sex"];
}
if(isset($_POST["website"]) AND $_POST["website"] != $user[website])
{
  mysql_update("user","website='$_POST[website]'","name='$user[name]'");
  echo"<center><b>Website erfolgreich ge&auml;ndert!</b></center><br />";

  $user[website] = $_POST["website"];
}
if(isset($_POST["icq"]) AND $_POST["icq"] != $user[icq])
{
  mysql_update("user","icq='$_POST[icq]'","name='$user[name]'");
  echo"<center><b>ICQ erfolgreich ge&auml;ndert!</b></center><br />";

  $user[icq] = $_POST["icq"];
}

if(isset($_POST["avatar"]) && $user["avatar"] != $_POST["avatar"])
{
$new_avatar = $_POST["avatar"];
$user[avatar] = $new_avatar;
mysql_update("user","avatar='$user[avatar]'","name='$user[name]'");
echo"<center><b>Avatar erfolgreich ge&auml;ndert.</b></center><br />";
$user[avatar] = $new_avatar;
}

if($user['avatar'] != '')
{
  $Showava = $user['avatar'];
}
else
{
  $Showava = '';
}

if(substr($user['avatar'],0,7) != 'http://' && $user['avatar'] != '')
{
  $user['avatar'] = 'http://'.$user['avatar'];
}

if($user['avatar'] == '')
{
  $user['avatar'] = 'Images/Design/pic.gif';
}

if(isset($_POST['descr']) && $_POST['descr'] != $user['descr'])
{
  $Descr = strip_tags(trim($_POST['descr']));

  if(strlen($Descr) > 0 && strlen($Descr) < 2500)
  {
    echo"<center><b>Charbeschreibung ge&auml;ndert.</b></center><br />";
	$user['descr'] = $Descr;

	$Descr = nl2br($Descr);

	$UpSql = "UPDATE user Set descr='$Descr' WHERE id='$user[id]' LIMIT 1";
	$UpQuery = mysql_query($UpSql);
  }
  else
  {
    echo"<center><b>Charbeschreibung zu kurz oder zu lang.</b></center><br />";
	$user['descr'] = $Descr;
  }
}

?>


<center>
<form method="post" name="profil" action="index.php?site=profil">
	<table>
		<tr>
			<th width=80><strong>E-Mail</strong></th>
			<th width=200><input type="text"  name="mail" value="<?php echo $user['mail']; ?>"></th>
		</tr>
		<tr>
			<th width=80><strong>Realname</strong></th>
			<th width=200><input type="text"  name="rlname" value="<?php echo $user['rlname']; ?>"></th>
		</tr>
		<tr>
			<th width=80><strong>Geschlecht:</strong></th>
			<th width=200>
				<select name="sex">
					<option value="0">--------</option>
					<option value="w" <?php if ($user['sex']=='w'){echo 'selected="selected"';}?>>Weiblich</option>
					<option value="m" <?php if ($user['sex']=='m'){echo 'selected="selected"';}?>>M&auml;nnlich</option>	
				</select>
			</th>
		</tr>
		<tr>
			<th width=80><strong>Website/Blog:</strong></th>
			<th width=200><input type="text"  name="website" value="<?php echo $user['website']; ?>"></th>
		</tr>
		<tr>
			<th width=80><strong>ICQ-Nummer:</strong></th>
			<th width=200><input type="text"  name="icq" value="<?php echo $user['icq']; ?>"></th>
		</tr>
		<tr>
			<th width=80><strong>Password(alt)</strong></th>
			<th width=200><input type="password"  name="password_old" /></th>
		</tr>
		<tr>
			<th width=80><strong>Password(neu)</strong></th>
			<th width=200><input type="password"  name="password_new" /></th>
		</tr>
	</table>
	<input type="submit" value="&auml;ndern"></form>
<br />
<hr width="80%">
<br />
<strong>Reflinks</strong><br />
Diesen Link kannst du deinen Freunden einladen, wenn diese dann ein bestimmtes Level erreichen bekommst du eine kleine Belohnung.
<br /><br />
<?php echo $GLOBALS['conf']['konst']['url'];?>/?site=anmelden&ref=<?php echo $user['id'];?>
<br />
<hr width="80%">
<br />
<strong>Avatar</strong>
<br />
	zb. http://www.gladiatorgame.de/1.JPG<br />
	du kannst dein Profilbild auf der Seite <br />
	<a href="http://image.gladiatorgame.de/">http://image.gladiatorgame.de</a><br />
	hochladen.
<br />
<div style="width:160px;height:130px;border:1px solid black;">
	<img src="<?php echo $user['avatar']; ?>" border="0" width="160" height="130">
</div>
<br />
<form name="form_avater" method="post" action="index.php?site=profil">
	<input type="text" name="avatar" value="<?php echo $Showava; ?>" style="width:280px;">
	<br />
	<br />
	<input type="submit" value="&auml;ndern">
	<hr width="80%">
	<br />
	<strong>Charbeschreibung (max. 2500 Zeichen)</strong>
	<br />
	<textarea name="descr" style="width:400px;height:200px;"><?php echo br2nl($user['descr']); ?></textarea>
	<br />
	<br />
	<input type="submit" value="&auml;ndern">
</form>
</center>
