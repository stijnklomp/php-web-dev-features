<?php
// Create rand ID
function createGUID(string $divideEntity = null) {
	$m = microtime(true);
	return sprintf('%X%X'.$divideEntity.'%d', floor($m), (($m - floor($m)) * 1000000), rand(100000000, 999999999));
}

// Retreive date
function getGUIDDate($GUID = null, $format = 'l jS \of F Y h:i:s A') {
	if(empty($GUID)) {
		$GUID = sprintf('%X%X', floor(microtime(true)), (microtime(true) - floor(microtime(true))) * 1000000);
	}
	return date($format, hexdec(substr($GUID, 0, 8)));
}
?>