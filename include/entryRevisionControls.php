<?php
function getEntryRevisionControls($revision,$options=array()) {
	static $instance = 0;

	$baselink = Entry::getEntryLink($revision).'/revisions';
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

	if(Acl::checkAcl($revision['eid'],BITWISE_OWNER)) {
		$structure['status']['critical delete'] = array(
			'href' => $baselink.'/delete?vid='.$revision['vid'],
			'title' => '이 버전을 삭제합니다.',
			'label' => '삭제하기',
		);
	}
	if(Acl::checkAcl($revision['eid'],BITWISE_USER)) {
		$structure['maintenance']['maintenance fork'] = array(
			'href' => $baselink.'/fork?vid='.$revision['vid'],
			'title' => '이 버전을 내 계정으로 복사합니다.',
			'label' => '복사하기',
		);
	}
	if(Acl::checkAcl($revision['eid'],BITWISE_EDITOR)) {
		$structure['maintenance']['maintenance backup'] = array(
			'href' => $baselink.'/backup?vid='.$revision['vid'],
			'title' => '이 버전을 압축파일로 내려받습니다.',
			'label' => '백업하기',
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
<div id="ui-controls_{$options['context']}_{$revision['eid']}_{$revision['vid']}" class="{$options['class']}" data-instance="{$instance}" data-object-instance="{$revision['eid']}">
<h3>이 항목을</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

function getEntryRevisionsControls($entry=array(),$options=array()) {
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
			'title' => '선택한 버전을 삭제합니다.',
			'label' => '삭제하기',
		);
	}
	$structure['maintenance']['maintenance fork'] = array(
		'action' => 'fork',
		'title' => '선택한 버전을 내 계정으로 복사합니다.',
		'label' => '복사하기',
	);
	$structure['maintenance']['maintenance backup'] = array(
		'action' => 'backup',
		'title' => '선택한 버전을 압축파일로 내려받습니다.',
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
<h3>선택한 항목을</h3>
{$markup}
</div>
EOT;
		$instance++;
	}

	return $markup;
}

?>
