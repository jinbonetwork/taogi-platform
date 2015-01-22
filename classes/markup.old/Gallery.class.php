<?php
//-----------------------------------------------------------------------------
//	Gallery markup builder
//-----------------------------------------------------------------------------

class Markup_Gallery extends Markup {

	public $controls;
	public $headers;

	function __construct($context='',$type='',$scope='') {
		$this->construct($context,$type,$scope);
		$this->template = 'gallery';
		$this->controls = new Markup_Controls($context,$type,$scope,$this);

		$this->headers = $this->getDefaultHeaders();
		$this->options = $this->getDefaultOptions();
	}

	function getDefaultHeaders() {
		$headers = null;

		switch($this->context) {
		case 'entries':
		case 'recentEntries':
		case 'userEntries':
			$headers = array(
				'item_image' => '타임라인 표지',
				'item_title' => '타임라인 제목',
				'item_description' => '타임라인 설명',
				'item_owner' => '타임라인 개설자',
				'item_controls' => '관리',
			);
			break;
		case 'revisions':
			// no revision gallery
			break;
		case 'users':
		case 'entryAuthors':
			$headers = array(
				'item_image' => '사용자 이미지',
				'item_name' => '사용자 이름',
				'item_description' => '사용자 설명',
				'item_controls' => '관리',
			);
			break;
		}

		return $headers;
	}

	function getDefaultOptions() {
		$options = null;

		switch($this->context) {
		case 'entries':
		case 'recentEntries':
		case 'userEntries':
			$options = array(
				'attributes' => array(
					'class' => array('timeline-gallery','timeline-gallery-timelines'),
				),
				'column_callbacks' => array(
					'item_controls' => $this->controls?array($this->controls,'getControls'):null,
				),
				'column_patterns' => array(
					'item_image' => '<div class="label image"><a class="keepCover" href="%permalink%"><img src="%image%" alt=""></a></div>',
					'item_title' => '<div class="label subject"><a href="%permalink%">%subject%</a></div>',
					'item_description' => '<div class="help summary">%summary%</div>',
					'item_owner' => '<a href="%owner_dashboard_link%" title="%owner_DISPLAY_NAME%">%author%</a>',
					'item_editor' => '<a href="%editor_dashboard_link%" title="">%editor_DISPLAY_NAME%</a>',
					'item_created' => '<div class="label date created" title="%created_relative%">%created_absolute%</div><div class="help user owner">%item_owner%</div>',
					'item_updated' => '<div class="label date updated" title="%updated_relative%">%updated_absolute%</div><div class="help user editor">%item_editor%</div>',
				),
				'cover_field' => 'item_image',
				'click_selector' => '.item_title',
				'checkbox_field' => 'eid',
				'checkbox_append_field' => 'item_image',
				'controls_field' => 'item_controls',
				'controls_switch_field' => 'item_image', 
			);
			break;
		case 'revisions':
			// no revision gallery
			break;
		case 'users':
		case 'entryAuthors':
			$options = array(
				'attributes' => array(
					'class' => array('user-gallery','user-gallery-users'),
				),
				'column_callbacks' => array(
					'item_controls' => $this->controls?array($this->controls,'getControls'):null,
				),
				'column_patterns' => array(
					'item_image' => '<div class="label image"><a class="keepCover" href="%dashboard_link%"><img src="%image%" alt=""></a></div>',
					'item_name' => '<div class="label DISPLAY_NAME"><a href="%dashboard_link%">%DISPLAY_NAME%</a></div>',
					'item_description' => '<div class="help summary">%summary%</div>',
				),
				'cover_field' => 'item_image',
				'click_selector' => '.item_title',
				'checkbox_field' => 'uid',
				'checkbox_append_field' => 'item_image',
				'controls_field' => 'item_controls',
				'controls_switch_field' => 'item_image', 
			);
			break;
		}

		return $options;
	}

