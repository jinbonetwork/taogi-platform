<?php
define('__JFE__',true);
@error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','1');

define('ROOT',dirname(dirname(__FILE__)));
define('JFE_PATH',ROOT);
define('JFE_URI',dirname($_SERVER["SCRIPT_NAME"])."/");
define('JFE_CONTRIBUTE_PATH',JFE_PATH."/contribute");

require_once ROOT."/framework/classes/Objects.class.php";
require_once ROOT."/framework/model/Config.class.php";
require_once ROOT."/framework/model/Context.class.php";
require_once ROOT."/framework/classes/Acl.class.php";
require_once ROOT."/framework/classes/Respond.class.php";
require_once ROOT."/framework/classes/RespondJson.class.php";

global $context, $config;
$context = Model_Context::instance();
$config = Model_Config::instance();

require_once ROOT."/framework/library/common.php";

if($context->getProperty('service.useoauth') != true) {
	RespondJson::ResultPage(array(5,'Gmail SMTP 설정이 되어 있지 않습니다.'));
}
if(!$context->getProperty('service.oauthClientId')) {
	RespondJson::ResultPage(array(5,'Gmail SMTP 설정이 되어 있지 않습니다.'));
}
if(!$context->getProperty('service.oauthClientSecret')) {
	RespondJson::ResultPage(array(5,'Gmail SMTP 설정이 되어 있지 않습니다.'));
}
if(!$context->getProperty('service.oauthRefreshToken')) {
	RespondJson::ResultPage(array(5,'Gmail SMTP 설정이 되어 있지 않습니다.'));
}
if( ( $_GET['oauthClientId'] != $context->getProperty('service.oauthClientId') ) ||
	( $_GET['oauthClientSecret'] != $context->getProperty('service.oauthClientSecret') ) ||
	( $_GET['oauthRefreshToken'] != $context->getProperty('service.oauthRefreshToken') )
	) {
	RespondJson::ResultPage(array(5,'Gmail API가 동일하지 않습니다.'));
}

if(!$_GET['receivers']) {
	RespondJson::ResultPage(array(1,'메일 수신자 정보를 입력하세요.'));
}
if(!is_array($_GET['receivers']) || @count($_GET['receivers']) < 1) {
	RespondJson::ResultPage(array(2,'메일 수신자 목록은 배열행태입니다.'));
}
if(!$_GET['subject']) {
	RespondJson::ResultPage(array(3,'제목을 입력하세요.'));
}
if(!$_GET['content']) {
	RespondJson::ResultPage(array(4,'내용을 입력하세요.'));
}

date_default_timezone_set('Asia/Seoul');

require_once JFE_CONTRIBUTE_PATH.'/PHPMailer/PHPMailerAutoload.php';
require_once JFE_CONTRIBUTE_PATH.'/PHPMailer/vendor/autoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailerOAuth;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'txt';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Set AuthType
$mail->AuthType = 'XOAUTH2';

//User Email to use for SMTP authentication - Use the same Email used in Google Developer Console
$mail->oauthUserEmail = $context->getProperty('service.smtpUsername');

//Obtained From Google Developer Console
$mail->oauthClientId = $context->getProperty('service.oauthClientId');

//Obtained From Google Developer Console
$mail->oauthClientSecret = $context->getProperty('service.oauthClientSecret');

//Obtained By running get_oauth_token.php after setting up APP in Google Developer Console.
//Set Redirect URI in Developer Console as [https/http]://<yourdomain>/<folder>/get_oauth_token.php
// eg: http://localhost/phpmail/get_oauth_token.php
$mail->oauthRefreshToken = $context->getProperty('service.oauthRefreshToken');

//Set who the message is to be sent from
//For gmail, this generally needs to be the same as the user you logged in as
$mail->setFrom($context->getProperty('service.senderEmail'), "=?UTF-8?B?".base64_encode($context->getProperty('service.senderName'))."?=");

//Set who the message is to be sent to
foreach($_GET['receivers'] as $receiver) {
	$mail->addAddress( $receiver['email'], "=?UTF-8?B?".base64_encode($receiver['name'])."?=" );
}

//Set the subject line
$mail->Subject = "=?UTF-8?B?".base64_encode($_GET['subject'])."?=";

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($_GET['content'], dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->AltBody = $mail->AltBody  = strip_tags($_GET['content']);

//send the message, check for errors
if (!$mail->send()) {
    RespondJson::ResultPage(array(5,$mail->ErrorInfo));
} else {
    RespondJson::ResultPage(array(0,"Message sent!"));
}
?>
