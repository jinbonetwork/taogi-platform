<?php
function get_filemanager_uri($field_id, $type = 'all'){
	$type_table = array( 'default', 'image', 'all', 'video', );
	$attr_given = array(
		'type'		=> array_search( $type, $type_table ),
		'subfolder'	=> '',
		'editor'	=> 'mce_0',
		'field_id'	=> $field_id,
		'lang'		=> 'ko_KR',
	);
	foreach( $attr_given as $key => $value ){
		$attr[] = $key . '=' . $value;
	}
	$uri = implode( '&', $attr );
	$uri = '/contribute/filemanager/filemanager/dialog.php?' . $uri;
	return $uri;
}

?>
	<section id="create">
		<div id="taogi-create-menu-bar">
			<h1>따오기 타임라인만들기</h1>
			<ul class="menu">
				<li class="save">
					<button class="button">저장하기(Ctrl+S)</button>
				</li>
				<li class="preview">
					<button class="button">미리보기</button>
				</li>
				<li class="configure">
					<button class="button">모양설정</button>
					<div id="extra_form" class="submenu">
						<form name="timeline_extra" onsubmit="return false;">
							<fieldset>
								<legend>전체 타임라인</legend>
								<div class="wrap">
									<div class="field">
										<label for="extra_theme_background">전체 배경색</label>
										<input class="color" type="text" id="extra_theme_background" name="extra[theme][background]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_canvas">타임라인 배경색</label>
										<input class="color" type="text" id="extra_theme_canvas" name="extra[theme][canvas]" value="">
									</div>
								</div>
							</fieldset>
							<fieldset class="cover extendable collapsed">
								<legend><a href="javascript:">표지 설정 <span class="show">보이기</span><span class="hide">감추기</span></a></legend>
								<div class="wrap">
									<div class="field">
										<label for="asset_media">표지 그림 주소</label>
										<input class="file" type="text" id="asset_media" name="asset[media]" value=""><a class="upload" href="<?php echo get_filemanager_uri('asset_media', 'image'); ?>">업로드</a>
									</div>
									<div class="field">
										<label for="asset_credit">표지 그림 출처</label>
										<input type="text" id="asset_credit" name="asset[credit]" value="">
									</div>
									<div class="field">
										<label for="asset_caption">표지 그림 설명</label>
										<input type="text" id="asset_caption" name="asset[caption]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_cover_background">표지 배경색</label>
										<input class="color" type="text" id="extra_theme_cover_background" name="extra[theme][cover][background]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_cover_subject_font-family">표지 제목 글자 모양</label>
										<input type="text" id="extra_theme_cover_subject_font-family" name="extra[theme][cover][subject][font-family]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_cover_subject_color">표지 제목 글자 색</label>
										<input class="color" type="text" id="extra_theme_cover_subject_font-family" name="extra[theme][cover][subject][color]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_cover_summary_font-family">표지 본문 글자 모양</label>
										<input type="text" id="extra_theme_cover_summary_font-family" name="extra[theme][cover][summary][font-family]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_cover_summary_color">표지 본문 글자 색</label>
										<input class="color" type="text" id="extra_theme_cover_summary_font-family" name="extra[theme][cover][summary][color]" value="">
									</div>
									<input type="hidden" name="extra[template]" value="touchcarousel">
								</div>
							</fieldset>
							<fieldset>
								<legend>슬라이드</legend>
								<div class="wrap">
									<div class="field">
										<label for="extra_theme_post_background">슬라이드 배경색</label>
										<input class="color" type="text" id="extra_theme_post_background" name="extra[theme][post][background]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_post_font-family">슬라이드 제목 글자 모양</label>
										<input type="text" id="extra_theme_post_subject_font-family" name="extra[theme][post][subject][font-family]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_post_subject_color">슬라이드 제목 글자 색</label>
										<input class="color" type="text" id="extra_theme_post_subject_font-family" name="extra[theme][post][subject][color]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_post_summary_font-family">슬라이드 본문 글자 모양</label>
										<input type="text" id="extra_theme_post_summary_font-family" name="extra[theme][post][summary][font-family]" value="">
									</div>
									<div class="field">
										<label for="extra_theme_post_summary_color">슬라이드 본문 글자 색</label>
										<input class="color" type="text" id="extra_theme_post_summary_font-family" name="extra[theme][post][summary][color]" value="">
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</li>
			</ul>
		</div>
		<form id="timeline_editor">
			<fieldset class="timeline_properties">
				<input type="hidden" name="type" value="default">
				<input type="hidden" id="eid" name="eid" value="">
				<h1 id="timeline-headline" class="field editable article" contenteditable="true" data-name="headline" data-default-value="타임라인 제목"></h1>
				<hr>
				<p id="timeline-text" class="field editable article" contenteditable="true" data-name="text" data-default-value="타임라인에 대한 간단한 설명"></p>
				<div id="extra_author" name="extra[author]" class="field text editable article" contenteditable="true" data-default-value="<?php print $user['name']; ?>"></div>
			</fieldset>
			<section class="slide-items" id="date" data-cid="0">
				<div class="slide-item" id="date___SLIDE_ID__" data-id="__SLIDE_ID__">
					<fieldset class="slide extendable"><!--슬라이드 하나 당 필드셋 하나-->
						<legend class="article" data-default-value="슬라이드"><a href="javascript://"></a></legend>
						<div class="wrap">
							<div class="field pubDate">
								<time id="" class="editable article" contenteditable="true" data-name="startDate" data-default-value="<?php print date("Y.m.d"); ?>"></time><button class="button datepicker">지정하기</button>
							</div>
							<div class="feature">
								<p class="switch">
									<button class="button media add" type="button" data-target="#date___SLIDE_ID___media"><span>미디어 추가</span></button>
								</p>
							</div>
							<div class="title-description">
								<h2 class="editable article" contenteditable="true" data-name="headline" data-default-value="제목"></h2>
								<div class="field description">
									<p class="editable article" contenteditable="true" data-name="text" data-default-value="본문"></p>
								</div>
							</div>
							<input type="hidden" name="date[__SLIDE_ID__][asset][media]" value="피쳐로 설정된 미디어 소스">
							<input type="hidden" name="date[__SLIDE_ID__][asset][thumbnail]" value="피쳐로 설정된 미디어 썸네일">
							<input type="hidden" name="date[__SLIDE_ID__][asset][credit]" value="피쳐로 설정된 미디어 출처">
							<input type="hidden" name="date[__SLIDE_ID__][asset][caption]" value="피쳐로 설정된 미디어 설명">
							<div class="media-nav">
								<ul class="thumbnails">
									<li class="thumbnail image featured"><img src="http://act.jinbo.net/drupal/sites/default/files/images/DSCF5895.preview.JPG" alt=" "><a class="remove cornered" href="javascript:"><span>삭제</span></a></li>
									<li class="thumbnail image"><img src="http://blog.jinbo.net/attach/3778/1099594379.jpg" alt=" "><a class="remove cornered" href="javascript:"><span>삭제</span></a></li>
									<li class="thumbnail googledoc"><span>&lt;인권시민단체 워크샵&gt; 패킷감청, 무엇이 문제인가</span><a class="remove cornered" href="javascript:"><span>삭제</span></a></li>
									<li class="thumbnail vimeo current"><span></span><a class="remove cornered" href="javascript:"><span>삭제</span></a></li>
									<li class="add"><a href="javascript:"><span>미디어 추가</span></a></li>
									<li class="fieldset-container">
										<fieldset class="media">
											<legend class="media">미디어 __MEDIA_ID__</legend>
											<div class="wrap">
												<div class="field console">
													<label for="date___SLIDE_ID___asset___MEDIA_ID__"><input type="radio" id="date___SLIDE_ID___asset___MEDIA_ID__" name="date[__SLIDE_ID__][asset]" value="__MEDIA_ID__">대표 미디어로 사용</label>
													<a class="remove labeled" href="javascript:"><span>미디어 삭제</span></a>
												</div>
												<figure class="preview"></figure>
												<div class="wrap-inner">
													<div class="field source">
														<label for="date___SLIDE_ID___media___MEDIA_ID___media">미디어 소스</label>
														<!--div class="text file" contenteditable="true" placeholder="소스"></div-->
														<textarea class="text file" id="date___SLIDE_ID___media___MEDIA_ID___media" name="date[__SLIDE_ID__][media][__MEDIA_ID__][media]"></textarea>
														<a class="upload" href="<?php echo get_filemanager_uri('date___SLIDE_ID___media___MEDIA_ID___media', 'all'); ?>"><span>업로드</span></a>
													</div>
													<div class="field thumbnail">
														<label for="date___SLIDE_ID___media___MEDIA_ID___thumbnail">미디어 썸네일</label>
														<input class="text file" type="text" id="date___SLIDE_ID___media___MEDIA_ID___thumbnail" name="date[__SLIDE_ID__][media][__MEDIA_ID__][thumbnail]" value="" placeholder="미디어 썸네일">
														<a class="upload" href="<?php echo get_filemanager_uri('date___SLIDE_ID___media___MEDIA_ID___thumbnail', 'image'); ?>"><span>업로드</span></a>
													</div>
													<div class="field credit">
														<label for="date___SLIDE_ID___media___MEDIA_ID___credit">미디어 출처</label>
														<input class="text" type="text" id="date___SLIDE_ID___media___MEDIA_ID___credit" name="date[__SLIDE_ID__][media][__MEDIA_ID__][credit]" value="" placeholder="출처">
													</div>
													<div class="field caption">
														<label for="date___SLIDE_ID___media___MEDIA_ID___caption">미디어 설명</label>
														<input class="text" type="text" id="date___SLIDE_ID___media___MEDIA_ID___caption" name="date[__SLIDE_ID__][media][__MEDIA_ID__][caption]" value="" placeholder="설명">
													</div>
												</div>
												<a class="close cornered labeled" href="javascript:"><span>창 닫기</span></a>
											</div>
										</fieldset>
									</li>
								</ul>
							</div>
							<div class="field console">
								<button class="button save" type="button">중간 저장</button>
								<a class="remove cornered" href="javascript:"><span>이 슬라이드 삭제</span></a>
							</div>
						</div>
					</fieldset>
					<div class="field console">
						<button class="button article add" type="button" data-target="#date___SLIDE_ID__">슬라이드 추가</button>
					</div>
				</div>
			</section>
			<div class="field console">
				<button class="button save submit" type="button">저장</button>
				<a class="button remove" href="javascript:">취소</a>
			</div>
			<div id="media_inline_form">
				<div class="media-inline-form-skin">
					<div class="media-inline-form-wrap">
						<div class="media-inline-form-outer">
							<div class="media-inline-form-header">
								<label for="media_source">미디어 URL 또는 소스</label>
							</div>
							<div class="media-inline-form-inner">
								<textarea class="file" id="mediasource" name="mediasource"></textarea><a class="upload" href=""><span>업로드</span></a>
							</div>
							<div class="media-inline-form-footer">
								<button class="ok" type="button">입력</button>
								<button class="cancel" type="button">취소</button>
							</div>
						</div>
						<a title="Close" class="media-inline-form-close" href="javascript://"></a>
					</div>
				</div>
				<div class="media-inline-form-overlay"></div>
			</div>
		</form>
	</section>
