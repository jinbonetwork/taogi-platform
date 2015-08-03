<?php
importResource("taogi-ui-controls");
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
$options['class'] = implode(' ',$options['class']);

if(!empty($structure)) {?>
	<div id="ui-controls_<?php print $options['context']; ?>_<?php print $entry['eid']; ?>_<?php print $user['uid']; ?>" class="<?php print $options['class']; ?>" data-instance="<?php print $instance; ?>" data-object-instance="<?php print $user['eid']; ?>">
		<h3>이 항목을</h3>
<?php	foreach($structure as $group => $items) {
			if(empty($items)) continue; ?>
			<ul class="<?php print $group; ?>">
<?php		foreach($items as $class => $item) {?>
				<li class="<?php print $class; ?>"><a href="<?php print $item['href']; ?>" title="<?php print $item['title']; ?>"<?php print Filter::buildAttributes($item); ?>><span><?php print $item['label']; ?></span></a></li>
<?php		}?>
			</ul>
<?php	}?>
	</div>
<?php }
?>
