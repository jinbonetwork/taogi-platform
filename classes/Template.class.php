<?php
class Template extends Objects {
	
	public static function instance(){
		return self::_instance(__CLASS__);
	}

	public static function getGeneralHeader(){
		$context = Model_Context::instance();
		$imageIndexes = $context->getOptions('imageIndexes');
		$base_uri = base_uri(); 
		$taogi = json_encode(array(
			'portrait' => array(
				'width' => $imageIndexes['portrait']['width'],
				'height' => $imageIndexes['portrait']['height'],
			),
		));
		$markup = "var base_uri = '{$base_uri}';
		var taogi = {$taogi};";
		View_Resource::addScriptSource($markup,-10000);
	}

	public static function buildSocialMetaTags($attributes=array()){
		$tags = array();
		$defaults = array(
			'sitename' => Platform::getProperty('service.title'),
		);
		$attributes = array_merge($defaults,$attributes);

		if(!empty($attributes)){
			foreach($attributes as $property => $content){
				switch($property){
					case 'sitename':
						$tags['og']['site_name'] = $content;
					break;
					case 'type':
						switch($content){
							default:
								$tags['og']['type'] = 'article';
								$tags['twitter']['card'] = 'summary';
							break;
						}
					break;
					case 'title':
						$tags['og']['title'] = addslashes(strip_tags($content));
						$tags['twitter']['title'] = addslashes(strip_tags($content));
					break;
					case 'url':
						$tags['og']['url'] = $content;
						$tags['twitter']['url'] = $content;
					break;
					case 'author':
						$tags['article']['author'] = $content;
					break;
					case 'published_time':
					case 'modified_time':
						$tags['article'][$property] = date('c',intval($content));
					break;
					case 'description':
						$tags['og']['description'] = addslashes(strip_tags($content));
						$tags['twitter']['description'] = addslashes(strip_tags($content));
					break;
					case 'image':
						$tags['og']['image'] = $content;
						$tags['twitter']['image:src'] = $content;
					break;
					default:
						$tags['default'][$property] = $content;
					break;
				}
			}
			ksort($tags);
		}

		return $tags;
	}

	public static function getSocialMetaTags($attributes=array()){
		$tags = self::buildSocialMetaTags($attributes);
		$lines = array();
		$markup = '';

		if(!empty($tags)){
			foreach($tags as $service => $contents){
				switch($service){
					case 'article':
						$label = 'property';
						$_key = 'article:';
					break;
					case 'og':
						$label = 'property';
						$_key = 'og:';
					break;
					case 'twitter':
						$label = 'name';
						$_key = 'twitter:';
					break;
					default:
						$label = 'name';
						$_key = '';
					break;
				}
				foreach($contents as $key => $value){
					$lines[] = "<meta {$label}='{$_key}{$key}' content='{$value}'>";
				}
			}
			$markup = implode(PHP_EOL,$lines).PHP_EOL;
		}

		return $markup;
	}

	public static function printSocialMetaTags($attributes=array()){
		echo self::getSocialMetaTags($attributes);
	}

	public static function checkExteriorBasicSettings($that){
		$that->source = $that->getJsonURI();
		if($that->entry['timeline']) {
			$that->json = json_decode($that->entry['timeline'],true);
		} else {
			if(!preg_match("/^http:\/\//i",$that->source) && file_exists($that->source)) 
				$that->json = json_decode(file_get_contents($that->source),true);
		}
		$that->exterior = array(
			'templates' => array(
				'asset__cover_background_image',
				'extra__cover_background_color',
				'extra__cover_title_color',
				'extra__cover_body_color',
				'extra__slide_background_color',
				'extra__slide_title_color',
				'extra__slide_body_color',
				'asset__back_background_image',
				'extra__back_background_color',
				'extra__back_title_color',
				'extra__back_body_color',
			),
			'filtered' => array(
			),
			'css' => getEntryExtraCssURL($that->entry['eid']),
		);
		foreach($that->exterior['templates'] as $scope_field){
			list($scope,$field) = explode('__',$scope_field);
			//$value = $that->exterior['json']->$scope->$field;
			$value = $that->json['timeline'][$scope][$field];
			if($value){
				if($scope=='asset'){
					$url = explode('/',$value);
					$url[count($url)-1] = rawurlencode($url[count($url)-1]);
					$value = implode('/',$url);
				}
				$that->exterior['filtered'][$scope_field] = $value;
			}
		}
	}

