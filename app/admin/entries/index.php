<?php
$Acl = 'administrator';
class entries_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getList(-1));

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];

		// Views
		// Resources - app
		importResource('taogi-app-admin-entries');

		$this->vcard = Component::get('user/vcard',array('user'=>$this->admin));
		$this->tabs = Component::get('admin/tabs',array('current'=>'entries'));
		$this->entryTable = Component::get('admin/entry/table',array('entries'=>$this->entries));
		$this->controls = Component::get('admin/entry/controls',array('admin'=>$this->admin));
	}
}
?>
