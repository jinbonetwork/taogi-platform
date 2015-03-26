<?php
class Taogi_Theme {
	var $theme;
	var $fontface;
	var $config;

	function Taogi_Theme($theme,$config) {
		$this->theme = $theme;
		$this->config = $config;
		$this->fontface = array();
	}

	function makeStyle() {
		ob_start();?>
		<style type="text/css">
<?php	if($this->theme['background']) {?>
			#carousel-timeline-box { background:<?php print $this->theme['background']; ?> !important; }
<?php	}
		if($this->theme['canvas']) {?>
			#carousel-timeline .section { background:<?php print $this->theme['canvas']; ?> !important; }
<?php	}
	if($this->theme['cover']) {
		if($this->theme['cover']['subject']) {?>
			#carousel-timeline .touchcarousel-title-bar.taogi-theme-cover .cover-title h1 {
<?php		if($this->theme['cover']['subject']['font-family']) {
				$this->fontface[] = $this->theme['cover']['subject']['font-family']; ?>
				font-family:<?php print $this->theme['cover']['subject']['font-family']; ?> !important;
<?php		}?>
<?php		if($this->theme['cover']['subject']['color']) {?>
				color:<?php print $this->theme['cover']['subject']['color']; ?> !important;
<?php		}?>
			}
<?php	}
		if($this->theme['cover']['summary']) {?>
			#carousel-timeline .touchcarousel-title-bar.taogi-theme-cover .cover-title h2 {
<?php		if($this->theme['cover']['summary']['font-family']) {
				$this->fontface[] = $this->theme['cover']['summary']['font-family']; ?>
				font-family:<?php print $this->theme['cover']['summary']['font-family']; ?> !important;
<?php		}
			if($this->theme['cover']['summary']['color']) {?>
				color:<?php print $this->theme['cover']['summary']['color']; ?> !important;
<?php		}?>
			}
<?php	}
		if($this->theme['cover']['background']) {?>
			#carousel-timeline .touchcarousel-title-bar.taogi-theme-cover .cover-description { background:<?php print $this->theme['cover']['background']; ?> !important; }
<?php	}
	}?>
<?php if($this->theme['post']) {
		if($this->theme['post']['subject']) {?>
			#carousel-timeline .section.taogi-theme-post h2.cover-title,
			#carousel-timeline .section.taogi-theme-post h3.cover-author,
			#carousel-timeline .section.taogi-theme-post h2.title {
<?php		if($this->theme['post']['subject']['font-family']) {
				$this->fontface[] = $this->theme['post']['subject']['font-family']; ?>
				font-family:<?php print $this->theme['post']['subject']['font-family']; ?> !important;
<?php		}
			if($this->theme['post']['subject']['color']) {?>
				color:<?php print $this->theme['post']['subject']['color']; ?> !important;
<?php		}?>
			}
<?php	}
		if($this->theme['post']['summary']) {?>
			#carousel-timeline .section.taogi-theme-post p.description {
<?php		if($this->theme['post']['summary']['font-family']) {
				$this->fontface[] = $this->theme['post']['summary']['font-family']; ?>
				font-family:<?php print $this->theme['post']['summary']['font-family']; ?> !important;
<?php		}
			if($this->theme['post']['summary']['color']) {?>
				color:<?php print $this->theme['post']['summary']['color']; ?> !important;
<?php		}?>
			}
<?php	}
	}?>
		</style>
<?php
		$style = ob_get_contents();
		ob_end_clean();

		return $style;
	}

	function getRequiredFontFace() {
		return $this->fontface;
	}

	function attr($item) {
		$use_proxy = str_replace(".","\\.",implode("|",$this->config['use_proxy']));
		$attr = "";
		$attr .= 'href="'.$item['media'].'"';
		if(preg_match("/(".$use_proxy.")/i",$item['media'])) {
			$attr .= ' use_proxy="1"';
		}
		$attr .= ' credit="'.htmlspecialchars($item['credit']).'"';
		$attr .= ' caption="'.htmlspecialchars($item['caption']).'"';
		if($item['thumbnail']) {
			$attr .= ' thumbnail="'.$item['thumbnail'].'"';
			if(preg_match("/(".$use_proxy.")/i",$item['thumbnail'])) {
				$attr .= ' use_thumb_proxy="1"';
			}
		}
		return $attr;
	}

	function time($item,$startEnd='startDate',$class='',$attr='') {
		global $lang;
		if($startEnd == 'endDate') {
			$html = '<time pubdate datetime="'.JNTimeLine_prettyTime($item['endDate']).'" title="'.JNTimeLine_formatTime($item['endDate'],$lang->_t('time_format')).'" class="pubdate'.($class ? ' '.$class : '').'"'.($attr ? ' '.$attr : '').'>'.JNTimeLine_formatTime($item['endDate'],$lang->_t('time_format')).'</time>';
		} else if($startEnd == 'startDate') {
			$html = '<time pubdate datetime="'.JNTimeLine_prettyTime($item['startDate']).'" title="'.JNTimeLine_formatTime($item['startDate'],$lang->_t('time_format')).'" class="pubdate'.($class ? ' '.$class : '').'"'.($attr ? ' '.$attr : '').'>'.JNTimeLine_formatTime($item['startDate'],$lang->_t('time_format')).'</time>';
		}
		return $html;
	}

	function figure($item,$class='',$attr='') {
		$html = '<figure class="figure'.($class ? ' '.$class : '').'" '.$this->attr($item).($attr ? ' '.$attr : '').'></figure>';
		return $html;
	}
	
	function thumbnail($item,$class='',$attr='') {
		if(preg_match("/^http(s)?:\/\/".$_SERVER['HTTP_HOST']."\/(.+)\.(jpeg|jpg|gif|png|bmp)$/i",$item['media'])) {
			if(!$item['thumbnail'])
				$item['thumbnail'] = $item['media']."?s=small";
		}
		$html = '<li class="thumbnail'.($class ? ' '.$class : '').'" '.$this->attr($item).($attr ? ' '.$attr : '').'></li>';
		return $html;
	}
}
?>
