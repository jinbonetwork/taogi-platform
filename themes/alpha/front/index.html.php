<div id="cover-background" class="keepCover">
	<video id="cover-background-video" class="cover-background shadow" data-master="#cover-background-image" width="768px" height="576px" muted loop>
		<source src="/themes/alpha/images/_front_cover_background-video.mp4" type="video/mp4">
	</video> 
	<img id="cover-background-image" class="cover-background master" data-shadow="#cover-background-video" width="768px" height="576px" src="/themes/alpha/images/_front_cover_background-image.jpg" alt="">
</div>
<section id="feature" class="stage">
	<div class="wrap">
		<div class="inner-wrap">
			<h1 id="site-name" class="keepCenter on-scroll-fade" data-on-scroll-fade-scroller=".taogi-model-wrap">따오기 타임라인</h1>
			<p id="site-description" class="on-scroll-fade" data-on-scroll-fade-scroller=".taogi-model-wrap"></p>
<!--
			<ul class="list">
				<li>쌍용자동차</li>
				<li>인터넷 실명제 위헌!</li>
				<li>진보넷 15년 타임라인</li>
				<li>진보넷 2012년 활동 </li>
				<li>프랑스 내전 - 파리꼬뮨</li>
				<li>국정원, 도감청의 역사 </li>
				<li>세월호는 왜</li>
				<li>그리고 당신의 타임라인...</li>
			</ul> -->
		</div>
	</div>
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
	$entryGallery->printGallery($entryList);

/*
$tls = array(
	array( 'id' => '1', 'link' => 'http://taogi.net/special/sewolho/background/', 'title' => '세월호는 왜.', 'description' => '타임라인으로 탐구하는 세월호 참사의 구조적 원인과 행위자들', 'author' => '진보넷+정보공개센터', 'image' => 'http://www.taogi.net/special/sewolho/background/images/og.jpg', ),
	array( 'id' => '1', 'link' => 'http://taogi.net/timeline/?src=http://taogi.net/timeline/samples/nis-surveillance.json', 'title' => '국정원, 도감청의 역사', 'description' => '보다 많은 사람들에 대한 보다 많은 정보를 은밀하게 수집하기 위해 권력을 남용했던 국정원의 도ㆍ감청사를 살펴보자.', 'author' => '진보넷 정보운동팀', 'image' => 'http://act.jinbo.net/secrets/images/199212.jpg', ),
	array( 'id' => '1', 'link' => 'http://taogi.net/timeline/?src=http://taogi.net/timeline/samples/paris-commune.json', 'title' => '프랑스 내전 - 파리 꼬뮨(작성중)', 'description' => '파리 꼬뮌 전후 역사 모른 채 마르크스의 [프랑스 내전] 읽기 힘들지 않음? 뭐가 어디 붙어 있는지도 모르겠고. 그래서 만들어봄 ㅇㅇ 이거 읽고 [프랑스 내전] 읽으면 머리에 쏙쏙 들어옵니다 보장<', 'author' => '뎡야핑', 'image' => 'http://www.culture.gouv.fr/Wave/image/joconde/0074/m500202_atpico-g70004_p.jpg', ),
	array( 'id' => '1', 'link' => 'http://taogi.net/timeline/?src=http://taogi.net/timeline/samples/jinbonet-2013.json', 'title' => '진보넷 2012년 활동', 'description' => '2012년의 활동들을 정리했습니다.', 'author' => '진보네트워크센터', 'image' => 'http://act.jinbo.net/drupal/sites/default/files/images/DSCF5895.preview.JPG', ),
	array( 'id' => '1', 'link' => 'http://center.jinbo.net/wordpress/?p=97', 'title' => '진보넷 15년 타임라인', 'description' => '진보넷 15년 역사', 'author' => '진보네트워크센터', 'image' => 'http://act.jinbo.net/drupal/sites/default/files/images/9ABE7400.JPG', ),
	array( 'id' => '1', 'link' => 'http://act.jinbo.net/timelineJS/internet_realname_system/', 'title' => '인터넷 실명제 위헌!', 'description' => '그 역사와 남은 과제', 'author' => '진보네트워크센터', 'image' => 'http://act.jinbo.net/timelineJS/internet_realname_system/images/timeline-13.jpg', ),
	array( 'id' => '1', 'link' => 'http://victory77.jinbo.net/story/timeline', 'title' => '쌍용 자동차 타임라인', 'description' => '쌍용 자동차 노동자들의 싸움은 재발할 수밖에 없는 구조 속에서 시작되고 끝이 났다. 싸움에서 이들은 무엇을 말하고 싶었을까', 'author' => '쌍용자동차 노조', 'image' => 'http://victory77.jinbo.net/wp/wp-content/uploads/2011/05/hopetent.png', ),
	array( 'id' => '1', 'link' => 'http://taogi.net/timeline/?src=http://taogi.net/timeline/samples/taogi-media-gallery-demo.json', 'title' => '미디어 갤러리 데모', 'description' => '미디어 갤러리 기능을 테스트합니다.', 'author' => '독넷', 'image' => 'http://cfile22.uf.tistory.com/T1050x1050/2734B34253143DE0171F8B', ),
);
foreach( $tls as $tl ){
	$tl = (object) $tl;
	echo "
					<li class='item'>
						<dl>
							<dd class='image keepRatio' data-width='16' data-height='10'><a class='keepCover' href='{$tl->link}'><img src='{$tl->image}' alt='타임라인 표지'></a></dd>
							<dt class='title'><a href='{$tl->link}'>{$tl->title}</a></dt>
							<dd class='description'>{$tl->description}</dd>
							<dd class='author'>{$tl->author}</dd>
						</dl>
					</li>" . PHP_EOL;
}
*/
?>
				</ul>
			</div>
		</div>
	</div>
</section>
