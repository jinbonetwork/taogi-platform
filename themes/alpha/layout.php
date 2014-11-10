<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<title>따오기 타임라인: <?php print $title; ?></title>
	<!-- jQuery -->
	<link rel="stylesheet" href="<?php print url('resources/fonts/fonts.css'); ?>">
	<link rel="stylesheet" href="<?php print url('resources/js/jquery-bootstrap-tooltip/jquery-bootstrap-tooltip.css'); ?>">
	<link rel="stylesheet" href="<?php print url('include/gnb/style.css'); ?>">
	<link rel="stylesheet" href="<?php print url('timeline/model/touchcarousel/css/gnb.css'); ?>">
	<script src="<?php print url('resources/js/jquery-1.11.0.min.js'); ?>"></script>
	<!-- jQuery UI -->
	<!--[if IE]>
	<script language="javascript" type="text/javascript" src="<?php print url('resources/js/script.html5.js'); ?>"></script>
	<![endif]-->
	<script src="<?php print url('resources/js/jquery-bootstrap-tooltip/jquery-bootstrap-tooltip.js'); ?>"></script>
	<script src="<?php print url('contribute/is-loading/jquery.isloading.js'); ?>"></script>
	<script src="<?php print url('contribute/imagesloaded/imagesloaded.pkgd.min.js'); ?>"></script>
	<script src="<?php print url('timeline/model/touchcarousel/js/gnb.js'); ?>"></script>
<?php print $header; ?>
</head>
<body class="taogi-net<?php print ' '.$view_mode.' '.$model.($has_gnb ? ' has_gnb' : ''); ?>">
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
			include JFE_PATH."/include/gnb/index.html.php";
?>		</div>
<?php }?>
	</div>
</body>
</html>
