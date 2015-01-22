<?php
class entry_json extends Controller {
	public function index() {
		$this->contentType = 'json';

		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');

		if(!$this->params['taogiid']) RespondJson::ResultPage(array(-1,"타임라인을 지정하세요"));
		$this->eid = $this->params['taogiid'];

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->params['vid']) {
			if($uid < 1) RespondJson::ResultPage(array(-1,"버젼별 타임라인을 보시려면 회원 가입을 하셔야 합니다."));
			$this->vid = $this->params['vid'];
		} else {
			$this->vid = $this->entry['vid'];
		}
		$this->revision = Entry::getEntryData($this->eid,$this->vid);
		if(!$this->revision) {
			RespondJson::NotFoundPage();
		}
		$this->revision['timeline'] = $this->fixBadUnicodeForJson($this->revision['timeline']);
	}

	private function fixBadUnicodeForJson($str) {
		$str = stripslashes(preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", $str));
		return $str;
	}

	public function format_json($json, $html = false, $tabspaces = null)
	{
		$tabcount = 0;
		$result = '';
		$inquote = false;
		$ignorenext = false;

		if ($html) {
			$tab = str_repeat("&nbsp;", ($tabspaces == null ? 4 : $tabspaces));
			$newline = "<br/>";
		} else {
			$tab = ($tabspaces == null ? "\t" : str_repeat(" ", $tabspaces));
			$newline = "\n";
		}

		for($i = 0; $i < strlen($json); $i++) {
			$char = $json[$i];

			if ($ignorenext) {
				$result .= $char;
				$ignorenext = false;
			} else {
				switch($char) {
					case ':':
						$result .= $char . (!$inquote ? " " : "");
						break;
					case '{':
						if (!$inquote) {
							$tabcount++;
							$result .= $char . $newline . str_repeat($tab, $tabcount);
						}
						else {
							$result .= $char;
						}
						break;
					case '}':
						if (!$inquote) {
							$tabcount--;
							$result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
						}
						else {
							$result .= $char;
						}
						break;
					case ',':
						if (!$inquote) {
							$result .= $char . $newline . str_repeat($tab, $tabcount);
						}
						else {
							$result .= $char;
						}
						break;
					case '"':
						$inquote = !$inquote;
						$result .= $char;
						break;
					case '\\':
						if ($inquote) $ignorenext = true;
						$result .= $char;
						break;
					default:
						$result .= $char;
				}
			}
		}

		return $result;
	}
}
?>
