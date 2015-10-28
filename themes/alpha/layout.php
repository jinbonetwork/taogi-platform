<!DOCTYPE html>
<html id="taogi-net">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<title><?php print ($pre_title ? $pre_title." > " : '').(isset($timeline['headline'])? $timeline['headline'].' :: ' : ($title ? $title." :: " : '') ); ?>따오기 타임라인</title>
<?php print $this->header(); ?>
</head>
<body class="taogi-net <?php print $breadcrumbs_class; ?> <?php print $entry_class; ?> <?php print ' '.$view_mode.' '.$model.($has_gnb ? ' has_gnb' : ''); ?>">
	<?php print $body_start; ?>
	<div id="taogi-net-site-main-container" class="container">
<?php if( ($view_mode != 'view' || $has_gnb == true ) && $params['skipgnb'] != 'true') {?>
		<div class="taogi-frame">
			<h1 id="taogi-timeline-title" class="taogi-gnb-switch"><a class="switch" href="#gnb">Menu</a><span><?php print $title; ?></span></h1>
			<div class="taogi-model-wrap">
<?php	if( $view_mode != 'view' ) {?>
				<article >
<?php	}
	}?>
<?php				print $content; ?>
<?php if( ($view_mode != 'view' || $has_gnb == true) && $params['skipgnb'] != 'true') {?>
<?php	if( $view_mode != 'view' ) {?>
				</article>
<?php	} ?>
			</div>
<?php
			print $taogi_gnb;
?>		</div>
<?php }?>
	</div>
<?php $this->footer(); ?>
</body>
</html>
