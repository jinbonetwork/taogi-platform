<?php
$IV = array(
	'POST' => array(
		'email_id' => array('email', 'default' => null),
		'taoginame' => array('string', 'default' => null),
		'permalink' => array('string', 'default' => null)
	)
);
class common_duplicate extends Controller {
	public function index() {
		$this->contentType = 'json';

		$uid = Acl::getIdentity('taogi');

		if(!$this->params['permalink'] && !$this->params['taoginame'] && !$this->params['email_id']) RespondJson::ResultPage(array(-1,"검색할 E-Mail주소 또는 타임라인 주소나 따오기 고유주소를 입력하세요"));

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
			if($_user['uid'] && $_user['uid'] != $uid) {
				RespondJson::ResultPage(array(-3,"이미 다른 분이 사용 중인 따오기 고유주소입니다. 다른 주소를 입력해주세요."));
			}
			$this->message = "사용가능한 따오기 고유 주소입니다.";
		} else if($this->params['email_id']) {
			$_user = User::getUserByEmail($this->params['email_id']);
			if($_user['uid']) {
				if(!$_user['taoginame']) {
					RespondJson::ResultPage(array(-1,"소셜펀치(SocialFunch)에 등록된 이메일 주소입니다. 따오기는 소셜펀치와 로그인 정보를 공유합니다. ".$this->params['email_id']." 이메일 주소로 바로 로그인하세요."));
				} else if($_user['uid'] != $uid) {
					RespondJson::ResultPage(array(-1,"이미 등록된 이메일 주소입니다. 다른 이메일 주소를 입력해주세요."));
				} else {
					$this->message = "현재 사용중이신 이메일 주소입니다.";
				}
			} else {
				$this->message = "사용가능한 이메일 주소입니다.";
			}
		}
	}
}
?>
