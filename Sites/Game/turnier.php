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
include"class/Turnier.php";

$Turnier = new Turnier();

echo '
	<br />
	<center>
		<form name="givTurnier" method="post" action="index.php?site=turnieruebersicht">
			<select name="id">';
			foreach ($Turnier->idListe(1) as $id => $t)
			{
					if ($t)
					{
						$text = $id." (".$t.")";
					} else
					{
						$text = $id." (noch offen)";
					}
					echo '<option value="'.$id.'">'.$text.'</option>';
			}

			echo '</select>
			&nbsp;
			<input type="submit" value="Anzeigen">
		</form>
	</center><br />';

if ($_REQUEST['this_turnier'] && $_REQUEST['this_turnier_id'])
{
	$t = $Turnier->ifTurnier($_REQUEST['this_turnier_id']);
	if ($t)
	{
		echo $Turnier->registerForm($user, $_REQUEST['this_turnier_id']);
		$Turnier->makeUebersicht($user);
	}
} else
{
	$Turnier->makeUebersicht($user);
}
?>
