<?php
function getEntryEcard($entry){
	$markup = <<<EOT
<div class="ui-block ui-ecard">
<h1 class="subject">{$entry['subject']}</h1>
<div class="summary">{$entry['summary']}</div>
</div><!--/.ui-ecard-->
EOT;
	return $markup;

}
?>
