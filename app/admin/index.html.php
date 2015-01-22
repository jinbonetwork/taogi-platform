<?php
	echo '<div id="admin-dashboard_'.$admin['uid'].'" class="app-admin app-admin-dashboard">'.PHP_EOL;
		echo '<div id="admin-dashboard-vcard" class="vcard-container">'.PHP_EOL;
			echo getUserVcard($admin);
			echo getAdminTabs('dashboard');
		echo '</div><!--/#admin-dashboard-vcard-->'.PHP_EOL;
		echo '<div id="admin-dashboard-updates" class="updates">'.PHP_EOL;
			echo '<p>Under Construction...</p>'.PHP_EOL;
		echo '</div><!--/#admin-dashboard-updates-->'.PHP_EOL;
	echo '</div><!--/#admin-dashboard_uid-->'.PHP_EOL;
?>
