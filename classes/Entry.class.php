<?php
class Entry extends Objects {
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function getEntryIDByName($name,$owner) {
		$que = "SELECT * FROM {entry} WHERE owner = ".$owner." AND nickname = '".trim($name)."'";
		$dbm = DBM::instance();
		$row = $dbm->getFetchArray($que);
		if($row) {
			$context = Model_Context::instance();
			$context->setProperty('entry.'.$row['eid'],$row);
		}
		return $row['eid'];
	}

	public static function getEntryInfoByID($eid,$context_opt) {
		$context = Model_Context::instance();
		if($context_opt) {
			$entry = $context->getProperty('entry.'.$eid);
		}
		if(!$entry) {
			$dbm = DBM::instance();
			$que = "SELECT * FROM {entry} WHERE eid = ".$eid;
			$entry = self::fetchEntry($dbm->getFetchArray($que));
			if($context_opt) {
				$context->setProperty('entry.'.$eid,$entry);
			}
		}
		return $entry;
	}

	public static function getEntryData($eid,$vid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {revision} WHERE vid = $vid AND eid = $eid";
		$revision = self::fetchRevision($dbm->getFetchArray($que));
		return $revision;
	}

	public static function getEntryExtra($eid) {
		$dbm = DBM::instance();
		$que = "SELECT * FROM {entryExtra} WHERE eid = $eid";
		while($row = $dbm->getFetchArray($que)) {
			$extra[$row['name']] = $row['val'];
		}
		return $extra;
	}

	public static function checkPermalink($uid,$nickname) {
		$dbm = DBM::instance();
		$que = "SELECT eid FROM {entry} WHERE owner = ".$uid." AND nickname = '".addslashes($nickname)."'";
		$row = $dbm->getFetchArray($que);
		return ($row['eid'] ? $row['eid'] : 0);
	}

	public static function searchByNickname($uid,$nickname) {
		$dbm = DBM::instance();
		$que = "SELECT eid FROM {entry} WHERE owner = ".$uid." AND nickname = '".addslashes($nickname)."'";
		$row = self::fetchEntry($dbm->getFetchArray($que));
		return $row;
	}

	public static function fetchEntry($entry) {
		if($entry) {
			$entry['asset'] = unserialize($entry['asset']);
			$entry['era'] = unserialize($entry['era']);
		}
		return $entry;
	}

	public static function fetchRevision($revision) {
		if($revision) {
			$revision['timeline'] = base64_decode($revision['timeline']);
		}
		return $revision;
	}

	public static function checkValidPermalink($permalink) {
		return preg_match("/^[0-9a-zA-Z\-_\.]+$/i",$permalink);
	}

	//-----------------------------------------------------------------------------------
	//	Utilities
	//-----------------------------------------------------------------------------------
	public static function getEntryLink($entry) {
		$context = Model_Context::instance();
		$protocol = 'http'.($context->getProperty('service.ssl')==true?'s':'');
		$domain = $context->getProperty('service.domain');

		if(is_numeric($entry)) {
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$user = User::getUser($entry['owner'],1);
		$userSlug = $user['taoginame'];
		$entrySlug = $entry['nickname'];

		$link = $protocol.'://'.$domain.'/'.$userSlug.'/'.$entrySlug;

		return $link;
	}

	public static function getEntryImage($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		if(isset($entry['asset']['media'])) {
			$image = $entry['asset']['media'];
		}
		if(!$image) {
			$image = DEFAULT_ENTRY_IMAGE;
		}
		return $image;
	}

	public static function getEntryBackground($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		if(isset($entry['asset']['media'])) {
			$background = $entry['asset']['media'];
		}
		if(!$background) {
			$background = DEFAULT_ENTRY_BACKGROUND;
		}
		return $background;
	}

	public static function getEditors($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$_editors = User_DBM::getEditors($entry['eid']);
		$editors = array();
		if(!empty($_editors)) {
			$dbm = DBM::instance();
			$que = "SELECT r.*,e.owner,e.nickname FROM {revision} AS r LEFT JOIN {entry} AS e ON ( e.eid = r.eid ) WHERE e.eid='{$entry['eid']}' AND r.editor='%s' ORDER BY r.vid DESC LIMIT 1";
			foreach($_editors as $editor) {
				$editor = User::getUserProfile($editor['uid']);
				while($revision = $dbm->getFetchArray(sprintf($que,$editor['uid']))) {
					$revision = Entry::getEntryProfile(Entry::fetchRevision($revision));
					$editor = array_merge($editor,$revision);
				}
				$editors[] = $editor;
			}
		}
		return $editors;
	}
	
	//-----------------------------------------------------------------------------------
	//	Filters
	//-----------------------------------------------------------------------------------
	public static function getEntryProfile($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		if(empty($entry)) {
			return PAGE_NOT_FOUND;
		}
		if(!is_array($entry)) {
			return INVALID_DATA_FORMAT;
		}
		if(!isset($entry['owner_uid'])) {
			$entry = Filter::rebuildColumnsByJoin($entry,array(
				'owner' => array(
					'callback' => 'User::getUserProfile',
					'arguments' => array($entry['owner']),
					//'arguments_eval' => array('$row[\'owner\']'),
					'key_before' => 'owner_',
				),
				'editor' => array(
					'callback' => 'User::getUserProfile',
					'arguments' => array($entry['editor']),
					//'arguments_eval' => array('$row[\'editor\']'),
					'key_before' => 'editor_',
				),
			));

			$entry['excerpt'] = Filter::getExcerpt($entry['summary']);
			$entry['permalink'] = self::getEntryLink($entry);
			$entry['image'] = self::getEntryImage($entry);
			$entry['COVERTAG'] = $entry['image']?"<img class=\"cover\" src=\"{$entry['image']}\">":'';
			$entry['background'] = self::getEntryBackground($entry);
			$entry['created_absolute'] = Filter::getAbsoluteTime($entry['published']);
			$entry['created_relative'] = ((time()-$entry['published'])>RELATIVE_TIME_COVERAGE)?$entry['created_absolute']:Filter::getRelativeTime($entry['published']);
			$entry['updated_absolute'] = Filter::getAbsoluteTime($entry['modified']);
			$entry['updated_relative'] = ((time()-$entry['modified'])>RELATIVE_TIME_COVERAGE)?$entry['updated_absolute']:Filter::getRelativeTime($entry['modified']);

			$entry['VERSION_LINKED'] = $entry['vid']?'(<a href="'.$entry['permalink'].'?vid='.$entry['vid'].'">#'.$entry['vid'].'</a>)':'';
		}

		return $entry;
	}

	public static function getEntryProfiles($entries) {
		if(empty($entries)) {
			return PAGE_NOT_FOUND;
		}
		if(!is_array($entries)) {
			return INVALID_DATA_FORMAT;
		}

		$filtered = array();
		foreach($entries as $entry) {
			$filtered[] = self::getEntryProfile($entry);
		}

		return $filtered;
	}
}
?>
