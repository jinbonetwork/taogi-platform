<?php
$Acl = "anonymous";
class front_index extends Controller {
	public function index() {
					        
		// Objects
		$context = Model_Context::instance();
		$this->entries = Entry::getEntryProfiles(Entry_List::getRecentList());

		// Resources - general
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';

		// Resources - view
		require_once JFE_PATH.'/include/recentEntryGallery.php';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';

		// Resources - app
		$this->css[] = 'app-front.css';
		$this->script[] = 'app-front.js';

	}
}
?>
