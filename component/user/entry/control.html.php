<?php
importResource("taogi-ui-controls");
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


if($entry['status']>0||Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$structure['manage']['manage view'] = array(
		'href' => $baselink,
		'title' => '타임라인을 봅니다.',
		'label' => '보기',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$structure['manage']['manage edit'] = array(
		'href' => $baselink.'/modify',
		'title' => '타임라인을 편집합니다.',
		'label' => '고치기',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$structure['manage']['manage history overlay'] = array(
		'href' => $baselink.'/revisions',
		'title' => '타임라인의 편집 이력을 봅니다.',
		'label' => '편집이력',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
	$structure['manage']['manage authors overlay'] = array(
		'href' => $baselink.'/authors',
		'title' => '편집그룹을 관리합니다.',
		'label' => '편집그룹',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR,'eq')) {
	$structure['manage']['critical leave'] = array(
		'href' => $baselink.'/resign',
		'title' => '편집그룹에서 탈퇴합니다.',
		'label' => '편집그룹탈퇴',
	);
}

if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
	$structure['status']['status public'.($entry['is_public']==NODE_STATUS_PUBLIC?' current':'')] = array(
		'href' => $baselink.'/status?status=2',
		'title' => '타임라인을 따오기 첫 페이지에 공개하고 검색엔진의 접근을 허용합니다.',
		'label' => '검색허용',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
	$structure['status']['status open'.($entry['is_public']==NODE_STATUS_OPEN?' current':'')] = array(
		'href' => $baselink.'/status?status=1',
		'title' => '방문객이 타임라인을 읽을 수 있도록 설정합니다.',
		'label' => '공개하기',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
	$structure['status']['status private'.($entry['is_public']==NODE_STATUS_PRIVATE?' current':'')] = array(
		'href' => $baselink.'/status?status=0',
		'title' => '편집자만 타임라인을 읽을 수 있도록 설정합니다.',
		'label' => '감추기',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
	$structure['status']['critical delete'] = array(
		'href' => $baselink.'/delete',
		'title' => '타임라인을 삭제합니다.',
		'label' => '삭제',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_OWNER)) {
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
}
if(Acl::checkAcl($entry['eid'],BITWISE_USER)) {
	$structure['maintenance']['maintenance fork'] = array(
		'href' => $baselink.'/fork',
		'title' => '이 타임라인을 내 계정으로 복사합니다.',
		'label' => '복사',
	);
}
if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$structure['maintenance']['maintenance backup'] = array(
		'href' => $baselink.'/backup',
		'title' => '이 타임라인을 압축파일로 내려받습니다.',
		'label' => '백업',
	);
}

$options['class'] = implode(' ',$options['class']);

if(!empty($structure)) {?>
	<div id="ui-controls_<?php print $options['context']; ?>_<?php print $entry['eid']; ?>" class="<?php print $options['class']; ?>" data-instance="<?php print $instance; ?>" data-object-instance="<?php print $entry['eid']; ?>">
		<h3><?php print $options['title']; ?></h3>
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
<?php
}
?>
