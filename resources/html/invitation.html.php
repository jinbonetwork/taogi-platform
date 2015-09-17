<?php print $content; ?>
<p>
	초대에 응할 의사가 있으시다면 아래 초대허락 버튼을 클릭하세요.
</p>
<p style="padding-top: 5px; padding-bottom: 15px; text-align: center;">
	<a href="<?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?>/authors/invitation/?email_id=<?php print $email; ?>&authtoken=<?php print $authtoken; ?>" target="_blank" style="padding: 15px; border: 2px solid #FF8888; background: #FF8888; color: #fff; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: bold; display: inline-block;">편집인 초대 승인</a>
</p>
<div style="padding-top: 5px; padding-bottom: 15px;">
	<p>
		이미 따오기 회원이시면 로그인만으로 공동 편집인으로 가입됩니다. 따오기 비회원이시면 간단한 회원가입 절차를 거치셔야 합니다.
	</p>
</div>
<div style="position: relative; z-index: 1; border:1px solid #ccc; padding: 25px 15px 15px 15px; margin-top: 30px; border-radius: 3px;">
	<h4 style="position: absolute; z-index: 2; top: -15px; left: 15px; background: #efefef; padding: 8px; border:1px solid #ccc; border-radius: 3px;">초대된 타임라인 정보</h4>
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
<?php	if($entry['asset']['cover_background_image']) {?>
			<td width="170">
				<img src="<?php print $entry['asset']['cover_background_image']; ?>" style="margin-right: 15px; width: 150px; height: 150px; overflow: hidden; background-color: #FFFFFF; border-radius: 100px; border: 3px solid #FFFFFF; box-shadow: 0 0 3px rgba(0, 0, 0, 0.25), inset 0 0 3px rgba(0, 0, 0, 0.25);">
			</td>
<?php	}?>
			<td>
				<ul style="margin: 0; padding: 0; list-style: none; line-height: 160%;">
					<li>타임라인 제목: <a href="<?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?>" target="_blank" style="color:#FF6600; text-decoration: none;"><?php print $entry['subject']; ?></a></li>
					<li>타임라인 주소: <a href="<?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?>" target="_blank" style="color:#FF6600; text-decoration: none;"><?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?></a>
					<li>
						<div>간략한 소개:</div>
						<div style="">
							<?php print $entry['summary']; ?>
						</div>
					</li>
				</ul>
			</td>
		</tr>
	</table>
</div>
<div style="position: relative; z-index: 1; border:1px solid #ccc; padding: 25px 15px 15px 15px; margin-top: 30px; border-radius: 3px;">
	<h4 style="position: absolute; z-index: 2; top: -15px; left: 15px; background: #efefef; padding: 8px; border:1px solid #ccc; border-radius: 3px;">초대한 제작자 정보</h4>
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
<?php	if($user['portrait']) {?>
			<td width="170">
				<img src="<?php print $user['portrait']; ?>" style="margin-right: 15px; width: 150px; height: 150px; overflow: hidden; background-color: #FFFFFF; border-radius: 100px; border: 3px solid #FFFFFF; box-shadow: 0 0 3px rgba(0, 0, 0, 0.25), inset 0 0 3px rgba(0, 0, 0, 0.25);">
			</td>
<?php	}?>
			<td>
				<ul style="margin: 0; padding: 0; list-style: none; line-height: 160^;">
					<li>이름: <?php print $user['display_name']; ?></li>
					<li>프로필 주소: <a href="<?php print $base_uri.$user['taoginame']; ?>/" target="_blank" style="color:#FF6600; text-decoration: none;"><?php print $base_uri.$user['taoginame']; ?></a></li>
					<li>
						<div>간략한 소개:</div>
						<div style="">
							<?php print $user['summary']; ?>
						</div>
					</li>
				</ul>
			</td>
		</tr>
	</table>
</div>
<div style="margin-top: 30px; padding: 20px; background: #333; color: #fff">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<a href="<?php print $base_uri; ?>"><img src="<?php print $base_uri; ?>resources/images/mail_top.png" style="width: 120px; background: #fff; border-radius: 55px;"></a>
			</td>
			<td>
				<p style="padding-left: 20px;">따오기 타임라인은 날짜 중심으로 구성된 프리젠테이션 문서를 제작할 수 있는 도구입니다.</p>
				<p style="padding-left: 20px;">따오기 타임라인은 오픈소스로 제작되었습니다. 소스는 <a href="https://github.com/jinbonetwork/taogi-platform" target="_blank" style="color: #fff;">https://github.com/jinbonetwork/taogi-platform</a>에서 다운받으실수 있으며, 개발 프로젝트팀에 참여도 환영합니다.</p>
			</td>
		</tr>
	</table>
</div>
