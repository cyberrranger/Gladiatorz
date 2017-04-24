/*inventar tools --> because WZ dont work?! O.O*/
wmtt = null;

document.onmousemove = updateWMTT;

function updateWMTT(e) {
	x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX;
	y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY;
	if (wmtt != null) {
		wmtt.style.left = (x + 20) + "px";
		wmtt.style.top 	= (y + 20) + "px";
	}
}

function showWMTT(id) {
	wmtt = document.getElementById(id);
	wmtt.style.display = "block"
}

function hideWMTT() {
	wmtt.style.display = "none";
}
/*WZ backend2 for inv end*/

var XMLHTTP = null;
  
  if(window.XMLHttpRequest)
  {
    XMLHTTP = new XMLHttpRequest();
  }
  
  else if(window.ActiveXObject)
  {
    try
	{
	  XMLHTTP = new ActiveXObject("Msxml.XMLHTTP");
	}
	
	catch(ex)
	{
	  try
	  {
	    XMLHTTP = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	  catch(ex)
	  {
	    alert("Error: Instanzierung des XMLHTTP Objekts ist fehlgeschlagen!");
	  }
	}
  }
 
var selinvitem = 0;
var itemid = 0;
var change = 'no';

function selectInvItem(id,item_id)
{
  if(id == selinvitem)
  {
	if(change=='no')
	{
	  document.getElementById('item'+id).style.border = '1px solid black';
	}
	else
	{
	  change='no';
	}
	selinvitem = 0;
	itemid = 0;
  }
  else if(selinvitem == 0)
  {
	document.getElementById('item'+id).style.border = '1px solid red';
	selinvitem = id;
	itemid = item_id;
  }
  else
  {
    document.getElementById('item'+id).style.border = '1px solid red';
	document.getElementById('item'+selinvitem).style.border = '1px solid black';
	selinvitem = id;
	itemid = item_id;
  }
}

var invslot;

function changeInvSlot(slot)
{
  if(selinvitem != 0 && itemid != 0)
  {
	document.getElementById('tab'+selinvitem).innerHTML = '<img onclick="changeInvSlot('+selinvitem+')" src="Images/Items/empty.gif" style="position:absolute;margin-left:-17px;margin-top:-17px;padding:0px;z-index:10;border:1px solid black;" />';
	
	XMLHTTP.open("GET", "Sites/Game/Inventar/changeslot.php?slot="+slot+'&to='+itemid);
	invslot = slot;
	XMLHTTP.onreadystatechange = showInvItem;
	XMLHTTP.send(null);
	
	change = 'yes';
	selectInvItem(selinvitem,itemid);
  }
}
 
function showInvItem()
{
  if(XMLHTTP.readyState == 4)
  {
	document.getElementById('tab'+invslot).innerHTML = '';
    document.getElementById('tab'+invslot).innerHTML = XMLHTTP.responseText;
  }
}

function putTrashInvItem()
{
  if(selinvitem != 0 && itemid != 0)
  {
	document.getElementById('tab'+selinvitem).innerHTML = '<img onclick="changeInvSlot('+selinvitem+')" src="Images/Items/empty.gif" style="position:absolute;margin-left:-17px;margin-top:-17px;padding:0px;z-index:10;border:1px solid black;" />';
	
	XMLHTTP.open("GET", "Sites/Game/Inventar/trash.php?item="+itemid);
	XMLHTTP.onreadystatechange = showTrashInvItem;
	XMLHTTP.send(null);
	
	itemid = 0;
	selinvitem = 0;
  }
}

function showTrashInvItem()
{
	if(XMLHTTP.readyState == 4)
	  {
	    alert(XMLHTTP.responseText);
	  }
}

function sellInvItem()
{
  if(selinvitem != 0 && itemid != 0)
  {
	document.getElementById('tab'+selinvitem).innerHTML = '<img onclick="changeInvSlot('+selinvitem+')" src="Images/Items/empty.gif" style="position:absolute;margin-left:-17px;margin-top:-17px;padding:0px;z-index:10;border:1px solid black;" />';
	
	XMLHTTP.open("GET", "Sites/Game/Inventar/sell.php?item="+itemid);
	XMLHTTP.onreadystatechange = showSellInvItem;
	XMLHTTP.send(null);
	
	itemid = 0;
	selinvitem = 0;
  }
}

function showSellInvItem()
{
	if(XMLHTTP.readyState == 4)
	  {
	    alert(XMLHTTP.responseText);
	  }
}

 
function HealerButton()
  {
    XMLHTTP.open("GET", "Scripts/shortheal.php");
	XMLHTTP.onreadystatechange = ActStats;
	XMLHTTP.send(null);
  }
  
  function ActStats()
  {
    if(XMLHTTP.readyState == 4)
	{
	  /*alert("DEBUG:"+XMLHTTP.responseText);*/
		
	  if(XMLHTTP.responseText == "true")
	  {
		hp = max_hp;
		newHP();
		
		new_gold = (gold-300);
		document.getElementById("gold").innerHTML = new_gold;
		
		alert("Dein Charakter wurde vollstaendig geheilt!");
	  }
	  else
	  {
	    alert("Dein Charakter muss nicht geheilt werden, er ist bereits fit fuer den naechsten Kampf!");
	  }
	}
  }
  

function chkAll()
{
  objForm = document.forms["checkform"];
  for(i=0;i<objForm.elements.length;i++)
  {
    if(objForm.elements[i].type == "checkbox")
    {
      if(objForm.elements[i].checked == false)
      {
        objForm.elements[i].checked = true;
      }
      else
      {
        objForm.elements[i].checked = false;
      }
    }
  }
}

function PopUp(URL,Width,Height,mode)
{
  if(mode == 1) // normal window
  {
	var Bars = 'resizable=1,location=1,directories=1,status=1,menubar=1,scrollbars=1,toolbar=1';
  }
  else // no bars window
  {
    var Bars = 'resizable=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,toolbar=0';
  }
  
  var NewWindow = open(URL,'PopUp','width='+Width+',height='+Height+','+Bars);
}

function goTo(Where)
{
  window.location.href = Where;
}

function changeColor(Color)
{
  this.style.backgroundColor = Color;
}
