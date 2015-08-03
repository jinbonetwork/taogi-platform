<?php
class View_Resource extends Objects {
	public static $css = array();
	public static $js = array();
	private static $map = array();

	public static function instance() {
		return self::_instance(__CLASS__);
	}

	function __construct() {
	}

	public static function initMap() {
		if(!self::$map) {
			$config = Model_Config::instance();
			self::$map = $config->readResourceMap();
		}
	}

	public static function addResource($key,$priority=0) {
		if(!self::$map) self::initMap();
		if(!self::$map[$key]) return;
		if(self::$map[$key]['loaded'] == true) return;
		$map = self::$map[$key];
		if(!$priority) {
			if($map['priority']) $priority = $map['priority'];
		} else if($priority && $map['priority']) {
			if($priority > $map['priority']) $priority = $map['priority'];
		}
		if($map['deps'] && is_array($map['deps'])) {
			if(@count($map['deps']) > 0) {
				foreach($map['deps'] as $deps) {
					self::depResource($deps,$priority);
				}
			}
		}
		if($map['options']) {
			$options = $map['options'];
		}
		if($map['js'] && @count($map['js']) > 0) {
			foreach($map['js'] as $js) {
				self::addJsURI(JFE_URI.$js,$priority,$options);
			}
		}
		if($map['css'] && @count($map['css']) > 0) {
			foreach($map['css'] as $css) {
				self::addCssURI(JFE_URI.$css,$priority,$options);
			}
		}
		self::$map[$key]['loaded'] = true;
	}

	private static function depResource($key,$priority) {
		if(!self::$map[$key]) return;
		if($priority < ($map['priority'] ? $map['priority'] : 0)) $_priority = $priority;
		else if($map['priority']) $_priority = $map['priority'];
		self::addResource($key,$_priority);
	}

	public static function addCss($css,$priority=0,$options='') {
		if(preg_match("/(.+)\.js$/i",$css)) {
			self::addJs($css,$priority,$options);
		} else {
			self::pushCss(JFE_RESOURCE_URI.'/css/'.$css,$priority,'src',$options);
		}
	}

	public static function addJsCss($css,$priority=0,$options='') {
		if(preg_match("/(.+)\.js$/i",$css)) {
			self::addJs($css,$priority,$options);
		} else {
			self::pushCss(JFE_RESOURCE_URI.'/js/'.$css,$priority,'src',$options);
		}
	}

	public static function addCssURI($css,$priority=0,$options='') {
		if(preg_match("/(.+)\.js$/i",$css)) {
			self::addJsURI($css,$priority,$options);
		} else {
			self::pushCss($css,$priority,'src',$options);
		}
	}

	public static function addLibraryCss($css,$priority=0,$options='') {
		if(preg_match("/(.+)\.js$/i",$css)) {
			self::addLibraryJs($css,$priority,$options);
		} else {
			self::pushCss(JFE_CONTRIBUTE_URI."/".$css,$priority,'src',$options);
		}
	}

	public static function addCssSource($source,$priority=0,$options='') {
		self::pushCss($source,$priority,'source',$options);
	}

	private static function pushCss($css,$priority,$type,$options) {
		if($type == 'source') $key = hash('sha256',$css,false);
		else $key = $css;
		if(self::$css[$key]) {
			if($priority && self::$css[$key]['priority'] < $priority) {
				self::$css[$key]['priority'] = $priority;
			}
		} else {
			self::$css[$key] = array('css'=>$css,'id'=>'taogi_css_'.self::getUniqueID($key),'priority'=>($priority ? $priority : 0),'type'=>$type,'options'=>$options);
		}
	}

	public static function addJs($js,$priority=0,$options='') {
		if(preg_match("/(.+)\.css$/i",$js)) {
			self::addJsCss($js,$priority,$options);
		} else {
			self::pushJs(JFE_RESOURCE_URI.'/js/'.$js,$priority,'src',$options);
		}
	}

	public static function addScript($js,$priority=0,$options='') {
		if(preg_match("/(.+)\.css$/i",$js)) {
			self::addCss($js,$priority,$options);
		} else {
			self::pushJs(JFE_RESOURCE_URI.'/script/'.$js,$priority,'src',$options);
		}
	}

	public static function addScriptSource($source,$priority=0,$options='') {
		self::pushJs($source,$priority,'source',$options);
	}

	public static function addJsURI($js,$priority=0,$options='') {
		if(preg_match("/(.+)\.css$/i",$js)) {
			self::addCssURI($js,$priority,$options);
		} else {
			self::pushJs($js,$priority,'src',$options);
		}
	}

	public static function addLibraryJs($js,$priority=0,$options='') {
		if(preg_match("/(.+)\.css$/i",$js)) {
			self::addLibraryCss($js,$priority,$options);
		} else {
			self::pushJs(JFE_CONTRIBUTE_URI.'/'.$js,$priority,'src',$options);
		}
	}

