<?php
class User_Invites extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function getList($eid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {invite} WHERE eid = ".$eid." ORDER BY id ASC";
		while($row = $dbm->getFetchArray($que)) {
			$invites[] = $row;
		}

		return $invites;
	}
}
