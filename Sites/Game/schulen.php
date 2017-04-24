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

error_reporting(E_ALL^E_NOTICE);

$Ally = new AllyClass();

if(isset($_GET['sub']))
{
  $Sub = $_GET['sub'];
}
else
{
  $Sub = null;
}

if($User['schule'] == 0)
{
  echo'
  <center>
  <a href="index.php?site=schulen&sub=list" target="_self">Liste</a> |
  <a href="index.php?site=schulen&sub=enter" target="_self">Beitreten</a> |
  <a href="index.php?site=schulen&sub=new" target="_self">Gründen</a>
  </center><br />';

  if($Sub == 'list' || $Sub == null)
  {
    // Allianzliste -> Allianzinformationen
	$Ally->ListAllys();
  }
  elseif($Sub == 'enter')
  {
    if(isset($_GET['add']) && !empty($_GET['add']))
	{
	  $Add = $_GET['add'];
	}
	else
	{
	  $Add = false;
	}

    if($Add != 'out' && $Add != false)
	{
	  $NewSchool = '0j'.$Add;

      $AddSql = "UPDATE ".TABUSER." Set schule = '$NewSchool' WHERE id='$user[id]' LIMIT 1";
	  $AddQuery = mysql_query($AddSql);

	  $AllySql = "SELECT id,name FROM ".TABALLY." WHERE id='$_GET[add]' LIMIT 1";
	  $AllyQuery = mysql_query($AllySql);
	  $Ally = mysql_fetch_assoc($AllyQuery);

	  echo'<center><strong>Du hast dich erfolgreich bei der Schule "'.$Ally['name'].'" beworben!</strong></center>';
	}
	elseif($Add == 'out' && $Add != false)
	{
	  $AddSql = "UPDATE ".TABUSER." Set schule = '0' WHERE id='$user[id]' LIMIT 1";
	  $AddQuery = mysql_query($AddSql);

	  echo'<center><strong>Du bist von deiner Bewerbung zurückgetreten!</strong></center>';
	}
	else
	{
	  // Allianz beitreten / Beitritt zur�ckziehen
	  $Ally->EnterAlly($User['AllyJoin']);
	}
  }
  elseif(strstr($Sub,'i') == 'info')
  {
    // Allianzscreen anschauen
	$Ally->AllyInfoscreen(substr($Sub,0,strlen($Sub)-4));
  }
  else
  {
    // Allianz gründen
	$Ally->NewAlly();
  }
}
else
{
  $AllySql = "SELECT * FROM ".TABALLY." WHERE id='$user[schule]' LIMIT 1";
  $AllyQuery = mysql_query($AllySql);
  $UserAlly = mysql_fetch_assoc($AllyQuery);

  $PinnSql = "SELECT * FROM ".TABFORUMS." WHERE id='$UserAlly[private]' LIMIT 1";
  $PinnQuery = mysql_query($PinnSql);
  $Pinn = mysql_fetch_assoc($PinnQuery);

  echo'
  <center>
  <a href="index.php?site=schulen&sub=list" target="_self">Liste</a> |
  <a href="index.php?site=schulen&sub='.$UserAlly['id'].'info" target="_self">'.$UserAlly['name'].'</a> |
  <a href="index.php?site=forum&showforum='.$Pinn['id'].'" target="_self">Pinnwand</a> |
  <a href="index.php?site=schulen&sub=gel" target="_self">Gel&auml;nde</a>';

  if($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'])
  {
    echo' | <a href="index.php?site=schulen&sub=gebet" target="_self">Gebetsstätte</a>';
  }

  if($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'] || $user['id'] == $UserAlly['diplo'])
  {
    echo' | <a href="index.php?site=rundmail" target="_self">Rundmail</a>';
	echo' | <a href="index.php?site=schulen&sub=ver" target="_self">Verwaltung</a>';
  }

  echo'</center><br />';

  if($Sub == 'list' || $Sub == null)
  {
    // Allianzliste -> Allianzinformationen
	$Ally->ListAllys();
  }
  elseif($Sub == 'kick' && isset($_GET['who']) && !empty($_GET['who']))
  {
    $Ally->KickUser($_GET['who']);
  }
  elseif(strstr($Sub,'i') == 'info')
  {
    // Allianzscreen anschauen
	$Ally->AllyInfoscreen(substr($Sub,0,strlen($Sub)-4));
  }
  elseif($Sub == 'leave')
  {
    // Ally verlassen
	if(isset($_GET['true']) && $_GET['true'] == 'y')
	{
	  $Ally->LeaveAlly($user['id']);
	}
	else
	{
      echo'
	  <script language="javascript" type="text/javascript">
	  <!--

	  Leave = confirm("Willst du wirklich deine Schule verlassen?\n(Eine Umkehrung dieser Entscheidung ist unm�glich!)");

      if(Leave == true)
      {
        document.location.href="index.php?site=schulen&sub=leave&true=y";
      }
	  else
	  {
	    document.location.href="index.php?site=schulen";
	  }

	  //-->
	  </script>';
	}
  }
  elseif($Sub == 'gel')
  {
    // Allianzgel�nde
            $Ally->AllyLand();
  }
  elseif($Sub == 'gebet' && ($user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize']))
  {
    // Allianzgebete
            $Ally->AllyGebet();
  }
  elseif($Sub == 'ver' && $user['id'] == $UserAlly['boss'] || $user['id'] == $UserAlly['vize'] || $user['id'] == $UserAlly['diplo'])
  {
		// Allianzverwaltung
		$Ally->AllyOptions();
	}
}
?>
