<div id="taogi-gnb">
	<div id="taogi-gnb-wrap">
		<div id="taogi-gnb-header">
			<h1 class="title"><?php print ($timeline['headline'] ? $timeline['headline'] : $title); ?></h1>
			<p class="description"><?php print ($timeline['text'] ? $timeline['text'] : $description); ?></p>
<?php
	if(is_timeline()) {
?>
			<div class="share">
				<h2 class="subtitle">공유</h2>
				<ul class="social">
					<li class="twitter"><a href="https://twitter.com/share?text=" target="_blank"><span>트위터에 링크하기</span></a></li>
					<li class="facebook"><a href="https://facebook.com/sharer.php?u=<?php print $permalink; ?>" target="_blank"><span>페이스북에 링크하기</span></a></li>
					<li class="googleplus"><a href="https://plus.google.com/share?url=<?php print $permalink; ?>" target="_blank"><span>구글플러스에 링크하기</span></a></li>
					<li class="kakaotalk"><a href="https://plus.google.com/share?url=<?php print $permalink; ?>" target="_blank"><span>카카오톡에 링크하기</span></a></li>
					<li class="embed" style="display:block"><a href="#"><span>홈페이지에 붙여넣기</span></a></li>
				</ul>
			</div>
<?php
	}
?>
		</div>
		<div id="taogi-gnb-body">
		</div>
		<div id="taogi-gnb-footer">
<?php
	function is_timeline() {
		global $taogiid;
		return ($taogiid ? true : false);
	}
	if(is_timeline()) {
		if(user_logged_in()) {
			$controls = new Markup_Controls('entrySidebar','entry','eid');
			$controls->printControls($entry);
		}
	}
?>
			<div id="user-console" class="ui-controls ui-console icon label list">
				<h3>사용자</h3>
				<ul class="user">
					<li class="home"><a href="/"><span>첫 페이지</span></a></li>
<?php		if(user_logged_in() > 0) {
					$profile_url = url($user['taoginame']);
					/*
					if($user['favicon']){
						if(preg_match("/http:\/\//i",$user['favicon'])) {
							$avatar_src = $user['favicon'];
						} else {
							$avatar_src = url($user['favicon'],array('ssl'=>false));
						}
					} else {
						$avatar_src = JFE_RESOURCE_URI."/images/user_default.png";
					}
					$avatar_img = '<img src="'.$avatar_src.'" alt="">';
					 */
					$avatar_img = '';
?>
					<li class="profile"><a href="<?php print $profile_url; ?>"><span><?php print $avatar_img; ?><?php print $user['nickname']; ?></span></a></li>
<?php		}?>
					<li class="write"><a href="<?php print url('create'); ?>"><span>타임라인 만들기</span></a></li>
<?php		if(!user_logged_in()) {?>
					<li class="login"><a class="fancybox ajax" href="<?php print url('login'); ?>"><span>로그인</span></a></li>
					<li class="register"><a class="fancybox ajax" href="<?php print url('regist'); ?>"><span>가입하기</span></a></li>
<?php		} else {?>
					<li class="logout"><a href="<?php print url('login/logout'); ?>"><span>로그아웃</span></a></li>
<?php		}?>
				</ul>
			</div><!--/#user-console-->
		</div>
	</div>
</div>
