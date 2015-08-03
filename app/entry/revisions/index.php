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

		// Resources - app
		importResource("taogi-app-entry-revisions");

		$this->ecard = Component::get("entry/ecard",array('entry'=>$this->entry));
		$this->revisionTable = Component::get('entry/revision/table',array('entry'=>$this->entry,'revisions'=>$this->revisions));
		$this->revisionsControls = Component::get('entry/revision/controls',array('entry'=>$this->entry));
	}
}
?>
