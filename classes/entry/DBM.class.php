<?php
class Entry_DBM extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function createEntry($uid,$timeline) {
		$dbm = DBM::instance();
		$que = "INSERT INTO {entry} (`vid`,`owner`,`nickname`,`subject`,`summary`,`asset`,`author`,`era`,`is_public`,`is_forkable`,`published`,`locked`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		$dbm->execute($que,array("ddssssssddds",
			0,
			$uid,
			$timeline['timeline']['permalink'],
			$timeline['timeline']['headline'],
			$timeline['timeline']['text'],
			serialize($timeline['timeline']['asset']),
			$timeline['timeline']['extra']['author'],
			serialize($timeline['timeline']['era']),
			($timeline['timeline']['extra']['published'] ? $timeline['timeline']['extra']['published'] : 0),
			(isset($timeline['timeline']['extra']['forkable']) ? $timeline['timeline']['extra']['forkable'] : 0),
			time(),
			$_COOKIE[Session::getName()]
		));
		$eid = $dbm->getLastInsertId();

		$vid = self::createRevision($eid,$uid,$timeline);

		$que = "UPDATE {entry} SET vid = ? WHERE eid = ?";
		$dbm->execute($que,array("dd", $vid,$eid));

		return array($eid,$vid);
	}

	public static function updateEntry($eid,$vid,$timeline) {
		$dbm = DBM::instance();
		$que = "UPDATE {entry} SET `vid`=?,`nickname`=?,`subject`=?,`summary`=?,`asset`=?,`author`=?,`era`=?,`is_public`=? WHERE `eid`=?";
		$dbm->execute($que,array("dssssssdd",
			$vid,
			$timeline['timeline']['permalink'],
			$timeline['timeline']['headline'],
			$timeline['timeline']['text'],
			serialize($timeline['timeline']['asset']),
			$timeline['timeline']['extra']['author'],
			serialize($timeline['timeline']['era']),
			$timeline['timeline']['extra']['published'],
			$eid
		));
	}

	public static function updateStatus($eid,$status) {
		$dbm = DBM::instance();
		$que = "UPDATE {entry} SET `is_public` = ? WHERE `eid` = ?";
		$dbm->execute($que,array("dd",
			$status,
			$eid
		));
	}

	public static function createRevision($eid,$uid,$timeline) {
		$dbm = DBM::instance();
		$que = "INSERT INTO {revision} (`eid`,`editor`,`subject`,`timeline`,`modified`) VALUES (?,?,?,?,?)";
		$dbm->execute($que,array("ddssd",
			$eid,
			$uid,
			$timeline['timeline']['headline'],
			base64_encode(json_encode($timeline)),
			time()
		));
		$vid = $dbm->getLastInsertId();
		return $vid;
	}

	public static function updateRevision($vid,$timeline) {
		$dbm = DBM::instance();
		$que = "UPDATE {revision} SET `subject`=?, `timeline`=?, `modified`=? WHERE `vid` = ?";
		$dbm->execute($que,array("ssdd",
			$timeline['timeline']['headline'],
			base64_encode(json_encode($timeline)),
			time(),
			$vid
		));
	}

	public static function setLock($eid) {
		$dbm = DBM::instance();
		$que = "UPDATE {entry} SET `locked` = ? WHERE `eid` = ?";
		$dbm->execute($que,array("sd",
			$_COOKIE[Session::getName()],
			$eid
		));
	}

	public static function unLock($eid) {
		$dbm = DBM::instance();
		$que = "UPDATE {entry} SET `locked` = ? WHERE `eid` = ?";
		$dbm->execute($que,array("sd",
			'',
			$eid
		));
	}

	public static function forkable($eid,$forkable) {
		$dbm = DBM::instance();
		$que = "UPDATE {entry} SET `is_forkable` = ? WHERE `eid` = ?";
		$dbm->execute($que,array("dd",
			($forkable ? 1 : 0),
			$eid
		));
	}

	public static function deleteEntry($eid) {
		$dbm = DBM::instance();
		$que = "DELETE FROM {entry} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {revision} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {privileges} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {entryExtra} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {comment} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {tag2entry} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));

		$que = "DELETE FROM {entry2terms} WHERE eid = ?";
		$dbm->execute($que,array("d",$eid));
	}
}
?>
