<?php
class Filter extends Objects {

	//-----------------------------------------------------------------------------------
	//	Singleton constructor
	//-----------------------------------------------------------------------------------
	public static function instance() {
		return self::_instance(__CLASS__);
	}

	//-----------------------------------------------------------------------------------
	//	Utilities -- backend
	//-----------------------------------------------------------------------------------
	public static function buildLimitClause($limit=0,$page=0) {
		// prepare
		$clause = '';

		// build
		if($limit>=0) {
			$limit = $limit==0?ITEMS_PER_PAGE:$limit;
			$offset = $limit*(($page<=0?1:$page)-1);
			$clause = " LIMIT {$offset},{$limit}";
		}

		// return
		return $clause;
	}

	//-----------------------------------------------------------------------------------
	//	Utilities -- frontend
	//-----------------------------------------------------------------------------------
	public static function buildURL($link,$arguments) {
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

	public static function getExcerpt($string='',$length=20) {
		if(!$string) {
			return;
		}

		$string = strip_tags($string);
		$words = explode(' ',$string);
		if(count($words)>$length) {
			$chopped = array_slice($words,0,$length);
			$excerpt = implode(' ',$chopped).'...';
		} else {
			$excerpt = $string;
		}

		return $excerpt;
	}

	public static function getAbsoluteTime($time,$format='') {
		$format = $format?$format:DEFAULT_TIME_FORMAT;
		$time = date($format,$time);

		return $time;
	}

	public static function getRelativeTime($time,$format='') {

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

	public static function applyPatternByArray($row=array(),$pattern='',$key_before='%',$key_after='%') {
		if(!empty($row)) {
			foreach($row as $column => $value) {
				$column = $key_before.$column.$key_after;
				$data[$column] = $value;
			}
		}
		$result = str_replace(array_keys($data),array_values($data),$pattern);
		return $result;	
	}

	public static function buildAttributes($data=array(),$defaults=array(),$filter=false) {
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
	public static function rebuildColumnsByJoin($row=array(),$queue=array()) {
		if(!empty($queue)) {
			foreach($queue as $option) {
				$option['key_before'] = isset($option['key_before'])?$option['key_before']:'';
				$option['key_after'] = isset($option['key_after'])?$option['key_after']:'';

				$arguments = array();
				if(!empty($option['arguments'])) {
					foreach($option['arguments'] as $argument) {
						$arguments[] = $argument;
						unset($argument);
					}
				}
				if(!empty($option['arguments_eval'])) {
					foreach($option['arguments_eval'] as $argument_eval) {
						eval('$arguments[] = '.$argument_eval.';');
						unset($argument_eval);
					}
				}
				$result = call_user_func_array($option['callback'],$arguments);
				if(!empty($result)) {
					$join = array();
					foreach($result as $key => $value) {
						$key = $option['key_before'].$key.$option['key_after'];
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

	public static function rebuildColumnsByCallback($row=array(),$callbacks=array()) {
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

	public static function rebuildColumnsByPattern($row=array(),$patterns=array()) {
		if(!empty($patterns)) {
			foreach($patterns as $column => $pattern) {
				$row[$column] = self::applyPatternByArray($row,$pattern);
			}
		}
		return $row;
	}

	public static function rebuildColumns($row=array(),$options=array()) {
		if(!empty($options['join_callbacks'])) {
			$row = self::rebuildColumnsByJoin($row,$options['join_callbacks']);
		}
		if(!empty($options['column_callbacks'])) {
			$row = self::rebuildColumnsByCallback($row,$options['column_callbacks']);
		}
		if(!empty($options['column_patterns'])) {
			$row = self::rebuildColumnsByPattern($row,$options['column_patterns']);
		}

		return $row;
	}



}
?>
