<?php
import('library.files');
class entry_index extends Interface_Entry {
	public function index() {
		$context = Model_Context::instance();
		$uri = $context->getProperty('uri');
		$this->timelineConfig = $context->getProperty('timeline.*');
		$this->getEntryInfo();
		$this->extra = Entry::getEntryExtra($this->entry['eid']);
		$this->view_mode ='view';

		if(!$this->entry['is_public']) {
			$taogi_id = $this->params['taogiid'];
			$__Acl = Acl::instance();
			$__Acl->setAcl($taogi_id,$uri->uri,'editor');
			$__Acl->check($taogi_id);
		}
		$this->mode = 'lasted';
		if($this->params['vid']) {
			if($_SESSION['acl']['taogi.'.$this->entry['eid']] < BITWISE_EDITOR) {
				Error("접근 권한이 없습니다.",403);
			}
			$this->mode = 'revision';
			$this->entry['vid'] = $this->params['vid'];
		}

		$revision = Entry::getEntryData($this->entry['eid'],$this->entry['vid']); 
		if($this->mode != 'revision') {
			if(!$this->entry['is_public']) {
				if($_SESSION['acl']['taogi.'.$this->entry['eid']] < BITWISE_EDITOR) {
					Error("접근 권한이 없습니다.",403);
				}
			}
			if(!($json_path = $this->getJsonPath())) {
				if($revision) {
					$this->entry = array_merge($this->entry,$revision);
					if($this->entry['is_public']) {
						$this->makeJson();
						$json_path = $this->getJsonPath();
						if(!$json_path) {
							Respond::NotFoundPage();
						}
					}
				} else {
					Respond::NotFoundPage();
				}
			}
		} else {
			if($revision) {
				$this->entry = array_merge($this->entry,$revision);
			} else {
				Respond::NotFoundPage();
			}
		}

		if($this->extra['model']) $this->model = $this->extra['model'];
		if($this->params['model']) $this->model = $this->params['model'];
		if(!$this->model || !file_exists(TAOGI_SOURCE_PATH."/model/".$this->model)){
			$this->model = $this->timelineConfig['default_model'];
		}

		if($this->extra['skinname']) $this->skinname = $this->extra['skinname'];
		if($this->params['skinname']) $this->skinname = $this->params['skinname'];
		if(!$this->skinname || !file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname)){
			$this->skinname = 'default';
		}

