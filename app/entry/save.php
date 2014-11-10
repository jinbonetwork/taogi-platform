<?php
import('library.validateJS');
import('library.getJson');
$Acl = "editor";
class entry_save extends Controller {
	public function index() {
		$this->contentType = 'json';
		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');
		if($uid < 1) RespondJson::ResultPage(array(-1,"타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다."));

		if(!$this->params['taogiid']) RespondJson::ResultPage(array(-3,"수정할 타임라인을 지정하세요"));
		$this->eid = $this->params['taogiid'];

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		$data = Entry::getEntryData($this->eid,$this->entry['vid']);

		if($data['editor'] != $uid || $this->entry['locked'] != $_COOKIE[Session::getName()]) {
			$revision = true;
		}

		require_once dirname(__FILE__)."/../../timeline/model/touchcarousel/config/config.php";

		$timeline = json_decode(stripslashes($this->params['content']),true);
		if(!$timeline['timeline']['permalink']) {
			RespondJson::ResultPage(array(-4,"타임라인 주소를 지정하세요"));
		}
		if(!Entry::checkValidPermalink($timeline['timeline']['permalink'])) {
			RespondJson::ResultPage(array(-4,"타임라인 주소는 알파벳과 숫자 그리고 .-_ 만 허용합니다."));
		}

		$_entry = Entry::searchByNickname($uid,$timeline['timeline']['permalink']);
		if($_entry && ($_entry['eid'] != $this->eid)) RespondJson::ResultPage(array(-2,"다른 타임라인에서 사용하는 주소입니다."));

		$vtimeline = validateTimeLineJS($timeline);

		if($revision == true) {
			$this->vid = Entry_DBM::createRevision($this->eid,$uid,$vtimeline);
			$commit = true;
		} else if($this->params['vid']) {
			$this->vid = $this->params['vid'];
			if($data['timeline'] != $vtimeline || $data['subject'] != $vtimeline['timeline']['headline']) {
				Entry_DBM::updateRevision($vid,$vtimeline);
				$commit = true;
			}
		}
		if( $this->entry['nickname'] != $vtimeline['timeline']['permalink'] ||
			$this->entry['subject'] != $vtimeline['timeline']['headline'] ||
			$this->entry['summary'] != $vtimeline['timeline']['text'] ||
			$this->entry['asset'] != $vtimeline['timeline']['asset'] ||
			$this->entry['author'] != $vtimeline['timeline']['extra']['author'] ||
			$this->entry['era'] != $vtimeline['timeline']['era'] ||
			$this->entry['is_public'] != $vtimeline['timeline']['extra']['published'] ) {
			Entry_DBM::updateEntry($this->eid,$this->vid,$vtimeline);
			$commit = true;
		}
		$this->nickname = $vtimeline['timeline']['permalink'];
		if($commit == true) {
			$dbm = DBM::instance();
			$dbm->commit();
		}

		$json_path = getJsonPath($this->eid);
		if($vtimeline['timeline']['extra']['published']) {
			$rtimeline = publishedTimeLineJs($vtimeline);

			$fp = fopen($json_path,"w");
			fputs($fp,json_encode($rtimeline));
			fclose($fp);
		} else {
			if(file_exists($json_path)) {
				unlink($json_path);
			}
		}
	}
}
?>
