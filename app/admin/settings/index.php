<?php
$Acl = 'administrator';
class settings_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->settings = array();

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

		require_once JFE_PATH.'/include/adminSettingsForm.php';
		$this->css[] = 'ui-form.css';
		$this->script[] = 'ui-form.js';

		// Resources - app
		$this->css[] = 'app-admin.css';
		$this->script[] = 'app-admin.js';
		$this->css[] = 'app-admin-settings.css';
		$this->script[] = 'app-admin-settings.js';
	}
}
?>
