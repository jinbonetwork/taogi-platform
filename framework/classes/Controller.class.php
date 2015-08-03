<?php
abstract class Controller {
	public $themes;
	public $layout;
	public $title;
	public $contentType = "html"; 
	public $script = array();
	public $css = array();
	private static $instances = array();

	public function handle($params) {
		global $user;

		$this->params = $params;
		$this->context = Model_Context::instance();
		$this->view_mode = "platform";

		$this->user = array('uid' => (($uid = Acl::getIdentity('taogi')) ? $uid : 0));
		if($_SESSION['user']) {
			$this->user = array_merge($this->user,$_SESSION['user']);
		}
		if(method_exists($this,$params['controller']['process'])) {
			call_user_func(array($this,$params['controller']['process']));

			$this->render();
		} else if(method_exists($this,'index')) {
			call_user_func(array($this,'index'));

			$this->render();
		} else {
			Respond::NotFoundPage();
		}
	}

	public function render() {
		global $uri;
		$context = Model_Context::instance();

		/**
		 * @brief settting gloabl variables to be used in themes
		 **/
		global $user;
		global $taogiid;

		$this->breadcrumbs = ltrim(substr($uri->uri['appPath'],strlen(JFE_PATH)),'/');
		if($uri->uri['appFile'] != 'index') $this->breadcrumbs .= "/".$uri->uri['appFile'];
		$this->MyAppClass();

		if(isset($this->owner) && !$this->owner) $this->owner = Acl::imMaster();

		if(!$this->user) {
			$user = array('uid' => (($uid = Acl::getIdentity('taogi')) ? $uid : 0));
			if($_SESSION['user']) {
				$user = array_merge($user,$_SESSION['user']);
			}
		} else $user = $this->user;
		$taogiid = $this->params['taogiid'];
		if($taogiid) {
			$this->permalink = "http://".$context->getProperty('service.domain')."/".implode("/",array_slice($uri->uri['fragment'],0,2));
			$this->MyEntryClass($taogiid);
		}

		if($this->total_cnt) $this->PageNavigation();
		if($this->contentType == "redirect") 
			header("Location: $this->redirectURI");
		else if(($this->params['output'] == "xml" || $this->contentType == "xml")) {
			extract((array)$this);
			if(file_exists($this->params['controller']['path']."/".$this->params['controller']['file'].".xml.php"))
				include $this->params['controller']['path']."/".$this->params['controller']['file'].".xml.php";
			else if(file_exists($this->params['controller']['path']."/".$this->params['controller']['process'].".xml.php"))
				include $this->params['controller']['path']."/".$this->params['controller']['process'].".xml.php";
			else
				Respond::NotFoundPage(true);
		} else if(($this->params['browserType'] == 'api' || $this->params['output'] == 'json' || $this->contentType == "json")) {
			extract((array)$this);
			if(file_exists($this->params['controller']['path']."/".$this->params['controller']['file'].".json.php"))
			include $this->params['controller']['path']."/".$this->params['controller']['file'].".json.php";
			else if(file_exists($this->params['controller']['path']."/".$this->params['controller']['process'].".json.php"))
			include $this->params['controller']['path']."/".$this->params['controller']['process'].".json.php";
			else
				print json_encode(array());
		} else {
			if(!$this->themes) $this->themes = $context->getProperty('service.themes');

			if($this->params['output'] != "nolayout") {
				/**
				 * @brief default javascript 를 호출. ex jquery
				 **/
//				View_Resource::addScript("jquery.min.js");
				$this->initJs($this->initscript);
				Template::getGeneralHeader();
				View_Resource::addScript("defaults.js",-100);
				View_Resource::addCss("defaults.css",-100);
				if($this->layout == "admin") {
					View_Resource::addCss("admin.css");
				}

				/**
				 * @brief resource/css에서 불러오도록 지정된 css들의 경로를 잡아준다.
				 **/
				if(@count($this->css)) {
					foreach($this->css as $css) {
						View_Resource::addCss($css);
					}
				}
				/**
				 * @brief 해당 controller 경로에 style.css가 있으면 이 경로 밑에 있는 모는 페이지는 이 style.css를 포함한다.
				 **/
				if(file_exists($this->params['controller']['path']."/style.css")) {
					View_Resource::addCssURI($this->params['controller']['uri']."/style.css");
				}
				/**
				 * @brief 해당 controller에 매치되는 controller.css가 있으면 포함한다.
				 **/
				if(file_exists($this->params['controller']['path']."/".$this->params['controller']['file'].".css")) {
					View_Resource::addCssURI($this->params['controller']['uri']."/".$this->params['controller']['file'].".css");
				}

				/**
				 * @brief resource/script에서 불러오도록 지정된 script들의 경로를 잡아준다.
				 **/

				if(@count($this->js)) {
					foreach($this->js as $js) {
						View_Resource::addJs($js);
					}
				}
				if(@count($this->script)) {
					foreach($this->script as $script) {
						View_Resource::addScript($script);
					}
				}
				/**
				 * @brief 해당 controller 경로에 script.js가 있으면 이 경로 밑에 있는 모는 페이지는 이 script.js를 포함한다.
				 **/
				if(file_exists($this->params['controller']['path']."/script.js")) {
					View_Resource::addJsURI($this->params['controller']['uri']."/script.js");
				}
				/**
				 * @brief 해당 controller에 매치되는 controller.js가 있으면 포함한다.
				 **/
				if(file_exists($this->params['controller']['path']."/".$this->params['controller']['file'].".js")) {
					View_Resource::addJsURI($this->params['controller']['uri']."/".$this->params['controller']['file'].".js");
				}

				/**
				 * @brief themes에 있는 기본 css와 script 들을 가장 먼저 포함한다.
				 **/
				if($this->layout != "admin") {
					if(file_exists(JFE_PATH."/themes/".$this->themes."/config.php")) {
						require_once JFE_PATH."/themes/".$this->themes."/config.php";
					}
					$this->dirCssJsHtml("themes/".$this->themes,1000);
					$this->dirCssJsHtml("themes/".$this->themes."/css",1000);
					$this->dirCssJsHtml("themes/".$this->themes."/script",1000);
				}
				if(($html_file = $this->appPath())) {
					$this->themeCssJs($html_file);
				}

				if($this->entry) {
//					$this->permalink = "http://".$context->getProperty('service.domain').base_uri().$this->entry['nickname'];
					$this->xmlns = init_fb_html_xmlns();
//					$this->header .= "\t".init_fb_meta($context->getProperty('service.fb_App_ID'),$this->entry['subject'],$this->uri,Favicon::image_url($this->entry['favicon']),$this->entry['summary']);
				}

				/* FaceBook plugin */
				$this->body_start = init_fb_script($context->getProperty('service.fb_App_ID'));
			}

			extract((array)$this);

			if(($html_file = $this->appPath())) {
				ob_start();
				include $this->renderPath($html_file);
				$content = ob_get_contents();
				ob_end_clean();

				$layout_file = $this->LayoutFile();
			} else {
				Respond::NotFoundPage();
			}

			if($this->params['output'] == "nolayout") {
				header("Content-Type:text/html; charset=utf-8");
				echo $content;
			} else {
				/**
				 * @brief include library file required in themes
				 **/
				if(file_exists(JFE_PATH."/themes/".$this->themes."/function.php")) {
					$theme_path = JFE_URI."themes/".$this->themes;
					require_once JFE_PATH."/themes/".$this->themes."/function.php";
				}
				/**
				 * @brief include gnb
				 **/
				if( ($this->view_mode != 'view' || $has_gnb == true) && $params['skipgnb'] != 'true' ) {
					ob_start();
					include_once JFE_PATH."/include/gnb/index.html.php";
					$taogi_gnb = ob_get_contents();
					ob_end_clean();
				}

				/**
				 * @brief make html result using theme layout
				 **/
				if($layout_file) {
					ob_start();
					include $layout_file;
					$html = ob_get_contents();
					ob_end_clean();
					print $html;
				}
			}
		}
	}

