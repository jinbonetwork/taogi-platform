<?php
	echo '<div id="entry-revisions_'.$entry['eid'].'" class="entry-revisions" data-eid="'.$entry['eid'].'" data-vid="'.$entry['vid'].'">'.PHP_EOL;
		echo $ecard;
		echo '<div id="entry-revisions-list" class="block list">'.PHP_EOL;
		require_once JFE_PATH.'/app/entry/revisions/index.html.common.php';
		echo '</div><!--/#user-archives-archives-->'.PHP_EOL;
	echo '</div><!--/#user-archives_uid-->'.PHP_EOL;
?>
