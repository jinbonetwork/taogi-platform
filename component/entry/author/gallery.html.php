<?php
if(empty($users)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($users))	{
	return INVALID_DATA_FORMAT;
}
importResource("taogi-ui-gallery");
$context = 'entryAuthorGallery';

if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$controls = true;
} else {
	$controls = false;
}
?>
<div id="<?php print $context; ?>" class="userGallery ui-block ui-gallery ui-gallery-narrow ui-checkbox-group">
	<ul class="ui-items">
<?php
	foreach($users as $index => $user) {?>
		<li class="ui-item" data-index="<?php print $index; ?>" data-uid="<?php print $user['uid']; ?>" data-hover-class="hover" data-click-target=".item_title a">
			<dl>
				<dt class="item_image keepRatio" data-width="1" data-height="1">
					<a href="<?php print $user['dashboard_link']; ?>"><div class="<?php print $user['PORTRAIT']['CLASS']; ?>" style="background-image:url('<?php print $user['PORTRAIT']['medium_versioned']; ?>')"></div></a>
<?php			if($controls == true) {?>
					<div class="item_checkbox ui-checkbox-container">
						<input class="ui-checkbox" type="checkbox" name="uid[]" value="<?php print $user['uid']; ?>">
					</div>
					<a class="ui-controls-switch" href="#ui-controls_<?php print $context; ?>_<?php print $entry['eid']; ?>_<?php print $user['uid']; ?>"><span>관리하기</span></a>
<?php			}?>
				</dt>
				<dt class="item_title">
					<a class="value name" href="<?php print $user['dashboard_link']; ?>"><?php print $user['NAMETAG']; ?></a>
				</dt>
				<dd class="item_excerpt">
					<?php print $user['excerpt']; ?>
				</dd>
			</dl>
<?php	if($controls == true) {?>
			<!-- control -->
			<div class="item_controls">
				<?php print Component::get('entry/author/control',array('entry'=>$entry,'user'=>$user,'options'=>array('context'=>$context,'class'=>array('group','icon','label')))); ?>
			</div>
<?php	}?>
		</li>
<?php
	} /* end of foreach */ ?>
	</ul>
<?php
if($controls) {?>
	<div class="ui-checkbox-switch-container">
		<input id="ui-checkbox-switch_" class="ui-checkbox-switch" type="checkbox">
		<label for="ui-checkbox-switch_">전체 선택하기</label>
	</div>
<?php } ?>
</div>
