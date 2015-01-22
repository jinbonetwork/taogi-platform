<?php
class Markup {

	//-----------------------------------------------------------------------------------
	//	Construction helpers
	//-----------------------------------------------------------------------------------
	
	/** Variables **/
	public $template; // kind of markup -- table, gallery, controls, form, ...
	public $context; // situation -- userEntries, entryRevisions, entryAuthors, ...
	public $type; // type of data -- entry, revision, user, ...
	public $scope; // key field -- eid, vid, uid, ...
	public $options;
	public $instance;

	/** Methods **/
	public function construct($context='',$type='',$scope='') {
		$this->context = $context;
		$this->type = $type;
		$this->scope = $scope;
	}

	//-----------------------------------------------------------------------------------
	//	Utilities
	//-----------------------------------------------------------------------------------
	public function buildURL($link,$arguments) {
		if(!empty($arguments)) {
			$trails = array();
			foreach($arguments as $key => $value) {
				$trails[] = urlencode($key).'='.urlencode($value);
			}
			$trail = implode('&',$trails);
		}
		$link .= (strpos($link,'?')===false?'?':'&').$trail;
		return $link;
	}

	public function getRelativeTime($time) {

		$second = '초';
		$minute = '분';
		$hour = '시간';
		$day = '일';
		$week = '주';
		$month = '개월';
		$year = '년';
		$plural = ''; // default is 's'
		$beforeUnit = ''; // default is ' '
		$ago = '전';
		$left = '남음';

		$d[0] = array(1,$second);
		$d[1] = array(60,$minute);
		$d[2] = array(3600,$hour);
		$d[3] = array(86400,$day);
		$d[4] = array(604800,$week);
		$d[5] = array(2592000,$month);
		$d[6] = array(31104000,$year);

		$w = array();

		$return = "";
		$now = time();
		$diff = ($now-$time);
		$secondsLeft = $diff;

		for($i=6;$i>-1;$i--) {
			$w[$i] = intval($secondsLeft/$d[$i][0]);
			$secondsLeft -= ($w[$i]*$d[$i][0]);
			if($w[$i]!=0) {
				$return.= abs($w[$i]) . $beforeUnit . $d[$i][1] . (($w[$i]>1)?$plural:'') ." ";
			}
		}

		$return .= ($diff>0)?$ago:$left;
		return $return;
	}

	public function applyPatternByArray($row=array(),$pattern='',$key_before='%',$key_after='%') {
		if(!empty($row)) {
			foreach($row as $column => $value) {
				$column = $key_before.$column.$key_after;
				$data[$column] = $value;
			}
		}
		$result = str_replace(array_keys($data),array_values($data),$pattern);
		return $result;	
	}

	public function buildAttributes($data=array(),$defaults=array(),$filter=false) {
		if(empty($data)) {
			if(empty($defaults)) {
				return;
			} else {
				$data = $defaults;
			}
		}
		if(!is_array($data)) {
			return;
		}

		$attributes = array_merge($defaults,$data);
		if($filter) {
			$attributes = array_filter($attributes,'trim');
		}
		if(!empty($defaults)) {
			$attributes = array_intersect_key($attributes,$defaults);
		}
		foreach($attributes as $attribute => $property) {
			if(is_array($property)) {
				$property=implode(' ',$property);
			} else if(!is_string($property)&&!is_numeric($property)) {
				continue;
			}
			$markup_attribute[] = "{$attribute}=\"{$property}\"";
		}
		$attributes = ' '.implode(' ',$markup_attribute);

		return $attributes;
	}

	//-----------------------------------------------------------------------------
	//	Filters
	//-----------------------------------------------------------------------------
	public function rebuildColumnsByJoin($row=array(),$callbacks=array()) {
		if(!empty($callbacks)) {
			foreach($callbacks as $entry) {
				$entry['key_before'] = isset($entry['key_before'])?$entry['key_before']:'';
				$entry['key_after'] = isset($entry['key_after'])?$entry['key_after']:'';

				$arguments = array();
				if(!empty($entry['arguments'])) {
					foreach($entry['arguments'] as $argument) {
						$arguments[] = $argument;
						unset($argument);
					}
				}
				if(!empty($entry['arguments_eval'])) {
					foreach($entry['arguments_eval'] as $argument_eval) {
						eval('$arguments[] = '.$argument_eval.';');
						unset($argument_eval);
					}
				}
				$result = call_user_func_array($entry['callback'],$arguments);
				if(!empty($result)) {
					$join = array();
					foreach($result as $key => $value) {
						$key = $entry['key_before'].$key.$entry['key_after'];
						$join[$key] = $value;
					}
					$row = array_merge($row,$join);
				}
				unset($result);
				unset($join);
			}
		}
		return $row;
	}

	public function rebuildColumnsByCallback($row=array(),$callbacks=array()) {
		if(!empty($callbacks)) {
			foreach($callbacks as $column => $callback) {
				if(!$callback){
					$row[$column] = '<!--empty callback-->';
				}
				if(is_array($callback)) {
					$object = $callback[0];
					$method = $callback[1];
				}
				$is_valid_callback = is_array($callback)?method_exists($object,$method):function_exists($callback);
				if($is_valid_callback) {	
					$row[$column] = is_array($callback)?call_user_func(array($object,$method),$row):call_user_func($callback,$row);
				} else {
					$row[$column] = "Invalid function call: ".is_array($callback)?get_class($object).'->'.$method:$callback;
				}
			}
		}
		return $row;
	}

