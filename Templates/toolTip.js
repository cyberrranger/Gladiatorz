/**
 * DHTML Tooltip script v1.1
 * (c) 2004 Mikko Mensonen
 * www.eternityproject.net
 *
 * It's a small miracle that it works, since
 * I scrambled it together in a small hurry.
 * But it's tested on IE6, FF1.0+, Netscape 7
 * and Opera 7.5 and they all worked.
 *
 * I'll do some testing and fixing on older
 * browsers some time later.
 */

function initTooltip(divId) {
	var tooltip = document.getElementById('tooltipMouseover');
	tooltip.style.display = '';
	tooltip.style.width = '350px';
	tooltip.innerHTML = document.getElementById( divId ).innerHTML;
	document.onmousemove=followMouse;
}

function hideTooltip() {
	var tooltip = document.getElementById('tooltipMouseover');
	document.onmousemove='';
	tooltip.style.display = 'none';
	
}

function followMouse(e){
	// offset from the cursor
	var offsetx = 20;
	var offsety = 20;
	var posx = 0;
	var posy = 0;
	var tooltip = document.getElementById('tooltipMouseover');
	if (!e) {
		var e = window.event;
	}
	if (e.pageX || e.pageY) {
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY) {
		posx = e.clientX + document.documentElement.scrollLeft;
		posy = e.clientY + document.documentElement.scrollTop;
	}
	if (window.innerWidth || window.innerHeight){ 
		docwidth = window.innerWidth; 
		docheight = window.innerHeight; 
	} else if (document.documentElement.clientHeight || document.documentElement.clientWidth) {
		docwidth = document.documentElement.clientWidth;
		docheight = document.documentElement.clientHeight;
	} else if (document.body.clientWidth || document.body.clientHeight){ 
		docwidth = document.body.clientWidth; 
		docheight = document.body.clientHeight; 
	}
	// if tooltip goes beyond visible area, move it higher by it's width
	if ((docheight - (posy + offsety + tooltip.offsetHeight)) < 0 ) {
		offsety *= -1;
		offsety -= (tooltip.offsetHeight+offsety);
	}
	// same for the width
	if ((docwidth - (posx + offsetx + tooltip.offsetWidth)) < 0 ) {
		offsetx *= -1;
		offsetx -= (tooltip.offsetWidth+offsetx);
	}
	
	tooltip.style.left = posx + offsetx + 'px';
	tooltip.style.top = posy + offsety + 'px';
	
}

// the tooltip div
// document.write('<div id="tooltipMouseover" class="tooltipMouseover"></div>');
// --> put into main bottom file