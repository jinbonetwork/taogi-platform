<?php
$Acl = "anonymous";
class front_index extends Controller {
	public function index() {
		$context = Model_Context::instance();
					        

		$this->entryList = Entry_List::getList();
	   	$this->entryGallery = new Markup_Gallery;

	}
}
?>
