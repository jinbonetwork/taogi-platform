<?php
class Markup_Controls extends Markup {

	public $parant;
	public $baselink;

	function __construct($context='',$type='',$scope='',&$parent=null) {
		$this->construct($context,$type,$scope);
		if(is_object($parent)) {
			$this->parent = $parent;
			$this->template = $parent->template;
		} else {
			$this->parent = null;
			$this->template = 'controls';
		}
		$this->options = $this->getDefaultOptions();
	}

	function getDefaultOptions() {
		$options = null;

		switch($this->context) {
		case 'entries':
		case 'userEntries':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '이 타임라인을',
				'instance_field' => 'eid',
			);
			break;
		case 'selectedUserEntries':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '선택한 타임라인을',
				'instance_field' => 'eid',
			);
			break;
		case 'revisions':
		case 'entryRevisions':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '이 버전을',
				'instance_field' => 'vid',
			);
			break;
		case 'selectedEntryRevisions':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '선택한 버전을',
				'instance_field' => 'vid',
			);
			break;
		case 'users':
		case 'entryAuthors':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '이 사용자를',
				'instance_field' => 'uid',
			);
			break;
		case 'selectedEntryAuthors':
			$options = array(
				'class' => array('icon','label','inline'),
				'title' => '선택한 사용자를',
				'instance_field' => 'uid',
			);
			break;
		case 'entrySidebar':
		default:
			$options = array(
				'class' => array('icon','label','list'),
				'title' => '이 타임라인 관리',
				'instance_field' => 'eid',
			);
			break;
		}
		if($this->template=='gallery') {
			$options['class'] = array('icon','inline');
		}
		$options['data-template'] = $this->template;
		$options['data-context'] = $this->context;
		$options['data-type'] = $this->type;
		$options['data-scope'] = $this->scope;

		return $options;
	}

	public function getDefaultStructure_entries($row=array()) {
		return $this->getDefaultStructure_userEntries($row);
	}

	public function getDefaultStructure_selectedEntries($row=array()) {
		return $this->getDefaultStructure_userEntries($row);
	}

	public function getDefaultStructure_entrySidebar($row=array()) {
		return $this->getDefaultStructure_userEntries($row);
	}

	public function getDefaultStructure_userEntries($row=array()) {
		$this->baselink = $this->getPermalink($row['owner'],$row['eid']);
		$structure = array();

		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR)) {
			$structure['manage']['manage edit'] = array(
				'href' => $this->baselink.'/modify',
				'title' => '타임라인을 편집합니다. 편집권한이 있는 사용자만 실행할 수 있습니다.',
				'label' => '편집하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR)) {
			$structure['manage']['manage history'] = array(
				'href' => $this->baselink.'/revisions',
				'title' => '타임라인의 편집 이력을 봅니다. 편집권한이 있는 사용자만 실행할 수 있습니다.',
				'label' => '편집이력 보기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['manage']['manage authors'] = array(
				'href' => $this->baselink.'/authors',
				'title' => '편집그룹을 관리합니다. 개설자와 관리자만 실행할 수 있습니다.',
				'label' => '편집그룹 관리',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR,'eq')) {
			$structure['manage']['critical leave'] = array(
				'href' => $this->baselink.'/resign',
				'title' => '편집그룹에서 탈퇴합니다. 개설자는 탈퇴할 수 없습니다.',
				'label' => '편집그룹 탈퇴하기',
			);
		}

		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['status']['status public'.($row['is_public']==NODE_STATUS_PUBLIC?' current':'')] = array(
				'href' => $this->baselink.'/status?status=2',
				'title' => '타임라인을 배포하면 따오기 첫 페이지에 공개하고 검색엔진의 접근을 허용합니다.',
				'label' => '배포하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['status']['status open'.($row['is_public']==NODE_STATUS_OPEN?' current':'')] = array(
				'href' => $this->baselink.'/status?status=1',
				'title' => '타임라인을 공개하면 방문객이 타임라인을 읽을 수 있습니다.',
				'label' => '공개하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['status']['status private'.($row['is_public']==NODE_STATUS_PRIVATE?' current':'')] = array(
				'href' => $this->baselink.'/status?status=0',
				'title' => '타임라인을 감추면 편집자만 타임라인을 읽을 수 있습니다.',
				'label' => '감추기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['status']['critical delete'] = array(
				'href' => $this->baselink.'/delete',
				'title' => '타임라인을 삭제합니다. 개설자만 실행할 수 있습니다.',
				'label' => '삭제하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_OWNER)) {
			$structure['maintenance']['status forkable'.($row['is_forkable']?' current':'')] = array(
				'href' => $this->baselink.'/status?forkable=1',
				'title' => '다른 사용자가 이 타임라인을 복사할 수 있습니다.',
				'label' => '복사 허락',
			);
			$structure['maintenance']['status notForkable'.($row['is_forkable']?'':' current')] = array(
				'href' => $this->baselink.'/status?forkable=0',
				'title' => '다른 사용자가 이 타임라인을 복사할 수 없습니다.',
				'label' => '복사 금지',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_USER)) {
			$structure['maintenance']['maintenance fork'] = array(
				'href' => $this->baselink.'/fork',
				'title' => '이 타임라인을 내 계정으로 복사합니다.',
				'label' => '복사하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR)) {
			$structure['maintenance']['maintenance backup'] = array(
				'href' => $this->baselink.'/backup',
				'title' => '이 타임라인을 백업합니다. (압축파일로 내려받기)',
				'label' => '백업하기',
			);
		}

		return $structure;
	}
	
	public function getDefaultStructure_selectedUserEntries($row=array()) {
		$structure = array();
		$this->baselink = '#';

		$structure['status']['status public'.($is_public?' current':'')] = array(
			'href' => 'javascript://',
			'title' => '타임라인을 배포하면 따오기 첫 페이지에 공개하고 검색엔진의 접근을 허용합니다.',
			'label' => '배포하기',
			'data-action' => 'status',
			'data-key' => 'status',
			'data-value' => '2',
		);
		$structure['status']['status open'.($is_open?' current':'')] = array(
			'href' => 'javascript://',
			'title' => '타임라인을 공개하면 방문객이 타임라인을 읽을 수 있습니다.',
			'label' => '공개하기',
			'data-action' => 'status',
			'data-key' => 'status',
			'data-value' => '1',
		);
		$structure['status']['status private'.($is_private?' current':'')] = array(
			'href' => 'javascript://',
			'title' => '타임라인을 감추면 편집자만 타임라인을 읽을 수 있습니다.',
			'label' => '감추기',
			'data-action' => 'status',
			'data-key' => 'status',
			'data-value' => '0',
		);
		$structure['status']['critical delete'] = array(
			'href' => 'javascript://',
			'title' => '타임라인을 삭제합니다. 개설자만 실행할 수 있습니다.',
			'label' => '삭제하기',
			'data-action' => 'delete',
		);

		return $structure;
	}

	public function getDefaultStructure_revisions($row=array()) {
		return $this->getDefaultStructure_entryRevisions($row);
	}

	public function getDefaultStructure_selectedRevisions($row=array()) {
		return $this->getDefaultStructure_selectedEntryRevisions($row);
	}

	public function getDefaultStructure_entryRevisions($row=array()) {
		$structure = array();
		$this->baselink = $this->getPermalink($row['owner'],$row['eid']);

		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR)) {
			$structure['status']['critical delete'] = array(
				'href' => $this->baselink.'/revisions/delete?vid='.$row['vid'],
				'title' => '이 버전을 삭제합니다. 편집자만 실행할 수 있습니다.',
				'label' => '삭제하기',
			);
		}
		if($this->ACL->checkAcl($row['eid'],BITWISE_EDITOR)) {
			$structure['maintenance']['critical maintenance restore'] = array(
				'href' => $this->baselink.'/revisions/restore?vid='.$row['vid'],
				'title' => '이 버전으로 복구합니다. 편집자만 실행할 수 있습니다.',
				'label' => '복구하기',
			);
		}

		return $structure;
	}

	public function getDefaultStructure_selectedEntryRevisions($row=array()) {
		$structure = array();
		$this->baselink = '#';

		$structure['status']['critical delete'] = array(
			'href' => 'javascript://',
			'title' => '선택한 버전을 삭제합니다. 편집자만 실행할 수 있습니다.',
			'label' => '삭제하기',
			'data-action' => 'delete',
		);

		return $structure;
	}

	public function getDefaultStructure_users($row=array()) {
		$structure = array();
		$this->baselink = '#';

		global $user;
		if($user['degree']>=BITWISE_ADMINISTRATOR) {
			$structure['status']['critical delete'] = array(
				'href' => $this->baselink.'/delete',
				'title' => '이 사용자를 사이트에서 삭제합니다. 사이트 관리자만 실행할 수 있습니다.',
				'label' => '삭제하기',
			);
		}

		return $structure;
	}

	public function getDefaultStructure_selectedUsers($row=array()) {
		$structure = array();
		$this->baselink = '#';

		global $user;
		if($user['degree']>=BITWISE_ADMINISTRATOR) {
			$structure['status']['critical delete'] = array(
				'href' => 'javascript://',
				'title' => '선택한 사용자를 사이트에서 삭제합니다. 사이트 관리자만 실행할 수 있습니다.',
				'label' => '삭제하기',
				'data-action' => 'delete',
			);
		}

		return $structure;
	}

	public function getDefaultStructure_entryAuthors($row=array()) {
		$structure = array();
		$this->baselink = $this->getPermalink($row['uid'],$row['eid']);

		if($row['degree']>=BITWISE_OWNER) {
			$structure['status']['critical delete'] = array(
				'href' => $this->baselink.'/authors/delete?uid='.$row['uid'],
				'title' => '이 사용자를 편집그룹에서 삭제합니다. 개설자만 실행할 수 있습니다.',
				'label' => '삭제하기',
			);
		}

		return $structure;
	}

	public function getDefaultStructure_selectedEntryAuthors($row=array()) {
		$structure = array();
		$this->baselink = '#';
	
		$structure['status']['critical delete'] = array(
			'href' => 'javascript://',
			'title' => '선택한 사용자를 편집그룹에서 삭제합니다. 개설자만 실행할 수 있습니다.',
			'label' => '삭제하기',
			'data-action' => 'delete',
		);

		return $structure;
	}

	public function getDefaultStructure($row=array()) {
		$object = $this;
		$callback = 'getDefaultStructure_'.$this->context;
		if(method_exists($object,$callback)) {
			$structure = call_user_func(array($object,$callback),$row);
			return $structure;
		}
	}

	public function buildControls($row=array()) {
		$markup = '';
		$this->ACL = Acl::instance();
		$options = $this->options;
		$structure = $this->getDefaultStructure($row);

		if(!empty($structure)) {
			if(!empty($options)) {
				foreach($options as $key => $value) {
					switch($key) {
					case 'title':
						$title = $value;
						unset($options['title']);
						break;
					}
				}
			}
			$options['id'] = 'ui-controls'.($this->template?'-'.$this->template.'-'.$this->instance:'').'_'.$row[$options['instance_field']];
			$options['class'][] = 'ui-controls';
			$options['class'][] = 'ui-console';
			$options['class'][] = 'buildControls';
			$options['data-controls-instance'] = $options['instance_field'].'-'.$row[$options['instance_field']];
			$markup[] = '<div '.Markup::buildAttributes($options).'>'.PHP_EOL;
			if($title) {
				$markup[] = "\t".'<h3>'.$title.'</h3>'.PHP_EOL;
			}
			foreach($structure as $group => $items) {
				if(!empty($items)) {
					$markup[] = '<ul class="'.$group.'">'.PHP_EOL;
					foreach($items as $class => $item) {
						$markup[] = '<li class="'.$class.'"><a href="'.$item['href'].'" title="'.$item['title'].'"'.$this->buildAttributes($item).'><span>'.$item['label'].'</span></a></li>'.PHP_EOL;
						unset($class);
						unset($item);
					}
					$markup[] = '</ul>'.PHP_EOL;
					unset($group);
					unset($items);
				}
			}
			$markup[] = '</div>'.PHP_EOL;
			$markup = implode('',$markup);
		}

		return $markup;
	}

	public function getControls($row=array()) {
		return $this->buildControls($row);
	}

	public function printControls($row=array()) {
		echo $this->getControls($row);
	}

}
?>
