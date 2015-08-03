<?php
if(empty($entries)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($entries))	{
	return INVALID_DATA_FORMAT;
}
$context = 'userEntryGallery';

importResource('taogi-ui-gallery');

$use_checkbox = false;
if($user['uid'] == Acl::getIdentity('taogi')) {
	$use_checkbox = true;
}
?>
<div id="<?php print $context; ?>" class="entryGallery ui-block ui-gallery ui-checkbox-group">
	<ul class="ui-items">
<?php
	foreach($entries as $index => $entry) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
			$controls = true;
		} else {
			$controls = false;
		}?><li class="ui-item" data-index="<?php print $index; ?>" data-eid="<?php print $entry['eid']; ?>" data-hover-class="hover" data-click-target=".item_title a">
			<dl>
				<dt class="keepRatio item_image" data-width="16" data-height="10">
					<a href="<?php print $entry['permalink']; ?>"><div class="<?php print $entry['COVERFRONT']['CLASS']; ?>" style="background-image:url('<?php print $entry['COVERFRONT']['medium_versioned']; ?>')"></div></a>
<?php			if($controls == true) {?>
					<div class="item_checkbox ui-checkbox-container">
						<input class="ui-checkbox" type="checkbox" name="eid[]" value="<?php print $entry['eid']; ?>">
					</div>
					<a class="ui-controls-switch" href="#ui-controls_<?php print $context; ?>_<?php print $entry['eid']; ?>"><span>관리하기</span></a>
<?php			}?>
				</dt>
				<dt class="item_title">
					<a href="<?php print $entry['permalink']; ?>"><?php print $entry['subject']; ?></a>
				</dt>
				<dd class="item_excerpt">
					<?php print $entry['excerpt']; ?>
				</dd>
			</dl>
<?php	if($controls == true) {?>
			<div class="item_controls">
<?php			print Component::get('user/entry/control',array('entry'=>$entry,'options'=>array('context'=>$context,'class'=>array('group','icon','label')))); ?>
			</div>
<?php	}?>
		</li><?php
	}?>
	</ul>
<?php
if($use_checkbox) {?>
	<div class="ui-checkbox-switch-container">
		<input id="ui-checkbox-switch_" class="ui-checkbox-switch" type="checkbox">
		<label for="ui-checkbox-switch_">전체 선택하기</label>
	</div>
<?php }?>
</div><!--/#userArchivesGallery-->
