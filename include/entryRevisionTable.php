<?php
function getEntryRevisionTable($entry,$revisions) {
	if(empty($revisions)) {
		return PAGE_NOT_FOUND;
	}
	if(!is_array($revisions))	{
		return INVALID_DATA_FORMAT;
	}

	$context = 'entryRevisionTable';

	$use_checkbox = false;
	foreach($revisions as $index => $revision) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)&&function_exists('getEntryRevisionControls')) {
			$controls = getEntryRevisionControls($revision,$context);
			$controls = <<<EOT
				<tr id="ui-controls-container_{$context}_{$revision['eid']}_{$revision['vid']}" class="ui-controls-container" data-index="{$index}" data-vid="{$revision['vid']}">
					<td class="item_controls" colspan="4">
				{$controls}
					</td>
				</tr>
EOT;
			$controls_switch = <<<EOT
				<a class="ui-controls-switch" href="#ui-controls_{$context}_{$revision['eid']}_{$revision['vid']}"><span>관리<span></a>
EOT;
			$checkbox = <<<EOT
				<td class="item_checkbox ui-checkbox-container">
					<input class="ui-checkbox" type="checkbox" name="vid[]" value="{$revision['vid']}">
				</td>
EOT;
			$use_checkbox = true;
		} else {
			$controls = '';
			$controls_switch = '';
			$checkbox = '';
		}
		$markup[] = <<<EOT
<tr data-index="{$index}" data-vid="{$revision['vid']}">
	$checkbox
	<td class="item_title key title">
		{$revision['COVERTAG']}
		<div class="subject value"><a href="{$revision['permalink']}?vid={$revision['vid']}">{$revision['subject']}</a><span class="vid value sub">(#{$revision['vid']})</span></div>
		<div class="excerpt detail">{$entry['excerpt']}</div>
		$controls_switch
	</td>
	<td class="item_updated general date">
		<div class="updated_absolute value" title="{$revision['updated_relative']}">{$revision['updated_absolute']}</div>
	</td>
	<td class="item_editor general name">
		<div class="editor value"><a href="{$revision['editor_dashboard_link']}">{$revision['editor_NAMETAG']}</a></div>
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
<table id="{$context}" class="ui-block ui-table ui-items ui-checkbox-group">
<thead>
<tr>
	$checkbox_switch
	<th class="item_title key title">버전 정보</th>
	<th class="item_updated general date">갱신된 날짜</th>
	<th class="item_editor general name">편집한 사람</th>
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
