function getItemList()
{
  if(MarketInterval)
  {
    clearInterval(MarketInterval);
  }
  
  document.getElementById('ItemList').innerHTML = '<br /><br /><div class="center"><strong>... loading ...</strong></div>';
	
  var MarketType = document.getElementById('MarketType').value;
  var MarketWhat = document.getElementById('MarketWhat').value;
  var MarketSort = document.getElementById('MarketSort').value;
	
  var result = actionRequest('action/marketplace/get_item_list.php', 'type='+MarketType+'&what='+MarketWhat+'&sort='+MarketSort)
  
  $("#ItemList").html(result.text)
  $(".tooltipsammlung").html(result.tool)
}

function buyItem(ItemID)
{
	var result = actionRequest('action/marketplace/buy.php', 'item=' + ItemID)
	alert(result.msg)
	
	var MarketType = document.getElementById('MarketType').value;
	var MarketWhat = document.getElementById('MarketWhat').value;
	var MarketSort = document.getElementById('MarketSort').value;
	var result = actionRequest('action/marketplace/get_item_list.php', 'type='+MarketType+'&what='+MarketWhat+'&sort='+MarketSort)
	  
	  $("#ItemList").html(result.text)
	  $(".tooltipsammlung").html(result.tool)
}

function bieteItem(ItemID)
{
	 $('#gebotabgeben').dialog('open');
	 $("#_item").val(ItemID)
}

function actionRequest(_url, _data)
{
    	var myresult;
    	$.ajax({
    		  type: "POST",
    		  async: false,
    		  url: _url,
    		  data: _data,
    		  datatype : 'json',
    		  success: function(data){
    			  myresult=data;
    		  }
    	});
    	eval("var myresult = "+myresult+";")
    	return myresult;
}

function reloadSB()
{
    	$.ajax({
    		  type: "POST",
    		  async: true,
    		  url: 'action/sb/reload.php',
    		  data: "reload=1",
    		  datatype : 'json',
    		  success: function(data){
    			  $("#show").html(data.text)
    		  }
    	});
}

function deleteMSG(what) {
	 $("#"+what+"_delete").click(function() {
		 var result = actionRequest("action/msg/del.php", 'do=del&what='+what)
		if (result)
		{
			$("#"+what+"_answer").hide();
			$("#"+what+"_delete").hide();
			$("#"+what+"_div").hide();
			$("#"+what+"_h").hide();
		}
	 });
}

function answerMSG(what) {
	$("#"+what+"_answer").click(function() {
		 alert(what+"A");
	});
}

function updatechar1(what)
{
    $("#"+what).click(function() {
		var result = actionRequest("action/charakter.php", 'do=char1&what='+what)
		if (result)
		{
				$("#_"+what).text(result[what])

				$( "#progressbar"+what ).progressbar({
						value: result[what] * 2
				});

				if (result.error == 'notenoughtpm')
				{
						$("#"+what).button( "option", "disabled", true );
						$("#"+what).attr('title', result.tmp + ' PM')
				} else
				{
						$("#pm").text(result.powmod)
						$("#"+what).attr('title', result.tmp + ' PM')
				}

				$("#savepm").text('Vorhandene PM sparen (' + result.pmspar + ')')
		}
    });
}

function opennewMsg() {
	$("#_to").val("");
	$("#_title").val("");
	$("#_msg").val("");
	$('#dialognewmsg').dialog('open');
}

function updatechar2(what)
{
	$("#"+what).click(function() {
		var result = actionRequest("action/charakter.php", 'do=char2&what='+what)
		if (result)
		{
				$("#_"+what).text(result[what])

				$( "#progressbar"+what ).progressbar({
						value: result[what]
				});

				if (result.error == 'notenoughtpm')
				{
						$("#"+what).button( "option", "disabled", true );
						$("#"+what).attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
				} else
				{
						$("#pm").text(result.powmod)
						$("#gold").text(result.gold)
						$("#"+what).attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
				}

				$("#savepm").text('Vorhandene PM sparen (' + result.pmspar + ')')
		}
    });
}

function updatetrain(what)
{
	$("#"+what).click(function() {
		var result = actionRequest("action/trainer.php", 'do=train1&what='+what)
		if (result)
		{
				$("#_"+what).text(result[what])

				$( "#progressbar"+what ).progressbar({
						value: result[what]
				});

				if (result.error == 'notenoughtpm')
				{
						$("#"+what).button( "option", "disabled", true );
						$("#"+what).attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
				} else
				{
						$("#pm").text(result.powmod)
						$("#gold").text(result.gold)
						$("#waffenkunde").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
						$("#ausweichen").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
						$("#taktik").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
						$("#zweiwaffenkampf").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
						$("#heilkunde").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
						$("#schildkunde").attr('title', result.tmp +' PM<br /> ' + result.tmp2 + ' Gold')
				}
		}
    });
}

function updatemove(what)
{
	$("#"+what).click(function() {
		var result = actionRequest("action/moves.php", 'do=move&what='+what)
		if (result)
		{
				$("#_"+what).text(result[what])

				$( "#progressbar"+what ).progressbar({
						value: result[what]
				});

				if (result.error == 'notenoughtpm') {
						$("#"+what).button( "option", "disabled", true );
				} else {
						$("#smskillpoints").text(result.count)
				}
		}
    });
}