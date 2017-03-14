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

}
