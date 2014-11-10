<?php
$Acl = "anonymous";
class front_index extends Controller {
	public function index() {
		$context = Model_Context::instance();
					        
		// Objects
		$this->entryList = Entry_List::getRecentList();

		// Views
		$this->entryGallery = new Markup_Gallery('recentEntries','entry','eid');
		unset($this->entryGallery->headers['item_controls']);
		unset($this->entryGallery->options['checkbox_field']);
		unset($this->entryGallery->options['checkbox_append_field']);
		unset($this->entryGallery->options['controls_field']);
		unset($this->entryGallery->options['controls_switch_field']);

		// Resources
		$this->css[] = 'ui-init.css';
		$this->script[] = 'ui-init.js';
		$this->css[] = 'ui-gallery.css';
		$this->script[] = 'ui-gallery.js';
		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';

	}
}
?>
