<?php
$Acl = "owner";
class authors_settings extends Interface_Entry {
	public function index() {
		$this->contentType = 'json';

		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		$this->extra = Entry::getEntryExtra($this->entry['eid']);


		$extrafield = array('privateAuthorInfo');
		$update = false;
		foreach($extrafield as $f) {
			if(isset($this->params[$f])) {
				if($this->extra[$f]) {
					$update = true;
					Entry_DBM::updateExtra($this->entry['eid'],$f,$this->params[$f]);
				} else {
					$update = true;
					Entry_DBM::addExtra($this->entry['eid'],$f,$this->params[$f]);
				}
			}
		}
		if($update == true) {
			$dbm = DBM::instance();
			$dbm->commit();
		}

		$this->result = array('error'=>0,'message'=>'저장되었습니다.');
		header('Content-type:application/json; charset=utf-8');
		echo json_encode($this->result);
		exit;
	}
}
