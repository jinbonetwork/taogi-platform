<?php
$Acl = "user";
class create_index extends Controller {
	public function index() {
		$this->title = "따오기 타임라인 만들기";
		$context = Model_Context::instance();
		require JFE_PATH."/timeline/model/touchcarousel/config/config.php";

		$this->mediaconfig = $config;
					        
		$uid = Acl::getIdentity('taogi');
		if($uid < 1) Error("타임라인을 만드시려면 먼저 회원 가입을 하셔야 합니다.");

		$_SESSION['current'] = array('mode'=>'entry_create');

		/* jQuery - DateTimePicker */
		$this->js[] = 'datetimepicker/jquery.datetimepicker.css';
		$this->js[] = 'datetimepicker/jquery.datetimepicker.js';

		/* jQuery UI */
		$this->js[] = 'jquery-ui-1.10.4/themes/base/jquery-ui.css';
		$this->js[] = 'jquery-ui-1.10.4/ui/minified/jquery-ui.min.js';
		
		/* jQuery UI - datepicker - i18n */
		//$this->js[] = 'jquery-ui-1.10.4/ui/i18n/jquery.ui.datepicker-ko.js';

		/* Ace Editor */
		$this->js[] = '../../contribute/ace/src-min-noconflict/ace.js';

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

		$this->css[] = 'ui-controls.css';
		$this->script[] = 'ui-controls.js';
		$this->css[] = 'app-create.css';
		$this->script[] = 'app-create.js';
	}
}
?>
