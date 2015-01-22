<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
	<body>
		<table cellpadding="0" cellspacing="0" style="width:600px;padding:0;border:0px;border-spacing:0;">
			<tr>
				<td style="padding:0; text-align:center;"><img src="<?php print $base_uri; ?>resources/images/mail_top.png" /></td>
			</tr>
			<tr>
				<td height="26" style="text-align:right;padding:3px 0 0 0;color:#1490d9;font-weight:bold;font-size:12px;font-family:'helvetica','맑은고딕','나눔고딕','applegothic','돋움';vertical-align:top;">  
					From <span style="color:#1490d9"><?php print $sender; ?></span> (<?php print date("Y.m.d"); ?>)
				</td>
			</tr>
			<tr>
				<td style="padding:50px 0 30px 40px;font-weight:bold;font-size:20px;font-family:'helvetica','맑은고딕','나눔고딕','applegothic','돋움';">
<?php			if($uri) {?>
					<a href="<?php print $uri; ?>" style="color:#1490d9;text-decoration:none;"><?php print $title; ?></a>
<?php			} else {?>
					<span style="color:#1490d9;"><?php print $title; ?></span>
<?php			}?>
				</td>
			</tr>
			<tr>
				<td style="padding:0px 40px 100px 40px;color:#666;font-weight:normal;line-height:160%;font-size:14px;font-family:'helvetica','맑은고딕','나눔고딕','applegothic','돋움';text-align:justify">
                    <?php print $content; ?>
                </td>
			</tr>
<?php	if($link && $link_title) {?>
			<tr>
				<td style="padding:0px 40px 100px 40px;color:#666;font-weight:normal;line-height:160%;font-size:14px;font-family:'helvetica','맑은고딕','나눔고딕','applegothic','돋움';text-align:center;">
					<a href="<?php print $link; ?>" target="_blank" style="background: #1490D8; border-radius: 3px 3px 3px 3px; border: none; margin: 0; padding: 5px 8px; color: white; font-size: 14px; font-family:'helvetica','맑은고딕','나눔고>딕','applegothic','돋움'; cursor:pointer; text-align:center; text-decoration: none;"><?php print $link_title; ?></a>
				</td>
			</tr>
<?php	}?>
			<tr>
				<td style="color:#999;font-size:12px;font-family:'helvetica','맑은고딕','나눔고딕','applegothic','돋움';padding:10px 0 0 0;border-top:#ddd 1px solid;text-align:center">
					<strong>따오기</strong> 타임라인
				</td>
			</tr>
		</table>
	</body>
</html>
