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

echo'
<center style="margin-bottom:5px;">
<a href="index.php?site=gasthaus" target="_self">Schankraum</a> |
<a href="index.php?site=chat" target="_self">Chat</a>
(<a href="javascript:PopUp(\'Sites/Game/chat.php\',540,408,0);" target="_self">im PopUp</a>) |
<a href="index.php?site=schlafraum" target="_self">Schlafraum</a> |
<a href="index.php?site=gluecksspiel" target="_self">Glücksspiel</a>
</center>';
if($user['schule'] != 0)
{
  $GH = explode('|',$schule['area']);
  $GH = $GH[2];
}
else
{
  $GH = 0;
}

if(isset($_POST['food']) && !empty($_POST['food']) && $user['kraft'] < $user['max_kraft'])
{
  $Food = $_POST['food'];

  switch($Food)
  {
    // Wasser und Brot
    case 1:
	  $Price = 20;
	  $Kraft = $user['max_kraft'] * 0.1;
	  $Item = '<strong>Wasser und Brot</strong>';
	break;

	// Saft und Obst
	case 2:
	  $Price = 70;
	  $Kraft = $user['max_kraft'] * 0.25;
	  $Item = '<strong>Saft und Obst</strong>';
	break;

	// Bier und Braten
	case 3:
	  $Price = 150;
	  $Kraft = $user['max_kraft'] * 0.5;
	  $Item = '<strong>Bier und Braten</strong>';
	break;

	// Wein und Erlesenes
	case 4:
	  $Price = 200;
	  $Kraft = $user['max_kraft'] * 0.75;
	  $Item = '<strong>Wein und Erlesenes</strong>';
	break;

	// 3 Gang Mahlzeit
	case 5:
	  $Price = 250;
	  $Kraft = $user['max_kraft'];
	  $Item = 'eine <strong>3 Gang Mahlzeit</strong>';
	break;
  }

  echo'<br /><center>';

  if($user['gold'] >= $Price)
  {
    $user['kraft'] += $Kraft;

	if($user['kraft'] > $user['max_kraft'])
	{
	  $user['kraft'] = $user['max_kraft'];
	}

	$user['gold'] -= $Price;

	$UpdateSql = "UPDATE user Set kraft='$user[kraft]',gold='$user[gold]' WHERE id='$user[id]' LIMIT 1";
	$UpdateQuery = mysql_query($UpdateSql);

	echo'Nach dem du '.$Item.' zu dir genommen hast fühlst du dich wieder stark genug für die Arena.';
  }
  else
  {
    echo'Du hast nicht genügend Gold.';
  }

  echo'</center>';
}
elseif(isset($_POST['food']) && !empty($_POST['food']) && $user['kraft'] >= $user['max_kraft'])
{
  echo'<br /><center>Du bist bereits gestärkt genug für den nächsten Kampf!</center>';
}
else
{
  echo'<center>';
  echo'Im Schankraum kannst du Mahlzeiten sowie Getränke zu dir nehmen, um dich zu stärken und deine Lebenskraft wieder aufzufüllen.';

  echo'
  <form name="foodform" action="index.php?site=gasthaus" method="post"><br />
    <table cellpadding="0" cellspacing="0" border="0" width="340" align="center">
	<thead>
	<tr>
	  <th colspan="2">Mahlzeit</th>
	  <th width="80">Kraft</th>
	  <th width="80">Kosten</th>
	</tr>
	</thead>
	<tbody>
    <tr>
	  <td width="20" align="center"><input name="food" type="radio" value="1" /></td>
	  <td align="center">Wasser und Brot</td>
	  <td width="80" align="center">10%</td>
	  <td width="80" align="center">25 Gold</td>
	</tr>';

	if($GH >= 1)
	{

	echo'<tr>
	  <td width="20" align="center"><input name="food" type="radio" value="2" /></td>
	  <td align="center">Saft und Obst</td>
	  <td width="80" align="center">25%</td>
	  <td width="80" align="center">70 Gold</td>
	</tr>

	<tr>
	  <td width="20" align="center"><input name="food" type="radio" value="3" /></td>
	  <td align="center">Bier und Braten</td>
	  <td width="80" align="center">50%</td>
	  <td width="80" align="center">150 Gold</td>
	</tr>';

	}

	if($GH == 2)
	{

	echo'<tr>
	  <td width="20" align="center"><input name="food" type="radio" value="4" /></td>
	  <td align="center">Wein und Erlesenes</td>
	  <td width="80" align="center">75%</td>
	  <td width="80" align="center">200 Gold</td>
	</tr>

	<tr>
	  <td width="20" align="center"><input name="food" type="radio" value="5" checked /></td>
	  <td align="center">3 Gang Mahlzeit</td>
	  <td width="80" align="center">100%</td>
	  <td width="80" align="center">250 Gold</td>
	</tr>';

	}

	echo'</tbody></table><br />

	<input type="submit" value="Bestellen" />

  </form>';

  echo'</center>';
}

?>
