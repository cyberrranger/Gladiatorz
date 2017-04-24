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
?>
<script>
$(document).ready(function() {
    $("#gebotabgeben").dialog({
    	bgiframe: true,
    	autoOpen: false,
    	height: 300,
    	modal: false,
    	buttons: {
    		OK: function() {
    			var how = $("input[name='_gebot']:checked").val();
    			if (how != 3)
    			{
    				var summe = 0;
    				var ItemID = $("#_item").val();
    				
    				var result = actionRequest('action/marketplace/gebot.php', 'item=' + ItemID + '&summe=' +summe + '&how=' + how)
    				
    				if (result.errorcode)
        			{
        				alert(result.msg)
        			} else
        			{
        				alert(result.msg)
        				getItemList()
        				$(this).dialog('close');
        			}
    	    		
    			} else
    			{
    				var summe = $("#_gebotsumme").val();

    				if (!summe)
    				{
    					alert('Es muss ein Gebot abgegeben werden');	
    				} else
    				{
						var ItemID = $("#_item").val();
    					
    					var result = actionRequest('action/marketplace/gebot.php', 'item=' + ItemID + '&summe=' +summe + '&how=' + how)
    					
    					if (result.errorcode)
        				{
        					alert(result.msg)
        				} else
        				{
        					alert(result.msg)
        					getItemList()
        					$(this).dialog('close');
        				}
    				}
    				
    			}
				
    		},
    		Abbrechen: function() {
    			$(this).dialog('close');
    		}
    	}
    });
  });
</script>

<div id="gebotabgeben" style="display:none" title="Gebot abgeben">
	<p>Bitte gebe ein Gebot ab</p>
	<form action="" method="post">
		<input name="_item" id="_item" type="hidden" value="" />
		<input name="_gebot" type="radio" value="1" /> 3%<br />
		<input name="_gebot" type="radio" value="2" /> 10%<br />
		<input name="_gebot" type="radio" value="3" checked="checked" /> Eigenes Gebot<br />
		<input id="_gebotsumme" name="_gebotsumme" type="text" />
	</form>
</div>
	
<div class="center">Sortieren:
	<select id="MarketType" name="type" onchange="getItemList();">
		<option value="13" >Alles</option>
		<option value="1" >Waffen</option>
		<option value="2" >Zweitwaffen</option>
		<option value="3" >Schilde</option>
		<option value="4" >Rüstungen</option>
		<option value="5" >Helme</option>
		<option value="6" >Handschuhe</option>
		<option value="7" >Beinrüstung</option>
		<option value="8" >Gürtel</option>
		<option value="9" >Schuhe</option>
		<option value="10" >Umhang</option>
		<option value="11" >Schulterrüstung</option>
	</select>
	<select id="MarketWhat" name="what" onchange="getItemList();">
		<option value="0">---</option>
		<option value="8">Alles</option>
		<option value="1">Schmiede</option>
		<option value="2">Schullager</option>
		<option value="3">nur Sofortkauf</option>
		<option value="4">nur Auktionen</option>
		<option value="5">nur Namensitems</option>
		<option value="6">meine Gebote</option>
		<option value="7">meine Items</option>
	</select>
	<select id="MarketSort" name="sort" onchange="getItemList();">
		<option value="2">nach Off</option>
		<option value="3">nach Def</option>
		<option value="1">nach Name</option>
		<option value="4">nach Wert</option>
		<option value="5">nach verbleibender Zeit</option>
		<option value="6">nach Preis</option>
	</select>
	
	<select id="MarketRang" name="minRang" onchange="getItemList();">
		<option value="25">Maximaler Rang</option>
		<option value="1"><?php echo getRangName2(1); ?> [1]</option> 
		<option value="2"><?php echo getRangName2(2); ?> [2]</option>
		<option value="3"><?php echo getRangName2(3); ?> [3]</option>
		<option value="4"><?php echo getRangName2(4); ?> [4]</option>
		<option value="5"><?php echo getRangName2(5); ?> [5]</option>
		<option value="6"><?php echo getRangName2(6); ?> [6]</option>
		<option value="7"><?php echo getRangName2(7); ?> [7]</option>
		<option value="8"><?php echo getRangName2(8); ?> [8]</option>
		<option value="9"><?php echo getRangName2(9); ?> [9]</option>
		<option value="10"><?php echo getRangName2(10); ?> [10]</option>
		<option value="11"><?php echo getRangName2(11); ?> [11]</option>
		<option value="12"><?php echo getRangName2(12); ?> [12]</option>
		<option value="13"><?php echo getRangName2(13); ?> [13]</option>
		<option value="14"><?php echo getRangName2(14); ?> [14]</option>
		<option value="15"><?php echo getRangName2(15); ?> [15]</option>
		<option value="16"><?php echo getRangName2(16); ?> [16]</option>
		<option value="17"><?php echo getRangName2(17); ?> [17]</option>
		<option value="18"><?php echo getRangName2(18); ?> [18]</option>
		<option value="19"><?php echo getRangName2(19); ?> [19]</option> 
		<option value="20"><?php echo getRangName2(20); ?> [20]</option> 
		<option value="21"><?php echo getRangName2(21); ?> [21]</option> 
		<option value="22"><?php echo getRangName2(22); ?> [22]</option> 
	</select>
</div>
<div id="ItemList">
	<br />
	<br />
	<div class="center"><strong>... loading ...</strong></div>
</div>
<div class="tooltipsammlung">
</div>
<script>
	MarketInterval = setInterval("getItemList()",1000);
</script>
