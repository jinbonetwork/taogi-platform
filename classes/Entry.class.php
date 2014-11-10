<?php
class Entry extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function getEntryIDByName($name,$owner) {
		$que = "SELECT * FROM {entry} WHERE owner = ".$owner." AND nickname = '".trim($name)."'";
		$dbm = DBM::instance();
		$row = $dbm->getFetchArray($que);
		if($row) {
			$context = Model_Context::instance();
			$context->setProperty('entry.'.$row['eid'],$row);
		}
		return $row['eid'];
	}

	public static function getEntryInfoByID($eid,$context_opt) {
		$context = Model_Context::instance();
		if($context_opt) {
			$entry = $context->getProperty('entry.'.$eid);
		}
		if(!$entry) {
			$dbm = DBM::instance();
			$que = "SELECT * FROM {entry} WHERE eid = ".$eid;
			$entry = self::fetchEntry($dbm->getFetchArray($que));
			if($context_opt) {
				$context->setProperty('entry.'.$eid,$entry);
			}
		}
		return $entry;
	}

	public static function getEntryData($eid,$vid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {revision} WHERE vid = $vid AND eid = $eid";
		$revision = self::fetchRevision($dbm->getFetchArray($que));
		return $revision;
	}

	public static function getEntryExtra($eid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {entryExtra} WHERE eid = $eid";
		while($row = $dbm->getFetchArray($que)) {
			$extra[$row['name']] = $row['val'];
		}
		return $extra;
	}

	public static function checkPermalink($uid,$nickname) {
		$dbm = DBM::instance();
		$que = "SELECT eid FROM {entry} WHERE owner = ".$uid." AND nickname = '".addslashes($nickname)."'";
		$row = $dbm->getFetchArray($que);
		return ($row['eid'] ? $row['eid'] : 0);
	}

	public static function searchByNickname($uid,$nickname) {
		$dbm = DBM::instance();
		$que = "SELECT eid FROM {entry} WHERE owner = ".$uid." AND nickname = '".addslashes($nickname)."'";
		$row = self::fetchEntry($dbm->getFetchArray($que));
		return $row;
	}

	public static function fetchEntry($entry) {
		if($entry) {
			$entry['asset'] = unserialize($entry['asset']);
			$entry['era'] = unserialize($entry['era']);
		}
		return $entry;
	}

	public static function fetchRevision($revision) {
		if($revision) {
			$revision['timeline'] = base64_decode($revision['timeline']);
		}
		return $revision;
	}

	public static function checkValidPermalink($permalink) {
		return preg_match("/^[0-9a-zA-Z\-_\.]+$/i",$permalink);
	}
}
?>
