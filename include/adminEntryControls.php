<?php
function getAdminEntryControls($entry,$options=array()) {
	static $instance = 0;

	$baselink = Entry::getEntryLink($entry);
	$structure = array();
	$defaults = array(
		'context' => '',
		'title' => '이 타임라인 관리하기',
		'class' => array('inline','icon','label'),
	);
	if(!is_array($options)) {
		$options = array('context' => $options);
	}
	$options = array_merge($defaults,$options);
	$options = array_intersect_key($options,$defaults);
	$options['class'] = array_merge(array('ui-controls',$options['context'],),$options['class']);


	$structure['manage']['manage view'] = array(
		'href' => $baselink,
		'title' => '타임라인을 봅니다.',
		'label' => '보기',
	);
	$structure['manage']['manage edit'] = array(
		'href' => $baselink.'/modify',
		'title' => '타임라인을 편집합니다.',
		'label' => '고치기',
	);
	$structure['manage']['manage history overlay'] = array(
		'href' => $baselink.'/revisions',
		'title' => '타임라인의 편집 이력을 봅니다.',
		'label' => '편집이력',
	);
	$structure['manage']['manage authors overlay'] = array(
		'href' => $baselink.'/authors',
		'title' => '편집그룹을 관리합니다.',
		'label' => '편집그룹',
	);
	$structure['manage']['critical leave'] = array(
		'href' => $baselink.'/resign',
		'title' => '편집그룹에서 탈퇴합니다.',
		'label' => '편집그룹탈퇴',
	);
	$structure['status']['status public'.($entry['is_public']==NODE_STATUS_PUBLIC?' current':'')] = array(
		'href' => $baselink.'/status?status=2',
		'title' => '타임라인을 따오기 첫 페이지에 공개하고 검색엔진의 접근을 허용합니다.',
		'label' => '검색허용',
	);
	$structure['status']['status open'.($entry['is_public']==NODE_STATUS_OPEN?' current':'')] = array(
		'href' => $baselink.'/status?status=1',
		'title' => '방문객이 타임라인을 읽을 수 있도록 설정합니다.',
		'label' => '공개하기',
	);
	$structure['status']['status private'.($entry['is_public']==NODE_STATUS_PRIVATE?' current':'')] = array(
		'href' => $baselink.'/status?status=0',
		'title' => '편집자만 타임라인을 읽을 수 있도록 설정합니다.',
		'label' => '감추기',
	);
	$structure['status']['critical delete'] = array(
		'href' => $baselink.'/delete',
		'title' => '타임라인을 삭제합니다.',
		'label' => '삭제',
	);
	$structure['maintenance']['status forkable'.($entry['is_forkable']?' current':'')] = array(
		'href' => $baselink.'/status?forkable=1',
		'title' => '이 타임라인의 복사를 허용합니다.',
		'label' => '복사허용',
	);
	$structure['maintenance']['status notForkable'.($entry['is_forkable']?'':' current')] = array(
		'href' => $baselink.'/status?forkable=0',
		'title' => '이 타임라인의 복사를 금지합니다.',
		'label' => '복사금지',
	);
	$structure['maintenance']['maintenance fork'] = array(
		'href' => $baselink.'/fork',
		'title' => '이 타임라인을 내 계정으로 복사합니다.',
		'label' => '복사',
	);
	$structure['maintenance']['maintenance backup'] = array(
		'href' => $baselink.'/backup',
		'title' => '이 타임라인을 압축파일로 내려받습니다.',
		'label' => '백업',
	);

	if(!empty($structure)) {
		foreach($structure as $group => $items) {
			if(!empty($items)) {
				$markup[] = '<ul class="'.$group.'">'.PHP_EOL;
				foreach($items as $class => $item) {
					$markup[] = '<li class="'.$class.'"><a href="'.$item['href'].'" title="'.$item['title'].'"'.Filter::buildAttributes($item).'><span>'.$item['label'].'</span></a></li>'.PHP_EOL;
					unset($class);
					unset($item);
				}
				$markup[] = '</ul>'.PHP_EOL;
				unset($group);
				unset($items);
			}
		}
		$markup = implode('',$markup);

		$options['class'] = implode(' ',$options['class']);
		$markup = <<<EOT
<div id="ui-controls_{$options['context']}_{$entry['eid']}" class="{$options['class']}" data-instance="{$instance}" data-object-instance="{$entry['eid']}">
<h3>{$options['title']}</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

function getAdminEntriesControls($admin,$options=array()) {
	if($_SESSION['user']['uid']!=$admin['uid']) {
		return;
	}
	$structure = array();
	$defaults = array(
		'context' => 'bulk',
		'title' => '선택한 타임라인 관리하기',
		'class' => array('inline','icon','label'),
	);
	if(!is_array($options)) {
		$options = array('context' => $options);
	}
	$options = array_merge($defaults,$options);
	$options = array_intersect_key($options,$defaults);
	$options['class'] = array_merge(array('ui-controls',$options['context'],),$options['class']);

	$structure['status']['status public'] = array(
		'action' => 'status',
		'key' => 'status',
		'value' => '2',
		'title' => '선택한 타임라인을 따오기 첫 페이지에 공개하고 검색엔진의 접근을 허용합니다.',
		'label' => '배포하기',
	);
	$structure['status']['status open'] = array(
		'action' => 'status',
		'key' => 'status',
		'value' => '1',
		'title' => '선택한 타임라인을 방문객이 읽을 수 있도록 설정합니다.',
		'label' => '공개하기',
	);
	$structure['status']['status private'] = array(
		'action' => 'status',
		'key' => 'status',
		'value' => '0',
		'title' => '선택한 타임라인을 편집자만 읽을 수 있도록 설정합니다.',
		'label' => '감추기',
	);
	$structure['status']['critical delete'] = array(
		'action' => 'delete',
		'title' => '선택한 타임라인을 삭제합니다.',
		'label' => '삭제하기',
	);
	$structure['maintenance']['status forkable'] = array(
		'action' => 'status',
		'key' => 'forkable',
		'value' => '1',
		'title' => '선택한 타임라인의 복사를 허용합니다.',
		'label' => '복사 허락',
	);
	$structure['maintenance']['status notForkable'] = array(
		'action' => 'status',
		'key' => 'forkable',
		'value' => '0',
		'title' => '선택한 타임라인의 복사를 금지합니다.',
		'label' => '복사 금지',
	);
	$structure['maintenance']['maintenance fork'] = array(
		'action' => 'fork',
		'title' => '선택한 타임라인을 내 계정으로 복사합니다.',
		'label' => '복사하기',
	);
	$structure['maintenance']['maintenance backup'] = array(
		'action' => 'backup',
		'title' => '선택한 타임라인을 압축파일로 내려받습니다.',
		'label' => '백업하기',
	);

	if(!empty($structure)) {
		foreach($structure as $group => $items) {
			if(!empty($items)) {
				$markup[] = '<ul class="'.$group.'">'.PHP_EOL;
				foreach($items as $class => $item) {
					$markup[] = '<li class="'.$class.'"><a href="javascript://" data-action="'.$item['action'].'" data-key="'.$item['key'].'" data-value="'.$item['value'].'" title="'.$item['title'].'"'.Filter::buildAttributes($item).'><span>'.$item['label'].'</span></a></li>'.PHP_EOL;
					unset($class);
					unset($item);
				}
				$markup[] = '</ul>'.PHP_EOL;
				unset($group);
				unset($items);
			}
		}
		$markup = implode('',$markup);
		$options['class'] = implode(' ',$options['class']);

		$markup = <<<EOT
<div class="{$options['class']}">
<h3>{$options['title']}</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

?>
