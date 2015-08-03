<?php
importLibrary('auth');
importLibrary('mail');

$Acl = 'user';

$IV = array(
	'POST' => array(
		'email_id' => array('email', 'default' => null),
		'email_id_confirm' => array('email', 'default' => null),
		'name' => array('string', 'default' => null),
		'taoginame' => array('string', 'default' => null),
		'display_name' => array('string', 'default' => null),
		'portrait' => array('string', 'default' => null),
		'summary' => array('string', 'default' => null),
		'password' => array('string', 'default' => null),
		'password_confirm' => array('string', 'default' => null),
	)
);

class profile_save extends Controller {
	public function index() {
		$this->contentType = 'json';

		$this->user = User::getUserProfile($this->params['userid']);

		if(!$this->user['uid']) {
			RespondJson::ResultPage(array(2,"로그인이 종료되었습니다. 다시 로그인 해주세요."));
		}

		if( !empty($this->params['email_id']) &&
			!empty($this->params['email_id_confirm']) ) {
			$this->change_email_id = false;
			if($this->params['email_id'] != $this->params['email_id_confirm']) {
				RespondJson::ResultPage(array(-1,"이메일 아이디가 서로 일치하지 않습니다."));
			}
			if($this->params['password']) {
				if($this->params['password'] != $this->params['password_confirm']) {
					RespondJson::ResultPage(array(-2,"변경하시려는 비밀번호가 서로 일치하지 않습니다."));
				}
			}
			if( $this->params['email_id'] != $this->user['email_id'] ) {

				if( !$this->params['password'] ) {
					RespondJson::ResultPage(array(-2,"로그인 이메일 아이디를 변경하시려면 비밀번호도 같이 변경됩니다. 비밀번호를 입력하세요."));
				}
				$token = User_DBM::makeChangeEmailToken($this->user['uid'],$this->params['email_id'],$this->params['password']);
				$ret = sendChangeMail($this->user['uid'],$this->params['email_id'], $this->user['name'], $token);
				if($ret != true)
					RespondJson::ResultPage(array(1,"이메일 수정 승인 메일을 보내는 동안 장애가 발생했습니다."));
				$this->change_email_id = true;

			} else if( $this->params['password'] ) {
				User_DBM::updatePassword($this->user['uid'],$this->params['email_id'],$this->params['password']);
			}
			if($this->change_email_id == true) {
				require_once dirname(__FILE__)."/change_email_auth.json.php";
				return;
			}
		} else {
			if( !$this->params['taoginame'] ) {
				RespondJson::ResultPage(array(-3, "프로필 주소를 입력해주세요."));
			}
			$this->rebuildGNB = 0;
			if($this->user['taoginame'] != $this->params['taoginame']) {
				$_user = User::getUserByNickname($this->params['taoginame']);
				if($_user['uid'] && $_user['uid'] != $this->user['uid']) {
					RespondJson::ResultPage(array(-3, "이미 다른 분이 사용 중인 프로필 주소입니다. 다른 주소를 입력해주세요."));
				}
				$this->params['old_taoginame'] = $this->user['taoginame'];
			} else {
				$this->params['old_taoginame'] = '';
			}
			if( !$this->params['name'] ) {
				RespondJson::ResultPage(array(-4, "이름을 입력해주세요."));
			}

			if(!User_DBM::updateUser($this->user['uid'],$this->params)) {
				RespondJson::ResultPage(array(1,"회원 정보 수정 도중 장애가 발생했습니다."));
			}
			$this->user['taoginame'] = $_SESSION['user']['taoginame'] = $this->params['taoginame'];
			$this->user['display_name'] = $_SESSION['user']['display_name'] = $this->params['display_name'];
			//$this->user['portrait'] = $_SESSION['user']['portrait'] = $this->params['portrait'];
			$this->user['summary'] = $this->params['summary'];

			if( $this->user['name'] != $this->params['name'] ) {
				if(!User_DBM::updateUserName($this->user['uid'],$this->params['name'])) {
					RespondJson::ResultPage(array(1,"회원 정보 수정 도중 장애가 발생했습니다."));
				}
				$this->user['name'] = $_SESSION['user']['name'] = $this->params['name'];
			}
			$dbm = DBM::instance();
			$dbm->commit();
		}
	}
}
?>
