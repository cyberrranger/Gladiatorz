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

$Query = mysql_query("SELECT count(id) FROM user");
$RegistrierteUser = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM user WHERE lonline > '".(time()-86400)."'");
$AktiveUser1 = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM user WHERE lonline > '".(time()-259200)."'");
$AktiveUser2 = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM user WHERE lonline > '".(time()-604800)."'");
$AktiveUser3 = mysql_fetch_row($Query);

$Query = mysql_query("SELECT sum(gold) FROM user");
$Gold = mysql_fetch_row($Query);

$Query = mysql_query("SELECT sum(medallien) FROM user");
$Medallien = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM ausruestung WHERE ort = 'basar' AND dauer >= 1 AND preis >= 1");
$BasarAngebote = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM ausruestung WHERE ort = 'markt' AND dauer >= 1 AND preis >= 1");
$MarktAngebote = mysql_fetch_row($Query);

$Query = mysql_query("SELECT count(id) FROM ally_schule");
$Schulen = mysql_fetch_row($Query);

$Query = mysql_query("SELECT SUM(collected_name_1) FROM collected");
$collected_1 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_2) FROM collected");
$collected_2 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_3) FROM collected");
$collected_3 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_4) FROM collected");
$collected_4 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_5) FROM collected");
$collected_5 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_6) FROM collected");
$collected_6 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_7) FROM collected");
$collected_7 = mysql_fetch_row($Query);
$Query = mysql_query("SELECT SUM(collected_name_8) FROM collected");
$collected_8 = mysql_fetch_row($Query);


echo '
<br /><div style="width:200px;margin:0 auto;">
<strong>&raquo; '.$RegistrierteUser[0].' registrierte User<br />
&raquo; '.$AktiveUser1[0].' aktive User (1 Tag)<br />
&raquo; '.$AktiveUser2[0].' aktive User (3 Tage)<br />
&raquo; '.$AktiveUser3[0].' aktive User (7 Tage)<br /><br />
&raquo; '.round(($Gold[0]/1000000),2).' Mio Gold<br />
&raquo; '.$Medallien[0].' Medaillen<br />
&raquo; '.$Schulen[0].' Schulen<br />
&raquo; '.$collected_1[0].' Felle<br />
&raquo; '.$collected_2[0].' Zähne<br />
&raquo; '.$collected_3[0].' Krallen<br />
&raquo; '.$collected_4[0].' Federn<br />
&raquo; '.$collected_5[0].' Stöcke<br />
&raquo; '.$collected_6[0].' Steine<br />
&raquo; '.$collected_7[0].' Pfeile<br />
&raquo; '.$collected_8[0].' Köcher<br />
&raquo; '.$MarktAngebote[0].' Marktangebote<br />
&raquo; '.$BasarAngebote[0].' Basarangebote</strong></div>';


?>

<br /><br /><center><strong>Ein großes Dankeschön geht an folgende Personen/Spieler:</strong><br /><br />Admin (satzzeichen), Janun, Dorgo (Ohne Worte :D), st rob, Gladius, Chackey, squall, Spalter, Steppo, Killer300, gone, Sin, key1991 (jetzt keyking, In guten wie in schlechten Zeiten, der beste Farmer auf allen Seiten!), Der Vorbote, Sandner (ältester, aktiver Gladi Gamer!), minamoto (40 IPs in 60 Minuten...), cyberrranger, rescale (Gladi zum "anfassen"), SirCire, -riecher-, Mautzi (aber komm ja nie wieder ;-D), Hannibal der Große, nitti, Lloyd, donnergott88, stone, Balthazare, Grisu, wildthings, druan, xerox, spoker, poolie, wayfearer, Morgoth<br /><br /><em style="font-size:10px;">PS: Auch ich bin nicht unfehlbar :D falls ich dich hier vergessen hab, schreib mir ne PM!</em></center><br />
