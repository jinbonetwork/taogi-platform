<?php
class Auth extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function authenticate($email_id,$password,$authtoken) {
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
			if($row['password'] != self::getPassword($email_id,$password)) {
				if($row['password'] != self::getPassword($email_id,$password,true))
					return -2;
			}
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

	public static function auth_user($row) {
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
		Acl::authorize($row['uid']);
	}

	public static function openid_user($identity,$id) {
		$_SESSION['identity'][$identity] = $id;
		$_SESSION['identity']['taogi'] = FB_ID;
		$_SESSION['user']['uid'] = FB_ID;
	}

	public static function getPassword($email_id,$password,$bitbybit=false) {
		$salt_len=7;
		$algo=PW_ALGO;

		if($bitbybit) {
			$salt = self::bitbybit_crc32($email_id);
		} else {
			$salt = crc32($email_id);
		}
		$salt=substr($salt, 0, $salt_len);

		if (function_exists('hash') && in_array($algo, hash_algos())){
			$hashed=hash($algo, $salt.$password);
		} else {
			$hashed=sha1($salt.$password);
		}
		return $salt.$hashed;
	}

	public static function makeAuthtoken($password) {
		if($password) return sha1('JFE'.$password);
		else return sha1(strtolower(substr(base64_encode(rand(0x10000000, 0x70000000)), 3, 8)));
	}

	private static function is64Bits() {
		return strlen(decbin(~0)) == 64;
	}

	private static function bitbybit_crc32($str,$first_call=false){

		//reflection in 32 bits of crc32 polynomial 0x04C11DB7
		$poly_reflected=0xEDB88320;

		//=0xFFFFFFFF; //keep track of register value after each call
		static $reg=0xFFFFFFFF;

		//initialize register on first call
		if($first_call) $reg=0xFFFFFFFF;

		$n=strlen($str);
		$zeros=$n<4 ? $n : 4;

		//xor first $zeros=min(4,strlen($str)) bytes into the register
		for($i=0;$i<$zeros;$i++)
			$reg^=ord($str{$i})<<$i*8;

		//now for the rest of the string
		for($i=4;$i<$n;$i++){
			$next_char=ord($str{$i});
			for($j=0;$j<8;$j++)
				$reg=(($reg>>1&0x7FFFFFFF)|($next_char>>$j&1)<<0x1F)
					^($reg&1)*$poly_reflected;
		}

		//put in enough zeros at the end
		for($i=0;$i<$zeros*8;$i++)
			$reg=($reg>>1&0x7FFFFFFF)^($reg&1)*$poly_reflected;

		//xor the register with 0xFFFFFFFF
		return ~$reg;
	}
}
?>
