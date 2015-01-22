<?php
import('library.getJson');
$Acl = "owner";
class entry_delete extends Controller {
	public function index() {
		$this->title = "따오기 타임라인 삭제하기";
		$context = Model_Context::instance();
		require JFE_PATH."/timeline/model/touchcarousel/config/config.php";

		$this->mediaconfig = $config;
					        
		$uid = Acl::getIdentity('taogi');
		if($uid < 1) Error("타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다.",403);

		if(!$this->params['taogiid']) Error("수정할 타임라인을 지정하세요");
		$this->eid = $this->params['taogiid'];

		$this->user = User::getUserProfile($this->params['userid']);

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->entry['locked'] && $this->entry['locked'] != $_COOKIE[Session::getName()]) {
			if( $this->entry['modified'] > ( time() - 3600 ) )
				Error("다른 분이 편집중입니다. 잠시후 다시 시도해주세요.",423);
		}
		if($this->entry['owner'] != $uid) Error("운영자만 삭제할 수 있습니다.");

		Entry_DBM::deleteEntry($this->eid);
		$dbm = DBM::instance();
		$dbm->commit();

		$json_path = getJsonPath($this->eid);
		if(file_exists($json_path)) {
			unlink($json_path);
		}
	}
}
?>
