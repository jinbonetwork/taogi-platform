<?php
importLibrary('auth');

$IV = array(
	'GET' => array(
		'uid' => array('int', 'mandatory' => false ),
		'email_id' => array('email', 'mandatory' => false ),
		'authtoken' => array('string', 'default' => null ),
		'requestURI' => array('string', 'default' => null ),
	),
	'POST' => array(
		'uid' => array('int', 'default' => null ),
		'email_id' => array('email', 'default' => null),
		'password' => array('string', 'default' => null),
		'requestURI' => array('string', 'default' => null ),
	)
);

class login_authemail extends Controller {
	public function index() {
		if( empty($this->params['uid']) || empty($this->params['email_id']) || empty($this->params['authtoken']) ) {
			Error("정상적인 접근이 아닙니다.");
		}
		$changeToken = User_DBM::getChangeEmailToken($this->params['uid']);
		if(!$changeToken) {
			Error("로그인 이메일 아이디 변경 신청 내역이 없습니다.");
		}
		if( $changeToken['value']['email_id'] != $this->params['email_id'] ) {
			Error("신청하신 로그인 이메일 아이디 변경 내역과 일치하지 않습니다.");
		}
		if( $changeToken['value']['token'] != $this->params['authtoken'] ) {
			Error("신청하신 로그인 이메일 아이디 변경 요청 코드와 일치하지 않습니다.");
		}
		User_DBM::updateEmailID($this->params['uid'],$this->params['email_id'],$changeToken['value']['password']);
		$isLogin = Login($this->params['email_id'],$changeToken['value']['password'],'');
		switch($isLogin) {
			case 1:
				$message = "아직 첫 로그인을 위한 링크 연결이 완료되지 않았습니다. 가입 시에 입력하신 이메일주소로 로그인을 할 수 있는 링크를 보냈습니다. 이메일을 확인하시고 이메일 본문에서 첫 로그인을 위한 링크를 클릭해주세요.";
				break;
			case 0:
				break;
			case -1:
				$message = "존재하지 않는 E-Mail 아이디입니다.";
				break;
			case -2:
				$message = "비밀번호가 일치하지 않습니다.";
				break;
		}
		if($message) Error($message);

		$url = "/".$_SESSION['user']['taoginame']."/profile";
		RedirectURL($url);
	}
}
?>
