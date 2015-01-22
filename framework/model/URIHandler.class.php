<?php
final class Model_URIHandler extends Objects {
	public $uri, $params, $appPath;
	public $taogiid;
	private $prefix_appArray = array('login','regist','create','admin','front','common','about','stat','api');
	private $redirect_appArray = array('list','search','category','tag');
	private $user_appArray = array('profile','archives','bookmarks');


	public static function instance() {
		return self::_instance(__CLASS__);
	}

	protected function __construct() {
		$this->__URIinterpreter();
	}

	public function URIParser() {
		$this->__URIParser();
	}

	private function __URIinterpreter() {
		global $service;

		$uri = parse_url(($_SERVER['HTTPS'] == 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['REQUEST_URI']));
		$uri += array (
			'fullpath'  => str_replace('index.php', '', $_SERVER["REQUEST_URI"]),
			'root'      => rtrim(str_replace('index.php', '', $_SERVER["SCRIPT_NAME"]), 'index.php')
		);
		if (strpos($uri['fullpath'],$uri['root']) !== 0)
			$uri['fullpath'] = $uri['root'].substr($uri['fullpath'], strlen($uri['root']) - 1);
		if($uri['fullpath'] == "/") {
			$uri['fullpath'] .= "front";
		}
		$uri['input'] = ltrim(substr($uri['fullpath'],strlen($uri['root']))).'/';
		$part = strtok($uri['input'], '/');
		if(in_array($part,array('resources','plugins','themes','files'))) {
			$part = ltrim(rtrim($uri['input']), '/');
			$part = (($qpos = strpos($part, '?')) !== false) ? substr($part, 0, $qpos) : $part;
			if(file_exists($part)) {
				require_once JFE_LIB_PATH.'/file.php';
				dumpWithEtag($part);
				exit;
			} else {
				header("HTTP/1.0 404 Not Found");exit;
			}
		}
		unset($uri['fragment']);
		$uri['fragment'] = array_values(array_filter(explode('/',strtok($uri['input'],'?'))));
		unset($part);


		if($uri['fullpath'] == '/') {
			$uri['appType'] = 'front';
		} else if(isset($uri['fragment'][0]) && in_array($uri['fragment'][0], $this->prefix_appArray)) {
			$uri['appType'] = $uri['fragment'][0];
			$pathPart = JFE_APP_PATH.rtrim(strtok($uri['input'], '?'),'/');
		} else if(isset($uri['fragment'][0]) && in_array($uri['fragment'][0], $this->redirect_appArray)) {
			$uri['appType'] = $uri['fragment'][0];
			$pathPart = JFE_APP_PATH.rtrim(strtok("list/".substr($uri['input'],strlen($uri['fragment'][0])+1), '?'),'/');
		} else if(($rp = strpos($uri['input'],"/resources/"))) {
			$part = JFE_PATH."/timeline/".ltrim(rtrim(substr(strtok($uri['input'],"?"),$rp),'/'),'/');
			if(file_exists($part)) {
				require_once JFE_LIB_PATH.'/file.php';
				dumpWithEtag($part);
				exit;
			} else {
				header("HTTP/1.0 404 Not Found");exit;
			}
		} else {
//			$user_type = 'normal';
//			$user_profile = false;
//			if($uri['fragment'][0] == 'user') {
//				if($uri['fragment'][2] == 'profile') {
//					$uri['appType'] = $uri['fragment'][2];
//					$__input = explode("/",rtrim($uri['input'],"/"));
//					$_input = "/user";
//					for($i=3; $i<@count($__input); $i++) {
//						$_input .= "/".$__input[$i];
//					}
//					$pathPart = JFE_APP_PATH.ltrim(rtrim(strtok(strstr($_input,'/'), '?'), '/'),'/');
//					$user_profile = true;
//				} else {
//					array_splice($uri['fragment'],0,3);
//				}
//			} else {
//				$user_type = 'nickname';
//				array_splice($uri['fragment'],0,2);
//			}
//			if(!$user_profile) {
				if (isset($uri['fragment'][0]) && file_exists(JFE_APP_PATH."/".$uri['fragment'][0])) {
					$uri['appType'] = $uri['fragment'][0];
					if($uri['appType'] == 'api') $uri['appType'] = '_api';
				} else {
					if(isset($uri['fragment'][0]) && (@count($uri['fragment']) == 1 || in_array($uri['fragment'][1],$this->user_appArray))) {
						$uri['appType'] = 'user';
						$__input = explode("/",rtrim(strtok($uri['input'],'?'),"/"));
						$uri['input'] = 'user/'.implode("/",array_slice($__input,0,1));
						$pathPart = JFE_APP_PATH."user";
						for($i=1; $i<@count($__input); $i++) {
							$uri['input'] .= "/".$__input[$i];
							$pathPart .= "/".$__input[$i];
						}
					} else {
						$uri['appType'] = 'entry';
						$__input = explode("/",rtrim(strtok($uri['input'],'?'),"/"));
						$uri['input'] = implode("/",array_slice($__input,0,2))."/entry";
						$pathPart = JFE_APP_PATH."entry";
						for($i=2; $i<@count($__input); $i++) {
							$uri['input'] .= "/".$__input[$i];
							$pathPart .= "/".$__input[$i];
						}
					}
				}
//			}
			if(!$pathPart)
				$pathPart = JFE_APP_PATH.ltrim(rtrim(strtok(strstr($uri['input'],'/'), '?'), '/'),'/');
		}

		$pathPart = strtok($pathPart,'&');

		if(file_exists($pathPart.".php")) {
			$uri['appPath'] = dirname($pathPart);
			$uri['appFile'] = basename($pathPart);
			$uri['appClass'] = basename($uri['appPath'])."_".$uri['appFile'];
			$uri['appProcessor'] = "index";
		} else if(file_exists($pathPart."/index.php")) {
			$uri['appPath'] = $pathPart;
			$uri['appFile'] = "index";
			$uri['appClass'] = basename($uri['appPath'])."_index";
			$uri['appProcessor'] = "index";
		} else if(file_exists(dirname($pathPart)."/index.php")) {
			$uri['appPath'] = dirname($pathPart);
			$uri['appFile'] = "index";
			$uri['appClass'] = basename($uri['appPath'])."_index";
			$uri['appProcessor'] = basename($pathPart);
		}
		$this->uri = $uri;
	}

