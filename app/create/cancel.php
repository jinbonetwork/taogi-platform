<?php
import('library.validateJS');
import('library.getJson');
import('library.files');
$Acl = "user";
class create_cancel extends Controller {
	public function index() {
		$this->contentType = 'json';
		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');
		if($uid < 1) RespondJson::ResultPage(array(-1,"타임라인을 만드시려면 먼저 회원 가입을 하셔야 합니다."));

		$trash = getEntryAttachedTmpPath();
		if(file_exists($trash)) {
			delTree($trash);
		}
	}
}
?>
