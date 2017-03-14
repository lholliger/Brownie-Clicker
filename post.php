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
		file_put_contents("$dir/purch", "0;0;0;0;0;0;0;0;");
		setcookie("BR_UUID", $UUID, time() + (10 * 365 * 24 * 60 * 60));
		echo $UUID;
	}


}
?>
