<?php
class Acl extends Objects {
	private $predefinedrole;
	private $predefinedapptype;
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	protected function __construct() {
		global $FuchAclPreDefinedRole, $TaogiAclFromAppType;

		$this->predefinedrole = $FuchAclPreDefinedRole;
		$this->predefinedapptype = $TaogiAclFromAppType;
	}

	public function setAcl($taogi_id,$uri,$TaogiAcl) {
		if($TaogiAcl)
			$this->role = $this->predefinedrole[$TaogiAcl];
		else if($uri['appType'] && $this->predefinedapptype[$uri['appType']])
			$this->role = $this->predefinedrole[$this->predefinedapptype[$uri['appType']]];
		else
			$this->role = BITWISE_ANONYMOUS;

/*		if($taogi_id) {
			if($_SESSION['identity']['taogi']) {
				if($_SESSION['user']['uid'] > 0) {
					$_SESSION['acl']["taogi.$taogi_id"] = BITWISE_USER;
				} else {
					$_SESSION['acl']["taogi.$taogi_id"] = BITWISE_ATHENTICATED;
				}
			}
		} */
	}

	public function authorize($uid) {
		$dbm = DBM::instance();

		$que = "SELECT p.uid,p.level,e.eid FROM {privileges} AS p LEFT JOIN {entry} AS e ON p.eid = p.eid WHERE p.uid = ".$uid;
		while($row = $dbm->getFetchArray($que)) {
			$_SESSION['acl']['taogi.'.$row['eid']] = BITWISE_OWNER;
		}
	}

	function check($taogi_id) {
		if($taogi_id) {
			$bitwise = $this->getCurrentPrivilege($taogi_id);
			if($_SESSION['user']['degree'] != BITWISE_ADMINISTRATOR && $bitwise < $this->role) {
				if($this->role > BITWISE_ANONYMOUS && !$_SESSION['identity']['taogi']) {
					importLibrary('auth');
					requireMembership();
				} else {
					Error('접근 권한이 없습니다');
					exit;
				}
			}
		} else {
			if($this->role > BITWISE_ANONYMOUS && !$_SESSION['identity']['taogi']) {
				importLibrary('auth');
				requireMembership();
			}
			if($_SESSION['identity']['taogi'] && $this->role > $_SESSION['user']['degree']) {
				Error('접근 권한이 없습니다');
				exit;
			}
		}
	}

	function getCurrentPrivilege($taogi_id=0) {
		$context = Model_Context::instance();
		/* anonymouse 보다 작음 */
		switch($this->role) {
			case BITWISE_ADMINISTRATOR:
				if($_SESSION['user']['degree'] == 10) return BITWISE_ADMINISTRATOR;
				break;
			case BITWISE_OWNER:
			case BITWISE_EDITOR:
				if(!$taogi_id) return 0;
				if(Acl::isValid($taogi_id) && $_SESSION['acl']["taogi.$taogi_id"] >= $this->role) {
					return $_SESSION['acl']["taogi.$taogi_id"];
				}
				break;
			case BITWISE_USER:
				if($_SESSION['identity']['taogi'] > 0)
					return BITWISE_USER;
				break;
			case BITWISE_ATHENTICATED:
				if($_SESSION['identity']['taogi']) return BITWISE_ATHENTICATED;
				break;
			case BITWISE_ANONYMOUS:
				return BITWISE_ANONYMOUS;
				break;
		}
		return BITWISE_ANONYMOUS;
	}

	function getIdentity($domain) {
		if( empty($_SESSION['identity'][$domain]) ) {
			return null;
		}
		return $_SESSION['identity'][$domain];
	}

	function clearAcl() {
		if( isset( $_SESSION['acl'] ) ) {
			unset($_SESSION['acl']);
		}
	}

	function isValid($taogi_id) {
		if(!isset($_SESSION['acl']) ||
			!is_array($_SESSION['acl']) ||
			!isset($_SESSION['acl']["taogi.$taogi_id"])) {
			return false;
		}

		return true;
	}

	function imMaster() {
		if($_SESSION['user']['degree'] == 10) return 1;
		else return 0;
	}

	function checkAcl($eid,$role,$eq='ge') {
		$permission = false;
		switch($eq) {
			case 'ge':
				if($_SESSION['acl']['taogi.'.$eid] >= $role)
					$permission = true;
				break;
			case 'le':
				if($_SESSION['acl']['taogi.'.$eid] <= $role)
				$permission = true;
				break;
			case 'eq':
				default:
				if($_SESSION['acl']['taogi.'.$eid] == $role)
				$permission = true;
				break;
		}
		return $permission;
	}
}
?>
