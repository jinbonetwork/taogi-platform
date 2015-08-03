<?php
if(empty($entries)) {
	return PAGE_NOT_FOUND;
}
if(!is_array($entries))	{
	return INVALID_DATA_FORMAT;
}
importResource('taogi-ui-gallery');
$context = 'recentEntryGallery';
?>
<div id="<?php print $context; ?>" class="entryGallery ui-block ui-gallery ui-checkbox-group">
	<ul class="ui-items">
<?php
	foreach($entries as $index => $entry) {?><li class="ui-item" data-index="<?php print $index; ?>" data-eid="<?php print $entry['eid']; ?>" data-hover-class="hover" data-click-target=".item_title a">
			<dl>
				<dt class="keepRatio item_image" data-width="16" data-height="10">
					<a href="<?php print $entry['permalink']; ?>"><div class="<?php print $entry['COVERFRONT']['CLASS']; ?>" style="background-image:url('<?php print $entry['COVERFRONT']['medium_versioned']; ?>')"></div></a>
				</dt>
				<dt class="item_title">
					<a href="<?php print $entry['permalink']; ?>"><?php print $entry['subject']; ?></a>
				</dt>
				<dd class="item_summary">
					<?php print $entry['summary']; ?>
				</dd>
			</dl>
		</li><?php
	}?>
	</ul>
</div><!--/#recentEntryGallery-->
