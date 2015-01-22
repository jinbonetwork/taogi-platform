<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<title>따오기 타임라인<?php echo (isset($timeline['headline'])?':'.$timeline['headline']:''); ?></title>

	<link rel="stylesheet" href="<?php print url('resources/fonts/fonts.css'); ?>">
	<link rel="stylesheet" href="<?php print url('include/gnb/style.css'); ?>">
	<link rel="stylesheet" href="<?php print url('timeline/model/touchcarousel/css/gnb.css'); ?>">
	<script src="<?php print url('resources/js/jquery-1.11.0.min.js'); ?>"></script>

	<!--[if IE]>
	<script language="javascript" type="text/javascript" src="<?php print url('resources/js/script.html5.js'); ?>"></script>
	<![endif]-->

	<script src="<?php print url('contribute/is-loading/jquery.isloading.js'); ?>"></script>
	<script src="<?php print url('contribute/imagesloaded/imagesloaded.pkgd.min.js'); ?>"></script>
	<script src="<?php print url('timeline/model/touchcarousel/js/gnb.js'); ?>"></script>
	<script src="<?php print url('include/gnb/script.js'); ?>"></script>

<?php print $header; ?>
	<link rel="stylesheet" href="<?php print url('resources/js/jquery-bootstrap-tooltip/jquery-bootstrap-tooltip.css'); ?>">
	<script src="<?php print url('resources/js/jquery-bootstrap-tooltip/jquery-bootstrap-tooltip.js'); ?>"></script>

	<link rel="stylesheet" href="<?php print url('resources/css/ui-init.css'); ?>">
	<link rel="stylesheet" href="<?php print url('resources/css/ui-form.css'); ?>">
	<link rel="stylesheet" href="<?php print url('resources/css/ui-controls.css'); ?>">
	<script src="<?php print url('resources/script/ui-init.js'); ?>"></script>
	<script src="<?php print url('resources/script/ui-form.js'); ?>"></script>
	<script src="<?php print url('resources/script/ui-controls.js'); ?>"></script>

	<link rel="stylesheet" href="<?php print url('resources/css/app-login.css'); ?>">
	<script src="<?php print url('resources/script/app-login.js'); ?>"></script>

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
			include JFE_PATH."/include/gnb/index.html.php";
?>		</div>
<?php }?>
	</div>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-58409853-1', 'auto');
		ga('send', 'pageview');
	</script>
</body>
</html>
