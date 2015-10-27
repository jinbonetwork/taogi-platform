<?php
class archives_index extends Controller {
	public function index() {
		// Objects
		$this->user = User::getUserProfile($this->params['userid']);
		$entries = Entry::getEntryProfiles(Entry_List::getEditList($this->params['userid'],-1));
		$this->entries = array();
		if(is_array($entries)) {
			foreach($entries as $entry) {
				if($entry['is_public'] < 1) {
					if(!Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
						continue;
					}
				}
				$this->entries[] = $entry;
			}
		}

		// Page
		$this->title = $this->user['DISPLAY_NAME'];
		$this->description = $this->user['summary'];

		/* FancyBox */
		importResource("fancybox");

		// Views
		// Resources - app
		importResource("taogi-app-user-archives");

		$this->vcard = Component::get('user/vcard',array('user'=>$this->user));
		$this->tabs = Component::get('user/tabs',array('user'=>$this->user,'current'=>'archives'));

		$this->entryGallery = Component::get('user/entry/gallery',array('user'=>$this->user,'entries'=>$this->entries));
		$this->entryTable = Component::get('user/entry/table',array('user'=>$this->user,'entries'=>$this->entries));
		$this->controls = Component::get('user/entry/controls',array('user'=>$this->user));
	}
}
?>
