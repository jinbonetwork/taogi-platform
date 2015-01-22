<?php
class login_fb extends Controller {
	public function index() {
		global $facebook;
		$fb_user = $facebook->getuser();
		$fb_user_profile = $facebook->api('/me');

		$_user = User::getUserBySns($fb_user,'facebook');
		if($_user) {
			Auth::auth_user($_user);
			$_SESSION['user']['sns_id'] = $fb_user_profile['id'];
			$_SESSION['user']['sns_site'] = 'facebook';
		} else {
			$_user = array(
				'email_id' => $fb_user_profile['email'],
				'name' => $fb_user_profile['name'],
				'sns_id' => $fb_user_profile['id'],
				'sns_site' => 'facebook',
				'homepage' => $fb_user_profile['link'],
				'nickname' => $fb_user_profile['username'],
				'degree' => BITWISE_USER,
				'favicon' => 'https://graph.facebook.com/'.$fb_user_profile['id'].'/picture'
			);
			$_user['uid'] = User_DBM::Regist_SNS($_user);
			
			Auth::auth_user($_user);
		}
		if($this->params['requestURI']) RedirectURL(rawurldecode($this->params['requestURI']));
		else RedirectURL(base_uri());
	}
}
