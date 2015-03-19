<?php
class User_DBM extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function updateUser($uid,$args) {
		$dbm = DBM::instance();
		$que = "UPDATE {user} SET `taoginame` = ?, `display_name` = ?, `portrait` = ?, `summary` = ? WHERE uid = ?";
		$dbm->execute($que,array("ssssd",
			$args['taoginame'],
			$args['display_name'],
			$args['portrait'],
			$args['summary'],
			$uid
		));
		return true;
	}

	public static function updateUserName($uid,$name) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);

		$que = "UPDATE {user} SET name = ? WHERE uid = ?";
		$dbm->execute($que,array("sd",$name,$uid));
		$dbm->bind($db,1);

		return true;
	}

	public static function updatePassword($uid,$email_id,$password) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);

		$que = "UPDATE {user} SET password = ? WHERE uid = ?";
		$dbm->execute($que,array("sd",Auth::getPassword($email_id,$password),$uid));
		$dbm->bind($db,1);

		return true;
	}

	public static function updateUserField($uid,$field,$value) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
//		$dbm->bind($userdb,1);

		$que = "UPDATE {user} SET {$field} = ? WHERE uid = ?";
		$dbm->execute($que,array("sd",$value,$uid));
//		$dbm->bind($db,1);

		return true;
	}

	public static function updateUserExtra($uid,$field,$value) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
//		$dbm->bind($userdb,1);

		$user = User::getUser($uid);
		if(!is_array($user['extra']))
			$extra = json_decode(base64_decode($user['extra']),true);
		$extra[$field] = $value;
		$extra = base64_encode(json_encode($extra));
		
		$que = "UPDATE {user} SET extra = ? WHERE uid = ?";
		$fp = fopen("/tmp/taogi.log","a+");
		fputs($fp,$que."\n");
		fputs($fp,$uid." ".$extra."\n");
		fclose($fp);
		$dbm->execute($que,array("sd",$extra,$uid));
//		$dbm->bind($db,1);

		return true;
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

	public static function makeChangeEmailToken($uid,$email_id,$password) {
		$context = Model_Context::instance();

		$token = Auth::makeAuthtoken($email_id);
		$token_value = array(
			'email_id' => $email_id,
			'password' => $password,
			'token' => $token
		);

		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);

		$que = "SELECT * FROM {userSettings} WHERE uid = ".$uid." AND name = 'changeEmailToken'";
		$row = $dbm->getFetchArray($que);
		if($row) {
			$que = "UPDATE {userSettings} SET value = ? WHERE uid = ? AND name = ?";
			$dbm->execute($que,array("sds",serialize($token_value),$uid,'changeEmailToken'));
		} else {
			$que = "INSERT INTO {userSettings} (uid,name,value) VALUES (?,?,?)";
			$dbm->execute($que,array("dss",$uid,'changeEmailToken',serialize($token_value)));
		}
		$dbm->bind($db,1);

		return $token;
	}

	public static function getChangeEmailToken($uid) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);
		$que = "SELECT * FROM {userSettings} WHERE uid = ".$uid." AND name = 'changeEmailToken'";
		$row = $dbm->getFetchArray($que);
		$dbm->bind($db,1);
		$row['value'] = unserialize($row['value']);
		return $row;
	}

	public static function clearChangeEmailToken($uid) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);
		$que = "DELETE FROM {userSettings} WHERE uid = ? AND name = ?";
		$dbm->execute($que,array("ds",$uid,'changeEmailToken'));
		$dbm->bind($db,1);

		return true;
	}

	public static function updateEmailID($uid,$email_id,$password) {
		$context = Model_Context::instance();
		$dbm = DBM::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm->bind($userdb,1);

		$que = "UPDATE {user} SET `email_id` = ?, `password` = ? WHERE uid = ?";
		$dbm->execute($que,array("ssd",$email_id,Auth::getPassword($email_id,$password),$uid));
		$dbm->bind($db,1);

		return true;
	}
}
