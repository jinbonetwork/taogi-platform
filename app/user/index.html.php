<?php
	echo '<div id="user-dashboard_'.$user['uid'].'" class="app-user app-user-dashboard">'.PHP_EOL;
		echo '<div id="user-dashboard-vcard" class="vcard-container">'.PHP_EOL;
			echo $vcard;
			echo $tabs;
		echo '</div><!--/#user-dashboard-vcard-->'.PHP_EOL;
		echo '<div id="user-dashboard-updates" class="updates">'.PHP_EOL;
			echo "<h2 class=\"ui-block\">{$user['DISPLAY_NAME']}님이 최근에 편집한 타임라인 목록</h2>".PHP_EOL;
			echo getUserEntryGallery($user,$entries);
		echo '</div><!--/#user-dashboard-updates-->'.PHP_EOL;
	echo '</div><!--/#user-dashboard_uid-->'.PHP_EOL;
?>