	private static function pushJs($js,$priority,$type,$options='') {
		if($type == 'source') $key = hash('sha256',$js,false);
		else $key = $js;
		if(self::$js[$key]) {
			if($priority && self::$js[$key]['priority'] < $priority) {
				self::$js[$key]['priority'] = $priority;
			}
		} else {
			self::$js[$key] = array('script'=>$js,'id'=>'taogi_js_'.self::getUniqueID($key),'priority'=>($priority ? $priority : 0),'type'=>$type,'options'=>$options);
		}
	}

	private static function getUniqueID($key) {
		return trim(strtr(base64_encode(hash('crc32',$key, true)), '+/=', '-_ '));
	}

	private static function sortCssByPriority() {
		if(is_array(self::$css)) {
			foreach(self::$css as $css) {
				if(!$cssOrder[$css['priority']]) $cssOrder[$css['priority']] = array();
				$cssOrder[$css['priority']][] = $css;
			}
			ksort($cssOrder);
		}
		return $cssOrder;
	}

	private static function sortJsByPriority() {
		if(is_array(self::$js)) {
			foreach(self::$js as $js) {
				if(!$jsOrder[$js['priority']]) $jsOrder[$js['priority']] = array();
				$jsOrder[$js['priority']][] = $js;
			}
			ksort($jsOrder);
		}
		return $jsOrder;
	}

	public static function renderCss($position='header') {
		$_css = self::sortCssByPriority();
		$stylesheet = '';
		if(is_array($_css)) {
			foreach($_css as $p => $__css) {
				if(is_array($__css)) {
					foreach($__css as $css) {
						if($position == 'header' && $css['options'] && $css['options']['position'] == 'footer') continue;
						else if($position != 'header' && (!$css['options'] || $css['options']['position'] != 'footer')) continue;
						if($css['options'] && $css['options']['condition']) {
							$stylesheet .= "\t<!--[".$css['options']['condition']."]>\n\t";
						}
						switch($css['type']) {
							case 'source':
								$stylesheet .= "\t".'<style id="'.$css['id'].'"'.($css['options'] && $css['options']['class'] ? ' class="'.$css['options']['class'].'"' : '').' type="text/css">'."\n\t\t".$css['css']."\n\t</style>\n";
								break;
							case 'src':
							default:
								$stylesheet .= "\t".'<link id="'.$css['id'].'"'.($css['options'] && $css['options']['class'] ? ' class="'.$css['options']['class'].'"' : '').' rel="stylesheet" href="'.$css['css'].'">'."\n";
								break;
						}
						if($css['options'] && $css['options']['condition']) {
							$stylesheet .= "\t<![endif]-->\n";
						}
					}
				}
			}
		}
		return $stylesheet;
	}

	public static function getCssList() {
		$css = array();
		$_css = self::sortCssByPriority();
		if(is_array($_css)) {
			foreach($_css as $p => $__css) {
				if(is_array($__css)) {
					foreach($__css as $___css) {
						$css[] = $___css;
					}
				}
			}
		}
		return $css;
	}

	public static function renderJs($position='header') {
		$_js = self::sortJsByPriority();
		$script = '';
		if(is_array($_js)) {
			foreach($_js as $p => $__js) {
				if(is_array($__js)) {
					foreach($__js as $js) {
						if($position == 'header' && $js['options'] && $js['options']['position'] == 'footer') continue;
						else if($position != 'header' && (!$js['options'] || $js['options']['position'] != 'footer')) continue;
						if($js['options'] && $js['options']['condition']) {
							$script .= "\t<!--[".$js['options']['condition']."]>\n\t";
						}
						switch($js['type']) {
							case 'source':
								$script .= "\t".'<script id="'.$js['id'].'"'.($js['options'] && $js['options']['class'] ? ' class="'.$js['options']['class'].'"' : '').' type="text/javascript">'."\n\t\t".$js['script']."\n\t</script>\n";
								break;
							case 'src':
							default:
								$script .= "\t".'<script id="'.$js['id'].'"'.($js['options'] && $js['options']['class'] ? ' class="'.$js['options']['class'].'"' : '').' type="text/javascript" src="'.$js['script'].'"></script>'."\n";
								break;
						}
						if($js['options'] && $js['options']['condition']) {
							$script .= "\t<![endif]-->\n";
						}
					}
				}
			}
		}
		return $script;
	}

	public static function getJsList() {
		$js = array();
		$_js = self::sortJsByPriority();
		if(is_array($_js)) {
			foreach($_js as $p => $__js) {
				if(is_array($__js)) {
					foreach($__js as $___js) {
						$js[] = $___js;
					}
				}
			}
		}
		return $js;
	}

	function __destruct() {
	}
}
