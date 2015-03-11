<?php
function getEntryAuthorGallery($entry,$users) {
	if(empty($users)) {
		return PAGE_NOT_FOUND;
	}
	if(!is_array($users))	{
		return INVALID_DATA_FORMAT;
	}
	$context = 'entryAuthorGallery';

	$use_checkbox = false;
	foreach($users as $index => $user) {
		if(Acl::checkAcl($entry['eid'],BITWISE_EDITOR)&&function_exists('getEntryAuthorControls')) {
			$controls = getEntryAuthorControls($entry,$user,array('context'=>$context,'class'=>array('group','icon','label')));
			$controls = <<<EOT
				<div class="item_controls">
					$controls
				</div>
EOT;
			$controls_switch = <<<EOT
				<a class="ui-controls-switch" href="#ui-controls_{$context}_{$entry['eid']}_{$user['uid']}"><span>관리하기</span></a>
EOT;
			$checkbox = <<<EOT
				<div class="item_checkbox ui-checkbox-container">
					<input class="ui-checkbox" type="checkbox" name="uid[]" value="{$user['uid']}">
				</div>
EOT;
			$use_checkbox = true;
		} else {
			$controls = '';
			$controls_switch = '';
			$checkbox = '';
		}
		$markup[] = <<<EOT
<li class="ui-item" data-index="{$index}" data-uid="{$user['uid']}" data-hover-class="hover" data-click-target=".item_title a">
	<dl>
		<dt class="item_image">
			<a href="{$user['dashboard_link']}">{$user['PORTRAITTAG']}</a>
			$checkbox
			$controls_switch
		</dt>
		<dt class="item_title">
			<a class="value name" href="{$user['dashboard_link']}">{$user['NAMETAG']}</a>
		</dt>
		<dd class="item_excerpt">
			{$user['excerpt']}
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
<div id="{$context}" class="userGallery ui-block ui-gallery ui-gallery-narrow ui-checkbox-group">
<ul class="ui-items">
$markup
</ul>
$checkbox_switch
</div><!--/#entryAuthorGallery-->
EOT;

	return $markup;
}
?>
