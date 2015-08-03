	<div id="entry-authors_<?php print $entry['eid']; ?>" class="entry-authors">
		<?php print $ecard; ?>
		<div id="entry-authors-list" class="block list">
<?php	if($isPriviate) {
			include_once dirname(__FILE__)."/private.html.php";
		} else {
			require_once dirname(__FILE__).'/index.html.common.php';
		}?>
		</div><!--/#entry-authors-list-->
	</div><!--/#entry-authors_eid-->
<?php if($isOwner) {?>
	<div id="entry-invite_<?php print $entry['eid']; ?>" class="entry-authors">
<?php	include_once dirname(__FILE__)."/inviteList.html.php"; ?>
	</div>
<?php	include_once dirname(__FILE__)."/inviteform.html.php"; ?>
<?php }?>