	public function header() {
		/**
		 * @brief make header. rendering dynamic css and script
		 **/
		print View_Resource::renderCss().View_Resource::renderJs()."\t".$this->header;
	}

	public function footer() {
		/**
		 * @brief add google statistic script before body end
		 **/
		ob_start();
		include_once JFE_RESOURCE_PATH."/script/google-static.js";
		$google_static = ob_get_contents();
		ob_end_clean();
		View_Resource::addScriptSource($google_static,0,array('position'=>'footer'));
		print $this->footer."\n".View_Resource::renderJs('footer');
	}

	private function dirCssJsHtml($dir,$priority) {
		if(!@is_dir(JFE_PATH."/".$dir)) return;
		$dp = opendir(JFE_PATH."/".$dir);
		while($f = readdir()) {
			if(substr($f,0,1) == ".") continue;
			if(@is_dir(JFE_PATH."/".$dir."/".$f)) continue;
			if(preg_match("/(.+)\.css$/i",$f)) {
				View_Resource::addCssURI(rtrim(JFE_URI,"/")."/".$dir."/".$f,$priority);
			} else if(preg_match("/(.+)\.js$/i",$f)) {     
				View_Resource::addJsURI(rtrim(JFE_URI,"/")."/".$dir."/".$f,$priority);
			}
		}
		closedir($dp);
	}

