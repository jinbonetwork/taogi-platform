<?php
if(Acl::getIdentity('taogi') != $admin['uid']) {
	return;
}
importResource("taogi-ui-controls");
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

$options['class'] = implode(' ',$options['class']);
if(!empty($structure)) {?>
	<div class="<?php print $options['class']; ?>">
		<h3><?php print $options['title']; ?></h3>
<?php
		foreach($structure as $group => $items) {
			if(!empty($items)) {?>
				<ul class="<?php print $group; ?>">
<?php
				foreach($items as $class => $item) {?>
					<li class="<?php print $class; ?>"><a href="javascript://" data-action="<?php print $item['action']; ?>" data-key="<?php print $item['key']; ?>" data-value="<?php print $item['value']; ?>" title="<?php print $item['title']; ?>"<?php print Filter::buildAttributes($item); ?>><span><?php print $item['label']; ?></span></a></li>
<?php			}?>
				</ul>
<?php		}
		}?>
	</div>
<?php
}
?>
