<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


if ($_GET['ACT'] == "GET_UUID") {
	if(isset($_COOKIE['BR_UUID'])) {
		$UUID = $_COOKIE["BR_UUID"];
		setcookie("BR_UUID", $UUID, time() + (10 * 365 * 24 * 60 * 60));
		echo $UUID;
	} else {
		$UUID = microtime_float();
		mkdir("../data/$UUID");
		$dir = "../data/$UUID"; 
		file_put_contents("$dir/username", "player");
		file_put_contents("$dir/perclick", "1");
		file_put_contents("$dir/persecond", "0");
		file_put_contents("$dir/total");
		file_put_contents("$dir/purchace", "0;0;0;0;0;0;0;0;");
		setcookie("BR_UUID", $UUID, time() + (10 * 365 * 24 * 60 * 60));
		echo $UUID;
	}


}

if ($_GET['ACT'] == "GET_BROWNIE") {
	$UUID = microtime_float();
	echo file_get_contents("../data/$UUID/total");
}

if ($_GET['ACT'] == "POST_TOTAL") {
	$UUID = $_COOKIE['BR_UUID'];
	$dir = "../data/$UUID"; 
	$TOTAL = $_GET['TOTAL'];
	$pc = file_get_contents("$dir/perclick");
	$ps = file_get_contents("$dir/persecond");
	$cu = file_get_contents("$dir/total");
	if ($TOTAL > ($cu + ($pc * 15 * 5) + ($ps * 5))) {
		echo "DENY-TOO-FAST";
	} else {
		echo "PASS-VER-TEST";
	}
}
?>
