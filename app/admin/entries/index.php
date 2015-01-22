<?php
$Acl = 'administrator';
class entries_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getList(-1));

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];

		// Views
		require_once JFE_PATH.'/include/userVcard.php';
		$this->css[] = 'ui-vcard.css';
		$this->script[] = 'ui-vcard.js';

		require_once JFE_PATH.'/include/adminTabs.php';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		require_once JFE_PATH.'/include/adminEntryControls.php';

		require_once JFE_PATH.'/include/adminEntryTable.php';
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		// Resources - app
		$this->css[] = 'app-admin.css';
		$this->script[] = 'app-admin.js';
		$this->css[] = 'app-admin-entries.css';
		$this->script[] = 'app-admin-entries.js';
	}


}
?>
