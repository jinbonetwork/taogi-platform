<?php
if(!$toolbar) {
	$fp = fopen(dirname(__FILE__)."/toolbar.html.php","r");
	$toolbar = fread($fp,filesize(dirname(__FILE__)."/toolbar.html.php"));
	fclose($fp);
}
?>
				<div class="slide-item" id="date___SLIDE_ID__" data-id="">
					<fieldset class="slide extendable"><!--슬라이드 하나 당 필드셋 하나-->
						<legend class="article" data-default-value="슬라이드"><a href="javascript://"></a></legend>
						<div class="wrap">
							<div class="pubDate">
								<time id="" class="editable article" contenteditable="true" data-name="startDate" data-default-value="<?php print date("Y.m.d"); ?>"></time><button class="button datepicker">지정하기</button>
							</div>
							<div class="feature">
								<p class="switch">
									<button class="button media add" type="button" data-target="#date___SLIDE_ID___media"><span>미디어 추가</span></button>
								</p>
							</div>
							<div class="title-description">
								<h2 class="title editable article" contenteditable="true" data-name="headline" data-default-value="제목"></h2>
								<div class="editor">
<?php print $toolbar; ?>
									<div class="description editable article" contenteditable="true" data-name="text" data-default-value="본문"></div>
								</div>
							</div>
							<input type="hidden" name="date[__SLIDE_ID__][asset][media]" value="피쳐로 설정된 미디어 소스">
							<input type="hidden" name="date[__SLIDE_ID__][asset][thumbnail]" value="피쳐로 설정된 미디어 썸네일">
							<input type="hidden" name="date[__SLIDE_ID__][asset][credit]" value="피쳐로 설정된 미디어 출처">
							<input type="hidden" name="date[__SLIDE_ID__][asset][caption]" value="피쳐로 설정된 미디어 설명">
							<div class="media-nav">
								<ul class="thumbnails">
									<li class="thumbnail"><a class="icon remove cornered" href="javascript:"><span>삭제</span></a></li>
									<li class="add"><a href="javascript:"><span>미디어 추가</span></a></li>
									<li class="fieldset-container">
										<fieldset class="media">
											<legend class="media">미디어 __MEDIA_ID__</legend>
											<div class="wrap">
												<div class="field console">
													<label for="date___SLIDE_ID___asset___MEDIA_ID__"><input type="radio" id="date___SLIDE_ID___asset___MEDIA_ID__" name="date[__SLIDE_ID__][asset]" value="__MEDIA_ID__">대표 미디어로 사용</label>
													<a class="icon remove trash labeled" href="javascript:"><span>미디어 삭제</span></a>
												</div>
												<figure class="preview"></figure>
												<div class="wrap-inner">
													<!--
													<div class="field source">
														<label for="date___SLIDE_ID___media___MEDIA_ID___media">미디어 소스</label>
														<textarea class="text file" id="date___SLIDE_ID___media___MEDIA_ID___media" name="date[__SLIDE_ID__][media][__MEDIA_ID__][media]" placeholder="미디어 소스"></textarea>
														<a class="upload" href=""><span>업로드</span></a>
													</div>
													-->
													<!--
													-->
													<div class="field source mode-textarea mode-upload"><!-- future default: mode-input -->
														<label for="date___SLIDE_ID___media___MEDIA_ID___media">미디어 소스</label>
														<input type="text" class="text file" id="date___SLIDE_ID___media___MEDIA_ID___media_text" name="date[__SLIDE_ID__][media][__MEDIA_ID__][media]" placeholder="미디어 소스">
														<textarea class="text file" id="date___SLIDE_ID___media___MEDIA_ID___media" name="date[__SLIDE_ID__][media][__MEDIA_ID__][media]" placeholder="미디어 소스"></textarea>
														<a class="upload" href=""><span>업로드</span></a>
													</div>
													<div class="field sourcetype">
														<label><input type="radio" class="radio use-input" checked="checked" id="date___SLIDE_ID___media___MEDIA_ID___mediamode_input" name="date[__SLIDE_ID__][media][__MEDIA_ID__][mediamode]" value="input"><span>파일/웹 주소</span></label>
														<label><input type="radio" class="radio use-textarea" id="date___SLIDE_ID___media___MEDIA_ID___mediamode_textarea" name="date[__SLIDE_ID__][media][__MEDIA_ID__][mediamode]" value="textarea"><span>문장/소스 코드</span></label>
													</div>
													<div class="field thumbnail">
														<label for="date___SLIDE_ID___media___MEDIA_ID___thumbnail">미디어 썸네일</label>
														<input class="text file" type="text" id="date___SLIDE_ID___media___MEDIA_ID___thumbnail" name="date[__SLIDE_ID__][media][__MEDIA_ID__][thumbnail]" value="" placeholder="미디어 썸네일">
														<a class="upload" href=""><span>업로드</span></a>
													</div>
													<div class="field credit">
														<label for="date___SLIDE_ID___media___MEDIA_ID___credit">미디어 출처</label>
														<input class="text" type="text" id="date___SLIDE_ID___media___MEDIA_ID___credit" name="date[__SLIDE_ID__][media][__MEDIA_ID__][credit]" value="" placeholder="출처">
													</div>
													<div class="field caption">
														<label for="date___SLIDE_ID___media___MEDIA_ID___caption">미디어 설명</label>
														<input class="text" type="text" id="date___SLIDE_ID___media___MEDIA_ID___caption" name="date[__SLIDE_ID__][media][__MEDIA_ID__][caption]" value="" placeholder="설명">
													</div>
													<div class="field console">
														<button type="button" class="button update">수정하기</button>
													</div>
												</div>
												<a class="icon close cornered labeled after" href="javascript:"><span>창 닫기</span></a>
											</div>
										</fieldset>
									</li>
								</ul>
							</div>
							<div class="field console">
								<button class="button article save" type="button">중간 저장</button>
								<a class="icon labeled hide article" href="javascript:" title="이 슬라이드 감추기"><span>감추기</span></a>
								<a class="icon labeled show article" href="javascript:" title="이 슬라이드 보이기"><span>보이기</span></a>
								<a class="icon labeled remove article" href="javascript:" title="이 슬라이드 삭제"><span>삭제</span></a>
							</div>
						</div>
					</fieldset>
					<div class="field console">
						<button class="button article add" type="button" data-target="#date___SLIDE_ID__" title="슬라이드 추가">슬라이드 추가</button>
					</div>
				</div>
