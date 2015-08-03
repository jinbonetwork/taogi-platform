<?php
class authors_index extends Interface_Entry {
	public function index() {
		// Objects
		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		$this->extra = Entry::getEntryExtra($this->entry['eid']);
		$this->authors = User::getUserProfiles(Entry::getEditors($this->params['taogiid']));

		if(!$this->entry['is_public']) {
			if(!Acl::checkAcl($this->entry['eid'],BITWISE_EDITOR)) {
				if(!$this->user) {
					importLibrary('auth');
					requireMembership();
				}
				else Error('접근 권한이 없습니다',403);
			}
		}
		if($this->extra['privateAuthorInfo'] >= 1 && !$this->user['uid'])
			$this->isPriviate = true;
		else if($this->extra['privateAuthorInfo'] == 2 && !Acl::checkAcl($this->entry['eid'],BITWISE_EDITOR))
			$this->isPriviate = true;

		// Page
		$this->title = $this->entry['subject']." -- 편집그룹 관리";
		$this->description = $this->entry['summary'];

		$this->base_uri = "http://".$context->getProperty('service.domain').base_uri();

		// Views

		// Resources - app
		if(Acl::checkAcl($this->entry['eid'],BITWISE_OWNER)) {
			$this->inviters = Entry_Invite::getList($this->entry['eid']);
			importResource('taogi-tinymce');
			importResource('jquery.actual');
			$this->isOwner = true;
		} else {
			$this->owner = User::getUserProfile($this->entry['owner']);
		}
		// Resources - app
		importResource('taogi-app-entry-authors');

		$this->ecard = Component::get("entry/ecard",array('entry'=>$this->entry));
		$this->authorTable = Component::get("entry/author/table",array('entry'=>$this->entry,'users'=>$this->authors));
		$this->authorGallery = Component::get("entry/author/gallery",array('entry'=>$this->entry,'users'=>$this->authors));
		$this->authorsControls = Component::get('entry/author/controls',array('entry'=>$this->entry));
		if($this->isOwner) {
			$this->inviteTable = Component::get("entry/invite/table",array('entry'=>$this->entry,'inviters'=>$this->inviters));
			$this->inviteControls = Component::get('entry/invite/controls',array('entry'=>$this->entry));
			$this->authorSettings = Component::get('entry/author/settings',array('entry'=>$this->entry,'extra'=>$this->extra));
		}
	}
}
?>
