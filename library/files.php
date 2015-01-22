<?php
function getEntryAttachedPath($eid) {
	return JFE_DATA_PATH."/attach/entry/".($eid% 16)."/".$eid;
}

function getEntryAttachedTmpPath() {
	return JFE_DATA_PATH."/attach/entry/tmp/".$_COOKIE[Session::getName()];
}

function getEntryAttachedURI($eid) {
	return JFE_DATA_URI."/attach/entry/".($eid% 16)."/".$eid;
}

function getEntryAttachedTmpURI() {
	return JFE_DATA_URI."/attach/entry/tmp/".$_COOKIE[Session::getName()];
}

function delTree($dir) { 
	$fp = fopen("/tmp/taogi_log.txt","a+");
	fputs($fp,"delTree ".$dir."\n");
	fclose($fp);
	if(!isset($dir) || !$dir) return false;
	$files = array_diff(scandir($dir), array('.','..')); 
	foreach ($files as $file) { 
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	} 
	return rmdir($dir); 
}
?>
