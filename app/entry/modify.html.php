<?php
	$editor_title = "따오기 타임라인 작성하기";
//    $fp = fopen(JFE_PATH."/include/editor/toolbar.html.php","r");
//	$toolbar = fread($fp,filesize(JFE_PATH."/include/editor/toolbar.html.php"));
//	fclose($fp);
	require_once JFE_PATH."/include/editor/head.html.php";
		if(@count($timeline['date']) > 0) {
			foreach($timeline['date'] as $idx => $item) {?>
				<div class="slide-item<?php if(isset($item['published']) && !$item['published']) print ' trashed'; ?>" id="date_<?php print $item['unique']; ?>" data-id="<?php print $item['unique']; ?>" data-index="<?php print ($idx+1); ?>" attr-published="<?php print (isset($item['published']) ? $item['published'] : 1); ?>">
					<fieldset class="slide extendable collapsed">
						<legend class="article" data-default-value="슬라이드"><a href="javascript://"><?php print $item['startDate'].' '.$item['headline']; ?></a></legend>
						<div class="wrap" style="display: none;">
							<div class="pubDate">
								<time id="" class="editable article" contenteditable="true" data-name="startDate" data-default-value="<?php print date("Y.m.d"); ?>"><?php print $item['startDate']; ?></time><button class="button datepicker">지정하기</button>
							</div>
							<div class="feature">
<?php						if($item['asset']['media']) {?>
								<div class="article">
									<figure class="figure thumb-image taogi_buildGallery" href="<?php print $item['asset']['media']; ?>" thumbnail="<?php print $item['asset']['thumbnail']; ?>" caption="<?php print $item['asset']['caption']; ?>" credit="<?php print $item['asset']['credit']; ?>" id="<?php print $item['asset']['gid']; ?>_thumb"></figure>
								</div>
<?php						} else {?>
								<p class="switch">
									<button class="button media add" type="button" data-target="#date___SLIDE_ID___media"><span>미디어 추가</span></button>
								</p>
<?php						}?>
							</div>
							<div class="title-description">
								<h2 class="title editable article valid" contenteditable="true" data-name="headline" data-default-value="제목" data-id="<?php print $item['unique']; ?>" data-index="1" data-content=""><?php print $item['headline']; ?></h2>
								<div class="editor">
<?php print $toolbar; ?>
									<div class="description editable article valid" contenteditable="true" data-name="text" data-default-value="본문" data-id="<?php print $item['unique']; ?>" data-type="article" data-index="2" data-content=""><?php print $item['text']; ?></div>
								</div>
							</div>
<?php					if(@count($item['media']) || $item['asset']['media']) {?>
							<div class="media-nav">
								<ul class="thumbnails ui-sortable">
<?php						if(@count($item['media'])) {
								for($m=0; $m<@count($item['media']); $m++) {
									if((isset($item['media'][$m]['gid']) && $item['media'][$m]['gid'] == $item['asset']['gid']) || ($item['media'][$m]['media'] == $item['asset']['media']) ) $featured = 1;
									else $featured = 0; ?>
									<li class="thumbnail<?php if($featured) print ' featured'; ?><?php if(!$m) print ' current'; ?>" id="t_<?php print $item['media'][$m]['gid']; ?>" data-id="<?php print $item['unique']; ?>" data-gid="<?php print $item['media'][$m]['gid']; ?>" <?php print $this->make_attr($item['media'][$m]); ?> featured="<?php print $featured; ?>" tabindex="100"><a class="icon remove cornered" href="javascript:"><span>삭제</span></a></li>
<?php							}
							} else if($item['asset']['media']) {?>
									<li class="thumbnail featured current" id="t_<?php print $item['asset']['gid']; ?>" data-id="<?php print $item['unique']; ?>" data-gid="<?php print $item['asset']['gid']; ?>" <?php print $this->make_attr($item['asset']); ?> tabindex="100"><a class="icon remove cornered" href="javascript:"><span>삭제</span></a></li>
<?php						}?>
									<li class="add"><a href="javascript:"><span>미디어 추가</span></a></li>
								</ul>
							</div>
<?php					} /* end of media-nav */ ?>
							<div class="field console">
								<button class="button article save" type="button">중간 저장</button>
								<a class="icon labeled hide article" href="javascript:" title="이 슬라이드 감추기" data-id="<?php print $item['unique']; ?>"><span>감추기</span></a>
								<a class="icon labeled show article" href="javascript:" title="이 슬라이드 보이기" data-id="<?php print $item['unique']; ?>"><span>보이기</span></a>
								<a class="icon labeled remove article" href="javascript:" title="이 슬라이드 삭제" data-id="<?php print $item['unique']; ?>"><span>삭제</span></a>
							</div>
						</div>
					</fieldset>
					<div class="field console">
						<button class="button article add" type="button" data-target="#date_<?php print $item['unique']; ?>" title="슬라이드 추가">슬라이드 추가</button>
					</div>
				</div>
<?php		}
		}
	require_once JFE_PATH."/include/editor/basic.html.php";
	require_once JFE_PATH."/include/editor/foot.html.php";
?>
