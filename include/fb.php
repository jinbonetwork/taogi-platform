<?php
if(!defined('__JFE__') && defined('ROOT')) {
	define('__JFE__',true);
	require_once ROOT."/config/config.php";

	global $context, $config;
	$context = Model_Context::instance();
	$context->setProperty('service.base_uri',$uri->uri['root']);
	$config = Model_Config::instance();
}
if($context->getProperty('service.fb_App_ID')) {
	require_once JFE_CONTRIBUTE_PATH."/facebook-php-sdk/src/facebook.php";
	global $facebook;
	$facebook = new Facebook(array(
		'appId'  => $context->getProperty('service.fb_App_ID'),
		'secret' => $context->getProperty('service.fb_App_Secret'),
	));
	$fb_user = $facebook->getUser();
	importLibrary("fb");
}
?>
