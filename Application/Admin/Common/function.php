<?php
	
function islogin(){
	if (!isset($_SESSION['userid'])) {
		return false;
	}
	$uid=$_SESSION['userid'];
	return $uid;
}

?>
