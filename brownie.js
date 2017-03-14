var total = 0;	// total brownies
var pc = 1;	// per click
var ps = 0;	// per second

var ct = 0;	// server cache
var cm = 5;	// check with server

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}

var uuid = httpGet("post.php?ACT=GET_UUID"); // get player id if not, make account


function init() {
	pc = parseInt(httpGet("post.php?ACT=GET_PC"));
	ps = parseInt(httpGet("post.php?ACT=GET_PS"));
	total = parseInt(httpGet("post.php?ACT=GET_BROWNIE"));
	update();
}

function update() {
	if (total == 0 || total > 0) {
	document.getElementById("brownies").innerHTML = total + " brownies";
	} else {
	document.getElementById("brownies").innerHTML = total + " brownie";
	}

	var id0 = " brownies";
	var id1 = " brownies";
	if (pc == 0 || pc > 0) {
	id0 = " brownie";
}

	if (ps == 0 || ps > 0) {
	id1 = " brownie";
}
	document.getElementById("info").innerHTML = pc + id0 + " per click | " + ps + id1 +" per second";
}


function addpt() {
	total = total + pc;
	update();
}