	private function __URIParser() {
		if(!isset($this->uri)) $this->__URIinterpreter();

		if(!in_array($this->uri['appType'],$this->prefix_appArray) && !in_array($this->uri['appType'],$this->redirect_appArray)) {
			if($this->uri['appType'] != '_api') $this->uri['appType'] = 'api';
			$fragment = array_values(array_filter(explode('/',strtok($this->uri['input'],'?'))));
			if(isset($fragment[0])) {
				if($fragment[0] == 'user') {
					if(isset($fragment[1])) {
						if(!($_user = User::getUserByNickname($fragment[1],1))) {
							Respond::NotFoundPage();
						}
						$this->userid = $_user['uid'];
						if(!$this->userid) {
							Respond::NotFoundPage();
						}
					} else {
						Respond::NotFoundPage();
					}
				} else {
					if(!($_user = User::getUserByNickname($fragment[0],1))) {
						Respond::NotFoundPage();
					}
					$this->userid = $_user['uid'];
					$this->taogiid = Entry::getEntryIDByName($fragment[1],$this->userid);
					if(!$this->taogiid || !$this->userid) {
						Respond::NotFoundPage();
					}
				}
			} else {
				Respond::NotFoundPage();
			}
		}
		if(!$this->uri['appPath'] || !$this->uri['appFile']) {
			Respond::NotFoundPage();
		}
		$this->params = array_merge($_GET, $_POST);
		if($this->taogiid) $this->params['taogiid'] = $this->taogiid;
		if($this->userid) $this->params['userid'] = $this->userid;
		$this->params['appType'] = $this->uri['appType'];
		$this->params['path'] = $this->uri['path'];
		$this->params['browserType'] = $this->uri['browserType'];
		$this->params['controller']['path'] = $this->uri['appPath'];
		$this->params['controller']['uri'] = rtrim($this->uri['root'].substr($this->uri['appPath'],strlen(JFE_PATH)+1),"/");
		$this->params['controller']['file'] = $this->uri['appFile'];
		$this->params['controller']['class'] = $this->uri['appClass'];
		$this->params['controller']['process'] = $this->uri['appProcessor'];
	}
}
?>
