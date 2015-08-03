<?php
$Acl = 'administrator';
class admin_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getRecentList(12));
		$this->users = User::getUserProfiles(User_List::getRecentList(12));

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];

		// Views

		// Resources - app
		importResource('taogi-app-admin');

		$this->vcard = Component::get('user/vcard',array('user'=>$this->admin));
		$this->tabs = Component::get('admin/tabs',array('current'=>'dashboard'));
	}
}
?>
