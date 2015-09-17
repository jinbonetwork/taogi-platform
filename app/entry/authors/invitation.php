<?php
class authors_invitation extends Interface_Entry {
	public function index() {
		$context = Model_Context::instance();
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);

		if(!$this->params['email_id']) {
			Error("본인의 E-Mail 주소를 입력하세요");
		}
		if(!$this->params['authtoken']) {
			Error("인증토큰을 입력하세요");
		}

		$author = User::search('email_id',$this->params['email']);
		if($author) {
			$priv = User_DBM::getPrivilege($author['uid'],$this->params['taogiid']);
			if($priv['uid']) {
				Error("이미 공동 편집인으로 등 록된 이용자의 E-Mail 주소입니다."));
			}
		}
	}
}
