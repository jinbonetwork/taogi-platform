<?php
import('library.validateJS');
import('library.getJson');
$Acl= 'editor';
class entry_status extends Controller {
	public function index() {
		$this->contentType = 'json';
		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');
		if($uid < 1) RespondJson::ResultPage(array(-1,"타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다."));

		if(!$this->params['taogiid']) RespondJson::ResultPage(array(-3,"수정할 타임라인을 지정하세요"));
		$this->eid = $this->params['taogiid'];
		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->params['vid']) {
			$this->vid = $this->params['vid'];
		} else {
			$this->vid = $this->entry['vid'];
		}

		if(isset($this->params['status'])) {
			Entry_DBM::updateStatus($this->eid,$this->params['status']);

			$json_path = getJsonPath($this->eid);
			if(!$this->params['status'] && file_exists($json_path)) {
				unlink($json_path);
			} else {
				$data = Entry::getEntryData($this->eid,$this->entry['vid']);
				$rtimeline = publishedTimeLineJs(json_decode($data['timeline'],true));
				$fp = fopen($json_path,"w");
				fputs($fp,json_encode($rtimeline));
				fclose($fp);
			}
		}
		if(isset($this->params['forkable'])) {
			Entry_DBM::forkable($this->eid,$this->params['forkable']);
		}
		if(isset($this->params['lock'])) {
			if($this->params['lock'] == 'on') {
				Entry_DBM::setLock($this->eid);
			} else if($this->params['lock'] == 'off') {
				Entry_DBM::unLock($this->eid);
			}
		}
		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		$dbm = DBM::instance();
		$dbm->commit();
	}
}
?>
