<?php
class User extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	//--------------------------------------------------------------------------------------
	//	Search
	//--------------------------------------------------------------------------------------
	function search($s_mode,$s_arg) {
		$context = Model_Context::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm = DBM::instance();
		$dbm->bind($userdb);
		$que = "SELECT * FROM {user} WHERE $s_mode = '$s_arg'";
		$search_user = $dbm->getFetchArray($que);
		$dbm->bind($db);
		return $search_user;
	}

	public static function filter($user) {
		return $user;
	}
	//--------------------------------------------------------------------------------------
	//	Get single object
	//--------------------------------------------------------------------------------------
	public static function getUser($uid,$context_opt = 0) {
		$context = Model_Context::instance();
		if($context_opt) {
			$user = $context->getProperty('user.'.$uid);
		}
		if(!$user) {
			$userdb = $context->getProperty('userdatabase.*');
			$db = $context->getProperty('database.*');
			$dbm = DBM::instance();
			$dbm->bind($userdb);
			$que = "SELECT * FROM {user} WHERE uid = $uid";
			$user = $dbm->getFetchArray($que);
			$dbm->bind($db);
			if($user) {
				$que = "SELECT * FROM {user} WHERE uid = $uid";
				$row = $dbm->getFetchArray($que);
				foreach($row as $k=>$v) $user[$k] = $v;
				if($context_opt) {
					$context->setProperty('user.'.$uid,$user);
					$context->setProperty('nickname.'.$user['taoginame'],$uid);
				}
			}
		}
		return self::filter($user);
	}

	function getUserByNickname($nickname,$context_opt = 0) {
		$context = Model_Context::instance();
		if($context_opt) {
			$uid = $context->getProperty('nickname.'.$nickname);
			if($uid) $user = self::getUser($uid,$context_opt);
		}
		if(!$user) {
			$dbm = DBM::instance();
			$que = "SELECT * FROM {user} WHERE taoginame = '".$nickname."'";
			$localuser = $dbm->getFetchArray($que);
			$uid = $localuser['uid'];
			if($uid) {
				$userdb = $context->getProperty('userdatabase.*');
				$db = $context->getProperty('database.*');
				$dbm->bind($userdb);
				$que = "SELECT * FROM {user} WHERE uid = $uid";
				$user = $dbm->getFetchArray($que);
				$dbm->bind($db);
				if(!$user) {
					$que = "DELETE FROM {user} WHERE uid = ?";
					self::DeleteMeta($uid);
				} else {
					foreach($localuser as $k=>$v) $user[$k] = $v;
					if($context_opt) {
						$context->setProperty('user.'.$uid,$user);
						$context->setProperty('nickname.'.$user['taoginame'],$uid);
					}
				}
			}
		}
		return self::filter($user);
	}

	function getUserBySns($sns_id,$sns_site,$context_opt) {
		$context = Model_Context::instance();
		if($context_opt) {
			$user = $context->getProperty($sns_site.'.'.$sns_id);
		}
		if(!$user) {
			$dbm = DBM::instance();
			$userdb = $context->getProperty('userdatabase.*');
			$db = $context->getProperty('database.*');
			$que = "SELECT * FROM {user} WHERE sns_id = '$sns_id' AND sns_site = '$sns_site'";
			$dbm->bind($userdb);
			$user = $dbm->getFetchArray($que);
			$dbm->bind($db);
			$que = "SELECT * FROM {user} WHERE uid = $uid";
			$row = $dbm->getFetchArray($que);
			$user['nickname'] = $row['nickname'];
			if($context_opt) {
				$context->setProperty($sns_site.'.'.$sns_id,$user);
			}
		}
		return self::filter($user);
	}

	//--------------------------------------------------------------------------------------
	//	Actions
	//--------------------------------------------------------------------------------------
	function DeleteMeta($uid) {
		$que = "DELETE FROM {user} WHERE uid = ?";
		$dbm->execute($que,array("d",$uid));
	}

	function Regist($params) {
		$context = Model_Context::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm = DBM::instance();
		$dbm->bind($userdb,1);
		$que = "INSERT INTO {user} (email_id,name,nickname,password,degree,reg_date,authtoken) VALUES (?,?,?,?,?,?,?)";
		if(!$dbm->execute($que,array("ssssdds",$params['email_id'],$params['name'],$params['name'],Auth::getPassword($params['email_id'],$params['password']),BITWISE_USER,time(),$params['authtoken']))) {
			$dbm->bind($userdb);
			return 0;
		}
		$uid = $dbm->getLastInsertId();
		$dbm->bind($db,1);
		return $uid;
	}

	function RegistLocalUser($params) {
		$dbm = DBM::instance();
		$que = "INSERT INTO {user} (`uid`,`taoginame`,`display_name`,`degree`,`reg_date`,`last_login`) VALUES (?,?,?,?,?,?)";
		if(!$dbm->execute($que,array("dssddd",$params['uid'],$params['taoginame'],($params['display_name'] ? $params['display_name'] : $params['name']),$params['degree'],time(),time()))) return false;
		return true;
	}

	//--------------------------------------------------------------------------------------
	//	Template tags
	//--------------------------------------------------------------------------------------
	public static function getUserBy($user=null,$field='') {
		if(is_numeric($user)) {
			$user = self::getUser($user);
		}
		if(!is_array($user)) {
			//return INVALID_DATA_FORMAT;
			return false;
		}

		return $user;	
	}

}
