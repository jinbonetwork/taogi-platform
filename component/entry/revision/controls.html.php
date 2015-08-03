<?php
if(!Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	return;
}
importResource("taogi-ui-controls");
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

$options['class'] = implode(' ',$options['class']);
if(!empty($structure)) {?>
	<div class="<?php print $options['class']; ?>">
	<h3>선택한 항목을</h3>
<?php
	foreach($structure as $group => $items) {
		if(!empty($items)) {?>
			<ul class="<?php print $group; ?>">
<?php		foreach($items as $class => $item) {?>
				<li class="<?php print $class; ?>"><a href="javascript://" data-action="<?php print $item['action']; ?>" data-key="<?php print $item['key']; ?>" data-value="<?php print $item['value']; ?>" title="<?php print $item['title']; ?>"<?php print Filter::buildAttributes($item); ?>><span><?php print $item['label']; ?></span></a></li>
<?php		}?>
			</ul>
<?php	}
	}?>
	</div>
<?php
}
?>
