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
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'subject' => '제목',
							'timeline' => '본문',
						),
					),
					'keyword' => array(
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
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
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'subject' => '제목',
							'timeline' => '본문',
						),
					),
					'keyword' => array(
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
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
						'label' => '범위',
						'type' => 'select',
						'options' => array(
							'name' => '이름',
						),
					),
					'keyword' => array(
						'label' => '내용',
						'type' => 'text',
						'value' => '',
						'placeholder' => '검색할 내용을 입력하세요.',
					),
					'buttons' => array(
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
					'display_name' => array(
						'label' => '이름',
						'type' => 'text',
						'required' => '1',
						'unique' => '0',
						'help' => '',
					),
					'portrait' => array(
						'label' => '초상화',
						'type' => 'file',
						'required' => '0',
						'unique' => '0',
						'help' => '',
					),
					'description' => array(
						'label' => '소개',
						'type' => 'textarea',
						'required' => '0',
						'unique' => '0',
						'help' => '',
					),
					'taoginame' => array(
						'label' => '아이디',
						'type' => 'text',
						'required' => '1',
						'unique' => '1',
						'help' => '영문소문자만 입력할 수 있습니다.',
					),
					'email_id' => array(
						'label' => '이메일',
						'type' => 'email',
						'required' => '1',
						'unique' => '1',
						'help' => '',
					),
					'password' => array(
						'label' => '비밀번호',
						'type' => 'password',
						'required' => true,
						'unique' => '0',
						'help' => '비밀번호의 길이는 적어도 8글자를 넘어야 합니다.',
					),
					'password2' => array(
						'label' => '비밀번호 확인',
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

	public function buildForm($options=array()) {
		if(empty($options)||!is_array($options)) {
			$options = $this->options;
		}

		$this->instance++;

		$form_default_attributes = array(
			'id' => '',
			'class' => '',
			'action' => '',
			'method' => '',
			'onsubmit' => '',

			'data-instance' => $this->instance,
			'data-generator' => 'buildForm',
		);
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
			}

			$markup[] = '<fieldset '.$fieldset_attributes.'>'.PHP_EOL;
			$markup[] = '<legend>'.$legend.'</legend>'.PHP_EOL;
			foreach($fieldset as $item => $option) {

				$option['id'] = 'buildForm_'.$buildForm.'_'.$item;
				$option['class'] = array_merge((isset($option['class'])?$option['class']:array()),array($item));

				if(isset($option['label'])) {
					$label = $option['label'];
					unset($option['label']);
				}

				if(isset($option['value'])) {
					$value = $option['value'];
					unset($option['value']);
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

				$markup[] = '<div class="'.$item.'">'.PHP_EOL;
				if($label) {
					$markup[] = '<label for="'.$option['id'].'">'.$label.'</label>'.PHP_EOL;
				}
				switch($option['type']) {
				case 'buttons':
					foreach($option['buttons'] as $button => $button_attributes) {
						$markup[] = '<button '.$this->buildAttributes($button_attributes).'>'.$button_attributes['label'].'</button>'.PHP_EOL;
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
					$textarea = $option['value'];
					$markup[] = '<textarea '.$item_attributes.'>'.$textarea.'</textarea>'.PHP_EOL;
					break;
				case 'password':
				case 'text':
				default:
					$markup[] = '<input '.$item_attributes.'>'.PHP_EOL;
					break;
				}
				if($help) {
					$markup[] = '<p class="help">'.$help.'</p>'.PHP_EOL;
				}
				$markup[] = '</div>'.PHP_EOL;
				unset($item_attributes);
			}
			$markup[] = '</fieldset>'.PHP_EOL;
			unset($fieldset_attributes);
		}
		$markup[] = '</form>'.PHP_EOL;
		unset($form_attributes);
		return implode('',$markup);
	}

	public function getForm($options=array()) {
		return $this->buildForm($options);
	}

	public function printForm($options=array()) {
		echo $this->getForm($options);
	}

}
?>
