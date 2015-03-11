<?php
class archives_index extends Controller {
	public function index() {
		// Objects
		$this->user = User::getUserProfile($this->params['userid']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getOwnList($this->params['userid'],-1));

		// Page
		$this->title = $this->user['DISPLAY_NAME'];
		$this->description = $this->user['summary'];

		/* FancyBox */
		$this->js[] = 'fancyBox/source/jquery.fancybox.css';
		$this->js[] = 'fancyBox/source/jquery.fancybox.pack.js';

		// Views
		require_once JFE_PATH.'/include/userVcard.php';
		$this->css[] = 'ui-vcard.css';
		$this->script[] = 'ui-vcard.js';

		require_once JFE_PATH.'/include/userTabs.php';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		require_once JFE_PATH.'/include/userEntryControls.php';

		require_once JFE_PATH.'/include/userEntryGallery.php';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';

		require_once JFE_PATH.'/include/userEntryTable.php';
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		// Resources - app
		$this->css[] = 'app-user.css';
		$this->script[] = 'app-user.js';
		$this->css[] = 'app-user-archives.css';
		$this->script[] = 'app-user-archives.js';
	}


}
?>
