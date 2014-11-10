<?php
class common_duplicate extends Controller {
	public function index() {
		$this->contentType = 'json';

		$uid = Acl::getIdentity('taogi');

		if(!$this->params['permalink'] && !$this->params['taoginame']) RespondJson::ResultPage(array(-1,"검색할 타임라인 주소나 따오기 고유주소를 입력하세요"));

		if($this->params['permalink']) {
			if($uid < 1) RespondJson::ResultPage(array(-1,"회원만 사용할 수 있는 API 입니다."));
			if(!preg_match("/^[0-9a-zA-Z\-\._]+$/i",$this->params['permalink'])) {
				RespondJson::ResultPage(array(-4,"타임라인 주소는 알파벳과 숫자 그리고 .-_ 만 허용합니다."));
			}

			$entry = Entry::searchByNickname($uid,$this->params['permalink']);
			if($this->params['eid']) {
				if($this->params['eid'] != $entry['eid']) RespondJson::ResultPage(array(-2,"이미 존재하는 타임라인 주소입니다."));
			} else {
				if($entry) RespondJson::ResultPage(array(-2,"이미 존재하는 타임라인 주소입니다."));
			}
			$this->message = "사용가능한 타임라인 주소입니다.";
		} else if($this->params['taoginame']) {
			if(!preg_match("/^[0-9a-zA-Z\-\._]+$/i",$this->params['taoginame'])) {
				RespondJson::ResultPage(array(-4,"따오기 고유 주소는 알파벳과 숫자 그리고 .-_ 만 허용합니다."));
			}

			$_user = User::getUserByNickname($this->params['taoginame']);
			if($_user['uid']) {
				RespondJson::ResultPage(array(-3,"이미 다른 분이 사용 중인 따오기 고유주소입니다. 다른 주소를 입력해주세요."));
			}
			$this->message = "사용가능한 따오기 고유 주소입니다.";
		}
	}
}
?>
