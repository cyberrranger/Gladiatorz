<center>
  <form name="FormHoF" method="post" action="index.php?site=hof">
    <select name="order" onchange="FormHoF.submit()">
    <option value="1">Punkte</option>
	<option value="2">Level</option>
	<option value="3">Gold</option>
	<option value="4">Brutalität</option>
    <option value="5">Quests</option>
		<option value="9">Medallien</option>
<option value="">----------</option>
	<option value="6">Tierkämpfe (gew.)</option>
	<option value="7">Arenakämpfe (gew.)</option>
	<option value="8">Prestigekämpfe (gew.)</option>
<option value="">----------</option>
	<option value="10">HoF Punkte</option>

    </select>
	&nbsp;<input type="submit" value="Ordnen">
  </form>
</center><br />

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

$Page = $_GET['page'];

if(!isset($Page))
{
  $Page = 1;
}

if(isset($_GET['sort']))
{
  $_POST['order'] = $_GET['sort'];
}

$Start = ($Page * $GLOBALS['conf']['vars']['highscore_size']) - $GLOBALS['conf']['vars']['highscore_size'];

if(!empty($_POST['order']) && is_numeric($_POST['order']) && $_POST['order'] >= 1 && $_POST['order'] <= 43)
{
  $Sort = $_POST['order'];

  if($Sort == 1)
  {
  
  echo'<center>Hier werden die Gesammtpunkte angezeigt. Die Gesammtpunkte ergeben sich aus Eigenschaften, Fertigkeiten, gewonnenen kämpfen, Level und geschaffte Quests.<br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Punkte</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>donnergott88</font></td><td><FONT SIZE=+3>dRdA</font></td><td><FONT SIZE=+3>106.160</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>Engelchen</font></td><td><FONT SIZE=+2>DhDt</font></td><td><FONT SIZE=+2>103.913</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Der Vorbote</font></td><td><FONT SIZE=+1>>>F*R</font></td><td><FONT SIZE=+1>95.424</font></td></tr>
	<tr><td>4</td><td>Stillwater</td><td>D-F</td><td>91.081</td></h3></tr>
	<tr><td>5</td><td>Fischfreak</td><td>dRdA</td><td>88.961</td></tr>
	<tr><td>6</td><td>puskin</td><td>SdN</td><td>85.118</td></tr>
	<tr><td>7</td><td>Bugghunter</td><td>>>F*R</td><td>83.771</td></tr>
	<tr><td>8</td><td>spoker</td><td>dRdA</td><td>81.614</td></tr>
	<tr><td>9</td><td>massiv</td><td>dRdA</td><td>80.621</td></tr>
	<tr><td>10</td><td>Dipar</td><td>D-F</td><td>75.369</td></tr>
	</table>";
	}
	elseif($Sort == 2)
  {
  echo'<center>Hier wird das Level angezeigt. Mit jedem neuen Level steigen die benötigten EXP für das nächste Level. Richtig hohe Level schaffen nur sehr gute Spieler.<br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Level</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Engelchen</font></td><td><FONT SIZE=+3>DhDt</font></td><td><FONT SIZE=+3>136</font></td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>donnergott88</font></td><td><FONT SIZE=+3>dRdA</font></td><td><FONT SIZE=+3>136</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>puskin</font></td><td><FONT SIZE=+1>SdN</font></td><td><FONT SIZE=+1>132</font></td></tr>
	<tr><td>4</td><td>spoker</td><td>dRdA</td><td>131</td></tr>
	<tr><td>5</td><td>Fischfreak</td><td>dRdA</td><td>130</td></tr>
	<tr><td>6</td><td>Dipar</td><td>D-F</td><td>129</td></tr>
	<tr><td>7</td><td>Der Vorbote</td><td>>>F*R</td><td>124</td></tr>
	<tr><td>7</td><td>Bugghunter</td><td>>>F*R</td><td>124</td></tr>
	<tr><td>9</td><td>massiv</td><td>dRdA</td><td>123</td></tr>
	<tr><td>10</td><td>SwordTiger</td><td>R*E*B</td><td>122</td></tr>
	</table>";
	}
	elseif($Sort == 3)
  {
  echo'<center>Hier wird das Gold angezeigt. Nur wer viel auf dem Basar handelt und ein wenig Glück mit den Drops hat steht ganz weit oben. Ein paar große Duelle helfen auch dabei die Highscore zu erklimmen ;-) <br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Gold</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Der Vorbote</font></td><td><FONT SIZE=+3>>>F*R</font></td><td><FONT SIZE=+3>405.078.342</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>donnergott88</font></td><td><FONT SIZE=+2>dRdA</font></td><td><FONT SIZE=+2>313.227.864</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Engelchen</font></td><td><FONT SIZE=+1>DhDt</font></td><td><FONT SIZE=+1>164.935.400</font></td></tr>
	<tr><td>4</td><td>Dipar</td><td>D-F</td><td>158.513.635</td></tr>
	<tr><td>5</td><td>Fischfreak</td><td>dRdA</td><td>157.821.375</td></tr>
	<tr><td>6</td><td>Bugghunter</td><td>>>F*R</td><td>123.789.354</td></tr>
	<tr><td>7</td><td>wildthings</td><td>*DHH*</td><td>113.246.109</td></h3></tr>
	<tr><td>8</td><td>spoker</td><td>dRdA</td><td>110.550.357</td></tr>
	<tr><td>9</td><td>SwordTiger</td><td>R*E*B</td><td>104.041.280</td></tr>
	<tr><td>10</td><td>massiv</td><td>dRdA</td><td>74.533.399</td></tr>
	
	</table>";
	}
	elseif($Sort == 4)
  {
  
   echo'<center>Hier wird die Brutalität angegeben. Sie ergibt sich aus den durchschnittlichen Kills pro Tag. <br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Brutalität</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>donnergott88</font></td><td><FONT SIZE=+3>dRdA</font></td><td><FONT SIZE=+3>253</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>Stillwater</font></td><td><FONT SIZE=+2>D-F</font></td><td><FONT SIZE=+2>244</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Engelchen</font></td><td><FONT SIZE=+1>DhDt</font></td><td><FONT SIZE=+1>231</font></td></tr>
	<tr><td>4</td><td>spoker</td><td>dRdA</td><td>230</td></tr>
	<tr><td>5</td><td>Dipar</td><td>D-F</td><td>208</td></tr>
	<tr><td>6</td><td>Fischfreak</td><td>dRdA</td><td>194</td></tr>
	<tr><td>7</td><td>Bugghunter</td><td>>>F*R</td><td>170</td></h3></tr>
	<tr><td>8</td><td>puskin</td><td>SdN</td><td>166</td></tr>
	<tr><td>9</td><td>Der Vorbote</td><td>>>F*R</td><td>164</td></tr>
	<tr><td>10</td><td>Lady Dipar</td><td>D-F</td><td>158</td></tr>
	
	</table>";
	}
	elseif($Sort == 5)
  {
    echo'<center>Hier werden die absolvierten Quests angezeigt. Da die Quest erst relativ spät eingeführt wurden kann man hier auch als neuerer Spieler in den Top10 sein. <br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Quest</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Engelchen</font></td><td><FONT SIZE=+3>DhDt</font></td><td><FONT SIZE=+3>156</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>Kaeselieb</font></td><td><FONT SIZE=+2>*K-H*</font></td><td><FONT SIZE=+2>149</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Stillwater</font></td><td><FONT SIZE=+1>D-F</font></td><td><FONT SIZE=+1>139</font></td></tr>
	<tr><td>4</td><td>Zottel</td><td>dRdA</td><td>136</td></tr>
	<tr><td>5</td><td>Fischfreak</td><td>dRdA</td><td>133</td></tr>
	<tr><td>5</td><td>Yedlik</td><td>D-F</td><td>133</td></tr>
	<tr><td>7</td><td>Heriosta</td><td>*DHH*</td><td>132</td></h3></tr>
	<tr><td>8</td><td>donnergott88</td><td>SdN</td><td>131</td></tr>
	<tr><td>9</td><td>bear der starke</td><td>SdN</td><td>130</td></tr>
	<tr><td>10</td><td>puskin</td><td>SdN</td><td>124</td></tr>
	
	</table>";
	}
		elseif($Sort == 6)
  {
    echo'<center>Hier werden die gewonnen Tierkämpfe angezeigt. Tierkämpfe sind die Grundlage für Gladiatorz und die erste anlaufstation für jeden Gladiator der sich in den Kampf wagt. <br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Tierkämpfe(gew.)</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Engelchen</font></td><td><FONT SIZE=+3>DhDt</font></td><td><FONT SIZE=+3>125.690</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>donnergott88</font></td><td><FONT SIZE=+2>dRdA</font></td><td><FONT SIZE=+2>110.929</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Der Vorbote</font></td><td><FONT SIZE=+1>>>F*R</font></td><td><FONT SIZE=+1>104.672</font></td></tr>
	<tr><td>4</td><td>Fischfreak</td><td>dRdA</td><td>95.237</td></tr>
	<tr><td>5</td><td>Bugghunter</td><td>>>F*R</td><td>90.000</td></tr>
	<tr><td>6</td><td>puskin</td><td>SdN</td><td>85.322</td></tr>
	<tr><td>7</td><td>spoker</td><td>dRdA</td><td>78.192</td></h3></tr>
	<tr><td>8</td><td>Dipar</td><td>D-F</td><td>73.090</td></tr>
	<tr><td>9</td><td>SwordTiger</td><td>R*E*B</td><td>68.387</td></tr>
	<tr><td>10</td><td>cyberrranger</td><td>dRdA</td><td>68.023</td></tr>
	
	</table>";
	}
	elseif($Sort == 7)
  {
    echo'<center>Hier werden die gewonnen Arenakämpfe angezeigt. Die Arena ist der älteste PvP Ort in Gladiatoz und eine 1a anlaufstelle um zu testen wie stark man wirklich ist.<br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Arenakämpfe(gew.)</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>puskin</font></td><td><FONT SIZE=+3>SdN</font></td><td><FONT SIZE=+3>8.731</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>Killer300</font></td><td><FONT SIZE=+2>SdG</font></td><td><FONT SIZE=+2>4.022</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Heroista</font></td><td><FONT SIZE=+1>*DHH*</font></td><td><FONT SIZE=+1>3.304</font></td></tr>
	<tr><td>4</td><td>stone</td><td>GlaDi</td><td>3.257</td></tr>
	<tr><td>5</td><td>druan</td><td>R*E*B</td><td>2.944</td></tr>
	<tr><td>6</td><td>spoker</td><td>dRdA</td><td>2.607</td></tr>
	<tr><td>7</td><td>D�ner</td><td>*DHH*</td><td>2.540</td></h3></tr>
	<tr><td>8</td><td>cyberrranger</td><td>dRdA</td><td>2.514</td></tr>
	<tr><td>9</td><td>Dipar</td><td>D-F</td><td>2.386</td></tr>
	<tr><td>10</td><td>donnergott88</td><td>dRdA</td><td>2.217</td></tr>
	
	</table>";
	}
	elseif($Sort == 8)
  {
    echo'<center>Hier werden die gewonnen Prestigkämpfe angezeigt. Das Publikum feiert jeden der es schafft gleich gegen mehrere gefährliche Tiere zu gewinnen. <br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Prestigkämpfe(gew.)</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Stillwater</font></td><td><FONT SIZE=+3>D-F</font></td><td><FONT SIZE=+3>33.694</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>donnergott88</font></td><td><FONT SIZE=+2>dRdA</font></td><td><FONT SIZE=+2>13.405</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>massiv</font></td><td><FONT SIZE=+1>dRdA</font></td><td><FONT SIZE=+1>11.226</font></td></tr>
	<tr><td>4</td><td>Gesichtskicker</td><td>DhDt</td><td>9.667</td></tr>
	<tr><td>5</td><td>valek</td><td>*DHH*</td><td>6.879</td></tr>
	<tr><td>6</td><td>Secundus</td><td>R*E*B</td><td>6.357</td></tr>
	<tr><td>7</td><td>jetstream</td><td>D-F</td><td>6.143</td></h3></tr>
	<tr><td>8</td><td>Der Vorbote</td><td>>>F*R</td><td>5.452</td></tr>
	<tr><td>9</td><td>Raveren</td><td>dRdA</td><td>4.088</td></tr>
	<tr><td>10</td><td>AngelDeath</td><td>SdN</td><td>3.268</td></tr>
	
	</table>";
	}
	elseif($Sort == 9)
  {
	echo" <table>
	  echo'<center>Hier werden die Medallien angezeigt. Sie sind eine Anzeige für den Ruhm eines Gladiators, nur wer vom Publikum geliebt wird schafft es hier in die Top10. <br \><br \></center>';
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >Medallien</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>Stillwater</font></td><td><FONT SIZE=+3>D-F</font></td><td><FONT SIZE=+3>1.739</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>donnergott88</font></td><td><FONT SIZE=+2>dRdA</font></td><td><FONT SIZE=+2>1.068</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>massiv</font></td><td><FONT SIZE=+1>dRdA</font></td><td><FONT SIZE=+1>859</font></td></tr>
	<tr><td>4</td><td>Fischfreak</td><td>dRdA</td><td>777</td></tr>
	<tr><td>5</td><td>puskin</td><td>SdN</td><td>591</td></tr>
	<tr><td>6</td><td>Kaeselieb</td><td>*K-H*</td><td>542</td></tr>
	<tr><td>7</td><td>rusteriminator</td><td>dRdA</td><td>501</td></h3></tr>
	<tr><td>8</td><td>SwordTiger</td><td>R*E*B</td><td>498</td></tr>
	<tr><td>9</td><td>jetstream</td><td>D-F</td><td>476</td></tr>
	<tr><td>10</td><td>Dipar</td><td>D-F</td><td>471</td></tr>
	
	</table>";
	}
	elseif($Sort == 10)
  {
    echo'<center>Das ist eine ganz Spezielle Score. Hier werden die 10 besten Gladiatoren der letzten Runde angezeigt. für jede Plazierung in der Hall of Fame gibt es verschieden viele Punkte, für den Ersten 10, für den Zweiten 9 usw. <br \><br \>
	An dieser stelle will ich auch erwähnen das es leider keine Hall of Fame für Duelle gibt da es da eine Fehlspeicherung gab. Die duelle hätten noch etwas bei den mittleren Plazierungen verändert, die Top3 sind aber fest.<br \>
	Auch zu erwähnen ist ein großer Gladiator namens st rob der leider von uns gegangen ist. Wenn er geblieben wäre wurde er hier auch ganz oben mit dabei stehen.<br \><br \></center>';
	echo" <table>
	<tr><td bgcolor='#b87b00' width='50'>Platz</td><td bgcolor='#b87b00' width='200' >Name</td><td bgcolor='#b87b00' width='70' >Schule</td><td bgcolor='#b87b00' width='100' >HoF Punkte</td></tr>
	<tr><td><FONT SIZE=+3>1</font></td><td><FONT SIZE=+3>donnergott88</font></td><td><FONT SIZE=+3>dRdA</font></td><td><FONT SIZE=+3>70</font></td></tr>
	<tr><td><FONT SIZE=+2>2</font></td><td><FONT SIZE=+2>Engelchen</font></td><td><FONT SIZE=+2>DhDt</font></td><td><FONT SIZE=+2>55</font></td></tr>
	<tr><td><FONT SIZE=+1>3</font></td><td><FONT SIZE=+1>Stillwater</font></td><td><FONT SIZE=+1>D-F</font></td><td><FONT SIZE=+1>44</font></td></tr>
	<tr><td>4</td><td>Fischfreak</td><td>dRdA</td><td>43</td></tr>
	<tr><td>5</td><td>puskin</td><td>SdN</td><td>38</td></tr>
	<tr><td>6</td><td>Der Vorbote</td><td>>>F*R</td><td>35</td></tr>
	<tr><td>7</td><td>spoker</td><td>dRdA</td><td>29</td></h3></tr>
	<tr><td>8</td><td>Dipar</td><td>D-F</td><td>25</td></tr>
	<tr><td>9</td><td>Bugghunter</td><td>>>F*R</td><td>22</td></tr>
	<tr><td>10</td><td>massiv</td><td>dRdA</td><td>21</td></tr>
	
	</table>";
	}
	}

?>
