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

session_start();

require('../System/conf.php');
require('../System/Database.php');

$ID = mysql_fetch_assoc(mysql_query("SELECT * FROM user WHERE name = '$_SESSION[name]' LIMIT 1"));
$ID = $ID['id'];

if(!empty($ID))
{
  $Time = time() - 300;
  $Sql = "SELECT * FROM user WHERE id = '$ID' AND lonline > $Time LIMIT 1";
  $Query = mysql_query($Sql);
  $User = mysql_fetch_assoc($Query);
  
  if(!$User)
  {
  	exit;
  }
  else
  {
    $User['max_kraft'] = ($User['staerke'] * 50) + ($User['kondition'] * 120) + ($User['geschick'] * 5) + ($User['heilkunde'] * 30) + ($User['level'] * 50) + ($User['Kraftprotz'] * 50);
    if($User['max_kraft'] < 100) $User['max_kraft']  = 100;
  }
}
  
if($User['kraft'] < $User['max_kraft'] && $User['gold'] >= 300)
{
  $User['gold'] -= 300;
  $User['kraft'] = $User['max_kraft'];
  
  $UpdateSql = "UPDATE user Set gold='$User[gold]',kraft='$User[kraft]' WHERE id='$ID' LIMIT 1";
  $UpdateQuery = mysql_query($UpdateSql);
  
  echo'true';
}
else
{
  echo'false';
}

?>
