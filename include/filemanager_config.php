<?
define('ROOT',dirname(rtrim(dirname(__FILE__),"/")));
$_tmp_base = strtok(ltrim(substr($_SERVER['SCRIPT_FILENAME'],strlen(ROOT)),"/"),"/");
$_tmp_path = explode("/",ltrim($_SERVER['SCRIPT_NAME'],"/"));
$_tmp_uri = "/";
for($i=0; $i<count($_tmp_path); $i++) {
	if($_tmp_path[$i] == $_tmp_base) break;
	else $_tmp_uri .= $_tmp_path[$i]."/";
}
define('JFE_URI',$_tmp_uri);
if(!defined('__JFE__') && defined('ROOT')) {
	define('__JFE__',true);
	require_once ROOT."/config/config.php";

	global $context, $config;
	$context = Model_Context::instance();
	$config = Model_Config::instance();
}
if($_POST[Session::getName()]) {
	$_COOKIE[Session::getName()] = $_POST[Session::getName()];
}
require_once JFE_SESSION_PATH;
if($_SESSION['current']['mode'] == 'entry_edit') {
	$eid = $_SESSION['current']['eid'];
	$root_folder = 'entry';
} else if($_SESSION['current']['mode'] == 'entry_create') {
	$eid = $_COOKIE[Session::getName()];
	$root_folder = 'entry';
} else if($_SESSION['current']['mode'] == 'profile') {
	$root_folder = 'user';
	$eid = Acl::getIdentity('taogi');
}
$fp = fopen("/tmp/taogi_log.txt","a+");
fputs($fp,$_SESSION['current']['mode']." ".$root_folder." ".$eid."\n");
fclose($fp);
if(!$eid) {
	Respond::NotFoundPage();
}
