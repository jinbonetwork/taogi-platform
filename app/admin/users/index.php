<?php
$Acl = 'administrator';
class users_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->users = User::getUserProfiles(User_List::getList(-1));

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];

		// Views

		// Resources - app
		importResource('taogi-app-admin-users');

		$this->vcard = Component::get('user/vcard',array('user'=>$this->admin));
		$this->tabs = Component::get('admin/tabs',array('current'=>'users'));
		$this->userEntries = Component::get('admin/user/table',array('users'=>$this->users));
		$this->controls = Component::get('admin/user/controls',array('admin'=>$this->admin));
	}
}
?>
