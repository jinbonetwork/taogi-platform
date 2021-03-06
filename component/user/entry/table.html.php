<?php
if(empty($entries)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($entries))	{
	return INVALID_DATA_FORMAT;
}

$context = 'userEntryTable';

importResource('taogi-ui-table');

$use_checkbox = false;
if($user['uid'] == Acl::getIdentity('taogi')) {
	$use_checkbox = true;
}
?>
<table id="<?php print $context; ?>" class="entryTable ui-block ui-table ui-items ui-checkbox-group">
	<thead>
		<tr>
<?php	if($use_checkbox == true) {?>
			<th class="item_checkbox ui-checkbox-switch-container"><input class="ui-checkbox-switch" type="checkbox"></th>
<?php	}?>
			<th class="item_title key">타임라인 제목</th>
			<th class="item_created general date">만든 날짜</th>
			<th class="item_updated general date">갱신된 날짜</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($entries as $index => $entry) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)) {
			$controls = true;
		} else {
			$controls = false;
		}?>
		<tr data-index="<?php print $index; ?>" data-eid="<?php print $entry['eid']; ?>" class="<?php print ( ($entry['owner'] == $user['uid']) ? 'owner' : 'editor' ); ?>">
<?php	if($controls == true) {?>
			<td class="item_checkbox ui-checkbox-container">
				<input class="ui-checkbox" type="checkbox" name="eid[]" value="<?php print $entry['eid']; ?>">
			</td>
<?php	}?>
			<td class="item_title key name">
				<div class="<?php print $entry['COVERFRONT']['CLASS']; ?>" style="background-image:url('<?php print $entry['COVERFRONT']['small_versioned']; ?>')"></div>
				<div class="subject value"><a href="<?php print $entry['permalink']; ?>"><?php print $entry['subject']; ?></a></div>
				<div class="excerpt detail"><?php print $entry['excerpt']; ?></div>
<?php		if($controls == true) {?>
				<a class="ui-controls-switch" href="#ui-controls_<?php print $context; ?>_<?php print $entry['eid']; ?>"><span>관리<span></a>
<?php		}?>
			</td>
			<td class="item_created general date">
				<div class="created_absolute value" title="<?php print $entry['created_relative']; ?>"><?php print $entry['created_absolute']; ?></div>
				<div class="owner detail"><a href="<?php print $entry['owner_dashboard_link']; ?>"><?php print $entry['owner_NAMETAG']; ?></a></div>
			</td>
			<td class="item_updated general date">
				<div class="updated_absolute value" title="<?php print $entry['updated_relative']; ?>"><?php print $entry['updated_absolute']; ?></div>
				<div class="editor detail"><a href="<?php print $entry['editor_dashboard_link']; ?>"><?php print $entry['editor_NAMETAG']; ?></a></div>
			</td>
		</tr>
<?php if($controls == true) {?>
		<tr id="ui-controls-container_<?php print $context; ?>_<?php print $entry['eid']; ?>" class="ui-controls-container" data-index="<?php print $index; ?>" data-eid="<?php print $entry['eid']; ?>">
			<td class="item_controls" colspan="4">
<?php			print Component::get('user/entry/control',array('entry'=>$entry,'options'=>array('context'=>$context))); ?>
			</td>
		</tr>
<?php }
	}?>
	</tbody>
</table>