	public function buildGallery($data=array(),$headers=array(),$options=array()) {
		if(empty($data)) {
			return DATA_NOT_FOUND;
		}
		if(!is_array($data)) {
			return INVALID_DATA_FORMAT;
		}
		$data = array_map(array($this,'get'.ucfirst($this->type)),$data);
		if(empty($headers)) {
			$headers = $this->headers;
		}
		if(empty($options)) {
			$options = $this->options;
		}

		$this->instance++;
		$this->controls->instance = $this->instance;
		$default_attributes = array(
			'id' => '',
			'class' => array(),
			'data-instance' => $this->instance,
			'data-generator' => 'buildGallery',
		);
		$options['attributes']['class'][] = 'ui-gallery';
		$options['attributes']['class'][] = 'ui-items';
		$options['attributes']['class'][] = 'buildGallery';
		if($options['checkbox_field']) {
			$options['attributes']['class'][] = 'ui-checkbox-group';
		}
		$attributes = $this->buildAttributes($options['attributes'],$default_attributes);

		if(empty($headers)) {
			foreach($data[0] as $header => $value) {
				$headers[$header] = $header;
			}
		}
		if($options['checkbox_field']) {
			$markup_header[] = '<table class="ui-table">'.PHP_EOL;
			$markup_header[] = '<tr>'.PHP_EOL;
			$markup_header[] = "<th class=\"ui-checkbox-switch-container {$options['checkbox_field']}\"><input class=\"ui-checkbox-switch\" type=\"checkbox\"></th>".PHP_EOL;
			foreach($headers as $header => $label) {
				if($header==$options['colspan_field']) {
					continue;
				}
				$markup_header[] = "<th class=\"{$header}\">{$label}</th>".PHP_EOL;
			}
			$markup_header[] = '</tr>'.PHP_EOL;
			$markup_header[] = '</table>'.PHP_EOL;
			$markup_header = implode('',$markup_header);
		}

		$markup[] = '<div '.$attributes.'>'.PHP_EOL;
		if($options['checkbox_field']) {
			$markup[] = $markup_header;
		}
		$markup[] = '<ul>'.PHP_EOL;

		if(empty($headers)) {
			foreach($data[0] as $header => $value) {
				$headers[$header] = $header;
			}
		}
		$row_index = 0;
		foreach($data as $row) {
			$row_attributes = array(
				'class' => array('ui-item'),
				'data-index' => $row_index,
				'data-use-hover-class' => 'true',
			);
			if(isset($options['click_selector'])) {
				$row_attributes['data-use-box-click'] = $options['click_selector'];
			}

			$row = $this->rebuildColumns($row,$options);

			$markup_row[] = "<dl>".PHP_EOL;
			foreach($headers as $header => $label) {
				if($options['checkbox_field']&&$header==$options['checkbox_append_field']) {
					$row[$header] .= "<label for=\"ui-checkbox-gallery-{$this->instance}_{$row[$options['checkbox_field']]}\" class=\"ui-checkbox-container {$options['checkbox_field']}\"><input id=\"ui-checkbox-gallery-{$this->instance}_{$row[$options['checkbox_field']]}\" class=\"ui-checkbox\" type=\"checkbox\" name=\"{$options['checkbox_field']}[]\" value=\"{$row[$options['checkbox_field']]}\"></label>".PHP_EOL;
					$row_attributes['data-'.$options['checkbox_field']] = $row[$options['checkbox_field']];
				}
				if($options['controls_field']&&$header==$options['controls_switch_field']) {
					$row[$header] .= '<a class="ui-controls-switch" href="#ui-controls-'.$this->template.'-'.$this->controls->instance.'_'.$row[$options['checkbox_field']].'"><span>관리</span></a>'.PHP_EOL;
				}
				if($header==$options['controls_field']) {
					$markup_row_controls = $row[$header];
				} else {
					if($header==$options['cover_field']) {
						$dd_attributes = array(
							'class' => array('keepRatio'),
							'data-width' => '16',
							'data-height' => '10',
						);
					}
					$dd_attributes['class'][] = $header;
					$dd_attributes = $this->buildAttributes($dd_attributes);
					$markup_row[] = "<dt>{$label}</dt>".PHP_EOL;
					$markup_row[] = "<dd {$dd_attributes}>{$row[$header]}</dd>".PHP_EOL;
					unset($dd_attributes);
				}
			}
			$markup_row[] = '</dl>'.PHP_EOL;
			if($markup_row_controls) {
				$markup_row[] = $markup_row_controls;
			}
			$markup[] = '<li '.$this->buildAttributes($row_attributes).'>'.PHP_EOL;
			$markup[] = implode('',$markup_row).PHP_EOL;
			$markup[] = '</li>'.PHP_EOL;
			$row_index++;
			unset($markup_row);
			unset($markup_row_controls);
			unset($row_attribute);
		}
		unset($row_index);
		$markup[] = '</ul>'.PHP_EOL;
		$markup[] = '</div>'.PHP_EOL;

		$gallery = implode('',$markup);

		return $gallery;

	}

	public function getGallery($data=array(),$headers=array(),$options=array()) {
		return $this->buildGallery($data,$headers,$options);
	}

	public function printGallery($data=array(),$headers=array(),$options=array()) {
		echo $this->getGallery($data,$headers,$options);
	}
}
?>
