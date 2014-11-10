<?php
$Acl = "editor";
class revisions_index extends Controller {
	public function index() {
		$context = Model_Context::instance();

		// Page

		// Objects
		$uid = Acl::getIdentity('taogi');
		$this->user = User::getUser($uid,1);
		$this->entry = Entry::getEntryInfoByID($this->params['taogiid'],1);
		$this->revisionList = Entry_List::getRevisionList($this->entry['eid']);

		$this->title = $this->entry['subject'];
		$this->description = $this->entry['summary'];

		// Views
		$this->entryProfile = new Markup_Profile($this->entry);
		$this->revisionControls = new Markup_Controls('selectedEntryRevisions','revision','vid');
		$this->revisionTable = new Markup_Table('entryRevisions','revision','vid');
		$this->revisionSearchForm = new Markup_Form('entryRevisionSearch','revision','vid');

		// Resources
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';
		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';

		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		$this->css[] = 'app-entry.css';
		$this->script[] = 'app-entry.js';
		$this->css[] = 'app-entry-revisions.css';
		$this->script[] = 'app-entry-revisions.js';

	}
}
?>
