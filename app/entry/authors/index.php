<?php
$Acl = "owner";
class authors_index extends Interface_Entry {
	public function index() {
		$context = Model_Context::instance();

		// Page
		$this->title = "따오기 편집그룹 관리";

		// Objects
		$uid = Acl::getIdentity('taogi');
		$this->user = User::getUser($uid,1);
		$this->entry = Entry::getEntryInfoByID($this->params['taogiid'],1);
		foreach(User_DBM::getEditors($this->entry['eid']) as $author) {
			$this->authorList[] = array_merge($author,User::getUser($author['uid'],1));
		}

		// Views
		$this->entryProfile = new Markup_Profile($this->entry);
		$this->authorControls = new Markup_Controls('selectedEntryAuthors','user','uid');
		$this->authorTable = new Markup_Table('entryAuthors','user','uid');
		$this->authorGallery = new Markup_Gallery('entryAuthors','user','uid');
		$this->authorSearchForm = new Markup_Form('entryAuthorSearch','user','uid');

		// Resources
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';
		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-galelry.js';

		$this->css[] = 'app-entry.css';
		$this->script[] = 'app-entry.js';
		$this->css[] = 'app-entry-authors.css';
		$this->script[] = 'app-entry-authors.js';
	}
}
?>