	private function cssHtml($uri) {
		View_Resource::addCssURI($uri);
	}

	private function jsHtml($uri) {
		if(preg_match("/\.css$/i",$uri)) {
			View_Resource::addCssURI($uri);
//			return $this->cssHtml($uri);
		}
		View_Resource::addJsURI($uri);
//		return "\t<script type=\"text/javascript\" src=\"$uri\"></script>\n";
	}

	private function initJs($src) {
		if(file_exists(JFE_PATH."/resources/script/init.js")) {
			$script .= file_get_contents(JFE_PATH."/resources/script/init.js");
		}
		if($src) $script .= $src;

		if($script) {
			View_Resource::addScriptSource($script,-50);
		}
	}

	private function appPath() {
		if($this->params['output'] == "nolayout") {
			$file_path = $this->params['controller']['path']."/".$this->params['controller']['process'].".".($extends ? $extends."." : "")."html.php";
			if(file_exists($file_path)) return $file_path;
		}
		$file_path = $this->params['controller']['path']."/".$this->params['controller']['file'].".".($extends ? $extends."." : "")."html.php";
		if(file_exists($file_path)) return $file_path;
		$file_path = $this->params['controller']['path']."/".$this->params['controller']['process'].".".($extends ? $extends."." : "")."html.php";
		if(file_exists($file_path)) return $file_path;
		return null;
	}

	private function themeCssJs($path) {
		$_path = dirname("themes/".$this->themes."/".substr($path,strlen(JFE_APP_PATH)));
		$this->dirCssJsHtml($_path,100);
	}

	private function renderPath($path) {
		$_path = JFE_PATH."/themes/".$this->themes."/".substr($path,strlen(JFE_APP_PATH));
		if(file_exists($_path)) {
			define("JFE_APP_CALL_PATH",$path);
			return $_path;
		}
		else return $path;
	}

	private function LayoutFile() {
		$extends = "layout";

		if($this->layout == "admin") {
			$layout_file = JFE_PATH."/resources/html/admin.".$extends.".html.php";
			if(file_exists($layout_file)) return $layout_file;
		} else {
			if($extends == "layout" && $this->layout)
				 $layout_file = JFE_PATH."/themes/".$this->themes."/".$this->layout.".php";
			else
				$layout_file = JFE_PATH."/themes/".$this->themes."/".$extends.".php";
			if(file_exists($layout_file)) return $layout_file;
		}
		return null;
	}

	private function PageNavigation() {
		if($this->total_cnt) {
			if(!$this->page) $this->page = 1;
			if(!$this->limit) $this->limit = 15;
			if(!$this->page_num) $this->page_num = 10;

			$this->total_page = (int)(($this->total_cnt-1) / $this->limit)+1;
			$this->s_page = (int)(($this->page - 1)/$this->page_num)*($this->page_num)+1;
			if($this->s_page > 1) $this->p_page = $this->s_page-1;
			else $this->p_page = 0;
			$this->e_page = min($this->total_page,$this->s_page + $this->page_num - 1);
			if($this->e_page < $this->total_page) $this->n_page = $this->e_page + 1;
			else $this->n_page = 0;
		}
	}

	protected function isMobile(){
		//$browser = Utils_Browser::instance();
		$detect = Utils_MobileDetect::instance();

		if($detect->isMobile() && !$detect->isTablet()) 
			return true;

		return false;
	}

	private function MyAppClass() {
		$breadcrumbs = explode("/",$this->breadcrumbs);
		$this->breadcrumbs_class = "";
		$bcnt = @count($breadcrumbs);
		for($i=0; $i<$bcnt; $i++) {
			$this->breadcrumbs_class .= ($this->breadcrumbs_class ? " " : "").implode("-",array_slice($breadcrumbs,0,$i+1));
		}
	}
	private function MyEntryClass($taogiid) {
		if($taogiid) {
			$this->entry_class = "entry-".$taogiid;
		}
	}
}
