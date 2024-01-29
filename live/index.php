<?php
    include_once '_common.php';
    include DOZZK_ROOT.'/header/head.php';
    include DOZZK_ROOT.'/toolbar/toolbar.php';

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/live/index.css">';
?>

<section class="screen_container">
    <video class="live-video" src="" controls></video>

    <div class="info-container">
        <div class="info-title">용과같이8 신작 (스포주의)</div>

        <div class="info-wrapper">
            <div class="info-left">
                <a class="profile-container" href="http://domi.kr/drm">
                    <img src="https://nng-phinf.pstatic.net/MjAyMzEyMjBfNzgg/MDAxNzAyOTk5MDU4NTQ1.q74UANafs4egu_GflqIXrKZvqweabjdsqb3q7F-vEPEg.0DlZf3Myopu6ITUmTkOYLU-GKcBLotgKn61A0o9ZAN4g.PNG/7d354ef2-b2a8-4276-8c12-5be7f6301ae0-profile_image-600x600.png?type=f120_120_na">

                </a>

                <div class="channel-info">
                    <a href="#" class="title">풍월량풍월량</a>
                    <a href="#" class="game"><img src="<?php echo DOZZK_URL; ?>/assets/game.svg">용과 같이 8 플레이 중</a>
                    <div class="misc-texts">
                        <div class="misc-people">현재 3,482 시청중</div>
                        <div class="misc-people-save">누적 9,675</div>
                        <div class="misc-time">01:51:20 스트리밍 중</div>
                    </div>
                </div>
            </div>

            <div class="info-right">
                <button class="follow"><img src="<?php echo DOZZK_URL; ?>/assets/heart.svg"><span>팔로우</span></button>
                <button class="misc"><img src="<?php echo DOZZK_URL; ?>/assets/other.svg"></button>
            </div>
        </div>
    </div>

    <?php include DOZZK_ROOT.'/tail/tail.php'; ?>
</section>

<!-- 채팅창 -->
<div class="chat-wrapper">
</div>