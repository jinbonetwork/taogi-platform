<div id="editor_config_exterior" class="inner collapsed">
<form name="timeline_extra" class="wrap" onsubmit="return false;">
	<?php
		$template = isset($timeline['extra']['template'])?$timeline['extra']['template']:DEFAULT_TEMPLATE;
	?>
	<input type="hidden" name="extra[template]" value="<?php echo $template; ?>">
	<ul class="tabs">
		<li class="tab"><a href="#editor_config_exterior_preset"><span>프리셋</span></a></li>
		<li class="tab active"><a href="#editor_config_exterior_basic"><span>기본설정</span></a></li>
		<li class="tab"><a href="#editor_config_exterior_advanced"><span>고급설정</span></a></li>
	</ul>
	<fieldset id="editor_config_exterior_preset" class="column">
		<legend>프리셋</legend>
		<div class="help"><p>미리 준비된 값을 기본설정/고급설정 항목에 입력합니다.</p></div>
		<div class="wrap">
<?php	if(empty($presets)){
			print 'No presets';
		}else{?>
			<ul class='presets'>
<?php		foreach($presets as $preset) {?>
				<li id='preset-<?php print $preset['name']; ?>' class='preset <?php print $preset['active']; ?>' data-name='<?php print $preset['name']; ?>' data-directory='<?php print $preset['directory']; ?>' data-settings='<?php print $preset['settings']; ?>' data-stylesheet='<?php print $preset['stylesheet']; ?>'>
					<label for='preset-<?php print $preset['name']; ?>-check'>
						<img src='<?php print $preset['screenshot']; ?>'>
						<span><?php print $preset['name']; ?></span>
						<input id='preset-<?php print $preset['name']; ?>-check' type='radio' name='extra[preset]' value='<?php print $preset['name']; ?>' <?php print $preset['checked']; ?>>
					</label>
				</li>
<?php		}?>
			</ul><!--/.presets-->
<?php	}?>
		</div>
	</fieldset>
	<fieldset id="editor_config_exterior_basic" class="column active">
		<legend>기본 설정</legend>
		<div class="help"><p>항목을 수정한 뒤 모양을 확인하려면 저장해야 합니다.</p></div>
		<div id="editor_config_exterior_basic_cover" class="row">
			<legend>앞표지</legend>
			<div class="wrap">
				<div class="field block">
					<label for="asset_cover_background_image_uploader">배경 그림</label>
					<div class="asset_cover_background_image_wrap">
						<a class="asset_cover_background_image_uploader" id="asset_cover_background_image_uploader" href="<?php echo JFE_CONTRIBUTE_URI.'/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=asset_cover_background_image&lang=ko_KR&taogi_select_mode='; ?>"><div class="asset_cover_background_image_preview" id="asset_cover_background_image_preview"></div></a>
						<a class="asset_cover_background_image_remover" id="asset_cover_background_image_remover" href="#asset_cover_background_image_preview"><span>이미지 삭제</span></a>
					</div>
					<input class="asset_cover_background_image" type="hidden" id="asset_cover_background_image" name="asset[cover_background_image]" value="<?php print $timeline['asset']['cover_background_image']; ?>" data-placeholder="<?php echo TRANSPARENT_PLACEHOLDER; ?>">
				</div>
				<div class="field inline">
					<label for="extra_cover_background_color">배경 색깔</label>
					<input class="color" type="text" id="extra_cover_background_color" name="extra[cover_background_color]" value="<?php echo $timeline['extra']['cover_background_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_cover_title_color">제목 글자 색깔</label>
					<input class="color" type="text" id="extra_cover_title_color" name="extra[cover_title_color]" value="<?php echo $timeline['extra']['cover_title_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_cover_body_color">본문 글자 색깔</label>
					<input class="color" type="text" id="extra_cover_body_color" name="extra[cover_body_color]" value="<?php echo $timeline['extra']['cover_body_color']; ?>">
				</div>
			</div><!--/.wrap-->
		</div><!--/.row-->
		<div id="editor_config_exterior_basic_slide" class="row">
			<legend>슬라이드</legend>
			<div class="wrap">
				<div class="field inline">
					<label for="extra_slide_background_color">배경 색깔</label>
					<input class="color" type="text" id="extra_slide_background_color" name="extra[slide_background_color]" value="<?php echo $timeline['extra']['slide_background_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_slide_title_color">제목 글자 색깔</label>
					<input class="color" type="text" id="extra_slide_title_color" name="extra[slide_title_color]" value="<?php echo $timeline['extra']['slide_title_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_slide_body_color">본문 글자 색깔</label>
					<input class="color" type="text" id="extra_slide_body_color" name="extra[slide_body_color]" value="<?php echo $timeline['extra']['slide_body_color']; ?>">
				</div>
			</div><!--/.wrap-->
		</div><!--/.row-->
		<div id="editor_config_exterior_basic_back" class="row">
			<legend>뒷표지</legend>
			<div class="wrap">
				<div class="field block">
					<label for="asset_back_background_image_uploader">배경 그림</label>
					<div class="asset_cover_background_image_wrap">
						<a class="asset_cover_background_image_uploader" id="asset_back_background_image_uploader" href="<?php echo JFE_CONTRIBUTE_URI.'/filemanager/filemanager/dialog.php?type=2&subfolder=&editor=&field_id=asset_back_background_image&lang=ko_KR&taogi_select_mode='; ?>"><div class="asset_cover_background_image_preview" id="asset_back_background_image_preview"></div></a>
						<a class="asset_cover_background_image_remover" id="asset_back_background_image_remover" href="#asset_back_background_image_preview"><span>이미지 삭제</span></a>
					</div>
					<input class="asset_cover_background_image" type="hidden" id="asset_back_background_image" name="asset[back_background_image]" value="<?php print $timeline['asset']['back_background_image']; ?>" data-placeholder="<?php echo TRANSPARENT_PLACEHOLDER; ?>">
				</div>
				<div class="field inline">
					<label for="extra_back_background_color">배경 색깔</label>
					<input class="color" type="text" id="extra_back_background_color" name="extra[back_background_color]" value="<?php echo $timeline['extra']['back_background_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_back_title_color">제목 글자 색깔</label>
					<input class="color" type="text" id="extra_back_title_color" name="extra[back_title_color]" value="<?php echo $timeline['extra']['back_title_color']; ?>">
				</div>
				<div class="field inline">
					<label for="extra_back_body_color">본문 글자 색깔</label>
					<input class="color" type="text" id="extra_back_body_color" name="extra[back_body_color]" value="<?php echo $timeline['extra']['back_body_color']; ?>">
				</div>
			</div><!--/.wrap-->
		</div><!--/.row-->
	</fieldset><!--/#editor_config_exterior_basic-->
	<fieldset id="editor_config_exterior_advanced" class="column">
		<legend>고급 설정</legend>
		<div class="help"><p>주의: 이 항목에서 잘못된 값을 저장하면 오동작을 일으킬 수 있습니다.</p></div>
		<div class="wrap">
			<label>CSS 코드 </label>
			<div class="extra_css_wrap">
				<textarea id="extra_css" name="extra[css]"><?php
					print $extraCss;
				?></textarea>
			</div>
			<div class="buttons">
				<a id="extra_css_editor" class="button" href="javascript://" data-dummy-id="extra_css_dummy" data-origin-id="extra_css"><span>편집기 사용</span></a>
			</div>
		</div><!--/.wrap-->
	</fieldset><!--/#editor_config_exterior_advanced-->
</form>
</div>

