<?php
//-----------------------------------------------------------------------------
//	Table markup builder
//-----------------------------------------------------------------------------

class Markup_Table extends Markup {

	public $controls;
	public $headers;

	function __construct($context='',$type='',$scope='') {
		$this->construct($context,$type,$scope);
		$this->template = 'table';
		$this->controls = new Markup_Controls($context,$type,$scope,$this);

		$this->headers = $this->getDefaultHeaders();
		$this->options = $this->getDefaultOptions();
	}

	function getDefaultHeaders() {
		$headers = null;

		switch($this->context) {
		case 'entries':
		case 'userEntries':
			$headers = array(
				'item_title' => '타임라인 제목',
				'item_created' => '만든 날짜',
				'item_updated' => '갱신된 날짜',
				'item_controls' => '관리',
			);
			break;
		case 'revisions':
		case 'entryRevisions':
			$headers = array(
				'item_title' => '버전 정보',
				'item_updated' => '갱신된 날짜',
				'item_editor' => '편집자',
				'item_controls' => '관리',
			);
			break;
		case 'users':
		case 'entryAuthors':
			$headers = array(
				'item_name' => '이름',
				'item_role' => '권한',
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
		case 'userEntries':
			$options = array(
				'attributes' => array(
					'class' => array('timeline-list','timeline-list-timelines'),
				),
				'column_callbacks' => array(
					'item_controls' => $this->controls?array($this->controls,'getControls'):null,
				),
				'column_patterns' => array(
					'item_title' => '<div class="label subject"><a href="%permalink%">%subject%</a></div><div class="help summary">%summary%</div>',
					'item_owner' => '<a href="%owner_dashboard_link%" title="%author%">%owner_DISPLAY_NAME%</a>',
					'item_editor' => '<a href="%editor_dashboard_link%" title="">%editor_DISPLAY_NAME%</a>',
					'item_created' => '<div class="label date created" title="%created_relative%">%created_absolute%</div><div class="help user owner">%item_owner%</div>',
					'item_updated' => '<div class="label date updated" title="%updated_relative%">%updated_absolute%</div><div class="help user editor">%item_editor%</div>',
				),
				'checkbox_field' => 'eid',
				'controls_field' => 'item_controls',
				'controls_switch_field' => 'item_title',
			);
			break;
		case 'revisions':
		case 'entryRevisions':
			$options = array(
				'attributes' => array(
					'class' => array('timeline-list','timeline-list-revisions'),
				),
				'column_callbacks' => array(
					'item_controls' => $this->controls?array($this->controls,'getControls'):null,
				),
				'column_patterns' => array(
					'item_title' => '<div class="label subject"><a href="%permalink%?vid=%vid%">%subject%</a>#%vid%</div>',
					'item_editor' => '<div class="label name"><a href="%editor_dashboard_link%" title="">%editor_DISPLAY_NAME%</a></div>',
					'item_updated' => '<div class="label date updated" title="%updated_relative%">%updated_absolute%</div>',
				),
				'checkbox_field' => 'vid',
			);
			break;
		case 'users':
		case 'entryAuthors':
			$options = array(
				'attributes' => array(
					'class' => array('user-list'),
				),
				'column_callbacks' => array(
					'item_controls' => $this->controls?array($this->controls,'getControls'):null,
				),
				'column_patterns' => array(
					'item_name' => '<div class="label DISPLAY_NAME"><a href="%dashboard_link%">%DISPLAY_NAME%</a></div>',
					'item_role' => '%ROLE%',
				),
				'checkbox_field' => 'uid',
			);
			break;
		}

		return $options;
	}

	public function buildTable($data=array(),$headers=array(),$options=array()) {
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
			'data-generator' => 'buildTable',
		);
		$options['attributes']['class'][] = 'ui-table';
		$options['attributes']['class'][] = 'ui-items';
		$options['attributes']['class'][] = 'buildTable';
		if($options['checkbox_field']) {
			$options['attributes']['class'][] = 'ui-checkbox-group';
		}
		$attributes = $this->buildAttributes($options['attributes'],$default_attributes);

		$markup[] = '<table '.$attributes.'>'.PHP_EOL;

		if(empty($headers)) {
			foreach($data[0] as $header => $value) {
				$headers[$header] = $header;
			}
		}
		$markup_header[] = '<thead>'.PHP_EOL;
		$markup_header[] = '<tr>'.PHP_EOL;
		if($options['checkbox_field']) {
			$markup_header[] = "<th class=\"ui-checkbox-switch-container {$options['checkbox_field']}\"><input class=\"ui-checkbox-switch\" type=\"checkbox\"></th>".PHP_EOL;
		}
		foreach($headers as $header => $label) {
			if($header==$options['controls_field']) {
				continue;
			}
			$markup_header[] = "<th class=\"{$header}\">{$label}</th>".PHP_EOL;
		}
		$markup_header[] = '</tr>'.PHP_EOL;
		$markup_header[] = '</thead>'.PHP_EOL;
		$markup[] = implode('',$markup_header);
		unset($markup_header);

		$markup[] = '<tbody>'.PHP_EOL;
		$row_index = 0;
		foreach($data as $row) {
			$row_attributes = array(
				'class' => array('ui-item'),
				'data-index' => $row_index,
			);

			$row = $this->rebuildColumns($row,$options);

			if($options['checkbox_field']) {
				$markup_row[] = "<td class=\"ui-checkbox-td {$options['checkbox_field']}\"><label for=\"ui-checkbox-table-{$this->instance}_{$row[$options['checkbox_field']]}\" class=\"ui-checkbox-container {$options['checkbox_field']}\"><input id=\"ui-checkbox-table-{$this->instance}_{$row[$options['checkbox_field']]}\" class=\"ui-checkbox\"type=\"checkbox\" name=\"{$options['checkbox_field']}[]\" value=\"{$row[$options['checkbox_field']]}\"></td>".PHP_EOL;
				$row_attributes['data-'.$options['checkbox_field']] = $row[$options['checkbox_field']];
			}
			foreach($headers as $header => $label) {
				if($header==$options['controls_field']) {
					$markup_colspan_count = count($headers);
					$markup_controls = "<td class=\"{$header}\" colspan=\"{$markup_colspan_count}\"><div class=\"wrap\">{$row[$header]}</div></td>".PHP_EOL;
				} else {
					if($header==$options['controls_switch_field']) {
						$row[$header] .= '<a class="ui-controls-switch" href="#ui-controls-'.$this->template.'-'.$this->controls->instance.'_'.$row[$options['checkbox_field']].'"><span>관리<span></a>'.PHP_EOL;
					}
					$markup_row[] = "<td class=\"{$header}\"><div class=\"wrap\">{$row[$header]}</div></td>".PHP_EOL;
				}
			}
			$markup[] = '<tr'.$this->buildAttributes($row_attributes).'>'.PHP_EOL;
			$markup[] = implode('',$markup_row).PHP_EOL;
			$markup[] = '</tr>'.PHP_EOL;
			if($markup_controls) {
				$row_attributes['id'] = 'ui-controls-container_'.$row[$options['checkbox_field']];
				$row_attributes['class'][] = 'ui-controls-container';
				$markup[] = '<tr'.$this->buildAttributes($row_attributes).'>'.PHP_EOL;
				$markup[] = $markup_controls;
				$markup[] = '</tr>'.PHP_EOL;
				unset($markup_controls);
			}
			$row_index++;
			unset($markup_row);
			unset($row_attribute);
		}
		unset($row_index);
		$markup[] = '</tbody>'.PHP_EOL;
		$markup[] = '</table>'.PHP_EOL;

		$table = implode('',$markup);

		return $table;

	}

	public function getTable($data=array(),$headers=array(),$options=array()) {
		return $this->buildTable($data,$headers,$options);
	}

	public function printTable($data=array(),$headers=array(),$options=array()) {
		echo $this->getTable($data,$headers,$options);
	}
}
?>
