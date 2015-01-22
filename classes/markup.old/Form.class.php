<?php
class Markup_Form extends Markup {

	function __construct($context='',$type='',$scope='') {
		$this->construct($context,$type,$scope);
		$this->options = $this->getDefaultOptions();
	}

	function getDefaultOptions() {
		$options = null;

		switch($this->context) {
		case 'entrySearch':
			$options = array(
				'form' => array(
					'id' => 'timeline-search',
					'class' => 'search',
				),
				'entrySearch' => array(
					'scope' => array(
						'name' => 'scope',
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'subject' => '제목',
							'timeline' => '본문',
						),
					),
					'keyword' => array(
						'name' => 'keyword',
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
						'name' => '',
						'type' => 'buttons',
						'buttons' => array(
							'search' => array(
								'label' => '찾기',
								'type' => 'submit',
								'name' => 'search',
								'value' => '',
							),
						),
					),
				),
			);
			break;
		case 'revisionSearch':
			$options = array(
				'form' => array(
					'id' => 'timeline-search',
					'class' => 'search',
				),
				'revisionSearch' => array(
					'scope' => array(
						'name' => 'scope',
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'subject' => '제목',
							'timeline' => '본문',
						),
					),
					'keyword' => array(
						'name' => '',
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
						'name' => '',
						'type' => 'buttons',
						'buttons' => array(
							'search' => array(
								'label' => '찾기',
								'type' => 'submit',
								'name' => 'search',
								'value' => '',
							),
						),
					),
				),
			);
			break;
		case 'userSearch':
			$options = array(
				'form' => array(
					'id' => 'user-search',
					'class' => 'search',
				),
				'userSearch' => array(
					'scope' => array(
						'name' => 'scope',
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'name' => '이름',
						),
					),
					'keyword' => array(
						'name' => 'keyword',
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
						'name' => '',
						'type' => 'buttons',
						'buttons' => array(
							'search' => array(
								'label' => '찾기',
								'type' => 'submit',
								'name' => 'search',
								'value' => '',
							),
						),
					),
				),
			);
			break;
		case 'userForm':
			$options = array(
				'form' => array(
					'id' => '',
					'class' => 'userForm',
					'action' => '',
				),
				'userForm' => array(
					'uid' => array(
						'name' => 'uid',
						'label' => '',
						'value' => '',
						'type' => 'hidden',
						'required' => '1',
						'unique' => '1',
						'help' => '',
					),
					'display_name' => array(
						'name' => 'display_name',
						'label' => '이름',
						'value' => '',
						'type' => 'text',
						'required' => '1',
						'unique' => '0',
						'help' => '',
					),
					'portrait' => array(
						'name' => 'portrait',
						'label' => '초상화',
						'value' => '',
						'type' => 'file',
						'required' => '0',
						'unique' => '0',
						'help' => '',
					),
					'summary' => array(
						'name' => 'summary',
						'label' => '소개',
						'value' => '',
						'type' => 'textarea',
						'required' => '0',
						'unique' => '0',
						'help' => '',
					),
					'taoginame' => array(
						'name' => 'taoginame',
						'label' => '아이디',
						'value' => '',
						'type' => 'text',
						'required' => '1',
						'unique' => '1',
						'help' => '영문소문자만 입력할 수 있습니다.',
					),
					'email_id' => array(
						'name' => 'email_id',
						'label' => '이메일',
						'value' => '',
						'type' => 'email',
						'required' => '1',
						'unique' => '1',
						'help' => '',
					),
					'password1' => array(
						'name' => 'password1',
						'label' => '비밀번호',
						'value' => '',
						'type' => 'password',
						'required' => true,
						'unique' => '0',
						'help' => '비밀번호의 길이는 적어도 8글자를 넘어야 합니다.',
					),
					'password2' => array(
						'name' => 'password2',
						'label' => '비밀번호 확인',
						'value' => '',
						'type' => 'password',
						'required' => true,
						'unique' => false,
						'help' => '비밀번호를 다시 한번 입력해주세요.',
					),
				),
			);
			break;	
		}

		return $options;
	}

	public function buildForm($options=array(),$row=array()) {
		if(empty($options)||!is_array($options)) {
			$options = $this->options;
		}

		$this->instance++;

		$form_default_attributes = array(
			'id' => '',
			'class' => array(),
			'action' => '',
			'method' => 'post',
			'onsubmit' => '',

			'data-instance' => $this->instance,
			'data-generator' => 'buildForm',
		);
		if(is_array($options['form']['class'])) {
			$options['form']['class'][] = 'ui-form';
		} else {
			$options['form']['class'] = array($options['form']['class'],'ui-form');
		}
		$options['form']['class'] = array_unique($options['form']['class']);

		if(!empty($options['form'])&&is_array($options['form'])) {
			$form_attributes = $this->buildAttributes($options['form'],$form_default_attributes);
		} else {
			$form_attributes = $this->buildAttributes($form_default_attributes);
		}
		$markup[] = '<form '.$form_attributes.'>'.PHP_EOL;
		foreach($options as $legend => $fieldset) {
			if($legend=='form') {
				continue;
			}

			if(!empty($fieldset['fieldset'])&&is_array($fieldset['fieldset'])) {
				$fieldset_attributes = $this->buildAttributes($fieldset['fieldset']);
			} else {
				$fieldset_attributes = '';
			}

			$markup[] = '<fieldset '.$fieldset_attributes.'>'.PHP_EOL;
			$markup[] = '<legend>'.$legend.'</legend>'.PHP_EOL;
			foreach($fieldset as $item => $option) {

				if(!isset($option['value'])||!$option['value']) {
					if(isset($row[$item])) {
						$option['value'] = $row[$item];
					}
				}

				$option['id'] = 'buildForm_'.$this->instance.'_'.$item;
				$option['class'] = array_merge((isset($option['class'])?$option['class']:array()),array($item));

				if(isset($option['label'])) {
					$label = $option['label'];
					unset($option['label']);
				}

				if(isset($option['help'])) {
					$help = $option['help'];
					unset($option['help']);
				}

				foreach($option as $ok => $ov) {
					$ok = in_array($ok,array('id','class','type','name','value'))?$ok:'data-'.$ok;
					$_option[$ok] = $ov;
				}
				
				$item_attributes = $this->buildAttributes($_option);

				if($option['type']!='hidden') {
					$markup[] = '<div class="'.$item.' ui-form-item '.$option['type'].'">'.PHP_EOL;
					if($label) {
						$markup[] = '<label for="'.$option['id'].'">'.$label.'</label>'.PHP_EOL;
					}
					$markup[] = '<div class="value '.$option['type'].'">'.PHP_EOL;
				}
				switch($option['type']) {
				case 'buttons':
					foreach($option['buttons'] as $button => $button_attributes) {
						$markup[] = '<button '.$this->buildAttributes($button_attributes).'>'.$button_attributes['label'].'</button>'.PHP_EOL;
					}
					break;
				case 'radio':
					$radio_index = 0;
					foreach($option['options'] as $value => $label) {
						$radio_attributes = $option;
						$radio_attributes['id'] = $radio_attributes['id'].'_'.$radio_index;
						$radio_attributes = $this->buildAttributes($radio_attributes);
						$is_checked = $value==$option['value']?' checked="checked"':'';
						$markup[] = '<input '.$radio_attributes.' value="'.$value.$is_checked.'"><label for="'.$option['id'].'">'.$label.'</label>'.PHP_EOL;
						$radio_index++;
					}
					break;
				case 'select':
					$markup[] = '<select '.$item_attributes.'>'.PHP_EOL;
					foreach($option['options'] as $value => $label) {
						$is_selected = $value==$option['value']?' selected="selected"':'';
						$markup[] = '<option value="'.$value.$is_selected.'">'.$label.'</option>'.PHP_EOL;
					}
					$markup[] = '</select>'.PHP_EOL;
					break;
				case 'textarea':
					if(isset($option['value'])) {
						$textarea = $option['value'];
						unset($option['value']);
					}
					$markup[] = '<textarea '.$item_attributes.'>'.$textarea.'</textarea>'.PHP_EOL;
					break;
				case 'password':
				case 'text':
				default:
					$markup[] = '<input '.$item_attributes.'>'.PHP_EOL;
					break;
				}
				if($option['type']!='hidden') {
					if($help) {
						$markup[] = '<p class="help">'.$help.'</p>'.PHP_EOL;
					}
					$markup[] = '</div><!--/.value-->'.PHP_EOL;
					$markup[] = '</div><!--/.ui-form-item-->'.PHP_EOL;
				}
				unset($item_attributes);
			}
			$markup[] = '</fieldset>'.PHP_EOL;
			unset($fieldset_attributes);
		}
		$markup[] = '</form>'.PHP_EOL;
		unset($form_attributes);
		return implode('',$markup);
	}

	public function getForm($options=array(),$row=array()) {
		return $this->buildForm($options,$row);
	}

	public function printForm($options=array(),$row=array()) {
		echo $this->getForm($options,$row);
	}

}
?>
