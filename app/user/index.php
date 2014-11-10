<?php
class user_index extends Controller {
	public function index() {

		// Objects
		$this->user = User::getUser($this->params['userid'],1);
		$this->entryList = Entry_List::getOwnList($this->params['userid']);

		// Views
		$this->userProfile = new Markup_Profile($this->user);
		$this->entryGallery = new Markup_Gallery('entries','entry','eid');
		unset($this->entryGallery->headers['item_controls']);
		unset($this->entryGallery->options['checkbox_field']);
		unset($this->entryGallery->options['checkbox_append_field']);
		unset($this->entryGallery->options['controls_field']);
		unset($this->entryGallery->options['controls_switch_field']);

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

		$this->css[] = 'app-user.css';
		$this->script[] = 'app-user.js';


	}


}
?>
