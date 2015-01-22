	<section id="create">
		<div id="taogi-create-menu-bar">
			<h1><?php print $editor_title; ?></h1>
			<ul class="menu">
				<!--
					공개/비공개 인터페이스
					1. 전환 후 button의 title 값을 data-enable-title 이나 data-disable-title 값으로 변경
					2. 권한이 없는 경우 button에 disable 클래스와 disabled 속성 추가

					버튼 그룹
					1. 개념적으로 나눌 필요는 없으므로 그룹을 별도의 블록으로 묶지 않는다.
					2. 그룹 내부의 li들은 in-group 클래스를 가진다.
					3. 그룹의 맨 왼쪽, 맨 오른쪽, 그 사이의 li들에게 각각 left, right, center 클래스를 지정한다.
					4. 참고: 미리보기와 모양설정 li는 그룹이지만 모양설정 권한이 없는 경우 미리보기 li만 남으므로 in-group 클래스를 지정하지 않는다.
				-->
				<!-- 공개 상태일 때 -->
				<li class="status-update status-publish save icon in-group left<?php if($entry['is_public']) print ' is-public'; ?>">
					<button class="button" title="" data-title="공개 상태입니다. 클릭하면 감춥니다." data-disable-title="공개 상태입니다."><span>공개 상태</span></button><!-- 비공개 상태로 바꾼 뒤 저장 -->
				</li>
				<!-- 비공개 상태일 때 -->
				<li class="status-update status-draft save icon in-group left<?php if($entry['is_public']) print ' is-public'; ?>">
					<button class="button" title="" data-title="잠금 상태입니다. 클릭하면 공개합니다."><span>잠금 상태</span></button><!-- 공개 상태로 바꾼 뒤 저장 -->
				</li>
				<li class="status-keep save in-group right">
					<button class="button" title="저장하기(Ctrl+S)"><span>저장하기</span></button><!-- 그냥 저장 -->
				</li>
				<li class="preview icon in-group left">
					<button class="button" title="미리보기"><span>미리보기</span></button>
				</li>
				<li class="configure in-group right">
					<button class="button" title="모양설정"><span>모양설정</span></button>
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
										<input class="file" type="text" id="asset_media" name="asset[media]" value="<?php print $timeline['asset']['media']; ?>"><a class="upload" href="">업로드</a>
									</div>
									<div class="field">
										<label for="asset_credit">표지 그림 출처</label>
										<input type="text" id="asset_credit" name="asset[credit]" value="<?php print $timeline['asset']['author']; ?>">
									</div>
									<div class="field">
										<label for="asset_caption">표지 그림 설명</label>
										<input type="text" id="asset_caption" name="asset[caption]" value="<?php print $timeline['asset']['caption']; ?>">
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
				<input type="hidden" id="uid" name="uid" value="<?php print $user['uid']; ?>">
				<input type="hidden" id="eid" name="eid" value="<?php print $eid; ?>">
				<input type="hidden" id="vid" name="vid" value="<?php print $vid; ?>">
				<input type="hidden" id="nickname" name="nickname" value="<?php print $entry['nickname']; ?>">
				<input type="hidden" id="is_public" name="is_public" value="<?php print ($entry['is_public'] ? $entry['is_public'] : 0); ?>">
				<h1 id="timeline_headline" class="editable article" contenteditable="true" data-name="headline" data-default-value="타임라인 제목"><?php print $timeline['headline']; ?></h1>
				<p id="timeline_url_structure"><code>http://taogi.net/<span id="taogi_permalink"><?php print ($user['taoginame'] ? $user['taoginame'] : $user['uid']); ?></span>/<span id="timeline_url" class="editable article" contenteditable="true" data-name="url" data-default-value="timeline"><?php print $entry['nickname']; ?></span></code></p>
				<hr>
				<p id="timeline_text" class="editable article" contenteditable="true" data-name="text" data-default-value="타임라인에 대한 간단한 설명"><?php print $timeline['text']; ?></p>
				<p id="extra_author_structure"><span id="extra_author_label">작성자: </span><span id="extra_author" name="extra[author]" class="editable article" contenteditable="true" data-default-value="<?php print $user['name']; ?>"><?php print ($timeline['extra']['author'] ? $timeline['extra']['author'] : $user['name']); ?></span></p>
			</fieldset>
			<section class="slide-items" id="date" data-cid="0">