	public function rebuildColumnsByPattern($row=array(),$patterns=array()) {
		if(!empty($patterns)) {
			foreach($patterns as $column => $pattern) {
				$row[$column] = $this->applyPatternByArray($row,$pattern);
			}
		}
		return $row;
	}

	public function rebuildColumns($row=array(),$options=array()) {
		if(!empty($options['join_callbacks'])) {
			$row = $this->rebuildColumnsByJoin($row,$options['join_callbacks']);
		}
		if(!empty($options['column_callbacks'])) {
			$row = $this->rebuildColumnsByCallback($row,$options['column_callbacks']);
		}
		if(!empty($options['column_patterns'])) {
			$row = $this->rebuildColumnsByPattern($row,$options['column_patterns']);
		}

		return $row;
	}

	//-----------------------------------------------------------------------------
	//	Global wrapper
	//-----------------------------------------------------------------------------

	public function getPermalink($user,$entry=0,$arguments=array()) {
		$context = Model_Context::instance();
		$permalink = 'http'.($context->getProperty('service.ssl')==true?'s':'').'://'.$context->getProperty('service.domain');
		if(is_numeric($user)) {
			$user = User::getUser($user,1);
		}
		$permalink .= '/'.$user['taoginame'];
		if($entry) {
			if(is_numeric($entry)) {
				$entry = Entry::getEntryInfoByID($entry,1);
			}
			$permalink .= '/'.$entry['nickname'];
		}
		if(!empty($arguments)) {
			$permalink .= $this->buildURL('',$arguments);
		}
		return $permalink;
	}
	public function getEntry($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}

		$entry = $this->rebuildColumnsByJoin($entry,array(
			'owner' => array(
				'callback' => array($this,'getUser'),
				'arguments' => array($entry['owner']),
				//'arguments_eval' => array('$row[\'owner\']'),
				'key_before' => 'owner_',
			),
			'editor' => array(
				'callback' => array($this,'getUser'),
				'arguments' => array($entry['editor']),
				//'arguments_eval' => array('$row[\'editor\']'),
				'key_before' => 'editor_',
			),
		));

		$entry['permalink'] = $this->getPermalink($entry['owner'],$entry['eid']);
		$entry['image'] = $this->getEntryImage($entry);
		$entry['created_absolute'] = $this->getEntryCreatedTimeAbsolute($entry);
		$entry['created_relative'] = $this->getEntryCreatedTimeRelative($entry);
		$entry['updated_absolute'] = $this->getEntryUpdatedTimeAbsolute($entry);
		$entry['updated_relative'] = $this->getEntryUpdatedTimeRelative($entry);

		return $entry;
	}

	public function getEntryImage($entry) {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		if($entry['asset']) {
			$image = $entry['asset']['media'];
		}
		if(!$image) {
			$image = DEFAULT_ENTRY_IMAGE;
		}
		return $image;
	}

	public function getEntryCreatedTimeAbsolute($entry,$format='') {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$format = $format?$format:DEFAULT_TIME_FORMAT;
		$time = date($format,$entry['published']);
		return $time;
	}

	public function getEntryCreatedTimeRelative($entry,$format='') {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$format = $format?$format:DEFAULT_TIME_FORMAT;
		$time = $this->getRelativeTime($entry['published'],$format);
		return $time;
	}
	public function getEntryUpdatedTimeAbsolute($entry,$format='') {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$format = $format?$format:DEFAULT_TIME_FORMAT;
		$time = date($format,$entry['modified']);
		return $time;
	}

	public function getEntryUpdatedTimeRelative($entry,$format='') {
		if(is_numeric($entry)) {
			$context = Model_Context::instance();
			$entry = Entry::getEntryInfoByID($entry,1);
		}
		$format = $format?$format:DEFAULT_TIME_FORMAT;
		$time = $this->getRelativeTime($entry['modified'],$format);
		return $time;
	}

	public function getRevision($revision) {
		return $this->getEntry($revision);
	}

	public function getUser($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = User::getUser($user,1);
		}
		$user['DISPLAY_NAME'] = $this->getUserDisplayName($user);
		$user['ROLE'] = $this->getUserRole($user);
		$user['image'] = $this->getUserImage($user);

		$user['dashboard_link'] = $this->getPermalink($user['uid']).'/';
		$user['profile_link'] = $this->getPermalink($user['uid']).'/profile';
		$user['archives_link'] = $this->getPermalink($user['uid']).'/archives';
		$user['bookmarks_link'] = $this->getPermalink($user['uid']).'/bookmarks';

		return $user;
	}

	public function getUserImage($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = User::getUser($user,1);
		}
		$image = $user['portrait'];
		if(!$image) {
			$image = DEFAULT_USER_IMAGE;
		}
		return $image;
	}

	public function getUserDisplayName($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = User::getUser($user,1);
		}
		$name = $user['display_name'];
		$name = !$name?$user['nickname']:$name;
		$name = !$name?$user['name']:$name;
		$name = !$name?$user['taoginame']:$name;

		return $name;
	}

	public function getUserRole($user) {
		if(is_numeric($user)) {
			$context = Model_Context::instance();
			$user = User::getUser($user,1);
		}
		global $FuchAclPreDefinedRole,$FuchAclPreDefinedRoleLabel;
		$roles = array_flip($FuchAclPreDefinedRole);
		$labels = $FuchAclPreDefinedRoleLabel;
		$role = $roles[$user['degree']];
		$label = $labels[$role];

		return $label;
	}

}

?>
