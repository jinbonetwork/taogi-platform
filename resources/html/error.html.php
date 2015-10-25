<?php
switch($type) {
	case 400:
		$error_class = "BAD_REQUEST";
		break;
	case 401:
		$error_class = "UNAUTHORIZED";
		break;
	case 403:
		$error_class = "ACCESS_DENIED";
		break;
	case 404:
		$error_class = "PAGE_NOT_FOUND";
		break;
	case 423:
		$error_class = "PAGE_LOCKED";
		break;
	case 503:
		$error_class = "SERVICE_UNAVAIL";
		break;
	case 505:
	default:
		$error_class = "SYSTEM_ERROR";
		break;
}
if(defined("__Require_Taogi_Error_Header__") && __Require_Taogi_Error_Header__ == true) {?>
<!DOCTYPE html>
<html id="taogi-net">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1">
	<title><?php print $error_class; ?></title>
	<link rel="stylesheet" href="<?php print JFE_RESOURCE_URI; ?>/css/error.css">
</head>
<body id="taogi-net">
	<div id="taogi-net-site-error-container" class="container">
<?php }?>
		<div class="error <?php print $error_class; ?>">
			<div class="message-box">
				<div class="info block">
					<div class="inner">
						<img src="<?php print JFE_RESOURCE_URI; ?>/images/mail_top.png">
						<h1><span class="code"><?php print $type; ?></span><span class="message"><?php print str_replace("_"," ",$error_class); ?></span></h1>
					</div>
				</div>
				<div class="message block">
					<div class="inner">
						<h2>오류메세지</h2>
						<p><?php print $message; ?></p>
					</div>
				</div>
				<div class="contact">
					자세한 문의사항은 E-Mail: truesig@jinbo.net 으로 해주세요.
					<div class="home-button"><a href="<?php print JFE_URI; ?>">홈으로</a></div>
				</div>
			</div>
		</div>
<?php if(defined("__Require_Taogi_Error_Header__") && __Require_Taogi_Error_Header__ == true) {?>
	</div>
</body>
<html>
<?php }?>
