<?php
$Acl = "editor";
class entry_modify extends Controller {
	public function index() {
		$this->title = "따오기 타임라인 수정하기";
		$context = Model_Context::instance();
		require JFE_PATH."/timeline/model/touchcarousel/config/config.php";

		$this->view_mode ='edit';
		$this->mediaconfig = $config;
					        
		$uid = Acl::getIdentity('taogi');
		if($uid < 1) Error("타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다.",403);

		if(!$this->params['taogiid']) Error("수정할 타임라인을 지정하세요");
		$this->eid = $this->params['taogiid'];

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->params['vid']) $this->entry['vid'] = $this->params['vid'];
		$this->vid = $this->entry['vid'];

		$data = Entry::getEntryData($this->eid,$this->vid);
		if($this->entry['locked'] && $this->entry['locked'] != $_COOKIE[Session::getName()]) {
			if( $this->entry['modified'] > ( time() - 3600 ) )
				Error("다른 분이 편집중입니다. 잠시후 다시 해주세요.",423);
		}
		$this->timeline = json_decode($data['timeline'],true);
		$this->timeline = $this->timeline['timeline'];
		$this->extra = Entry::getEntryExtra($this->eid);
		if( $this->entry['locked'] != $_COOKIE[Session::getName()] ) {
			Entry_DBM::setLock($this->eid);
			$this->revision = true;
			$dbm = DBM::instance();
			$dbm->commit();
		}
		$_SESSION['current'] = array('mode'=>'entry_edit','eid'=>$this->eid);

		/* jQuery - DateTimePicker */
		$this->js[] = 'datetimepicker/jquery.datetimepicker.css';
		$this->js[] = 'datetimepicker/jquery.datetimepicker.js';

		/* jQuery UI */
		$this->js[] = 'jquery-ui-1.10.4/themes/base/jquery-ui.css';
		$this->js[] = 'jquery-ui-1.10.4/ui/minified/jquery-ui.min.js';
		
		/* jQuery UI - datepicker - i18n */
		//$this->js[] = 'jquery-ui-1.10.4/ui/i18n/jquery.ui.datepicker-ko.js';

		/* FancyBox */
		$this->js[] = 'fancyBox/source/jquery.fancybox.css';
		$this->js[] = 'fancyBox/source/jquery.fancybox.pack.js';

		/* Spectrum Color Picker */
		$this->js[] = 'bgrins-spectrum-9619bb4/spectrum.css';
		$this->js[] = 'bgrins-spectrum-9619bb4/spectrum.js';

		/* Medium-style Rich Editor */
		$this->css[] = '../../contribute/medium-editor/dist/css/medium-editor.css';
		$this->css[] = '../../contribute/medium-editor/dist/css/themes/default.css';
		$this->script[] = '../../contribute/medium-editor/dist/js/medium-editor.js';

		/* Local */
		$this->css[] = '../../timeline/resources/css/media.css';
//		$this->css[] = 'create.css';
		$this->script[] = '../../timeline/model/touchcarousel/js/jquery.taogi.touchcarousel.js';
		$this->script[] = '../../contribute/caret/jquery.caret.js';

		/** App **/
		$this->css[] = 'app-create.css';
		$this->script[] = 'app-create.js';

		/* UI */
//		$this->script[] = 'app-entry-modify.js';
		$this->css[] = 'app-entry-modify.css';
	}

	public function make_attr($item) {
		$use_proxy = str_replace(".","\\.",implode("|",$this->mediaconfig['use_proxy']));
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
}
?>
