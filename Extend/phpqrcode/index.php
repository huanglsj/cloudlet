<?php    
include "qrlib.php";

if (!isset($_GET['d'])) {
	echo "error";
	return;
}

$data = $_GET['d'];

if (isset($_GET['l'])) {
	$level = $_GET['l'];
}
else {
	$level = 4;
}

if (isset($_GET['s'])) {
	$size = $_GET['s'];
}
else {
	$size = 4;
}

if (isset($_GET['m'])) {
	$margin = $_GET['m'];
}
else {
	$margin = 4;
}
QRcode::png($data, false, $level, $size, $margin);

