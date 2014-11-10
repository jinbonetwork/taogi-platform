<?php
class Auth extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	function authenticate($email_id,$password,$authtoken) {
		$context = Model_Context::instance();
		$userdb = $context->getProperty('userdatabase.*');
		Acl::clearAcl();
		$dbm = DBM::instance();
		$dbm->bind($userdb);
		$que = "SELECT u.* FROM {user} AS u WHERE u.email_id = '".$email_id."'";
		$row = $dbm->getFetchArray($que);
		if(!$row) return -1;
		if($password) {
			if($row['authtoken']) return 1;
			if($row['password'] != self::getPassword($email_id,$password)) return -2;
		} else if($authtoken) {
			if(!$row['authtoken']) return 1;
			if($row['authtoken'] != $authtoken) return -2;
		}

		$que = "UPDATE {user} SET last_login = ?,authtoken = '' WHERE uid = ?";
		$dbm->execute($que,array("dd",time(),$row['uid']));
		$dbm->commit();

		$db = $context->getProperty('database.*');
		$dbm->release();
		$dbm->bind($db);

		$que = "SELECT * FROM {user} WHERE uid = ".$row['uid'];
		$row2 = $dbm->getFetchArray($que);
		if(!$row2) {
			$que = "INSERT INTO {user} (uid,taoginame,display_name,portrait,degree,reg_date,last_login) VALUES (?,?,?,?,?,?,?)";
			if($row['favicon']) {
				$portrait = "http://socialfunch.org/".$row['favicon'];
			} else {
				$portrait = '';
			}
			$dbm->execute($que,array("dsssddd",(int)$row['uid'],(string)$row['uid'],($row['nickname'] ? $row['nickname'] : $row['name']),$portrait,BITWISE_USER,time(),time()));
			$dbm->commit();
			$row['taoginame'] = (string)$row['uid'];
			$row['display_name'] = ($row['nickname'] ? $row['nickname'] : $row['name']);
			$row['degree'] = BITWISE_USER;
			$row['portrait'] = $portrait;
		} else {
			$row['taoginame'] = $row2['taoginame'];
			$row['display_name'] = $row2['display_name'];
			$row['portrait'] = $row2['portrait'];
			if($authtoken || $row2['degree'] == BITWISE_ATHENTICATED) {
				$que = "UPDATE {user} SET taoginame = ?, display_name = ?, portrait = ?, degree = ?, last_login = ? WHERE uid = ?";
				$dbm->execute($que,array("sssddd",$row['taoginame'],$row['display_name'],$row['portrait'],BITWISE_USER,time(),$row['uid']));
				$row['degree'] = BITWISE_USER;
			} else {
				$que = "UPDATE {user} SET last_login = ? WHERE uid = ?";
				$dbm->execute($que,array("dd",time(),$row['uid']));
				$row['degree'] = $row2['degree'];
			}
			$dbm->commit();
		}

		self::auth_user($row);

		return 0;
	}

	function auth_user($row) {
		$_SESSION['identity']['taogi'] = $row['uid'];
		$_SESSION['user'] = array(
			'uid' => $row['uid'],
			'email_id' => $row['email_id'],
			'name' => $row['name'],
			'taoginame' => $row['taoginame'],
			'display_name' => $row['display_name'],
			'nickname' => $row['nickname'],
			'degree' => $row['degree'],
			'favicon' => $row['favicon'],
			'portrait' => $row['portrait'],
			'sns_id' => $row['sns_id'],
			'sns_site' => $row['sns_site']
		);
		if($row['degree'] >= BITWISE_EDITOR) {
			Acl::authorize($row['uid']);
		}
	}

	function openid_user($identity,$id) {
		$_SESSION['identity'][$identity] = $id;
		$_SESSION['identity']['taogi'] = FB_ID;
		$_SESSION['user']['uid'] = FB_ID;
	}

	function getPassword($email_id,$password) {
		$salt_len=7;
		$algo=PW_ALGO;

		$salt = crc32($email_id);
		$salt=substr($salt, 0, $salt_len);

		if (function_exists('hash') && in_array($algo, hash_algos())){
			$hashed=hash($algo, $salt.$password);
		} else {
			$hashed=sha1($salt.$password);
		}
		return $salt.$hashed;
	}

	function makeAuthtoken($password) {
		if($password) return sha1('JFE'.$password);
		else return sha1(strtolower(substr(base64_encode(rand(0x10000000, 0x70000000)), 3, 8)));
	}
}
?>