		$this->sendHeader('last-modified');
		eval("\$this->{$this->model}();");
		$this->header .= Template::getSocialMetaTags(array(
			'type' => 'article',
			'title' => $this->json['timeline']['headline'],
			'url' => Entry::getEntryLink($this->params['taogiid']),
			'author' => User::getUserLink($this->params['userid']),
			'published_time' => $this->entry['published'],
			'modified_time' => $this->entry['modified'],
			'description' => $this->json['timeline']['text'],
			'image' => $this->exterior['filtered']['asset__cover_background_image'],
		));
		$this->header .= Template::getExteriorHeaderTags($this);
	}

	public function sendHeader($message){
		switch($message){
			case 'last-modified':
				$this->entry['modified'] = $revision['modified'];
				$last_modified = gmdate('D, d M Y H:i:s',$this->entry['modified']);
				$header = array("Last-Modified: {$last_modified} GMT",true,200);
			break;
		}
		if(is_string($header)){
			header($header);
		}else if(is_array($header)){
			$header[0] = "'{$header[0]}'";
			eval("header(".implode(",",$header).");");
		}
	}

	public function fb() {
		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/config/config.php";
		require_once TAOGI_SOURCE_PATH."/library/times.library.php";
		require_once TAOGI_SOURCE_PATH."/classes/Data.class.php";
		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/classes/MediaElement.class.php";
		$this->config = $config;
		$this->has_gnb = true;

		if(!$this->params['line_cnt']) $this->params['line_cnt'] = ($this->extra['line_cnt'] ? $this->extra['line_cnt'] : $config['line_cnt']);
		if(!$this->params['width']) $this->params['width'] = ($this->extra['width'] ? $this->extra['width'] : '100%');
		if(!$this->params['order']) $this->params['order'] = ($this->extra['order'] ? $this->extra['order'] : 'asc');

		if($this->entry['timeline']) {
			$this->json = $this->entry['timeline'];
		} else {
			$json_path = $this->getJsonPath();
			$fp = fopen($json_path,"r");
			$this->json = fread($fp,filesize($json_path));
			fclose($fp);
		}
		if($this->json) {
			$this->json = JNTimeLine_Data::getJson($this->json,$this->params['order']);
			$this->timeline = $this->json['timeline'];
			$this->datalist = $this->json['timeline']['date'];
		}

		if($this->datalist) {
			$this->total_cnt = @count($this->datalist);
			$this->page = ($this->params['page'] ? $this->params['page'] : 1);
		}
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/style.css"))
			$this->header .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/style.css\" />\n";

		$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/js/jquery.masonry.js\"></script>\n";
		$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/js/timeline.js\"></script>\n";
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/script.js")) {
			$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/script.js\"></script>\n";
		}
	}

	public function timelineJS() {
		$this->has_gnb = true;
		if(!$this->params['width']) $this->params['width'] = ($this->extra['width'] ? $this->extra['width'] : '100%');
		if(!$this->params['height']) $this->params['height'] = ($this->extra['height'] ? $this->extra['height'] :  "100%");
		if(!$this->params['lang']) $this->params['lang'] = ($this->extra['lang'] ? $this->extra['lang'] : "en");
		if(!$this->params['maptype']) $this->params['maptype'] = ($this->extra['maptype'] ? $this->extra['maptype'] : "toner");
		if(!$this->params['start_at_end']) $this->params['start_at_end'] = ($this->extra['start_at_end'] ? $this->extra['start_at_end'] : "false");
		if(!$this->params['hash_bookmark']) $this->params['hash_bookmark'] = ($this->extra['hash_bookmark'] ? $this->extra['hash_bookmark'] : "false");
		if(!$this->params['debug']) $this->params['debug'] =  ($this->extra['debug'] ? $this->extra['debug'] : "false");
		if(!$this->params['start_at_slide']) $this->params['start_at_slide'] = ($this->extra['start_at_slide'] ? $this->extra['start_at_slide'] : null);
		if(!$this->params['start_zoom_adjust']) $this->params['start_zoom_adjust'] = ($this->extra['start_zoom_adjust'] ? $this->extra['start_zoom_adjust'] : null);
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/style.css"))
			$this->header .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/style.css\" />\n";

		$this->header .= <<<TIMELINECONFIG

			<script type="text/javascript">
				var timeline_config = {
					width: '{$this->params['width']}',
					height: '{$this->params['height']}',
					source: '{$this->source}',
					embed_id: 'timeline-embed',
					start_at_end: {$this->params['start_at_end']},
					start_at_slide: '{$this->params['start_at_slide']}',
					start_zoom_adjust: '{$this->params['start_zoom_adjust']}',
					hash_bookmark: {$this->params['hash_bookmark']},
					font: '{$this->params['font']}',
					debug: {$this->params['debug']},
					lang: '{$this->params['lang']}',
					maptype: '{$this->params['maptype']}'
				};	
			</script>

TIMELINECONFIG;
	}

	public function touchcarousel() {
		$this->has_gnb = false;
		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/config/config.php";
		require_once TAOGI_SOURCE_PATH."/library/times.library.php";
		require_once TAOGI_SOURCE_PATH."/classes/Data.class.php";
		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/classes/ElementResolution.class.php";
//		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/classes/Theme.class.php";
		require_once JFE_PATH."/classes/TouchCarouselTheme.class.php";
		require_once TAOGI_SOURCE_PATH."/model/".$this->model."/classes/Language.class.php";
		$this->config = $config;
		$this->taogi_ER = new Taogi_ElementResolution();

		if(!$this->params['order']) $this->params['order'] = ($this->extra['order'] ? $this->extra['order'] : 'asc');

		if($this->entry['timeline']) {
			$this->json = $this->entry['timeline'];
		} else {
			$json_path = $this->getJsonPath();
			$fp = fopen($json_path,"r");
			$this->json = fread($fp,filesize($json_path));
			fclose($fp);
		}
		if($this->json) {
			$this->json = JNTimeLine_Data::getJson($this->json,$this->params['order']);
			$this->timeline = $this->json['timeline'];
			$this->datalist = $this->json['timeline']['date'];
		}

		$this->taogi_language = new TaogiLanguage(TAOGI_SOURCE_PATH."/model/".$this->model,$this->config['lang'],$this->skinname);

		/* load necessary css/js resource */
		$this->header .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/resources/css/media.css\" />\n";
		$this->header .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/css/layout.css\" />\n";
		$this->header .= "\t<!--[if lt IE 9]>\n\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/css/layout.ie.css\" />\n\t<![endif]-->\n";
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/style.css"))
			$this->header .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/style.css\" />\n";
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/style.ie.css"))
			$this->header .= "\t<!--[if lt IE 9]>\n\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/style.ie.css\" />\n\t<![endif]-->\n";

		$this->taogi_theme = new Taogi_Theme($json['timeline']['extra']['theme'],$config);
		if($json['timeline']['extra']['theme']) {
			$this->header .= $this->taogi_theme->makeStyle();
		}

		$this->header .= "\t<script type=\"text/javascript\">\n\t\tvar TaogiLanguagePack='".$this->taogi_language->json_url(TAOGI_SOURCE_URI."/model/".$this->model)."';\n\t\t</script>\n";
		$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/js/jquery.easing.1.3.js\"></script>\n";
		$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/resources/jquery-mousewheel/jquery.mousewheel.min.js\"></script>\n";
		$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/js/jquery.taogi.touchcarousel.js\"></script>\n";
		if(file_exists(TAOGI_SOURCE_PATH."/model/".$this->model."/skin/".$this->skinname."/script.js")) {
			$this->header .= "\t<script type=\"text/javascript\" src=\"".TAOGI_SOURCE_URI."/model/".$this->model."/skin/".$this->skinname."/script.js\"></script>\n";
		}
	}
}
?>
