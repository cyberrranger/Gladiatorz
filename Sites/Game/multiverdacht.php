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
if(!isMod())
{
	exit;
}

$sql = "SELECT * FROM multiverdacht";
$result = mysql_query($sql) or die(mysql_error());

echo"
<table border=1>
<tr>
<th>Username</th>
<th>IP</th>
<th>Welcher USer verwendet auch diese IP?</th>
<th>Wann war das?</th>
</tr>";

while ($ausgabe = mysql_fetch_array ($result))
{
	echo"
	<tr>
	<td>".$ausgabe['user']."</td>
	<td>".$ausgabe['ip']."</td>
	<td>".$ausgabe['ipfromuser']."</td>
	<td>".$ausgabe['date']."</td>
	</tr>";
}

echo"</table><br><br><br>";

?>
