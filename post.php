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
		file_put_contents("$dir/sync",  "" . microtime_float());
		mkdir("$dir/purchace");
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
		if ((file_get_contents("$dir/sync") + 4) > microtime_float()) {
			echo "DENY-FAST-SYNC";
		} else {
			echo "PASS-VER-TEST";
			file_put_contents("$dir/total", $TOTAL);
		}
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
		array_push($u, file_get_contents("../data/$file/username"));
		array_push($s, file_get_contents("../data/$file/total"));
		array_push($sb, file_get_contents("../data/$file/total"));
	}
	echo "<br>";
	arsort($s, SORT_NUMERIC);
	$s2 =array_slice($s,0,5);
	$tt = array();
	$i = 0;
	foreach($s2 as $p) {
		foreach($sb as $n) {
		if ($p == $n) {
			array_push($tt, array($u[$i], $sb[$i]));
			unset($u[$i]);
			unset($sb[$i]);
		} else {
		}
		$i = $i + 1;
		}
		$i = 0;
	}
	echo "<table>";
	echo "<tr><td><b>Userame</b></td><td><b>Score</b></td></tr>";
	foreach($tt as $b) {
		echo "<tr><td>" . $b[0] . "</td><td>" . $b[1] . "</td></tr>";
	}
	echo "</table>";
}


if ($_GET['ACT'] == "BUY_ITEM") {
	$item = $_GET['ITID'];
	$UUID = $_COOKIE['BR_UUID'];
	$alld = array("1", "2","3", "4","5", "6","7", "8"); //This is to keep you haxors from making items that just take up my storage/get past my files
	$dir = "../data/$UUID";
	if (in_array($item, $alld)) {
		echo "PUR_ACC";
		if (file_exists("$dir/purchace/$item")) {

			$ao = file_get_contents("$dir/purchace/$item");

		} else {

			file_put_contents("$dir/purchace/$item", "0");
			$ao = 0;

		}
		$bal = file_get_contents("$dir/total");

		if ($ao == 0) {
			$cost = file_get_contents("costs/$item");
			if ($bal >= $cost) {
				echo "BAL_ACC";
				file_put_contents("$dir/total", $bal - $cost);
				file_put_contents("$dir/purchace/$item", $ao + 1);
				$itdata = file_get_contents("change/$item");

				if ($itdata[0] == "s") {
					$itdata = str_replace("s", "", $itdata);
					file_put_contents("$dir/persecond", file_get_contents("$dir/persecond") + $itdata);
				}

				if ($itdata[0] == "c") {
					$itdata = str_replace("c", "", $itdata);
					file_put_contents("$dir/persecond", file_get_contents("$dir/persecond") + $itdata);
				}
				
			} else {
				echo "BAL_DEN";
			}
		} else {
			$cost = file_get_contents("costs/$item") * file_get_contents("multiply/$item") * (file_get_contents("$dir/purchace/$item"));
			echo $cost;
			if ($bal >= $cost) {
				echo "BAL_ACC";
				file_put_contents("$dir/total", $bal - $cost);
				file_put_contents("$dir/purchace/$item", $ao + 1);
				$itdata = file_get_contents("change/$item");



				if ($itdata[0] == "s") {
					$itdata = str_replace("s", "", $itdata);
					file_put_contents("$dir/persecond", file_get_contents("$dir/persecond") + $itdata);
				}

				if ($itdata[0] == "c") {
					$itdata = str_replace("c", "", $itdata);
					file_put_contents("$dir/persecond", file_get_contents("$dir/persecond") + $itdata);
				}
			} else {
				echo "BAL_DEN";
			}
		}

	} else {
		echo "PUR_DEN";
	}
}
if ($_GET['ACT'] == "SET_USER") {
	$USR = $_COOKIE['BR_UUID'];
	$string = $_GET['UN'];
	$string=preg_replace('/[[:^print:]]/','',$string);
	if (strlen($string) <= 20) { // http://stackoverflow.com/questions/11434091/add-if-string-is-too-long-php
		file_put_contents("../data/$USR/username", $string);
	} else {
		file_put_contents("../data/$USR/username", substr($string, 0, 20));
	}
}

?>
