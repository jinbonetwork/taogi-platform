<?php
function getUserVcard($user) {
	if(is_numeric($user)||!isset($user['DISPLAY_NAME'])) {
		$user = User::getUserProfile($user);
	}
	if(empty($user)) {
		return;
	}
	$markup = <<<EOT
<div class="ui-block ui-vcard">
	<div class="wrap">
		<div class="BACKGROUND" style="background-image:url({$user['BACKGROUND']});background-size:cover;"></div>
		<h1 class="DISPLAY_NAME">{$user['DISPLAY_NAME']}</h1>
		{$user['PORTRAITTAG']}
		<div class="summary">{$user['summary']}</div>
	</div>
</div><!--/.ui-vcard-->
EOT;
	return $markup;
}
?>
