<?php
class entry_gnb extends Controller {
	public function index() {
		$this->title = "타임라인 수정하기";
		$this->params['output'] = 'json';
		$context = Model_Context::instance();

		$this->view_mode ='edit';

		$uid = Acl::getIdentity('taogi');
		if($uid < 1) RespondJson::PrintResult(array('error'=>1,'message'=>"타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다."));

		if(!$this->params['taogiid']) RespondJson::PrintResult(array('error'=>1,'message'=>"수정할 타임라인을 지정하세요"));
		$this->eid = $this->params['taogiid'];

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->params['vid']) $this->entry['vid'] = $this->params['vid'];
		$this->vid = $this->entry['vid'];

		$data = Entry::getEntryData($this->eid,$this->vid);
		$this->timeline = json_decode($data['timeline'],true);
		$this->timeline = $this->timeline['timeline'];
		$this->extra = Entry::getEntryExtra($this->eid);
	}
}
