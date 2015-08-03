<?php
$Acl = "editor";
class revisions_index extends Controller {
	public function index() {
		// Objects
		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		$this->revisions = Entry::getEntryProfiles(Entry_List::getRevisionList($this->entry['eid']));

		// Page
		$this->title = $this->entry['subject'];
		$this->description = $this->entry['summary'];

		// Views
		require_once JFE_PATH.'/include/userEntryControls.php';
		require_once JFE_PATH.'/include/entryRevisionControls.php';

		require_once JFE_PATH.'/include/entryEcard.php';
		$this->css[] = 'ui-hcard.css';
		$this->script[] = 'ui-hcard.js';

		require_once JFE_PATH.'/include/entryRevisionTable.php'; 
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		// Resources - app
		$this->css[] = 'app-entry.css';
		$this->script[] = 'app-entry.js';
		$this->css[] = 'app-entry-revisions.css';
		$this->script[] = 'app-entry-revisions.js';

	}
}
?>
