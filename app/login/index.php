<?php
importLibrary('auth');

$IV = array(
	'GET' => array(
		'email_id' => array('string', 'mandatory' => false ),
		'authtoken' => array('string', 'default' => null ),
		'requestURI' => array('string', 'default' => null ),
	),
	'POST' => array(
		'email_id' => array('string', 'default' => null),
		'password' => array('string', 'default' => null),
		'requestURI' => array('string', 'default' => null ),
	)
);

class login_index extends Controller {
	public function index() {
		global $facebook;
		$this->facebook = $facebook;

		$context = Model_Context::instance();
		$redirect_uri = "https://".$context->getProperty('service.domain').base_uri().'login/fb';
		if($this->params['request_URI'])
			$redirect_uri .= "?requestURI=".$this->params['request_URI'];
		$this->fb_login_url = $facebook->getLoginUrl(array('redirect_uri'=>$redirect_uri));

		if($this->params['output'] != "xml") {
			importResource("taogi-app-login");
		}
		if(doesHaveMembership()) {
			if($this->params['output'] == "xml") {
				Respond::ResultPage(array(2,"이미 로그인하셨습니다"));
			} else {
				Error("이미 로그인하셨습니다.");
			}
		}
		if( !empty($this->params['email_id']) &&
			( !empty($_POST['password']) || !empty($this->params['authtoken']) ) ) {
			$isLogin = Login($this->params['email_id'],$_POST['password'],$this->params['authtoken']);
			switch($isLogin) {
				case 1:
					$message = "아직 첫 로그인을 위한 링크 연결이 완료되지 않았습니다. 가입 시에 입력하신 이메일주소로 로그인을 할 수 있는 링크를 보냈습니다. 이메일을 확인하시고 이메일 본문에서 첫 로그인을 위한 링크를 클릭해주세요.";
					break;
				case 0:
					if($this->params['requestURI']) {
						$message = rawurldecode($this->params['requestURI']);
					} else {
						$message = base_uri();
					}
					break;
				case -1:
					$message = "존재하지 않는 E-Mail 아이디입니다.";
					break;
				case -2:
					$message = "비밀번호가 일치하지 않습니다.";
					break;
			}
			if(!empty($this->params['authtoken'])) {
				RedirectURL($message);
			} else {
				Respond::ResultPage(array($isLogin,$message));
			}
		}
		$this->title = JFE_NAME." 로그인";
	}
}
