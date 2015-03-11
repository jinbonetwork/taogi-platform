<?php
class User extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	//--------------------------------------------------------------------------------------
	//	Search
	//--------------------------------------------------------------------------------------
	public static function search($s_mode,$s_arg) {
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
	//	Utilities
	//--------------------------------------------------------------------------------------
	public static function getUserLink($user) {
		$context = Model_Context::instance();
		$protocol = 'http'.($context->getProperty('service.ssl')==true?'s':'');
		$domain = $context->getProperty('service.domain');

		if(is_numeric($user)) {
			$user = self::getUser($user,1);
		}
		$userSlug = $user['taoginame'];

		$link = $protocol.'://'.$domain.'/'.$userSlug;

		return $link;
	}

	public static function getUserPortrait($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = self::getUser($user,1);
		}
		if(empty($user)) {
			return;
		}
		$image = $user['portrait'];
		if(!$image) {
			$image = DEFAULT_USER_PORTRAIT;
		}

		return $image;
	}

	public static function getUserBackground($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = self::getUser($user,1);
		}
		if(empty($user)) {
			return;
		}
		$background = $user['background'];
		if(!$background) {
			$background = DEFAULT_USER_BACKGROUND;
		}

		return $background;
	}

	public static function getUserDisplayName($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = self::getUser($user,1);
		}
		if(empty($user)) {
			return;
		}
		$name = $user['display_name'];
		$name = !$name?$user['nickname']:$name;
		$name = !$name?$user['name']:$name;
		$name = !$name?$user['taoginame']:$name;

		return $name;
	}

	public static function getUserRole($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = self::getUser($user,1);
		}
		if(empty($user)) {
			return;
		}
		global $FuchAclPreDefinedRole,$FuchAclPreDefinedRoleLabel;
		$roles = array_flip($FuchAclPreDefinedRole);
		$labels = $FuchAclPreDefinedRoleLabel;
		$role = $roles[$user['degree']];
		$label = $labels[$role];

		return $label;
	}

	//--------------------------------------------------------------------------------------
	//	Get single object
	//--------------------------------------------------------------------------------------
	public static function getUserProfile($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = self::getUser($user,1);
		}
		if(empty($user)) {
			return;
		}
		$user['excerpt'] = Filter::getExcerpt($user['summary']);
		$user['dashboard_link'] = self::getUserLink($user['uid']).'/';
		$user['profile_link'] = self::getUserLink($user['uid']).'/profile';
		$user['archives_link'] = self::getUserLink($user['uid']).'/archives';
		$user['bookmarks_link'] = self::getUserLink($user['uid']).'/bookmarks';

		$user['joined_absolute'] = Filter::getAbsoluteTime($user['reg_date']);
		$user['joined_relative'] = Filter::getRelativeTime($user['reg_date']);

		$user['DISPLAY_NAME'] = self::getUserDisplayName($user);
		$user['ROLE'] = self::getUserRole($user);
		$user['PORTRAIT'] = self::getUserPortrait($user);
		$user['BACKGROUND'] = self::getUserBackground($user);

		$user['NAMETAG'] = "<span class=\"NAMETAG value composition\">".($user['degree']?"<span class=\"ROLE value part\" data-degree=\"{$user['degree']}\"><span class=\"value-wrap-open\">(</span><span class=\"value-wrap-value\">{$user['ROLE']}</span><span class=\"value-wrap-close\">)</span></span>":'')."<span class=\"DISPLAY_NAME value part\">".$user['DISPLAY_NAME']."</span></span>";
		$user['PORTRAITTAG'] = '<div class="PORTRAITTAG'.($user['PORTRAIT']==DEFAULT_USER_PORTRAIT?' default_user_portrait default_image_container':'').'"><img src="'.$user['PORTRAIT'].'"></div>';

		return $user;
	}

	public static function getUserProfiles($users) {
		if(empty($users)) {
			return PAGE_NOT_FOUND;
		}
		if(!is_array($users)) {
			return INVALID_DATA_FORMAT;
		}

		$filtered = array();
		foreach($users as $user) {
			$filtered[] = self::getUserProfile($user);
		}

		return $filtered;
	}

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

	public static function getUserByEmail($email_id) {
		$context = Model_Context::instance();
		$userdb = $context->getProperty('userdatabase.*');
		$db = $context->getProperty('database.*');
		$dbm = DBM::instance();
		$dbm->bind($userdb);
		$que = "SELECT * FROM {user} WHERE email_id = '$email_id'";
		$user = $dbm->getFetchArray($que);
		$dbm->bind($db,1);
		if($user) {
			$que = "SELECT * FROM {user} WHERE uid = ".$user['uid'];
			$localuser = $dbm->getFetchArray($que);
			if($localuser) {
				foreach($localuser as $k=>$v) $user[$k] = $v;
			}
		} else {
			return null;
		}
		return self::filter($user);
	}

	public static function getUserByNickname($nickname,$context_opt = 0) {
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

	public static function getUserBySns($sns_id,$sns_site,$context_opt) {
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
	public static function DeleteMeta($uid) {
		$dbm = DBM::instance();
		$que = "DELETE FROM {user} WHERE uid = ?";
		$dbm->execute($que,array("d",$uid));
	}

	public static function Regist($params) {
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

	public static function RegistLocalUser($params) {
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
