<?php
importResource("taogi-ui-controls");
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

$options['class'] = implode(' ',$options['class']);

if(!empty($structure)) {?>
	<div id="ui-controls_<?php print $options['context']; ?>_<?php print $revision['eid']; ?>_<?php print $revision['vid']; ?>" class="<?php print $options['class']; ?>" data-instance="<?php print $instance; ?>" data-object-instance="<?php print $revision['eid']; ?>">
	<h3>이 항목을</h3>
<?php
	foreach($structure as $group => $items) {
		if(!empty($items)) {?>
			<ul class="<?php print $group; ?>">
<?php		foreach($items as $class => $item) {?>
				<li class="<?php print $class; ?>"><a href="<?php print $item['href']; ?>" title="<?php print $item['title']; ?>"<?php print Filter::buildAttributes($item); ?>><span><?php print $item['label']; ?></span></a></li>
<?php		}?>
			</ul>
<?php	}
	}?>
	</div>
<?php }?>
