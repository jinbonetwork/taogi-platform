<?php
function getEntryInvitesTable($entry,$users) {
	if(empty($users)) {
		return PAGE_NOT_FOUND;
	}
	if(!is_array($users))	{
		return INVALID_DATA_FORMAT;
	}

	$context = 'entryAuthorTable';

	$use_checkbox = false;
	foreach($users as $index => $user) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)&&function_exists('getEntryAuthorControls')) {
			$controls = getEntryAuthorControls($entry,$user,$context);
			$controls = <<<EOT
				<tr id="ui-controls-container_{$context}_{$entry['eid']}_{$user['uid']}" class="ui-controls-container" data-index="{$index}" data-uid="{$user['uid']}">
					<td class="item_controls" colspan="4">
						{$controls}
					</td>
				</tr>
EOT;
			$controls_switch = <<<EOT
				<a class="ui-controls-switch" href="#ui-controls_{$context}_{$entry['eid']}_{$user['uid']}"><span>관리<span></a>
EOT;
			$checkbox = <<<EOT
				<td class="item_checkbox ui-checkbox-container">
					<input class="ui-checkbox" type="checkbox" name="uid[]" value="{$user['uid']}">
				</td>
EOT;
			$use_checkbox = true;
		} else {
			$controls = '';
			$controls_switch = '';
			$checkbox = '';
		}
		$markup[] = <<<EOT
<tr data-index="{$index}" data-eid="{$user['uid']}">
	$checkbox
	<td class="item_title key name">
		<div class="{$user['PORTRAIT']['CLASS']}" style="background-image:url('{$user['PORTRAIT']['small_versioned']}')"></div>
		<div class="NAMETAG value"><a href="{$user['dashboard_link']}">{$user['NAMETAG']}</a></div>
		<div class="excerpt detail">{$user['excerpt']}</div>
		$controls_switch
	</td>
	<td class="item_joined general date">
		<div class="joined_absolute value" title="{$user['joined_relative']}">{$user['joined_absolute']}</div>
	</td>
	<td class="item_updated general date">
		<div class="updated_relative value" title="{$user['updated_absolute']}">{$user['updated_relative']}</div>
		<div class="subject detail"><a href="{$user['permalink']}">{$user['subject']}</a> {$user['VERSION_LINKED']}</div>
	</td>
</tr>
$controls
EOT;
	}

	if($use_checkbox) {
		$checkbox_switch = <<<EOT
			<th class="item_checkbox ui-checkbox-switch-container"><input class="ui-checkbox-switch" type="checkbox"></th>
EOT;
	}
	$markup = implode('',$markup);
	$markup = <<<EOT
<table id="{$context}" class="userTable ui-block ui-table ui-checkbox-group">
<thead>
<tr>
	$checkbox_switch
	<th class="item_title key name">초대자 이름</th>
	<th class="item_joined general date">초대일</th>
	<th class="item_updated general date">최근 편집일</th>
</tr>
</thead>
<tbody>
$markup
</tbody>
</table>
EOT;
	return $markup;
}
?>
