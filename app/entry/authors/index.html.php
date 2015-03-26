	<div id="entry-authors_<?php print $entry['eid']; ?>" class="entry-authors">
		<?php print getEntryEcard($entry); ?>
		<div id="entry-authors-list" class="block list">
<?php		require_once dirname(__FILE__).'/index.html.common.php'; ?>
		</div><!--/#entry-authors-list-->
	</div><!--/#entry-authors_eid-->
<?php if($entry['owner'] == $user['uid']) {?>
	<div id="entry-invite_<?php print $entry['eid']; ?>" class="entry-authors">
	</div>
<?php	include_once dirname(__FILE__)."/inviteform.html.php"; ?>
<?php }
