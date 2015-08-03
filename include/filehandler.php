<?php
importLibrary('file');

function filehandler($input,$s='') {
	$path = strtok($input, '/');
	$part = ltrim(rtrim($input), '/');
	$part = (($qpos = strpos($part, '?')) !== false) ? substr($part, 0, $qpos) : $part;
	if(!$s && file_exists($part)) {
		dumpWithEtag($part);
		exit;
	} else {
		if($path == 'files') {
			$part = rawurldecode($part);
			if($s) {
				$i_indexes = Image::getImageIndexes($part);
				$part = $i_indexes[$s]['filepath'];
			}
			if(file_exists($part)) {
				dumpWithEtag($part);
				exit;
			}
			if(Image::checkImage(JFE_PATH."/".$part)) {
				dumpWithEtag($part);
				exit;
			}
			header("HTTP/1.0 404 Not Found");
			exit;
		} else {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	exit;
}

filehandler($uri['input'],$_GET['s']);
?>
