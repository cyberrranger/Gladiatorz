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

if(!empty($_POST['username']) && !empty($_POST['pw']))
{
  $Username = $_POST['username'];
  $PW = md5($_POST['pw']);

  $Query = mysql_query("SELECT id,name,pw,aktiv FROM ".TAB_USER." WHERE binary name='".$Username."' LIMIT 1");
  $Login = mysql_fetch_assoc($Query);

  
  if($Login == true)
  {
    if($Login['pw'] == $PW && $Login['aktiv'] == 1)
	{
	  echo'
	  <center>
	    <h2 style="color:'.$GLOBALS['conf']['vars']['success_color'].';">
	      Erfolgreich eingeloggt! <a href="?site=uebersicht">Hier gehts weiter...</a></h2>
	      
	  </center>';
    
   
    
    #echo'<center><h2>Bitte f�gt in eurem Profil eure Emailadresse ein  </h2></center>';

	  if(isset($_SESSION['name']) && $_SESSION['name'] != $Login['name'])
	  {
        mysql_query("UPDATE ".TAB_USER." SET multi='".($Login['mutli']+1)."' WHERE id='".$Login['id']."' LIMIT 1");

        $Query = mysql_query("SELECT * FROM ".TAB_USER." WHERE BINARY name='".$_SESSION['name']."' LIMIT 1");
        $Cheater = mysql_fetch_assoc($Query);

        if($Cheater == true)
        {
          mysql_query("UPDATE ".TAB_USER." SET multi='".($Cheater['multi']+1)."' WHERE id='".$Cheater['id']."' LIMIT 1");
        }
      }

	  $_SESSION['name'] = $Login['name'];
	  $_SESSION['id'] = $Login['id'];
	}
	else
	{
	  if($Login['pw'] != $PW)
          {
		echo'<center><h2 style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">Falsches Passwort!</h2></center>';
	  }
	  elseif($Login['aktiv'] != '1')
          {
		echo'<center><h2 style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">Du hast deinen Account noch nicht best&auml;tigt!</h2></center>';
	  }

	}
  }
  else
  {
    echo'<center><h2 style="color:'.$GLOBALS['conf']['vars']['alert_color'].';">Dieser Benutzer existiert nicht!</h2></center>';
  }
}

?>
