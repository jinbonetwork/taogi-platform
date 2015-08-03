<?php
$Acl = 'user';
class profile_index extends Controller {
	public function index() {

		// Objects
		$this->user = User::getUserProfile($this->params['userid']);

		$uid = Acl::getIdentity('taogi');
		if($uid != $this->user['uid']) Error("접근 권한이 없습니다.",403);

		// Views
		$_SESSION['current'] = array('mode'=>'profile');

		importResource('taogi-app-user-profile');

		$this->password_necessary = "necessary";

		$this->vcard = Component::get('user/vcard',array('user'=>$this->user));
		$this->tabs = Component::get('user/tabs',array('user'=>$this->user,'current'=>'profile'));
		$this->email_form = Component::get('user/forms/email_id',array('user'=>$this->user));
		$this->password_form = Component::get('user/forms/password',array('password_necessary'=>$this->password_necessary));
		$this->name_form = Component::get('user/forms/name',array('user'=>$this->user));
		$this->profile_form = Component::get('user/forms/profile',array('user'=>$this->user));
	}
}
?>
