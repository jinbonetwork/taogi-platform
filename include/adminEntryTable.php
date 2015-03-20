<?php
function getAdminEntryTable($entries) {
	if(empty($entries)) {
		return PAGE_NOT_FOUND;
	}
	if(!is_array($entries))	{
		return INVALID_DATA_FORMAT;
	}

	$context = 'adminEntryTable';

	$use_checkbox = false;
	foreach($entries as $index => $entry) {
		if(true){
			$controls = getAdminEntryControls($entry,$context);
			$controls = <<<EOT
				<tr id="ui-controls-container_{$context}_{$entry['eid']}" class="ui-controls-container" data-index="{$index}" data-eid="{$entry['eid']}">
					<td class="item_controls" colspan="4">
				{$controls}
					</td>
				</tr>
EOT;
			$controls_switch = <<<EOT
				<a class="ui-controls-switch" href="#ui-controls_{$context}_{$entry['eid']}"><span>관리<span></a>
EOT;
			$checkbox = <<<EOT
				<td class="item_checkbox ui-checkbox-container">
					<input class="ui-checkbox" type="checkbox" name="eid[]" value="{$entry['eid']}">
				</td>
EOT;
			$use_checkbox = true;
		}
		$markup[] = <<<EOT
<tr data-index="{$index}" data-eid="{$entry['eid']}">
	$checkbox
	<td class="item_title key name">
		{$entry['COVERFRONTTAG']}
		<div class="subject value"><a href="{$entry['permalink']}">{$entry['subject']}</a></div>
		<div class="excerpt detail">{$entry['excerpt']}</div>
		$controls_switch
	</td>
	<td class="item_created general date">
		<div class="created_absolute value" title="{$entry['created_relative']}">{$entry['created_absolute']}</div>
		<div class="owner detail"><a href="{$entry['owner_dashboard_link']}">{$entry['owner_NAMETAG']}</a></div>
	</td>
	<td class="item_updated general date">
		<div class="updated_absolute value" title="{$entry['updated_relative']}">{$entry['updated_absolute']}</div>
		<div class="editor detail"><a href="{$entry['editor_dashboard_link']}">{$entry['editor_NAMETAG']}</a></div>
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
<table id="{$context}" class="entryTable ui-block ui-table ui-items ui-checkbox-group">
<thead>
<tr>
	$checkbox_switch
	<th class="item_title key">타임라인 제목</th>
	<th class="item_created general date">만든 날짜</th>
	<th class="item_updated general date">갱신된 날짜</th>
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
