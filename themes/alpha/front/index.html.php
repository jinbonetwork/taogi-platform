<div id="cover-background" class="keepCover">
	<video id="cover-background-video" class="cover-background shadow" data-master="#cover-background-image" width="768px" height="576px" muted loop>
		<source src="/themes/alpha/images/_front_cover_background-video.mp4" type="video/mp4">
	</video> 
	<img id="cover-background-image" class="cover-background master" data-shadow="#cover-background-video" width="768px" height="576px" src="/themes/alpha/images/_front_cover_background-image.jpg" alt="">
</div>
<section id="feature" class="stage">
	<div class="wrap">
		<div class="inner-wrap">
			<div id="splash" class="keepCenter">
				<h1 id="site-name" data-on-scroll-fade-scroller=".taogi-model-wrap">따오기 타임라인</h1>
				<!--p id="site-description" class="" data-on-scroll-fade-scroller=".taogi-model-wrap"></p-->
				<ul class="buttons">
					<li class="create"><a href="/create"><img src="/themes/alpha/images/front-button-create.svg" alt="타임라인 만들기"></a></li>
					<li class="join"><a href="/regist"><img src="/themes/alpha/images/front-button-join.svg" alt="회원가입하기"></a></li>
				</ul>
			</div><!--/#splash-->
		</div><!--/.inner-wrap-->
	</div><!--/.wrap-->
</section>
<section id="about" class="stage">
	<div class="wrap">
		<div class="inner-wrap">
			<h1>따오기 타임라인 소개</h1>
			<div class="content">
				<p>따오기 타임라인은 날짜 중심으로 구성된 프리젠테이션 문서를 제작할 수 있는 도구입니다. 터치캐러셀 방식은 태블릿이나 스마트폰에서도 읽을 수 있도록 반응형 인터페이스와 터치 제스쳐를 지원합니다.</p>
			</div>
			<div class="feature keepRatio" data-width="610" data-height="390">
				<img class="sample desktop" src="/themes/alpha/images/decoration_device_snapshot_desktop.png" alt="">
				<img class="sample tablet" src="/themes/alpha/images/decoration_device_snapshot_tablet.png" alt="">
				<img class="sample mobile" src="/themes/alpha/images/decoration_device_snapshot_mobile.png" alt="">
			</div>
			<div class="content">
			</div>
		</div>
	</div>
</section>
<section id="list" class="stage">
	<div class="wrap">
		<div class="inner-wrap">
			<h1>타임라인 목록</h1>
			<div class="content">
<?php
	print Component::get('entry/recent/gallery',array('entries'=>$entries));
?>
				</ul>
			</div>
		</div>
	</div>
</section>
