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

//error_reporting(E_ALL);

// JesusCry Funktionsbibliothek v0.09

// 1. Systemvariablen
// 2. Errorhandling
// 3. Datenbankfunktionen
// 4. Grafikfunktionen
// 5. Textformatierung

////////////////////////////////
//* Systemvariablen - Anfang *//
////////////////////////////////

function syswars()
{
  global $errormessage;
  global $show_error;
  
  $errormessage = null; // muss immer 'null' sein, wichtig f�r Errorhandling
  $show_error = "no"; // sollen Errors ausgegeben werden?
}

syswars();

////////////////////////////
//*Errorhandling - Anfang*//
////////////////////////////

// eh = Errorhandling

// eh.a = Allgemeines Errorhandling

// eh.a1. Errormessages empfangen & ausgeben

/* eh.a1. Errormessages empfangen & ausgeben */
function errormessage($error)
{
  global $errormessage;
  $errormessage = "<p><div style=\"font-size:14px;\" align=center><b>Error: $error</b></div></p><br>";
  echo"$errormessage";
}

//////////////////////////////////
//*Datenbankfunktionen - Anfang*//
//////////////////////////////////

// db = Datenbankfunktionen

// db.a = Verbindung
// db.b = Spalten

// db.a1. Hostverbindung aufbauen & Datenbank w�hlen

// db.b1. SELECT: Tabellenspalte auslesen
// db.b2. UPDATE: Tabellenspalte �ndern
// db.b3. INSERT: Tabellenspalte hinzuf�gen
// db.b4. DELETE: Tabellenspalte entfernen
// db.b5. COUNT: Tabellenspalte z�hlen

/* db.a1. Hostverbindung aufbauen & Datenbank w�hlen */
function db_connect($host, $username, $password, $db)
{
  $connect_mysql = mysql_connect($host, $username , $password);
  $connect_db = mysql_select_db($db);

  if($connect_mysql != null AND $connect_db != null)
  {
    return true;
  }
  else
  {
	$error = "db_connect($host, $username, $password, $db)";
	if($show_error == "yes"){$errormessage($error);}
	return false;
  }
}

/* db.b1. SELECT: Tabellenspalte auslesen */
function mysql_select($auswahl,$tabellenname,$where=null,$order=null,$limit=null)
{
  $abfrage = "SELECT $auswahl FROM $tabellenname";
  
  if($where != null)
  {
    $abfrage = $abfrage." WHERE $where";
  }
  
  if($order != null)
  {
    $abfrage = $abfrage." ORDER BY $order";
  }
  
  if($limit != null)
  {
    $abfrage = $abfrage." LIMIT $limit";
  }
  
  $select = @mysql_query($abfrage);
  $return = @mysql_fetch_assoc($select);
  
  if($select == true AND $return != null)
  {
    return $return;
  }
  else
  {
    $error = "mysql_select($auswahl, $tabellenname, $where, $order, $limit)";
	if($show_error == "yes"){errormessage($error);}
	return false;
  }
}

/* db.b2. UPDATE: Tabellenspalte �ndern */
function mysql_update($tabellenname, $set, $where)
{
  $abfrage = "UPDATE $tabellenname SET $set";
  
  if($where != null)
  {
    $abfrage = $abfrage." WHERE $where";
  }
  
  $update = mysql_query($abfrage);
  
  if($update == true)
  {
    return true;
  }
  else
  {
    $error = "mysql_update($tabellenname, $set, $where)";
	if($show_error == "yes"){errormessage($error);}
	return false;
  }
}

/* db.b3. INSERT: Tabellenspalte hinzuf�gen */
function mysql_insert($tabellenname, $spalten, $values)
{
  $abfrage = "INSERT INTO $tabellenname ($spalten) VALUES ($values)";
  $insert = mysql_query($abfrage);
  
  if($insert == true)
  {
    return true;
  }
  else
  {
    $error = "mysql_insert($tabellenname, $spalten, $values)";
	if($show_error == "yes"){errormessage($error);}
	return false;
  }
}

/* db.b4. DELETE: Tabellenspalte entfernen */
function mysql_delete($tabellenname,$where)
{
  $abfrage = "DELETE FROM $tabellenname WHERE $where";
  $loeschen = mysql_query($abfrage);

  if($loeschen != false AND $loeschen != null)
  {
    return true;
  }
  else
  {
    $error = "mysql_count($tabellenname,$where)";
    if($show_error == "yes"){errormessage($error);}
    return false;
  } 
}

/* db.b5. COUNT: Tabellenspalte z�hlen */
function mysql_count($tabellenname,$count,$where)
{
  $abfrage = "SELECT COUNT($count) FROM $tabellenname";
  
  if($where != null)
  {
    $abfrage = $abfrage." WHERE $where";
  }

  $ergebnis = mysql_query($abfrage);
  $anzahl = mysql_fetch_row($ergebnis);

  if($anzahl[0] != false OR $anzahl[0] != null)
  {
    return $anzahl[0];
  }
  else
  {
    $error = "mysql_count($tabellenname,$count)";
    if($show_error == "yes"){errormessage($error);}
    return false;
  } 
}

////////////////////////////////
//////* Grafikfunktionen *//////
////////////////////////////////

