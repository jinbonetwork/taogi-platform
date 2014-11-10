<?php
class Markup_Profile extends Markup {

	public $user;
	public $userTabs;
	public $userVcard;
	public $userInfo;
	public $userForm;

	//-----------------------------------------------------------------------------------
	//	Constructor
	//-----------------------------------------------------------------------------------
	function __construct($row) {
		$row = $this->checkUser($row);
		$this->user = $row;

		$this->userTabs = array(
			'dashboard' => array(
				'href' => '',
				'label' => '대시보드',
			),
			'profile' => array(
				'href' => '/profile',
				'label' => '프로필',
			),
			'archives' => array(
				'href' => '/archives',
				'label' => '아카이브',
			),
		);

		$this->userVcard = array(
			'DISPLAY_NAME' => array(
				'name' => 'display_name',
			),
			'image' => array(
				'name' => 'portrait',
				'class' => array('keepRatio'),
			),
			'description' => array(
				'name' => 'description',
			),
		);

		$this->userInfo = array(
			'taoginame' => array(
				'name' => 'taoginame',
				'label' => '아이디',
			),
			'email_id' => array(
				'name' => 'email_id',
				'label' => '이메일',
			),
		);

		$this->userForm = new Markup_Form('userForm','uid');
	}

	//-----------------------------------------------------------------------------------
	//	Utilities
	//-----------------------------------------------------------------------------------
	public function checkUser($user=array()) {
		if(empty($user)) {
			$user = $this->user;
		}
		if(is_numeric($user)) {
			$user = $this->getUser($user);
		}
		if(!isset($user['DISPLAY_NAME'])) {
			$user = $this->getUser($user);
		}
		if(empty($user)) {
			return DATA_NOT_FOUND; 
		}

		return $user;
	}

	public function buildFields($items=array(),$structure=array()) {
		$markup = '';

		if(empty($items)) {
			return;
		}
		$structure = $structure?$structure:$items;

		foreach($structure as $field => $property) {
			$value = $field!='password'?$items[$field]:'';
			$property['class'] = isset($property['class'])?$property['class']:array();
			$property['class'] = array_unique(array_merge($property['class'],array('value')));

			foreach($property as $pk => $pv) {
				$pk = !in_array($pk,array('id','class',))?'data-'.$pk:$pk;
				$_property[$pk] = $pv;
			}
			$attributes = $this->buildAttributes($_property);

			$markup[] = '<div class="'.$field.'">'.PHP_EOL;
			$markup[] = '<label>'.$property['label'].'</label>'.PHP_EOL;
			$markup[] = '<div '.$attributes.'>'.$value.'</div>'.PHP_EOL;
			$markup[] = '</div><!--/.'.$key.'-->'.PHP_EOL;

			unset($value);
		}

		$markup = implode('',$markup);

		return $markup;
	}

	//-----------------------------------------------------------------------------------
	//	Processor
	//-----------------------------------------------------------------------------------
	public function buildUserTabs($row=array(),$current='') {
		$row = $this->checkUser($row);
		$this->instance++;
		$markup[] = '<div class="user-tabs ui-tabs-container" data-instance="'.$this->instance.'">'.PHP_EOL;
		$markup[] = '<ul class="ui-tabs">'.PHP_EOL;
		foreach($this->userTabs as $userTab => $link) {
			$is_current = $userTab==$current?' current':'';
			$markup[] = '<li class="user-tab ui-tab '.$userTab.$is_current.'"><a href="'.$this->getPermalink($row['uid']).$link['href'].'"><span>'.$link['label'].'</span></a></li>'.PHP_EOL;
		}
		$markup[] = '</ul>'.PHP_EOL;
		$markup[] = '</div><!--/.user-tabs-->'.PHP_EOL;

		$markup = implode('',$markup);
		return $markup;
	}

	public function getUserTabs($row=array(),$current='') {
		return $this->buildUserTabs($row,$current);
	}

	public function printUserTabs($row=array(),$current='') {
		echo $this->getUserTabs($row,$current);
	}

	public function buildVcard($row=array()) {
		$row = $this->checkUser($row);
		$this->instance++;

		$markup[] = '<div class="userVcard" data-instance="'.$this->instance.'">'.PHP_EOL;
		$markup[] = $this->buildFields($row,$this->userVcard);
		$markup[] = '</div><!--/.userVcard-->'.PHP_EOL;

		$markup = implode('',$markup);
		return $markup;
	}

	public function getVcard($row=array()) {
		return $this->buildVcard($row);
	}

	public function printVcard($row=array()) {
		echo $this->getVcard($row);
	}

	public function buildUserinfo($row=array()) {
		$markup = '';
		$row = $this->checkUser($row);

		$this->instance++;
		$markup[] = '<div class="userInfo" data-instance="'.$this->instance.'">'.PHP_EOL;
		$markup[] = $this->buildFields($row,$this->userInfo);
		$markup[] = '</div><!--/.userInfo-->'.PHP_EOL;

		$markup = implode('',$markup);

		return $markup;
	}

	public function getUserinfo($row=array()) {
		return $this->buildUserinfo($row);
	}

	public function printUserinfo($row=array()) {
		echo $this->getUserinfo($row);
	}
}
?>
