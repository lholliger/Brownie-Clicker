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
		file_put_contents("$dir/total", "0");
		file_put_contents("$dir/purchace", "0;0;0;0;0;0;0;0;");
		setcookie("BR_UUID", $UUID, time() + (10 * 365 * 24 * 60 * 60));
		echo $UUID;
	}


}

if ($_GET['ACT'] == "GET_BROWNIE") {
	$UUID = $_COOKIE['BR_UUID'];
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
		file_put_contents("$dir/total", $TOTAL);
	}
}

if ($_GET['ACT'] == "GET_PC") {
	$UUID = $_COOKIE['BR_UUID'];
	echo file_get_contents("../data/$UUID/perclick");
}


if ($_GET['ACT'] == "GET_PS") {
	$UUID = $_COOKIE['BR_UUID'];
	echo file_get_contents("../data/$UUID/persecond");
}


if ($_GET['ACT'] == "GET_HS") {
	$files = array_diff(scandir("../data"), array('..', '.'));
	$u = array();
	$s = array();
	$sb= array();
	foreach($files as $file) {
		echo $file  . "<br>" . file_get_contents("../data/$file/total");
		$u = array_push($u, $file);
		$s = array_push($s, file_get_contents("../data/$file/total"));
		$sb= array_push($sb, file_get_contents("../data/$file/total"));
	}
	print_r($s);
	$s = arsort($s, SORT_NUMERIC);
	print_r($s);
	$s = array_slice($s,0,5);
	$tt = array();
	$i = 0;
	foreach($s as $p) {
		if ($p == $sb[$i]) {
			$tt = array_push($tt, array($u[$i], $sb[$i]));
		} else {
		}
		$i = $i + 1;
	}
	print_r($tt);
}
?>
