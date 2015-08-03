<?php
importResource("taogi-ui-hcard");
$entry['COVERFRONT']['TAG'] = "<div class=\"ui-hcard-image {$entry['COVERFRONT']['CLASS']}\" style=\"background-image:url('{$entry['COVERFRONT']['medium_versioned']}')\"></div>";
$entry['COVERBACK']['TAG'] = "<div class=\"ui-hcard-background {$entry['COVERBACK']['CLASS']}\" style=\"background-image:url('{$entry['COVERBACK']['large_versioned']}')\"></div>";
$entryControllerOptions = array('entry'=>$entry,'options'=>array('context'=>'entrySidebar','class'=>array('inline','icon','label','hcard')));
$entryController = Component::get('user/entry/control',$entryControllerOptions);
?>
<div class="ui-block ui-ecard ui-hcard">
	<div class="ui-hcard-wrap">
		<a href="<?php print $entry['permalink']; ?>"><?php print $entry['COVERBACK']['TAG']; ?></a>
		<h1 class="ui-hcard-title subject"><div class="wrap"><a href="<?php print $entry['permalink']; ?>"><?php print $entry['subject']; ?></a></div></h1>
		<a href="<?php print $entry['permalink']; ?>"><?php print $entry['COVERFRONT']['TAG']; ?></a>
		<div class="ui-hcard-description summary"><div class="wrap"><a href="<?php print $entry['permalink']; ?>"><?php print $entry['summary']; ?></a></div></div>
		<?php echo $entryController; ?>
	</div>
</div><!--/.ui-ecard-->
