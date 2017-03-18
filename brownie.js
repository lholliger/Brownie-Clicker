var total = 0;	// total brownies
var pc = 1;	// per click
var ps = 0;	// per second

var sync = 0;	// server cache
var cm = 5;	// check with server

var ccps = 0;	// current clicks per second
var acps = 15;	// allowed cps

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
	if (ccps > acps) {
		document.getElementById("server").innerHTML = "<font color='red'><b>Oops:</b> You are only allowed to click 15 times per second!</font>";
	} else {
	ccps++;
	total = total + pc;
	update();
	}
}

function second() {
	sync++;
	if (sync >= 5) {
		five();
		sync = 0;
	}

	total = total + ps;
	update();
	ccps = 0;
}
function five() {
	var returninfo = httpGet("post.php?ACT=POST_TOTAL&TOTAL=" + total);
	if (returninfo == "DENY-TOO-FAST") {
		document.getElementById("server").innerHTML = "<font color='red'><b>Error:</b> the server rejected your sync due to adding to many points too fast. reload your page now</font>";
		total = httpGet("post.php?ACT=GET_BROWNIE");
		update();
	}

	if (returninfo == "DENY-ACT-DECT") {
		document.getElementById("server").innerHTML = "<font color='red'><b>Error:</b> the server rejected your sync due to possible autoclicking. Stop clicking, you can be tempbanned by the server</font>";
		total = httpGet("post.php?ACT=GET_BROWNIE");
		update();
	}
	if (returninfo == "DENY-FAST-SYNC") {
		document.getElementById("server").innerHTML = "<font color='red'><b>Error:</b> the server rejected your sync due to syncing too fast</font>";
		total = httpGet("post.php?ACT=GET_BROWNIE");
		update();
	}

	if (returninfo == "PASS-VER-TEST") {
		document.getElementById("server").innerHTML = "<font color='green'>points saved to server</font>";
	}
	if (document.getElementById("highscores").style.display == "block") {
	var hs = httpGet("post.php?ACT=GET_HS");
		document.getElementById("highscores").innerHTML = hs;
	}
	
}

function show_hs() {

if (document.getElementById("highscores").style.display != "block") {
document.getElementById("highscores").style.display = "block";
var hs = httpGet("post.php?ACT=GET_HS");
document.getElementById("highscores").innerHTML = hs;

} else {
document.getElementById("highscores").style.display = "none";
}
}


setInterval(second,1000);
