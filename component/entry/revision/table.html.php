<?php
if(empty($revisions)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($revisions))	{
	return INVALID_DATA_FORMAT;
}

importResource("taogi-ui-table");
$context = 'entryRevisionTable';

if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
	$controls = true;
}
?>
<table id="<?php print $context; ?>" class="ui-block ui-table ui-items ui-checkbox-group">
	<thead>
		<tr>
<?php	if($controls) {?>
			<th class="item_checkbox ui-checkbox-switch-container"><input class="ui-checkbox-switch" type="checkbox"></th>
<?php	}?>
			<th class="item_title key title">버전 정보</th>
			<th class="item_updated general date">갱신된 날짜</th>
			<th class="item_editor general name">편집한 사람</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($revisions as $index => $revision) {?>
		<tr data-index="<?php print $index; ?>" data-vid="<?php print $revision['vid']; ?>">
<?php	if($controls) {?>
			<td class="item_checkbox ui-checkbox-container">
				<input class="ui-checkbox" type="checkbox" name="vid[]" value="<?php print $revision['vid']; ?>">
			</td>
<?php	}?>
			<td class="item_title key title">
				<div class="<?php print $revision['COVERFRONT']['CLASS']; ?>" style="background-image:url('<?php print $revision['COVERFRONT']['small_versioned']; ?>')"></div>
				<div class="subject value"><a href="<?php print $revision['permalink']; ?>?vid=<?php print $revision['vid']; ?>"><?php print $revision['subject']; ?></a><span class="vid value sub">(#<?php print $revision['vid']; ?>)</span></div>
				<div class="excerpt detail"><?php print $entry['excerpt']; ?></div>
<?php		if($controls) {?>
				<a class="ui-controls-switch" href="#ui-controls_<?php print $context; ?>_<?php print $revision['eid']; ?>_<?php print $revision['vid']; ?>"><span>관리<span></a>
<?php		}?>
			</td>
			<td class="item_updated general date">
				<div class="updated_absolute value" title="<?php print $revision['updated_relative']; ?>"><?php print $revision['updated_absolute']; ?></div>
			</td>
			<td class="item_editor general name">
				<div class="editor value"><a href="<?php print $revision['editor_dashboard_link']; ?>"><?php print $revision['editor_NAMETAG']; ?></a></div>
			</td>
		</tr>
<?php if($controls) {?>
		<tr id="ui-controls-container_<?php print $context; ?>_<?php print $revision['eid']; ?>_<?php print $revision['vid']; ?>" class="ui-controls-container" data-index="<?php print $index; ?>" data-vid="<?php print $revision['vid']; ?>">
			<td class="item_controls" colspan="4">
				<?php print Component::get('entry/revision/control',array('revision'=>$revision,'options'=>array('context'=>$context))); ?>
			</td>
		</tr>
<?php }
	}?>
	</tbody>
</table>
