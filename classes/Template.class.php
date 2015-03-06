<?php
class Template extends Objects {
	
	public static function instance(){
		return self::_instance(__CLASS__);
	}

	public static function getSocialMetaTags($attributes=array()){
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

	public static function printSocialMetaTags($attributes=array()){
		if(isset($attributes['echo'])){
			$echo = $attributes['echo'];
			unset($attributes['echo']);
		}else{
			$echo = true;
		}
		$tags = self::getSocialMetaTags($attributes);
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

		if($echo){
			print $markup;
		}else{
			return $markup;
		}
	}
}
?>
