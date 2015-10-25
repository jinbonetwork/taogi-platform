<div id="invitation">
	<div id="join">
		<div class="wrap">
			<h2>편집인 초대수락을 위한 회원가입</h2>
			<div class="entry-author-invitation-info ui-form-info">
				<ul>
					<li class="summary-profile">
						<h4>초대한 타임라인</h4>
<?php				if( $entry['asset']['cover_background_image'] ) {?>
						<a href="<?php print $baselink; ?>" target="_blank"><img src="<?php print $entry['asset']['cover_background_image']; ?>"></a>
<?php				}?>
						<h5><a href="<?php print $baselink; ?>" target="_blank"><?php print $entry['subject']; ?></a></h5>
						<p><a href="<?php print $baselink; ?>" target="_blank"><?php print $entry['summary']; ?></a></p>
					</li>
					<li class="summary-profile">
						<h4>초대자 정보</h4>
<?php				if( $owner['portrait'] ) {?>
						<a href="<?php print $base_uri.$owner['taoginame']; ?>" target="_blank"><img src="<?php print $owner['portrait']; ?>"></a>
<?php				}?>
						<h5><a href="<?php print $base_uri.$owner['taoginame']; ?>" target="_blank"><?php print $owner['display_name']; ?></a></h5>
						<p><a href="<?php print $base_uri.$owner['taoginame']; ?>" target="_blank"><?php print $owner['summary']; ?></a></p>
					</li>
				</ul>
			</div>
			<div class="entry-author-invitation-info ui-form-info">
				<ul class="login-notice">
					<li>따오기 공동편집인으로 활동하시려면, 따오기 계정이 필요합니다.</li>
					<li>이미 따오기 회원이시면 <a class="login button" href="<?php print url("login",array('ssl'=>true,'query'=>array('requestURI'=>$_SERVER['REQUEST_URI'],'email_id'=>$params['email_id'],'authtoken'=>$params['authtoken']))); ?>">로그인</a>만으로 공동 편집인으로 등록됩니다. 따오기 비회원이시면 아래에 보이는 간단한 <a class="regist button" href="javascript://">회원가입</a> 절차를 거치셔야 합니다.</li>
					<li>따오기 TimeLine 서비스는 <a href="http://www.socialfunch.org" target="_blank">소셜펀치(SocialFunch)</a> 계정과 연동하여 사용하실 수 있습니다.</li>
					<li>따오기 비회원이지만, 소셜펀치에는 가입되어 있으신 분은 바로 <a class="login button" href="<?php print url("login",array('ssl'=>true,'query'=>array('requestURI'=>$_SERVER['REQUEST_URI'],'email_id'=>$params['email_id'],'authtoken'=>$params['authtoken']))); ?>">로그인</a>하시면 됩니다.</li>
				</ul>
			</div>
			<form id="join_form"name="join_form"class="ui-form ui-user-form" method="post" autocomplete="off">
				<legend>회원 가입하기</legend>
				<input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
				<input type="hidden" name="email_id" value="<?php print $params['email_id']; ?>" />
				<input type="hidden" name="authtoken" value="<?php print $params['authtoken']; ?>" />
				<?php print $profile_form;?>
				<?php print $name_form;?>
				<?php print $password_form;?>
				<div class="buttons">
					<button class="button join" type="submit"><span>회원가입</span></button>
					<button class="button cancel" type="button" onclick="hostory.back();"><span>취소</span></button>
				</div>
			</form>
		</div><!--/.wrap-->
	</div><!--/#join-->
</div><!--/#joinus-->
