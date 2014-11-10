<?php
$Acl = "editor";
class editor_resign extends Interface_Entry {
	public function index() {
		$this->title = "따오기 편집그룹 탈퇴";
		$this->getEntryInfo();
		$this->uid = Acl::getIdentity('taogi');
		if(!$this->uid) Error("로그인한 사용자만 접근가능합니다",404);
		$this->myinfo = User::getUser($this->uid,1);
		if(!$this->myinfo) Error("이용자님의 정보를 검색할 수 없습니다.");
		$this->privilege = User_DBM::getPrivilege($this->uid,$this->entry['eid']);

		if(!$this->privilege) {
			if(!$this->myinfo) Error($this->myinfo['username']."님의 이 타임라인에 대한 접근권한 정보를 검색할 수 없습니다.");
		}
		if($this->privilege['level'] == BITWISE_OWNER) Error("운영자는 타임라인 편집진에서 탈퇴할 수 없습니다.");
		$revision = Entry::getEntryData($this->entry['eid'],$this->entry['vid']);
		$this->timeline = json_decode($revision['timeline'],true);
		$this->timeline = $this->timeline['timeline'];
		if($this->params['act'] == 'proc') {
			User_DBM::deletePrivilege($this->uid,$this->entry['eid']);
			unset($_SESSION['acl']['taogi.'.$this->entry['eid']]);
		}
	}
}
?>