/* Thumbnail erstellen */
function create_thumbnail($image,$breite)
{
  $Grafikdatei = "$image";
  $Bilddaten = getimagesize($Grafikdatei);
  
  $OriginalBreite = $Bilddaten[0];
  $OriginalHoehe = $Bilddaten[1];
  
  $ThumbnailBreite = $breite;

  if($OriginalBreite < $ThumbnailBreite)
  {
    $ThumbnailBreite = $OriginalBreite;
  }

  $Skalierungsfaktor = $OriginalBreite/$ThumbnailBreite;
  $ThumbnailHoehe = intval($OriginalHoehe/$Skalierungsfaktor);
  
  $GrafikZeichenzahl = strlen($Grafikdatei) - 4;
  $Grafikname = substr($Grafikdatei,0,$GrafikZeichenzahl);
  $Grafikname = $Grafikname."_thumb";

  if($Bilddaten[2] == 1)
  {
    $Originalgrafik = ImageCreateFromGIF($Grafikdatei);
    $Thumbnailgrafik = ImageCreateTrueColor($ThumbnailBreite, $ThumbnailHoehe);
    ImageCopyResized($Thumbnailgrafik,$Originalgrafik,0,0,0,0,$ThumbnailBreite,$ThumbnailHoehe,$OriginalBreite,$OriginalHoehe);
    $Grafikname = $Grafikname.".gif";
	ImageGIF($Thumbnailgrafik, $Grafikname);
  }
  elseif($Bilddaten[2] == 2)
  {
    $Originalgrafik = ImageCreateFromJPEG($Grafikdatei);
    $Thumbnailgrafik = ImageCreateTrueColor($ThumbnailBreite, $ThumbnailHoehe);
    ImageCopyResized($Thumbnailgrafik,$Originalgrafik,0,0,0,0,$ThumbnailBreite,$ThumbnailHoehe,$OriginalBreite,$OriginalHoehe);
    $Grafikname = $Grafikname.".jpg";
	ImageJPEG($Thumbnailgrafik, $Grafikname);
  }
  elseif($Bilddaten[2] == 3)
  {
    $Originalgrafik = ImageCreateFromPNG($Grafikdatei);
    $Thumbnailgrafik = ImageCreateTrueColor($ThumbnailBreite, $ThumbnailHoehe);
    ImageCopyResized($Thumbnailgrafik,$Originalgrafik,0,0,0,0,$ThumbnailBreite,$ThumbnailHoehe,$OriginalBreite,$OriginalHoehe);
    $Grafikname = $Grafikname.".png";
	ImagePNG($Thumbnailgrafik, $Grafikname);
  }

  $return = "<img src=\"$Grafikname\">";
  return $return;
}

////////////////////////////////
//////* Textformatierung *//////
////////////////////////////////

// txf = Textformatierung

// txf.rp = Ersetzfunktionen

// txf.rp.a1. BB Code umwandeln
// txf.rp.a2. Deutsche Umlaute umwandeln

/* BB Code umwandeln */
function create_bb_code($text)
{
/*
  $format = array("[i]","[/i]","[b]","[/b]","[u]","[/u]","[color=","[/color]","[url=","[/url]","]");
  $format_to = array("<i>","</i>","<b>","</b>","<u>","</u>","<font color=","</font>","<a target=blank href=","</a>",">");
  $anzahl_format = count($format);
  
  $schleife = 0;
  while($schleife <= $anzahl_format)
  {
    $text = str_replace("$format[$schleife]","$format_to[$schleife]",$text);
    $schleife = $schleife + 1;
  }
  
  $text = $text."</font></b></u></i></a>";
  
  */
  return $text;
}

/* txf.rp.a2. Deutsche Umlaute umwandeln */
function convert_umlaut($text,$aktion) // in arbeit...
{
  // $aktion = 1 -> Umlaute zu Vokalen
  // $aktion = 2 -> Umlaute zu HTML
  
  // $aktion = 3 -> Vokale zu Umlauten
  // $aktion = 4 -> Vokale zu HTML
  
  // $aktion = 5 -> HTML zu Umlauten
  // $aktion = 6 -> HTML zu Vokale

  $format_uml = array("�","�","�","�","�","�");
  $format_vok = array("ae","oe","ue","Ae","Oe","Ue");
  $format_html = array("&auml;","&ouml;","&uuml;","&Auml;","&Ouml;","&Uuml;");
  
  $schleife = 0;
  while($schleife < 3)
  {
    $text = str_replace("$format[$schleife]","$format_to[$schleife]",$text);
    $schleife++;
  }
  
  return $text;
}

////////////////////////////////
///////* Mailfunktionen *///////
////////////////////////////////

function htmlmail($empfaenger,$subject,$body)
{

  $header = "From: stefanjohne@yahoo.de\r\n";
  $header.= "MIME-Version: 1.0\r\n";
  $header.= "Content-Type: text/html; charset=\"iso-8859\"\r\n";
  $header.= "Content-Transfer-Encoding: 8bit ";

  $body = "
  <html>
  <head>
    <title></title>
  </head>
  <body>
    $body
  </body>
  </html>";

  mail($empfaenger,$subject,$body,$header);
}

?>
