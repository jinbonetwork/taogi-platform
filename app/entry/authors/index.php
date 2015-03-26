<?php
$Acl = "editor";
class authors_index extends Interface_Entry {
	public function index() {
		// Objects
		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		$this->authors = User::getUserProfiles(Entry::getEditors($this->params['taogiid']));

		// Page
		$this->title = $this->entry['subject']." -- 편집그룹 관리";
		$this->description = $this->entry['summary'];

		$this->base_uri = "http://".$context->getProperty('service.domain').base_uri();

		// Views
		require_once JFE_PATH.'/include/userEntryControls.php';
		require_once JFE_PATH.'/include/entryAuthorControls.php';

		require_once JFE_PATH.'/include/entryEcard.php';
		$this->css[] = 'ui-hcard.css';
		$this->script[] = 'ui-hcard.js';

		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		require_once JFE_PATH.'/include/entryAuthorTable.php';
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		require_once JFE_PATH.'/include/entryAuthorGallery.php';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';

		$this->css[] = 'ui-form.css';
		$this->script[] = 'wysiwyg_editor.js';

		// Resources - app
		$this->css[] = 'app-entry.css';
		$this->script[] = 'app-entry.js';
		$this->css[] = 'app-entry-authors.css';
		$this->script[] = 'app-entry-authors.js';
	}
}
?>
