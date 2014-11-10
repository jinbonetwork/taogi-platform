<?php
class archives_index extends Controller {
	public function index() {

		// Objects
		$this->user = User::getUser($this->params['userid'],1);
		$this->entryList = Entry_List::getOwnList($this->params['userid'],-1);

		// Views
		$this->userProfile = new Markup_Profile($this->user);
		$this->entryControls = new Markup_Controls('selectedUserEntries','entry','eid');
		$this->entryTable = new Markup_Table('userEntries','entry','eid');
		$this->entryGallery = new Markup_Gallery('userEntries','entry','eid');
		$this->entrySearchForm = new Markup_Form('userEntrySearch','entry','eid');

		// Resources
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';
		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';

		$this->css[] = 'ui-profile.css';
		$this->script[] = 'ui-profile.js';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';
		$this->css[] = 'ui-table.css';
		$this->script[] = 'ui-table.js';

		$this->css[] = 'app-user.css';
		$this->script[] = 'app-user.js';
		$this->css[] = 'app-user-archives.css';
		$this->script[] = 'app-user-archives.js';

	}


}
?>
