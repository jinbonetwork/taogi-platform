<?php
	echo '<div id="admin-entries_'.$admin['uid'].'" class="app-admin app-admin-entries">'.PHP_EOL;
		echo '<div id="admin-entries-vcard" class="block vcard-container">'.PHP_EOL;
			echo $vcard;
			echo $tabs;
		echo '</div><!--/#admin-entries-vcard-->'.PHP_EOL;
		echo '<div id="admin-entries" class="block archives">'.PHP_EOL;
			echo '<h2 class="ui-block">타임라인 목록</h2>'.PHP_EOL;
			echo '<form id="adminEntries" action="/admin/entries/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo $entryTable;
				echo $controls;
			echo '</form>'.PHP_EOL;
			//$entrySearchForm->printForm();
		echo '</div><!--/#admin-entries-->'.PHP_EOL;
	echo '</div><!--/#admin-entries_uid-->'.PHP_EOL;
?>
