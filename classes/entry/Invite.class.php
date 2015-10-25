<?php
class Entry_Invite extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function getList($eid,$limit=0,$page=0) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {invite} WHERE eid = ".$eid." ORDER BY id ASC".Filter::buildLimitClause(($limit ? $limit : -1),$page);
		$lists = array();
		while($row = $dbm->getFetchArray($que)) {
			$lists[] = Entry::fetchEntry($row);
		}

		for($i=0; $i<@count($lists); $i++) {
			if( $lists[$i]['uid'] ) {
				$row2 = User::getUserProfile($lists[$i]);
				$lists[$i] = array_merge($lists[$i],$row2);
			}
		}
		return $lists;
	}

	public static function getbyEmail($eid,$email) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {invite} WHERE eid = ".$eid." AND email_id = '".$email."'";
		$row = $dbm->getFetchArray($que);
		if($row['uid']) {
			$row = User::getUserProfile($row);
		}
		return $row;
	}

	public static function get($eid,$id) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {invite} WHERE eid = ".$eid." AND id = ".$id;
		$row = $dbm->getFetchArray($que);
		if($row['uid']) {
			$row = User::getUserProfile($row);
		}
		return $row;
	}

	public static function add($args) {
		$dbm = DBM::instance();
		$que = "INSERT INTO {invite} (`eid`,`uid`,`email_id`,`name`,`taoginame`,`display_name`,`degree`,`portrait`,`invite_date`,`authtoken`) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$dbm->execute($que,array("ddssssdsds",
			$args['eid'],
			($args['uid'] ? $args['uid'] : 0),
			$args['email_id'],
			$args['name'],
			$args['taoginame'],
			$args['display_name'],
			($args['degree'] ? $args['degree'] : BITWISE_ANONYMOUS),
			$args['portrait'],
			time(),
			$args['authtoken']
		));
	}

	public static function deletes($eid,$ids) {
		if(!is_array($ids) || @count($ids) < 1) return;
		$dbm = DBM::instance();
		$que = "DELETE FROM {invite} WHERE eid = ? AND id IN ( ? )";
		$dbm->execute( $que, array("ds",$eid,implode(",",$ids)) );
	}

	public static function deletebyUid($eid,$uid) {
		if(!$uid) return;
		$dbm = DBM::instance();
		$que = "DELETE FROM {invite} WHERE eid = ? AND uid = ?";
		$dbm->execute( $que, array( "dd", $eid, $uid ) );
	}

	public static function deletebyEmail($eid,$email) {
		$dbm = DBM::instance();
		$que = "DELETE FROM {invite} WHERE eid = ? AND email_id = ?";
		$dbm->execute( $que, array( "ds", $eid, $email ) );
	}
}
?>
