<?php
function getEntryEcard($entry){
	$entry['COVERFRONT']['TAG'] = "<div class=\"ui-hcard-image {$entry['COVERFRONT']['CLASS']}\" style=\"background-image:url('{$entry['COVERFRONT']['medium_versioned']}')\"></div>";
	$entry['COVERBACK']['TAG'] = "<div class=\"ui-hcard-background {$entry['COVERBACK']['CLASS']}\" style=\"background-image:url('{$entry['COVERBACK']['large_versioned']}')\"></div>";
	$markup = <<<EOT
<div class="ui-block ui-ecard ui-hcard">
	<div class="ui-hcard-wrap">
		{$entry['COVERBACK']['TAG']}	
		<h1 class="ui-hcard-title subject">{$entry['subject']}</h1>
		{$entry['COVERFRONT']['TAG']}	
		<div class="ui-hcard-description summary">{$entry['summary']}</div>
	</div>
</div><!--/.ui-ecard-->
EOT;
	return $markup;

}
?>
