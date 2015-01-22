<?php
import('library.getJson');
class Interface_Entry extends Controller {
	public function getEntryInfo() {
		$this->entry = Entry::getEntryInfoByID($this->params['taogiid'],1);
		$this->entry['json'] = getJsonPath($this->entry['eid']);

		if($this->entry['uid'] == $this->user['uid']) {
			$this->owner = 2;
		} else {
			if($_SESSION['acl']["taogi.".$this->entry['eid']] == BITWISE_EDITOR) {
				$this->owner = 1;
			}
			$this->owner = 0;
		}
	}

	public function getJsonPath() {
		if(file_exists($this->entry['json'])) {
			return $this->entry['json'];
		}
		return null;
	}

	public function getJsonURI() {
		return $this->entry['json'];
	}

	public function makeJson() {
		if($this->entry['eid']) {
			if($this->entry['timeline']) {
				$fp = fopen($this->entry['json'],"w");
				fwrite($fp,$this->entry['timeline']);
				fclose($fp);
			}
		}
	}

	public function permalink() {
		"http://".$context->getProperty('service.domain')."/".implode("/",array_slice($uri->uri['fragment'],0,2));
	}
}
?>
