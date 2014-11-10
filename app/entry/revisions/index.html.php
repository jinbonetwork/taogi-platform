<?php
	echo '<div id="entry-revisions_'.$entry['eid'].'" class="entry-revisions" data-eid="'.$entry['eid'].'" data-vid="'.$entry['vid'].'">'.PHP_EOL;
		echo '<div id="entry-revisions-ecard" class="block ecard">'.PHP_EOL;
			echo "<h2>{$entry['subject']}</h2>".PHP_EOL;
			echo '<div>'.$entry['summary'].'</div>'.PHP_EOL;
		echo '</div><!--/#entry-revisions-ecard-->'.PHP_EOL;
		echo '<div id="entry-revisions-list" class="block list">'.PHP_EOL;
			echo '<h2>'.$entry['subject'].' 편집 이력</h2>'.PHP_EOL;
			echo '<form id="entryRevisions" class="" action="'.$revisionControls->getPermalink($entry['owner'],$entry['eid']).'/revisions/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo $revisionTable->printTable($revisionList);
				$revisionControls->printControls();
			echo '</form>'.PHP_EOL;
			//$revisionSearchForm->printForm();
		echo '</div><!--/#user-archives-archives-->'.PHP_EOL;
	echo '</div><!--/#user-archives_uid-->'.PHP_EOL;
?>
