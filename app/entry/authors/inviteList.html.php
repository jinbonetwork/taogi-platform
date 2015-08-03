	<h3 class="ui-block inviters">초대자 관리</h3>
	<form id="entryInviters" action="<?php print $entry['permalink'] ;?>/authors/invite" method="post">
		<input id="userActionKey" type="hidden" name="" value="">
		<div id="inviteTableList">
<?php		print $inviteTable; ?>
		</div>
<?php	print $inviteControls; ?>
	</form>
