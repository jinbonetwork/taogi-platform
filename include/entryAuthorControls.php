<?php
function getEntryAuthorControls($entry,$user,$options=array()) {
	static $instance = 0;

	$baselink = Entry::getEntryLink($entry).'/authors';
	$structure = array();
	$defaults = array(
		'context' => '',
		'class' => array('inline','icon','label'),
	);
	if(!is_array($options)) {
		$options = array('context' => $options);
	}
	$options = array_merge($defaults,$options);
	$options = array_intersect_key($options,$defaults);
	$options['class'] = array_merge(array('ui-controls',$options['context'],),$options['class']);

	$structure['manage']['manage dashboard'] = array(
		'href' => $user['dashboard_link'],
		'title' => '이 사용자의 프로필을 봅니다.',
		'label' => '프로필',
	);
	if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
		$structure['manage']['manage history'] = array(
			'href' => $baselink.'/revisions?uid='.$user['uid'],
			'title' => '이 사용자의 편집 이력을 봅니다.',
			'label' => '편집이력',
		);
	}
	if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)&&$user['uid']!=$entry['owner']) {
		$structure['manage']['critical leave'] = array(
			'href' => $baselink.'/delete?uid='.$user['uid'],
			'title' => '편집그룹에서 삭제합니다.',
			'label' => '삭제',
		);
	}

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
<div id="ui-controls_{$options['context']}_{$entry['eid']}_{$user['uid']}" class="{$options['class']}" data-instance="{$instance}" data-object-instance="{$user['eid']}">
<h3>이 항목을</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

function getEntryAuthorsControls($entry=array(),$options=array()) {
	if(!Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
		return;
	}
	$structure = array();
	$defaults = array(
		'context' => 'bulk',
		'class' => array('inline','icon','label'),
	);
	if(!is_array($options)) {
		$options = array('context' => $options);
	}
	$options = array_merge($defaults,$options);
	$options = array_intersect_key($options,$defaults);
	$options['class'] = array_merge(array('ui-controls',$options['context'],),$options['class']);

	if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
		$structure['status']['critical delete'] = array(
			'action' => 'delete',
			'title' => '선택한 사용자를 삭제합니다.',
			'label' => '삭제하기',
		);
	}
	
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
<h3>선택한 항목을</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

?>
