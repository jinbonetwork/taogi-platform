<?php
$Acl = 'user';
class profile_index extends Controller {
	public function index() {

		// Objects
		$this->user = User::getUserProfile($this->params['userid']);

		$uid = Acl::getIdentity('taogi');
		if($uid != $this->user['uid']) Error("접근 권한이 없습니다.",403);

		/* FancyBox */
		$this->js[] = 'fancyBox/source/jquery.fancybox.css';
		$this->js[] = 'fancyBox/source/jquery.fancybox.pack.js';

		/* Cropper */
		if($_SESSION['user']['uid']==$this->user['uid']){
			$this->css[] = '../../contribute/cropper/dist/cropper.min.css';
			$this->js[] = '../../contribute/cropper/dist/cropper.min.js';
		}

		// Views
		$_SESSION['current'] = array('mode'=>'profile');
		require_once JFE_PATH.'/include/userVcard.php';
		$this->css[] = 'ui-vcard.css';
		$this->script[] = 'ui-vcard.js';

		require_once JFE_PATH.'/include/userTabs.php';
		$this->css[] = 'ui-tabs.css';
		$this->script[] = 'ui-tabs.js';

		// Resources - app
		$this->css[] = 'app-user.css';
		$this->css[] = 'ui-form.css';
		$this->script[] = '../../contribute/jquery.actual/jquery.actual.min.js';
		$this->script[] = 'ui-form.js';
		$this->script[] = 'wysiwyg_editor.js';
		$this->script[] = '../../contribute/jQuery-File-Upload/js/vendor/jquery.ui.widget.js';
		$this->script[] = '../../contribute/jQuery-File-Upload/js/jquery.iframe-transport.js';
		$this->script[] = '../../contribute/jQuery-File-Upload/js/jquery.fileupload.js';
		$this->script[] = 'app-user.js';
		$this->css[] = 'app-user-profile.css';
		$this->script[] = 'app-user-profile.js';

		$this->password_necessary = "necessary";
	}
}
?>
