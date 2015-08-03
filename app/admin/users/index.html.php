<?php
	echo '<div id="admin-users_'.$admin['uid'].'" class="app-admin app-admin-users">'.PHP_EOL;
		echo '<div id="admin-users-vcard" class="block vcard-container">'.PHP_EOL;
			echo $vcard;
			echo $tabs;
		echo '</div><!--/#admin-users-vcard-->'.PHP_EOL;
		echo '<div id="admin-users-archives" class="block archives">'.PHP_EOL;
			echo '<h2 class="ui-block">사용자 목록</h2>'.PHP_EOL;
			echo '<form id="adminUsers" action="/admin/users/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo $userEntries;
				echo $controls;
			echo '</form>'.PHP_EOL;
			//$entrySearchForm->printForm();
		echo '</div><!--/#admin-users-->'.PHP_EOL;
	echo '</div><!--/#admin-users_uid-->'.PHP_EOL;
?>
