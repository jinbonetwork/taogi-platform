<?php
ob_start();
?>
<div id="login" class="round_10px white_form">
	<?php include dirname(__FILE__)."/login.common.php"; ?>
	<div id="close_button">
		<input type="button" class="close" onclick="jfe_pop_close('login');" />
	</div>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
Respond::ResultPage(array(0,$content));
?>
