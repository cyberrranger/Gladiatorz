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
	error_reporting(E_ALL^E_NOTICE^E_DEPRECATED);

	$GLOBALS['conf'] = array();

	//Datenbank
	$GLOBALS['conf']['db_host'] = "localhost";
	$GLOBALS['conf']['db_user'] = "root";
	$GLOBALS['conf']['db_pass'] = "demo";
	$GLOBALS['conf']['db_name'] = "gladiatorz";

	//Admin
	$GLOBALS['conf']['team'] = array('admin','player2','player3','player4');
	$GLOBALS['conf']['_team']['Admins: '] = array($GLOBALS['conf']['team'][0]);
	$GLOBALS['conf']['_team']['Designer: '] = array($GLOBALS['conf']['team'][1]);
	$GLOBALS['conf']['_team']['Gamemaster: '] = array($GLOBALS['conf']['team'][2]);
	$GLOBALS['conf']['_team']['Wiki: '] = array($GLOBALS['conf']['team'][3]);
	$GLOBALS['conf']['gladi_down'] = false;

	//Deaktivieren
	$GLOBALS['conf']['activ']['basar'] = false;
	$GLOBALS['conf']['activ']['shoutbox'] = false;
	$GLOBALS['conf']['activ']['markt'] = false;
	$GLOBALS['conf']['activ']['umfragen'] = false;

	//Konstanten
	$GLOBALS['conf']['konst']['max_eigenschaften'] = 50;
	$GLOBALS['conf']['konst']['max_faehigkeiten'] = 50;
	$GLOBALS['conf']['konst']['max_pm'] = 10;
	$GLOBALS['conf']['konst']['url'] = "http://www.gladiatorgame.de";

	//global_vars
	#$GLOBALS['conf']['vars']['contest_running_until'] = 1204743600;
	$GLOBALS['conf']['vars']['min_online_time'] = 180;
	$GLOBALS['conf']['vars']['success_color'] = "#31c32a";
	$GLOBALS['conf']['vars']['alert_color'] = "#e36363";
	$GLOBALS['conf']['vars']['team_off_color'] = "#EE0011";
	$GLOBALS['conf']['vars']['myself_color'] = "#8b0000";
	$GLOBALS['conf']['vars']['friends_color'] = "#006400";
	$GLOBALS['conf']['vars']['hover_color'] = "#5b5b5b";
	$GLOBALS['conf']['vars']['highscore_size'] = 40;

	$GLOBALS['conf']['mail']['host'] = 'ssl://smtp.gmail.com:465';
	$GLOBALS['conf']['mail']['username'] = "";
	$GLOBALS['conf']['mail']['password'] = "";
	$GLOBALS['conf']['mail']['from'] = "info@gladiatorgame.de";
	$GLOBALS['conf']['mail']['fromName'] = "Gladiatorz";
?>
