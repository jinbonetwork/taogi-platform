<?php
class Entry_List extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function buildLimitClause($limit=0,$page=0) {
		// defaults
		$limit = $limit==0?6:$limit;
		$page = $page==0?1:$page;

		// prepare
		$clause = '';

		// build
		if($limit!=-1) {
			$clause = " LIMIT ".($limit * ($page-1)).",".$limit;
		}

		// return
		return $clause;
	}

	public static function getRecentList($limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT e.*,r.* FROM {entry} AS e LEFT JOIN {revision} AS r ON (e.vid = r.vid) WHERE is_public = 2 ORDER BY e.vid DESC".self::buildLimitClause($limit,$page);
		$lists = array();
		while($row = $dbm->getFetchArray($que)) {
			$lists[] = Entry::fetchEntry($row);
		}
		return $lists;
	}

	public static function getRevisionList($eid,$limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT r.*,e.owner,e.nickname FROM {revision} AS r LEFT JOIN {entry} AS e ON (e.eid = r.eid) WHERE r.eid = ".$eid." ORDER BY r.vid DESC".self::buildLimitClause($limit,$page);
		$list = array();
		while($row = $dbm->getFetchArray($que)) {
			$list[] = Entry::fetchRevision($row);
		}
		return $list;
	}

	public static function getOwnList($uid,$limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {entry} AS e LEFT JOIN {revision} AS r ON (e.eid = r.eid AND e.vid = r.vid) WHERE e.owner = ".$uid.self::buildLimitClause($limit,$page);
		$list = array();
		while($row = $dbm->getFetchArray($que)) {
			$list[] = Entry::fetchRevision(Entry::fetchEntry($row));
		}
		return $list;
	}

	public static function getEditList($uid,$limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {privileges} AS p LEFT JOIN {entry} AS e ON p.eid = e.eid LEFT JOIN {revision} AS r ON (e.eid = r.eid AND e.vid = r.vid) WHERE p.uid = ".$uid.self::buildLimitClause($limit,$page);
		$list = array();
		while($row = $dbm->getFetchArray($que)) {
			$list[] = Entry::fetchRevision(Entry::fetchEntry($row));
		}
		return $list;
	}
}
?>
