<?php
function validateTimeLineJS($json) {
	$rJSON = array();
	$rJSON['timeline'] = array(
		'headline' => $json['timeline']['headline'],
		'permalink' => $json['timeline']['permalink'],
		'type' => ($json['timeline']['type'] ? $json['timeline']['type'] : 'default'),
		'startDate' => $json['timeline']['startDate'],
		'text' => $json['timeline']['text'],
		'asset' => $json['timeline']['asset'],
		'date' => array(),
		'era' => array(
			'startDate' => $json['timeline']['era']['startDate'],
			'endDate' => $json['timeline']['era']['endDate']
		),
		'extra' => array(
			'template' => ($json['timeline']['extra']['template'] ? $json['timeline']['extra']['template'] : 'touchcarousel'),
			'author' => $json['timeline']['extra']['author'],
			'theme' => $json['timeline']['extra']['theme'],
			'order' => ($json['timeline']['extra']['order'] == 'desc' ? 'desc' : 'asc'),
			'published' => ($json['timeline']['extra']['published'] ? $json['timeline']['extra']['published'] : 0)
		)
	);
	for($i=0; $i<count($json['timeline']['date']); $i++) {
		$item = $json['timeline']['date'][$i];
		if(!$item['startDate']) continue;
		if($item['published'] < 0) continue;
		if(!$item['asset']['media'] && $item['media'][0]['media'])
			$item['asset'] = $item['media'][0];
		$item['asset']['media'] = stripslashes($item['asset']['media']);
		for($j=0; $j<count($item['media']); $j++) {
			$item['media'][$j]['media'] = stripslashes($item['media'][$j]['media']);
		}
		$rJSON['timeline']['date'][] = $item;
	}
	if(is_array($json['timeline']['extra'])) {
		foreach($json['timeline']['extra'] as $k => $v) {
			$rJSON['timeline']['extra'][$k] = $v;
		}
	}
	if(!$rJSON['timeline']['startDate'] || $rJSON['timeline']['startDate'] == 'null')
		$rJSON['timeline']['startDate'] = $rJSON['timeline']['date'][0]['startDate'];

	return $rJSON;
}

function publishedTimeLineJs($json) {
	$dates = $json['timeline']['date'];
	$n_date = array();
	for($i=0; $i<@count($dates); $i++) {
		if(!(int)$dates[$i]['published']) continue;
		$n_date[] = $dates[$i];
	}
	$json['timeline']['date'] = $n_date;
	unset($json['timeline']['permalink']);
	unset($json['timeline']['extra']['published']);

	return $json;
}
?>
