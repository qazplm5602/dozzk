<?php
    include_once '_common.php';
    include DOZZK_ROOT.'/header/head.php';
    include DOZZK_ROOT.'/toolbar/toolbar.php';
?>

<link rel="stylesheet" href="<?php echo DOZZK_URL; ?>/lives/video_card.css">
<link rel="stylesheet" href="<?php echo DOZZK_URL; ?>/lives/index.css">

<section class="screen_container">
    <div class="lives-title">라이브 영상</div>
    <section class="lives-contents">
        <div class="video_card-container">
            <a href="#">
                <div class="video_card-thumbnail">
                    <img src="https://livecloud-thumb.akamaized.net/chzzk/livecloud/KR/stream/26452270/live/4275801/record/24798899/thumbnail/image_480.jpg">
                    <div class="info">
                        <div class="live">LIVE</div>
                        <div class="count">1,925명 시청</div>
                    </div>
                </div>
            </a>

            <div class="video_card-channel">
                <a href="#" class="icon"><img src="https://nng-phinf.pstatic.net/MjAyMzEyMThfMTU0/MDAxNzAyODY5MDk1NTY1.oTT5XMYykEunzMRCJToJl5Fl7DUzs4QEGvjshF2E87cg.OJrKteepM6J4JyAkcNvGSG4b2bSO9h9BRu9uc07Oteog.JPEG/1702869083892.jpg?type=f120_120_na"></a>    
                <div class="info">
                    <a href="#" class="channel-title">테스트</a>
                    <a href="#" class="channel-name">도미임</a>
                    <a href="#" class="channel-game">용과 다르게</a>
                </div>
            </div>
        </div>
    </section>

    <?php include DOZZK_ROOT.'/tail/tail.php'; ?>
</section>
