<?php
	echo '<div id="user-dashboard_'.$user['uid'].'" class="user-dashboard">'.PHP_EOL;
		echo '<div id="user-dashboard-vcard" class="block vcard-container">'.PHP_EOL;
			$userProfile->printVcard();
			$userProfile->printUserTabs(array(),'dashboard');
		echo '</div><!--/#user-dashboard-vcard-->'.PHP_EOL;
		echo '<div id="user-dashboard-updates" class="block updates">'.PHP_EOL;
			echo "<h2>{$userProfile->user['DISPLAY_NAME']}님이 최근에 편집한 타임라인 목록</h2>".PHP_EOL;
			$entryGallery->printGallery($entryList);
		echo '</div><!--/#user-dashboard-updates-->'.PHP_EOL;
	echo '</div><!--/#user-dashboard_uid-->'.PHP_EOL;
?>
