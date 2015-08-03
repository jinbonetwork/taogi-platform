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
				<a href="#" class="button" title="모양설정" data-submenu="editor_config_exterior" data-submenu-container="editor_config_exterior_wrap"><span>모양설정</span></a>
			</li>
		</ul>
	</div>
	<div id="taogi-create-editor">
		<div id="editor_config_exterior_wrap" class="wrap">
			<?php require_once dirname(__FILE__).'/config.advanced.html'; ?>
		</div>
		<div id="timeline_editor_wrap" class="wrap">
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
					<p id="timeline_text" class="editable article" contenteditable="true" data-name="text" data-default-value="타임라인에 대한 간단한 설명"><?php print strip_tags($timeline['text']); ?></p>
					<p id="extra_author_structure"><span id="extra_author_label">작성자: </span><span id="extra_author" name="extra[author]" class="editable article" contenteditable="true" data-default-value="<?php print $user['name']; ?>"><?php print ($timeline['extra']['author'] ? $timeline['extra']['author'] : $user['name']); ?></span></p>
				</fieldset>
				<section class="slide-items" id="date" data-cid="0">
