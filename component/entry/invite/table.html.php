<?php
if(empty($inviters)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($inviters))	{
	return INVALID_DATA_FORMAT;
}

importResource("taogi-ui-table");
$context = 'entryInviteTable';

if(!Acl::checkAcl($entry['eid'],BITWISE_OWNER))
	return;
?>
<table id="<?php print $context; ?>" class="userTable ui-block ui-table ui-checkbox-group">
	<thead>
		<tr>
			<th class="item_checkbox ui-checkbox-switch-container"><input class="ui-checkbox-switch" type="checkbox"></th>
			<th class="item_title key name">초대자 정보</th>
			<th class="item_joined general date">초대일</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($inviters as $index => $inviter) {?>
		<tr data-index="<?php print $index; ?>" data-vid="<?php print $inviter['id']; ?>">
			<td class="item_checkbox ui-checkbox-container">
				<input class="ui-checkbox" type="checkbox" name="vid[]" value="<?php print $inviter['uid']; ?>">
			</td>
			<td class="item_title key name<?php if(!$inviter['uid']) print ' email_id'; ?>">
<?php			if($inviter['uid']) {?>
					<div class="<?php print $inviter['PORTRAIT']['CLASS']; ?>" style="background-image:url('<?php print $inviter['PORTRAIT']['small_versioned']; ?>')"></div>
					<div class="NAMETAG value">
						<a href="<?php print $invite['dashboard_link']; ?>"><?php print $inviter['NAMETAG']; ?></a>
					</div>
<?php			} else {?>
					<?php print $inviter['email_id']; ?>
<?php			}?>
				<div class="excerpt detail"><?php print $inviter['excerpt']; ?></div>
			</td>
			<td class="item_joined general date">
				<div class="joined_absolute value" title="<?php print date("Y-m-d",$inviter['invite_date']); ?>"><?php print date("Y-m-d",$inviter['invite_date']); ?></div>
			</td>
		</tr>
<?php }?>
	</tbody>
</table>
