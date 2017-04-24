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

echo'
<strong>Na, lust auf ein kleines Spielchen?</strong><br />
Such dir eins der beiden Spiele aus und stell dein Glück auf die Probe<br />
Möge Fortuna dir holt sein.';

echo'
	<div class="center">
		<strong><a href="?site=muenzenspiel"><h3>&raquo; Eine Münze werfen</h3></a></strong>
	</div><br />';
  
echo'
	<div class="center">
		<strong><a href="?site=zahlenspiel"><h3>&raquo; Errate die richtigen Zahlen</h3></a></strong>
	</div><br />';
?>
