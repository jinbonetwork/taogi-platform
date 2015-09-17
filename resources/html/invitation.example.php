<h3 style="padding: 18px 15px; margin: 20px 0 10px 0; background: #333; border-radius: 3px; color: #fff; line-height: 1.6em;">
	<a href="<?php print $base_uri.$user['taoginame']; ?>/" target="_blank" style="color: #fff;"><span id="invitation_name"><?php print $user['display_name']; ?></span></a>님이 <span id="invitation_email" style="color:#fff"><?php print $invite_email; ?></span>님을 따오기 <a href="<?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?>" target="_blank" style="color: #fff;">'<?php print $entry['subject']; ?>' 타임라인</a>의 공동 편집인으로 초대했습니다.
</h3>
<div style="padding-top: 5px; padding-bottom: 15px;">
	<p>
		<a href="<?php print $base_uri.$user['taoginame']; ?>/" target="_blank" style="color: #333;"><span id="invitation_name"><?php print $user['display_name']; ?></span></a>님이 <span id="invitation_email2"><?php print $invite_email; ?></span>님을 따오기 <a href="<?php print $base_uri.$user['taoginame']; ?>/<?php print $entry['nickname']; ?>" target="_blank" style="color: #333;">'<?php print $entry['subject']; ?>' 타임라인</a>의 공동 편집인으로 초대했습니다.
	</p>
	<p>
		<?php print $entry['subject']; ?> 타임라인의 공동 편집인이 되시면, 타임라인을 같이 만드실 수 있습니다.
	</p>
</div>
