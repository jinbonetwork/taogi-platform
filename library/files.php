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

function getEntryExtraCssPath($eid){
	return getEntryAttachedPath($eid)."/style.css";
}

function getEntryExtraCssContent($eid){
	$file = getEntryExtraCssPath($eid);
	$content = '';
	if(file_exists($file)){
		$content = file_get_contents($file);
	}
	return $content;
}

function getEntryExtraCssURL($eid){
	$file = getEntryExtraCssPath($eid);
	$cache = '';
	$content = '';
	$url = '';
	if(file_exists($file)){
		require_once JFE_CONTRIBUTE_PATH.'/lessphp/lessc.inc.php';
		$less = new lessc; 
		$cache = str_replace('.css','.min.css',$file);
		if(!file_exists($cache)||filemtime($file)>filemtime($cache)){
			$source = file_get_contents($file);	
			$source = "html#taogi-net{{$source}}";
			$content = $less->compile($source);
			file_put_contents($cache,$content);
		}
		$url = getEntryAttachedURI($eid)."/style.min.css";
	}
	return $url;
}

function _file_put_contents($file,$content){
	$old = file_exists($file)?file_get_contents($file):'';
	$new = $content;

	if($new!=''&&$old!=$new){
		file_put_contents($file,$content);
		return true;
	}else{
		return false;
	}
}

function _file_get_contents($file){
	$content = '';
	if(file_exists($file)){
		$content = file_get_contents($file);
	}
	return $content;
}
?>
