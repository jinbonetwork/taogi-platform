<?php
class User_DBM extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function updateUser($uid,$args) {
		$dbm = DBM::instance();
		$que = "UPDATE {user} SET taoginame = ? WHERE uid = ?";
		$dbm->execute($que,array("sd",
			$args['taoginame'],
			$uid
		));
	}

	public static function getPrivilege($uid,$eid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {privileges} WHERE uid = ".$uid." AND eid = ".$eid;
		$row = $dbm->getFetchArray($que);
		return $row;
	}

	public static function getEditors($eid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {privileges} AS p LEFT JOIN {user} AS u ON p.uid = u.uid WHERE p.eid = ".$eid;
		$users = array();
		while($row = $dbm->getFetchArray($que)) {
			$users[] = $row;
		}
		return $users;
	}

	public static function getUserList($uids) {
		$context = Model_Context::instance();
		if(!is_array($uids)) $uids = array($uids);

		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb);
		$que = "SELECT * FROM {user} WHERE uid IN (".implode(",",$uids).")";
		while($row = $dbm->getFetchArray($que)) {
			$users[$row['uid']] = $row;
		}
		$dbm->bind($db,1);
		return $users;
	}

	public static function addPrivilege($uid,$eid,$level) {
		if(!$level) return;
		$dbm = DBM::instance();
		$privilege = User_DBM::getPrivilege($uid,$eid);
		if($privilege) {
			$que = "UPDATE {privileges} SET `level` = ? WHERE `uid` = ? AND `eid` = ?";
			$dbm->execute($que,array("ddd",
				$level,
				$uid,
				$eid
			));
		} else {
			$que = "INSERT INTO {privileges} (`uid`,`eid`,`level`,`created`) VALUES (?,?,?,?)";
			$dbm->execute($que,array("dddd",
				$uid,
				$eid,
				$level,
				time()
			));
		}
	}

	public static function updatePrivilege($uid,$eid,$level) {
		if(!$level) return;
		$dbm = DBM::instance();
		$que = "UPDATE {privileges} SET `level` = ? WHERE `uid` = ? AND `eid` = ?";
		$dbm->execute($que,array("ddd",
			$level,
			$uid,
			$eid
		));
	}

	public static function deletePrivilege($uid,$eid=0) {
		$dbm = DBM::instance();
		if($eid) {
			$que = "DELETE FROM {privileges} WHERE uid = ? AND eid = ?";
			$dbm->execute($que,array("dd",$uid,$eid));
		} else {
			$que = "DELETE FROM {privileges} WHERE uid = ?";
			$dbm->execute($que,array("d",$uid));
		}
	}
}
