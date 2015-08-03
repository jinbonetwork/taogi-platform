<?php
class user_index extends Controller {
	public function index() {
		// Objects
		$this->user = User::getUserProfile($this->params['userid']);
		// force redirection to archives while alpha period
		header('location:'.$this->user['archives_link']);
		$this->entries = Entry::getEntryProfiles(Entry_List::getOwnList($this->params['userid']));

		// Page
		$this->title = $this->user['DISPLAY_NAME'];
		$this->description = $this->user['summary'];

		// Views
		/* FancyBox */
		importResource("fancybox");

		// Resources - app
		importResource("taogi-app-user");

		$this->vcard = Component::get('user/vcard',array('user'=>$this->user));
		$this->tabs = Component::get('user/tabs',array('user'=>$this->user,'current'=>'dashboard'));
	}
}
?>
