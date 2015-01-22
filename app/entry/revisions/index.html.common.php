<?php
	echo '<h2 class="ui-block">&lsquo;'.$entry['subject'].'&rsquo; 편집 이력</h2>'.PHP_EOL;
	echo '<form id="entryRevisions" class="" action="'.$entry['permalink'].'/revisions/" method="post">'.PHP_EOL;
		echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
		echo getEntryRevisionTable($entry,$revisions);
		echo getEntryRevisionsControls($entry);
	echo '</form>'.PHP_EOL;
	//$revisionSearchForm->printForm();
?>
