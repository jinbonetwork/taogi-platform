<?php
importLibrary('auth');
importLibrary('mail');
$IV = array(
	'POST' => array(
		'email_id' => array('email', 'default' => null),
		'email_id_confirm' => array('email', 'default' => null),
		'name' => array('string', 'default' => null),
		'taoginame' => array('string', 'default' => null),
		'password' => array('string', 'default' => null),
		'password_confirm' => array('string', 'default' => null),
		'code' => array('string', 'default' => null),
	)
);

class regist_index extends Controller {
	public function index() {
		global $facebook;
		$this->facebook = $facebook;

		if($this->params['output'] != "xml") {
			importResource('taogi-app-regist');
		}
		$this->title = JFE_NAME." 회원 가입";
		if(doesHaveMembership()) {
			if($this->params['output'] == "xml") {
				RespondJson::ResultPage(array(2,"이미 로그인하셨습니다"));
			} else {
				Error("이미 로그인하셨습니다.");
			}
		}
		$this->password_necessary = "necessary";
		$context = Model_Context::instance();
		if( !empty($this->params['email_id']) &&
			!empty($this->params['email_id_confirm']) &&
			!empty($this->params['name']) &&
			!empty($this->params['taoginame']) &&
			!empty($this->params['password']) &&
			!empty($this->params['password_confirm']) ) {
			$this->regist = 1;
			if(User::search('email_id',$this->params['email_id'])) {
				RespondJson::ResultPage(array(-1,$this->params['email_id']."은 이미 등록된 이메일입니다."));
				$this->regist = 0;
			}
			if($this->params['email_id'] != $this->params['email_id_confirm']) {
				RespondJson::ResultPage(array(-1,"이메일 아이디가 서로 일치하지 않습니다."));
				$this->regist = 0;
			}
			if($this->params['password'] != $this->params['password_confirm']) {
				RespondJson::ResultPage(array(-2, "비밀번호가 서로 일치하지 않습니다."));
				$this->regist = 0;
			}
			if(!$this->params['taoginame'])
				RespondJson::ResultPage(array(-3, "따오기 고유주소를 입력해주세요."));
			$_user = User::getUserByNickname($this->params['taoginame']);
			if($_user['uid']) {
				RespondJson::ResultPage(array(-3, "이미 다른 분이 사용 중인 따오기 고유주소입니다. 다른 주소를 입력해주세요."));
				$this->regist = 0;
			}
			if($this->regist) {

				$this->params['authtoken'] = Auth::makeAuthtoken($this->params['password']);
				if(!($reg_uid = User::Regist($this->params)))
					RespondJson::ResultPage(array(1,"회원 가입 도중 장애가 발생했습니다."));
				$this->params['uid'] = $reg_uid;
				$this->params['degree'] = BITWISE_ANONYMOUS;
				if(!User::RegistLocalUser($this->params))
					RespondJson::ResultPage(array(1,"회원 가입 도중 장애가 발생했습니다."));

				$ret = sendRegistMail($this->params['email_id'], $this->params['name'], $this->params['authtoken']);
				if($ret[0] != true)
					RespondJson::ResultPage(array(1,"회원 가입 승인 메일을 보내는 동안 장애가 발생했습니다."));

				if(!$this->themes) $this->themes = $context->getProperty('service.themes');
				if(file_exists(JFE_PATH."/themes/".$this->themes."/regist/invitation.html.php")) {
					ob_start();
					include_once(JFE_PATH."/themes/".$this->themes."/regist/invitation.html.php");
					$content = ob_get_contents();
					ob_end_clean();
					RespondJson::ResultPage(array(0,$content));
				} else {
					require_once(dirname(__FILE__)."/invitation.json.php");
				}
			}
		}

		$redirect_uri = "https://".$context->getProperty('service.domain').base_uri().'login/fb';
		if($this->params['request_URI'])
			$redirect_uri .= "?requestURI=".$this->params['request_URI'];
		$this->fb_login_url = $facebook->getLoginUrl(array('redirect_uri'=>$redirect_uri));
		$this->email_form = Component::get('user/forms/email_id',array('user'=>null));
		$this->password_form = Component::get('user/forms/password',array('password_necessary'=>'necessary'));
		$this->name_form = Component::get('user/forms/name',array('user'=>null));
		$this->profile_form = Component::get('user/forms/profile',array('user'=>null));
	}
}
?>
