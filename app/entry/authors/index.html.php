<?php
	echo '<div id="entry-authors_'.$entry['eid'].'" class="entry-authors">'.PHP_EOL;
		echo getEntryEcard($entry);
		echo '<div id="entry-authors-list" class="block list">'.PHP_EOL;
		require_once JFE_PATH.'/app/entry/authors/index.html.common.php';
		echo '</div><!--/#entry-authors-list-->'.PHP_EOL;
	echo '</div><!--/#entry-authors_eid-->'.PHP_EOL;
?>
