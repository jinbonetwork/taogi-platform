<?php
if(empty($users)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($users))	{
	return INVALID_DATA_FORMAT;
}

importResource("taogi-ui-table");
$context = 'entryAuthorTable';

if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR))
	$controls = true;
else
	$controls = false;
?>
<table id="<?php print $context; ?>" class="userTable ui-block ui-table ui-checkbox-group">
	<thead>
		<tr>
<?php	if($controls) {?>
			<th class="item_checkbox ui-checkbox-switch-container"><input class="ui-checkbox-switch" type="checkbox"></th>
<?php	}?>
			<th class="item_title key name">편집자 이름</th>
			<th class="item_joined general date">가입일</th>
			<th class="item_updated general date">최근 편집일</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($users as $index => $user) {?>
		<tr data-index="<?php print $index; ?>" data-eid="<?php print $user['uid']; ?>">
<?php	if($controls == true) {?>
			<td class="item_checkbox ui-checkbox-container">
				<input class="ui-checkbox" type="checkbox" name="uid[]" value="<?php print $user['uid']; ?>">
			</td>
<?php	}?>
			<td class="item_title key name">
				<div class="<?php print $user['PORTRAIT']['CLASS']; ?>" style="background-image:url('<?php print $user['PORTRAIT']['small_versioned']; ?>')"></div>
				<div class="NAMETAG value"><a href="<?php print $user['dashboard_link']; ?>"><?php print $user['NAMETAG']; ?></a></div>
				<div class="excerpt detail"><?php print $user['excerpt']; ?></div>
<?php		if($controls == true) {?>
				<a class="ui-controls-switch" href="#ui-controls_<?php print $context; ?>_<?php print $entry['eid']; ?>_<?php print $user['uid']; ?>"><span>관리<span></a>
<?php		}?>
			</td>
			<td class="item_joined general date">
				<div class="joined_absolute value" title="<?php print $user['joined_relative']; ?>"><?php print $user['joined_absolute']; ?></div>
			</td>
			<td class="item_updated general date">
				<div class="updated_relative value" title="<?php print $user['updated_absolute']; ?>"><?php print $user['updated_relative']; ?></div>
				<div class="subject detail"><a href="<?php print $user['permalink']; ?>"><?php print $user['subject']; ?></a> <?php print $user['VERSION_LINKED']; ?></div>
			</td>
		</tr>
<?php	if($controls == true) {?>
			<tr id="ui-controls-container_<?php print $context; ?>_<?php print $entry['eid']; ?>_<?php print $user['uid']; ?>" class="ui-controls-container" data-index="<?php print $index; ?>" data-uid="<?php print $user['uid']; ?>">
				<td class="item_controls" colspan="4">
					<?php print Component::get('entry/author/control',array('entry'=>$entry,'user'=>$user,'options'=>array('context'=>$context))); ?>
				</td>
			</tr>
<?php	}
	}?>
	</tbody>
</table>
