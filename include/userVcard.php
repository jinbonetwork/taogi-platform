<?php
require_once JFE_PATH.'/config/options.php';
function getUserVcard($user) {
	global $uri,$imageIndexes;
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

	$user['PORTRAIT']['TAG'] = "<div class=\"ui-hcard-image {$user['PORTRAIT']['CLASS']}\" style=\"background-image:url('{$user['PORTRAIT']['medium_versioned']}')\"></div>";
	$user['BACKGROUND']['TAG'] = "<div class=\"ui-hcard-background {$user['BACKGROUND']['CLASS']}\" style=\"background-image:url('{$user['BACKGROUND']['large_versioned']}')\"></div>";

	if($_SESSION['user']['uid']==$user['uid'] && $uri->params['controller']['uri']=='/app/user/profile'){
		//$this->css[] = '../../contribute/cropper/dist/cropper.min.css';
		//$this->script[] = '../../contribute/cropper/dist/cropper.min.js';

		$userIdInput = "<input type='hidden' id='userIdInput' name='uid' value='{$user['uid']}'>".PHP_EOL;

		$userPortraitInput = "<input type='hidden' id='userPortraitInput' class='userImageInput' name='portrait' value='".$user['PORTRAIT']['medium']."'>".PHP_EOL;
		$userPortraitUploader = "<a class='uploader' href='".JFE_CONTRIBUTE_URI."/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=userPortraitInput&lang=ko_KR&taogi_select_mode='><span>초상화 업로드</span></a>";
		$userPortraitRemover = "<a class='remover' href='#'><span>초상화 삭제</span></a>";
		$userPortraitController = "<div class='ui-image-controller userImageController' id='userPortraitController' data-display-selector='#userPortraitPreview' data-display-property='style' data-display-default='".IMAGE_PLACEHOLDER."' data-file-width='{$imageIndexes['portrait']['width']}' data-file-height='{$imageIndexes['portrait']['height']}'>{$userPortraitInput}{$userPortraitUploader}{$userPortraitRemover}</div>";
		$userPortraitTagPattern = array(
			'<div ' => '<div id="userPortraitPreview" ',
			'</div>' => $userPortraitController.'</div>',
		);
		$user['PORTRAIT']['TAG'] = str_replace(array_keys($userPortraitTagPattern),array_values($userPortraitTagPattern),$user['PORTRAIT']['TAG']);

		$userBackgroundInput = "<input type='hidden' id='userBackgroundInput' class='userImageInput' name='background' value='".$user['BACKGROUND']['large']."'>".PHP_EOL;
		$userBackgroundUploader = "<a class='uploader' href='".JFE_CONTRIBUTE_URI."/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=userBackgroundInput&lang=ko_KR&taogi_select_mode='><span>배경그림 업로드</span></a>";
		$userBackgroundRemover = "<a class='remover' href='#userBackgroundInput'><span>배경그림 삭제</span></a>";
		$userBackgroundController = "<div class='ui-image-controller userImageController' id='userBackgroundController' data-display-selector='#userBackgroundPreview' data-display-property='style' data-display-default='".IMAGE_PLACEHOLDER."'>{$userBackgroundInput}{$userBackgroundUploader}{$userBackgroundRemover}</div>";
		$userBackgroundTagPattern = array(
			'<div ' => '<div id="userBackgroundPreview" ',
			'</div>' => $userBackgroundController.'</div>',
		);
		$user['BACKGROUND']['TAG'] = str_replace(array_keys($userBackgroundTagPattern),array_values($userBackgroundTagPattern),$user['BACKGROUND']['TAG']);
	}

	$markup = <<<VCARD
<div class="ui-block ui-vcard ui-hcard">
	<div class="ui-hcard-wrap">
		{$userIdInput}
		{$user['BACKGROUND']['TAG']}
		<h1 class="ui-hcard-title DISPLAY_NAME"><div class="wrap">{$user['DISPLAY_NAME']}</div></h1>
		{$user['PORTRAIT']['TAG']}
		<div class="ui-hcard-description summary"><div class="wrap">{$user['summary']}</div></div>
	</div>
</div><!--/.ui-vcard-->
VCARD;
	return $markup;
}
?>