	public static function getExteriorCss($that){
		$output;
		$lesss = (object) array(
			'preset' => '',
			'basic' => '',
			'extra' => '',
		);

		if($that->preset){
			$preset_style_path = JFE_PRESET_PATH.'/'.$that->model.'/'.$that->preset.'/style.css';
			$lesss->preset = file_get_contents($preset_style_path);
		}

		if(true){
			self::checkExteriorBasicSettings($that);
			foreach($that->exterior['filtered'] as $selector => $property){
				if($property){
					switch($selector){
						case 'asset__cover_background_image':
							$lesss->basic .= <<<ASSET__COVER_BACKGROUND_IMAGE

								.touchcarousel-item.cover.front section.article {
									background: transparent url('{$property}') scroll repeat center center;
									background-size: cover;
								}

ASSET__COVER_BACKGROUND_IMAGE;
						break;
						case 'extra__cover_background_color':
							$lesss->basic .= <<<EXTRA__COVER_BACKGROUND_COLOR

								.touchcarousel-item.cover.front section.article:before {
									content: '';
									position: absolute;
									z-index: 1;
									top: 0;
									left: 0;
									display: block;
									width: 100%;
									height: 100%;
									background-color: {$property};
								}
								.touchcarousel-item.cover.front section.article  article.wrap {
									z-index: 2;
								}

EXTRA__COVER_BACKGROUND_COLOR;
						break;
						case 'extra__cover_title_color':
							$lesss->basic .= <<<EXTRA__COVER_TITLE_COLOR

								.touchcarousel-item.cover.front section.article .title {
									color: {$property};
								}
								.touchcarousel-item.cover.front section.article .description {
									border-top-color: {$property};
								}

EXTRA__COVER_TITLE_COLOR;
						break;
						case 'extra__cover_body_color':
							$lesss->basic .= <<<EXTRA__COVER_BODY_COLOR

								.touchcarousel-item.cover.front section.article .description,
								.touchcarousel-item.cover.front section.article .author,
								.touchcarousel-item.cover.front section.article .pubdate,
								.touchcarousel-item.cover.front section.article .social a {
									color: {$property};
								}

EXTRA__COVER_BODY_COLOR;
						break;
						case 'extra__slide_background_color':
							$lesss->basic .= <<<EXTRA__SLIDE_BACKGROUND_COLOR

								.touchcarousel-item section.article {
									background-color: {$property};
								}

EXTRA__SLIDE_BACKGROUND_COLOR;
						break;
						case 'extra__slide_title_color':
							$lesss->basic .= <<<EXTRA__SLIDE_TITLE_COLOR

								.touchcarousel-item section.article .title {
									color: {$property};
								}

EXTRA__SLIDE_TITLE_COLOR;
						break;
						case 'extra__slide_body_color':
							$lesss->basic .= <<<EXTRA__SLIDE_BODY_COLOR

								.touchcarousel-item section.article .description,
								.touchcarousel-item section.article .pubdate {
									color: {$property};
								}
								.touchcarousel-item section.article .pubdate {
									border-bottom-color: {$property};
								}

EXTRA__SLIDE_BODY_COLOR;
						break;
						case 'asset__back_background_image':
							$lesss->basic .= <<<ASSET__BACK_BACKGROUND_IMAGE
							
								.touchcarousel-item.cover.back section.article {
									background: transparent url('{$property}') scroll repeat center center;
									background-size: cover;
								}

ASSET__BACK_BACKGROUND_IMAGE;
						break;
						case 'extra__back_background_color':
							$lesss->basic .= <<<EXTRA__BACK_BACKGROUND_COLOR

								.touchcarousel-item.cover.back section.article:before {
									content: '';
									position: absolute;
									z-index: 1;
									top: 0;
									left: 0;
									display: block;
									width: 100%;
									height: 100%;
									background-color: {$property};
								}
								.touchcarousel-item.cover.back section.article article.wrap {
									z-index: 2;
								}

EXTRA__BACK_BACKGROUND_COLOR;
						break;
						case 'extra__back_title_color':
							$lesss->basic .= <<<EXTRA__BACK_TITLE_COLOR

								.touchcarousel-item.cover.back section.article .title {
									color: {$property};
								}

EXTRA__BACK_TITLE_COLOR;
						break;
						case 'extra__back_body_color':
							$lesss->basic .= <<<EXTRA__BACK_BODY_COLOR

								.touchcarousel-item.cover.back section.article .description,
								.touchcarousel-item.cover.back section.article .author,
								.touchcarousel-item.cover.back section.article .pubdate,
								.touchcarousel-item.cover.back section.article .social a {
									color: {$property};
								}
								.touchcarousel-item.cover.back section.article .description {
									border-bottom-color: {$property};
								}

EXTRA__BACK_BODY_COLOR;
						break;
					}
				}
			}
		}

		if($that->exterior['css']){
			$lesss->extra = file_get_contents(JFE_PATH.$that->exterior['css']);
		}

		if(!empty($lesss)){
			require_once JFE_CONTRIBUTE_PATH."/lessphp/lessc.inc.php";
			$less = new lessc;
			$source = implode(PHP_EOL,(array) $lesss);
			$output = $less->compile("html#taogi-net{{$source}}");
		}

		return $output;
	}

	public static function printExteriorCss($that){
		echo self::getExteriorCss($that);
	}

	public static function getExteriorHeaderTags($that){
		$header;

		$header .= "<style>".self::getExteriorCss($that)."</style>".PHP_EOL;

		return $header;
	}

	public static function printExteriorHeaderTags($that){
		echo self::getExteriorHeaderTags($that);
	}
}
?>
