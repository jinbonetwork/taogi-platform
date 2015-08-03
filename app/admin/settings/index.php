<?php
$Acl = 'administrator';
class settings_index extends Controller {
	public function index() {
		// Objects
		$this->admin = User::getUserProfile($_SESSION['user']['uid']);
		$this->settings = array();

		// Page
		#$this->title = $this->user['DISPLAY_NAME'];
		#$this->description = $this->user['summary'];
		
		// Views

		// Resources - app
		importResource('taogi-app-admin-settings');

		$this->vcard = Component::get('user/vcard',array('user'=>$this->admin));
		$this->tabs = Component::get('admin/tabs',array('current'=>'settings'));
		$this->forms = Component::get('admin/settings/form',array());
	}
}
?>
