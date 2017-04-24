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
$Chatname = $_SESSION['name'];

?>

<center>
<applet name="applet" code=IRCApplet.class archive="irc.jar" width=520 height=390 codebase="http://werde-legende.de/~webchat/pjirc/">
<param name="CABINETS" value="irc.cab,securedirc.cab"><!--irc.cab,securedirc.cab-->
<param name="alternatenick" value="Gast???">
<param name="name" value="ChatUser">
<param name="host" value="irc.nonsense.irc-mania.de"><!---->
<param name="port" value="6667">
<param name="channel" value="#Gladiatorz" />
<param name="titleExtra" value=" - IRC-Joty2424 - CHAT" />
<param name="username" value="ChatUser" />
<param name="realname" value="WebChat, http://www.joty24.de" />
<param name="nick" value="<? echo $Chatname; ?>" />
<param name="command1" value="join #Gladiatorz">
<param name="nickfield" value="false">
<param name="language" value="german">
<param name="smileys" value="true">
<param name="bitmapsmileys" value="true">
<param name="highlight" value="true">
<param name="highlightnick" value="true">
<param name="highlightcolor" value="9">
<param name="asv" value="true">
<param name="quitmessage" value="Good By!">
<param name="showabout" value="false">
<param name="styleselector" value="true">
<param name="setfontonstyle" value="true">
<param name="basecolor" value="78,8C,A5">
<param name="aslfemale" value="f">
<param name="aslmale" value="m">
<param name="showconnect" value="true">
<param name="channelfont" value="12 Arial">
<param name="chanlistfont" value="12 Arial">
<param name="nickfield" value="false">
<param name="useinfo" value="true">
<param name="styleselector" value="true">
<param name="setfontonstyle" value="true">
<param name="backgroundimage" value="true">
<param name="defaultbackgroundimage" value="awc.gif">
<param name="floatingasl" value="true">
<param name="color5" value="FF9933">
<param name="color6" value="4B4B4B">
<param name="smiley1" value=":) img/sourire.gif">
<param name="smiley2" value=":-) img/sourire.gif">
<param name="smiley3" value=":-D img/content.gif">
<param name="smiley4" value=":d img/content.gif">
<param name="smiley5" value=":-O img/OH-2.gif">
<param name="smiley6" value=":o img/OH-1.gif">
<param name="smiley7" value=":-P img/langue.gif">
<param name="smiley8" value=":p img/langue.gif">
<param name="smiley9" value=";-) img/clin-oeuil.gif">
<param name="smiley10" value=";) img/clin-oeuil.gif">
<param name="smiley11" value=":-( img/triste.gif">
<param name="smiley12" value=":( img/triste.gif">
<param name="smiley13" value=":-| img/OH-3.gif">
<param name="smiley14" value=":| img/OH-3.gif">
<param name="smiley15" value=":.( img/pleure.gif">
<param name="smiley16" value=":$ img/rouge.gif">
<param name="smiley17" value=":-$ img/rouge.gif">
<param name="smiley18" value="(H) img/cool.gif">
<param name="smiley19" value="(h) img/cool.gif">
<param name="smiley20" value=":-@ img/enerve1.gif">
<param name="smiley21" value=":@ img/enerve2.gif">
<param name="smiley22" value=":-S img/roll-eyes.gif">
<param name="smiley23" value=":s img/roll-eyes.gif">
<param name="helppage" value="http://www.onlyfree.de/chatserver/hilfe.php">
</applet></center>
