<?php
	
function islogin(){
	if (!isset($_SESSION['cuid'])) {
		return false;
	}
	$uid=$_SESSION['cuid'];
	return $uid;
}

?>
