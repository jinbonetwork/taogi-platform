<?php
function getUserVcard($user) {
	global $uri;
	if(is_numeric($user)||!isset($user['DISPLAY_NAME'])) {
		$user = User::getUserProfile($user);
	}

	if(empty($user)) {
		return;
	}

	$userPortraitUploader;
	$userPortraitRemover;
	$userPortraitController;
	$userBackgroundUploader;
	$userBackgroundRemover;
	$userBackgroundController;

	$userPortraitTagPattern = array(
		'<div class="' => '<div class="ui-hcard-image ',
	);
	$user['PORTRAITTAG'] = str_replace(array_keys($userPortraitTagPattern),array_values($userPortraitTagPattern),$user['PORTRAITTAG']);

	$userBackgroundTagPattern = array(
		'<div class="' => '<div class="ui-hcard-background ',
	);
	$user['BACKGROUNDTAG'] = str_replace(array_keys($userBackgroundTagPattern),array_values($userBackgroundTagPattern),$user['BACKGROUNDTAG']);

	if($_SESSION['user']['uid']==$user['uid'] && $uri->params['controller']['uri']=='/app/user/profile'){
		//$this->css[] = '../../contribute/cropper/dist/cropper.min.css';
		//$this->script[] = '../../contribute/cropper/dist/cropper.min.js';

		$userIdInput = "<input type='hidden' id='userIdInput' name='uid' value='{$user['uid']}'>".PHP_EOL;

		$userPortraitInput = "<input type='hidden' id='userPortraitInput' class='userImageInput' name='portrait' value='".$user['PORTRAIT']."'>".PHP_EOL;
		$userPortraitUploader = "<a class='uploader' href='".JFE_CONTRIBUTE_URI."/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=userPortraitInput&lang=ko_KR&taogi_select_mode='><span>초상화 업로드</span></a>";
		$userPortraitRemover = "<a class='remover' href='#'><span>초상화 삭제</span></a>";
		$userPortraitController = "<div class='ui-image-controller userImageController' id='userPortraitController' data-display-selector='#userPortraitPreview' data-display-property='style' data-display-default='".IMAGE_PLACEHOLDER."' data-file-width='".PORTRAIT_WIDTH."' data-file-height='".PORTRAIT_HEIGHT."'>{$userPortraitInput}{$userPortraitUploader}{$userPortraitRemover}</div>";
		$userPortraitTagPattern = array(
			'<div ' => '<div id="userPortraitPreview" ',
			'</div>' => $userPortraitController.'</div>',
		);
		$user['PORTRAITTAG'] = str_replace(array_keys($userPortraitTagPattern),array_values($userPortraitTagPattern),$user['PORTRAITTAG']);

		$userBackgroundInput = "<input type='hidden' id='userBackgroundInput' class='userImageInput' name='background' value='".$user['BACKGROUND']."'>".PHP_EOL;
		$userBackgroundUploader = "<a class='uploader' href='".JFE_CONTRIBUTE_URI."/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=userBackgroundInput&lang=ko_KR&taogi_select_mode='><span>배경그림 업로드</span></a>";
		$userBackgroundRemover = "<a class='remover' href='#userBackgroundInput'><span>배경그림 삭제</span></a>";
		$userBackgroundController = "<div class='ui-image-controller userImageController' id='userBackgroundController' data-display-selector='#userBackgroundPreview' data-display-property='style' data-display-default='".IMAGE_PLACEHOLDER."'>{$userBackgroundInput}{$userBackgroundUploader}{$userBackgroundRemover}</div>";
		$userBackgroundTagPattern = array(
			'<div ' => '<div id="userBackgroundPreview" ',
			'</div>' => $userBackgroundController.'</div>',
		);
		$user['BACKGROUNDTAG'] = str_replace(array_keys($userBackgroundTagPattern),array_values($userBackgroundTagPattern),$user['BACKGROUNDTAG']);
	}

	$markup = <<<VCARD
<div class="ui-block ui-vcard ui-hcard">
	<div class="ui-hcard-wrap">
		{$userIdInput}
		{$user['BACKGROUNDTAG']}
		<h1 class="ui-hcard-title DISPLAY_NAME">{$user['DISPLAY_NAME']}</h1>
		{$user['PORTRAITTAG']}
		<div class="ui-hcard-description summary">{$user['summary']}</div>
	</div>
</div><!--/.ui-vcard-->
VCARD;
	return $markup;
}
?>
