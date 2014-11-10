<?php
import('library.validateJS');
import('library.getJson');
import('library.files');
$Acl = "user";
class create_save extends Controller {
	public function index() {
		$this->contentType = 'json';
		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');
		if($uid < 1) RespondJson::ResultPage(array(-1,"타임라인을 만드시려면 먼저 회원 가입을 하셔야 합니다."));

		require_once dirname(__FILE__)."/../../timeline/model/touchcarousel/config/config.php";

		$timeline = json_decode(stripslashes(rawurldecode($this->params['content'])),true);
		if(!$timeline['timeline']['permalink']) {
			list($usec,$sec) = explode(" ",microtime());
			$timeline['timeline']['permalink'] = $sec.substr($usec,2);
		}

		if($timeline['timeline']['permalink']) {
			$entry = Entry::searchByNickname($uid,$timeline['timeline']['permalink']);
			if($entry) RespondJson::ResultPage(array(-2,"이미 존재하는 타임라인입니다."));
			if(!Entry::checkValidPermalink($timeline['timeline']['permalink'])) {
				RespondJson::ResultPage(array(-4,"타임라인 주소는 알파벳과 숫자 그리고 .-_ 만 허용합니다."));
			}
		}
		$vtimeline = validateTimeLineJS($timeline);

		list($this->eid,$this->vid) = Entry_DBM::createEntry($uid,$vtimeline);
		$this->nickname = $timeline['timeline']['permalink'];

		User_DBM::addPrivilege($uid,$this->eid,BITWISE_OWNER);

		$source = getEntryAttachedTmpPath();
		if(file_exists($source)) {
			$target = getEntryAttachedPath($this->eid);
			if(!rename($source,$target)) {
				RespondJson::ResultPage(array(-4,"첨부폴더를 이동하는 도중 장애가 발생되었습니다."));
			}
			$src_uri = $_SERVER['SERVER_NAME'].getEntryAttachedTmpURI();
			$tar_uri = $_SERVER['SERVER_NAME'].getEntryAttachedURI($this->eid);
			for($d=0; $d<@count($vtimeline['timeline']['date']); $d++) {
				$vtimeline['timeline']['date'][$d]['asset']['media'] = str_replace($src_uri,$tar_uri,$vtimeline['timeline']['date'][$d]['asset']['media']);
				for($m=0; $m<@count($vtimeline['timeline']['date'][$d]['media']); $m++) {
					$vtimeline['timeline']['date'][$d]['media'][$m]['media'] = str_replace($src_uri,$tar_uri,$vtimeline['timeline']['date'][$d]['media'][$m]['media']);
				}
			}
			Entry_DBM::updateEntry($this->eid,$this->vid,$vtimeline);
			Entry_DBM::updateRevision($this->vid,$vtimeline);
			$this->src_uri = $src_uri;
			$this->tar_uri = $tar_uri;
		}

		$dbm = DBM::instance();
		$dbm->commit();

		if($vtimeline['timeline']['extra']['published']) {
			$rtimeline = publishedTimeLineJs($vtimeline);

			$json_path = getJsonPath($this->eid);
			$fp = fopen($json_path,"w");
			fputs($fp,json_encode($rtimeline));
			fclose($fp);
		}
	}
}
?>
