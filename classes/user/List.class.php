<?php
class User_List extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function getList($limit=0,$page=0) {
		$dbm = DBM::instance();
		$users_que = "SELECT uid FROM {user} ORDER BY reg_date DESC".Filter::buildLimitClause($limit,$page);
		while($row = $dbm->getFetchArray($users_que)) {
			$uids[] = $row['uid'];
		}
		$users = array();
		$list = array();
		foreach($uids as $uid) {
			$user = User::getUserProfile($uid);
			$user['entry_que'] = "SELECT e.*,r.* FROM {entry} AS e LEFT JOIN {revision} AS r ON e.vid = r.vid WHERE r.editor = '{$user['uid']}' LIMIT 1";

			$entry = $dbm->getFetchArray($user['entry_que']);
			if(!empty($entry)) {
				$entry = Entry::getEntryProfile($entry);
				$user = array_merge($entry,$user);
			}

			$list[] = $user;
		}

		return $list;
	}

	public static function getRecentList($limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT u.* FROM {user} AS u LEFT JOIN {revision} AS r ON (u.uid = r.editor) ORDER BY r.vid DESC".Filter::buildLimitClause($limit,$page);
		$list = array();
		while($row = $dbm->getFetchArray($que)) {
			$list[] = $row;
		}
		return $list;
	}

	public static function getNewList($limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {user} ORDER BY uid DESC".Filter::buildLimitClause($limit,$page);
		$lists = array();
		while($row = $dbm->getFetchArray($que)) {
			$lists[] = $row;
		}
		return $lists;
	}
}
?>
