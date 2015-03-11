<?php
class user_index extends Controller {
	public function index() {
		// Objects
		$this->user = User::getUserProfile($this->params['userid']);
		// force redirection to archives while alpha period
		header('location:'.$this->user['archives_link']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getOwnList($this->params['userid']));

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

		require_once JFE_PATH.'/include/userEntryGallery.php';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';

		// Resources - app
		$this->css[] = 'app-user.css';
		$this->script[] = 'app-user.js';
	}

}
?>
