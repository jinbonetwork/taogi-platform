<?php
/**
 * @file	config/config.php
 * @brief	기본적으로 사용하는 환경 설정 변수 값 설정 및 class 파일의 include
 **/

@error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','1');

if(!defined('__JFE__')) exit();

/**
 * @brief JinboNet Donate System의 전체 버젼 표기
 **/
define('JFE_NAME', 'Taogi');
define('JFE_VERSION', '0.5');

/**
 * @brief JinboNet Donate System이 설치된 장소의 base path를 구함
 **/
define('JFE_PATH',str_replace('/config/config.php','',str_replace('\\', '/', __FILE__)));
if(!defined('JFE_URI')) {
	define('JFE_URI',rtrim(str_replace('index.php', '', $_SERVER["SCRIPT_NAME"])));
}

/**
 * Path Configuration
 **/

define('JFE_CLASS_PATH', JFE_PATH.'/framework/classes');
define('JFE_LIB_PATH', JFE_PATH.'/framework/library');
define('JFE_RESOURCE_URI', JFE_URI.'resources');
define('JFE_RESOURCE_PATH', JFE_PATH.'/resources');
define('JFE_CONTRIBUTE_URI', JFE_URI.'contrib');
define('JFE_CONTRIBUTE_PATH', JFE_PATH.'/contribute');
define('JFE_DATA_URI', JFE_URI.'files');
define('JFE_DATA_PATH', JFE_PATH.'/files');

define('JFE_PRESET_URI',JFE_URI.'presets');
define('JFE_PRESET_PATH',JFE_PATH.'/presets');

define('JFE_APP_PATH', JFE_PATH.'/app/');
define('JFE_API_PATH', JFE_PATH.'/app/api');

define('JFE_SESSION_PATH', JFE_PATH.'/include/session.php');

define('TAOGI_SOURCE_PATH', JFE_PATH.'/timeline');
define('TAOGI_SOURCE_URI', JFE_URI.'timeline');

define('JFE_DEBUG',			1);

define("JFE_LOG_TYPE_PRINT",	1);
define("JFE_LOG_TYPE_FILE",	2);
define("JFE_LOG_TYPE_ALL",		3);

define("JFE_LOG_TYPE",			JFE_LOG_TYPE_PRINT);

define("JFE_LOG_ID", 'donate');
define("JFE_LOG_DATE_FORMAT", 'Y-m-d H:i:s');
define("JFE_ERROR_LOG_PATH", JFE_PATH."/files/log/");

define("JFE_ERROR_ACTION_AJAX", 1);
define("JFE_ERROR_ACTION_URL", 2);
define("JFE_ERROR_AJAX_MSG", "FAIL");

define("JFE_COMMON_ERROR_PAGE", "");

require_once JFE_CLASS_PATH."/Autoload.class.php";
require_once JFE_CLASS_PATH."/Objects.class.php";
require_once JFE_CLASS_PATH."/Controller.class.php";

require_once JFE_LIB_PATH."/common.php";
require_once JFE_LIB_PATH."/import.php";

spl_autoload_register(array('Autoload', 'load'));

define( 'BITWISE_ADMINISTRATOR', 10 );
define( 'BITWISE_OWNER', 7 );
define( 'BITWISE_EDITOR', 5 );
define( 'BITWISE_USER', 3 );
define( 'BITWISE_ATHENTICATED', 1 );
define( 'BITWISE_ANONYMOUS', 0 );

global $FuchAclPreDefinedRole;
$FuchAclPreDefinedRole = array(
	'administrator'=>BITWISE_ADMINISTRATOR,
	'owner'=>BITWISE_OWNER,
	'editor'=>BITWISE_EDITOR,
	'user'=>BITWISE_USER,
	'authenticated'=>BITWISE_ATHENTICATED,
	'anonymous'=>BITWISE_ANONYMOUS
);

global $FuchAclPreDefinedRoleLabel;
$FuchAclPreDefinedRoleLabel = array(
	'administrator'=>'관리자',
	'owner'=>'개설자',
	'editor'=>'편집자',
	'user'=>'사용자',
	'authenticated'=>'사용자',
	'anonymous'=>'방문객',
);

global $FunchAclFromAppType;
$FunchAclFromAppType =
	array (
		'admin' => 'administrator',
		'owner' => 'owner',
		'editor' => 'editor',
	);

define( 'PW_ALGO', 'sha256' );

define( 'FB_ID', -1);
define( 'TWIT_ID', -2);
define( 'OPEN_ID', -10);

/** 출력용 기본값들 **/
define('ITEMS_PER_PAGE',6);
define('RELATIVE_TIME_COVERAGE',60*60*24); // in seconds
define('DEFAULT_TIME_FORMAT','Y-m-d H:i:s');
define('DEFAULT_ENTRY_IMAGE','/resources/images/default_entry_image.png');
define('DEFAULT_ENTRY_BACKGROUND','/resources/images/default_entry_background.jpg');
define('DEFAULT_USER_PORTRAIT','/resources/images/default_user_portrait.png');
define('DEFAULT_USER_BACKGROUND','/resources/images/default_user_background.jpg');

/** 노드의 상태 **/
define('NODE_STATUS_PRIVATE',0);
define('NODE_STATUS_OPEN',1);
define('NODE_STATUS_PUBLIC',2);
define('NODE_STATUS_FORKABLE',1);

/** 데이터 검증 결과 **/
define('INVALID_DATA_FORMAT',500);
define('DATA_NOT_FOUND',404);

define('DIRECTORY_SEPARATOR','/');
?>
