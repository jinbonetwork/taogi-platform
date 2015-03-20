<?php
function getEntryEcard($entry){
	$entryCoverFrontTagPattern = array(
		'<div class="' => '<div class="ui-hcard-image ',
	);
	$entry['COVERFRONTTAG'] = str_replace(array_keys($entryCoverFrontTagPattern),array_values($entryCoverFrontTagPattern),$entry['COVERFRONTTAG']);

	$entryCoverBackTagPattern = array(
		'<div class="' => '<div class="ui-hcard-background ',
	);
	$entry['COVERBACKTAG'] = str_replace(array_keys($entryCoverBackTagPattern),array_values($entryCoverBackTagPattern),$entry['COVERBACKTAG']);
	$markup = <<<EOT
<div class="ui-block ui-ecard ui-hcard">
	<div class="ui-hcard-wrap">
		{$entry['COVERBACKTAG']}	
		<h1 class="ui-hcard-title subject">{$entry['subject']}</h1>
		{$entry['COVERFRONTTAG']}	
		<div class="ui-hcard-description summary">{$entry['summary']}</div>
	</div>
</div><!--/.ui-ecard-->
EOT;
	return $markup;

}
?>
