<?php
function getRecentEntryGallery($entries) {
	if(empty($entries)) {
		return PAGE_NOT_FOUND;
	}
	if(!is_array($entries))	{
		return INVALID_DATA_FORMAT;
	}
	$context = 'recentEntryGallery';

	$use_checkbox = false;
	foreach($entries as $index => $entry) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)&&function_exists('getUserEntryControls')) {
			$controls = getUserEntryControls($entry,$context);
			$controls = <<<EOT
				<div class="item_controls">
					$controls
				</div>
EOT;
			$controls_switch = <<<EOT
				<a class="ui-controls-switch" href="#ui-controls_{$context}_{$entry['eid']}"><span>관리하기</span></a>
EOT;
			$checkbox = <<<EOT
				<div class="item_checkbox ui-checkbox-container">
					<input class="ui-checkbox" type="checkbox" name="eid[]" value="{$entry['eid']}">
				</div>
EOT;
			$use_checkbox = true;
		} else {
			$controls = '';
			$controls_switch = '';
			$checkbox = '';
		}
		$markup[] = <<<EOT
<li class="ui-item" data-index="{$index}" data-eid="{$entry['eid']}" data-hover-class="hover" data-click-target=".item_title a">
	<dl>
		<dt class="keepRatio item_image" data-width="16" data-height="10">
			<a class="keepCover" href="{$entry['permalink']}"><img src="{$entry['image']}"></a>
			$checkbox
			$controls_switch
		</dt>
		<dt class="item_title">
			<a href="{$entry['permalink']}">{$entry['subject']}</a>
		</dt>
		<dd class="item_summary">
			{$entry['summary']}
		</dd>
	</dl>
	{$controls}
</li>
EOT;
	}

	if($use_checkbox) {
		$checkbox_switch = <<<EOT
			<div class="ui-checkbox-switch-container">
				<input id="ui-checkbox-switch_" class="ui-checkbox-switch" type="checkbox">
				<label for="ui-checkbox-switch_">전체 선택하기</label>
			</div>
EOT;
	}
	$markup = implode('',$markup);
	$markup = <<<EOT
<div id="{$context}" class="entryGallery ui-block ui-gallery ui-checkbox-group">
<ul class="ui-items">
$markup
</ul>
$checkbox_switch
</div><!--/#recentEntryGallery-->
EOT;

	return $markup;
}
?>
