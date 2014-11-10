<?php
class profile_index extends Controller {
	public function index() {

		// Objects
		$this->user = User::getUser($this->params['userid'],1);

		// Views
		$this->userProfile = new Markup_Profile($this->user);
		foreach($this->user as $key => $value) {
			if(isset($this->userProfile->userForm->options['userForm'][$key])) {
				$this->userProfile->userForm->options['userForm'][$key]['value'] = $value;
			}
		}

		// Resources
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';
		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';

		$this->css[] = 'ui-profile.css';
		$this->script[] = 'ui-profile.js';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		$this->css[] = 'app-user.css';
		$this->script[] = 'app-user.js';
		$this->css[] = 'app-user-profile.css';
		$this->script[] = 'app-user-profile.js';

	}
}
?>
