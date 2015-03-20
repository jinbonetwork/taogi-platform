<?php
$Acl = 'administrator';
class admin_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getRecentList(12));
		$this->users = User::getUserProfiles(User_List::getRecentList(12));

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];

		// Views
		require_once JFE_PATH.'/include/userVcard.php';
		$this->css[] = 'ui-hcard.css';
		$this->script[] = 'ui-hcard.js';

		require_once JFE_PATH.'/include/adminTabs.php';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		require_once JFE_PATH.'/include/adminEntryTable.php';
		require_once JFE_PATH.'/include/adminUserTable.php';
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		// Resources - app
		$this->css[] = 'app-admin.css';
		$this->script[] = 'app-admin.js';
	}

}
?>
