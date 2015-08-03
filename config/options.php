<?php
$options['imageIndexes'] = array(
	'small' => array(
		'dirname' => 'thumbs',
		'width' => 150,
		'height' => 150,
		'quality' => 100,
		'crop' => true,
	),
	'medium' => array(
		'dirname' => 'medium',
		'width' => 800,
		'height' => 800,
		'quality' => 100,
		'crop' => false,
	),
	'large' => array(
		'dirname' => 'large',
		'width' => 1600,
		'height' => 1600,
		'quality' => 100,
		'crop' => false,
	),
	'original' => array(
		'dirname' => 'files',
		'width' => 2048,
		'height' => 2048,
		'quality' => 100,
		'crop' => false,
	),
	'portrait' => array(
		'dirname' => 'files',
		'width' => 300,
		'height' => 300,
		'quality' => 100,
		'crop' => true,
		'filename' => 'portrait.png',
		'format' => 'png',
	),
);
?>
