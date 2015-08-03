<?php
$Acl = "anonymous";
class front_index extends Controller {
	public function index() {
					        
		// Objects
		$context = Model_Context::instance();
		$this->entries = Entry::getEntryProfiles(Entry_List::getRecentList());

		// Resources - general

		// Resources - view

		// Resources - app
		importResource("taogi-app-front");
	}
}
?>
