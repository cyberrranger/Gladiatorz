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
// Spielerzahl
function countPlayer()
{
	$Sql = "SELECT count(id) FROM user WHERE lonline > '".(time() - 600)."'";
	$Query = mysql_query($Sql);
	$Player = mysql_fetch_row($Query);
  
	return $Player[0];
}

// Spieler online anzeigen
function showOnlinePlayer()
{
	$List = array();
	$Sql = "SELECT id,name,lonline FROM user WHERE lonline > '".(time() - 600)."'";
	$Query = mysql_query($Sql);
	
	while($Player = mysql_fetch_assoc($Query))
	{
		$List[] = $Player['name'];
	}
	  
	return implode(', ',$List);
}

// Arrays sortieren
function ArrayKeysort($array,$key)
{
	for($i = 0;$i < sizeof($array);$i++) 
	{
		$sort_values[$i] = $array[$i][$key];
	}
   
	asort ($sort_values);
	reset ($sort_values);
   
	while(list($arr_key, $arr_val) =each($sort_values)) 
	{
		$sorted_arr[] = $array[$arr_key];
	}
   
   return $sorted_arr;
}

// Variable auf Leerinhalt �berpr�fen
function blank($String)
{
	$Replace = array(' ','&nbsp;');
	$String = trim($String);
	$String = str_replace($Replace,'',$String);
  
	if($String == '' && empty($String) == true || $String == null)
	{  
		return true;
	} else {
		return false;
	}
} 

function br2nl($str = "") 
{
	return str_replace(nl2br(chr(13)), chr(13), $str);
} 

function getip()
{
	//wenn der User �ber nen Proxy in's Internet geht...
	//muss die IP so "geholt" werden...
	if(getenv("HTTP_X_FORWARDED_FOR"))
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else {
		//ansonsten so...
		$ip = getenv("REMOTE_ADDR");
	}
	
	return $ip;
}

function strim($String)
{
	return str_replace(' ','',strip_tags(trim($String)));
} 

?>
