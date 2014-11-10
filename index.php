<?php
define('__JFE__',true);
define('ROOT','.');

/**
 * @brief 필요한 설정 파일들 include
 **/
require_once('./config/config.php');
define('__JFE_LOADED_CLASS__',true);

$browser = Utils_Browser::instance();

global $context, $config;
$uri = Model_URIHandler::instance();
$context = Model_Context::instance();
$context->setProperty('service.base_uri',$uri->uri['root']);
$config = Model_Config::instance();

try {
	if(!is_null($context->getProperty('database.DB'))) {
		$db = $context->getProperty('database.*');
		$dbm = DBM::instance();
		$dbm->bind($db,1);
		register_shutdown_function( array($dbm,'release') );
		$uri->URIParser();
	}

	/**
	 * @brief Funch는 URL을 기반으로 소스 경로와 controller class 를 지정한다.
	 * 또한 각각의 파일에는 Validate 정의와 권한정보가 포함되어야 한다.
	 * Validate는 $IV = array()
	 * Privilege는 $Acl = ''
	 **/
	include_once $uri->uri['appPath']."/".$uri->uri['appFile'].".php";
	$controller_class = $uri->uri['appClass'];

	/**
	* check Basic Post/GET variable validation.
	**/
	$valid = true;
	if (isset($IV)) $valid = $valid && Validator::validate($IV);

	// Basic SERVER variable validation to prevent hijacking possibility.
	$basicIV = array(
		'SCRIPT_NAME' => array('string'),
		'REQUEST_URI' => array('string'),
		'REDIRECT_URL' => array('string', 'mandatory' => false)
	);
	$valid = $valid && Validator::validateArray($_SERVER, $basicIV);

	// Basic URI information validation.
	if (!$valid) {
		header('HTTP/1.1 404 Not Found');
		exit;
	}

	/**
	 * @brief session include and start
	 **/
	if(!defined('NO_SESSION')) {
		require_once JFE_SESSION_PATH;
		require_once ROOT."/include/fb.php";
	}

	/*
	 * @brief Acl(Access Controll Logic
	**/
	$taogi_id = $uri->taogiid;
	$__Acl = Acl::instance();
	$__Acl->setAcl(($taogi_id ? $taogi_id : 0),$uri->uri,$Acl);
	$__Acl->check($taogi_id ? $taogi_id : 0);

	$controller = new $controller_class;
	$controller->handle($uri->params);

	$dbm->release();
} catch(Exception $e) {
	$logger = Logger::instance();
	$logger->Error($e);
}
?>
