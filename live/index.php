<?php
    include_once '_common.php';

    $channelID = $_GET["id"];

    $error_title = "존재하지 않는 채널입니다.";
    $error_why = "지금 입력하신 주소의 페이지는</br>사라졌거나 다른 페이지로 변경되었습니다.</br>주소를 다시 확인해주세요.";
    
    if (!isset($channelID) || $channelID === "") {
        die( include(DOZZK_ROOT.'/error/error.php') );
    }

    $result = sql_fetch("SELECT title, game FROM dozzk_channel WHERE id = \"$channelID\"");
    if (is_null($result)) {
        die( include(DOZZK_ROOT.'/error/error.php') );
    }
    $streamer = get_member($channelID);
    if (is_null($streamer)) {
        die( include(DOZZK_ROOT.'/error/error.php') );
    }

    unset($error_title);
    unset($error_why);
?>

<?php
    include DOZZK_ROOT.'/header/head.php';
    include DOZZK_ROOT.'/toolbar/toolbar.php';

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/live/index.css">';
?>

<script>
    var dozzk_channel_id = "<?php echo $channelID; ?>";
    var dozzk_is_logined = <?php echo $is_member ? 'true' : "false"; ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="<?php echo DOZZK_URL; ?>/live/index.js"></script>

<section class="screen_container">
    <video class="live-video" src="" controls></video>

    <div class="info-container">
        <div class="info-title"><?php echo $result["title"]; ?></div>

        <div class="info-wrapper">
            <div class="info-left">
                <a class="profile-container" href="http://domi.kr/drm">
                    <?php echo get_member_profile_img($channelID); ?>
                </a>

                <div class="channel-info">
                    <a href="#" class="title"><?php echo $streamer["mb_nick"]; ?></a>
                    <a href="#" class="game"><img src="<?php echo DOZZK_URL; ?>/assets/game.svg"><?php echo (is_null($result["game"]) ? "talk" : $result["game"]." 플레이 중"); ?></a>
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
    <div class="chat-header">
        <button><img src="<?php echo DOZZK_URL; ?>/assets/upload.svg"></button>
        <span>채팅</span>
        <button class="rotate"><img src="<?php echo DOZZK_URL; ?>/assets/other.svg"></button>
    </div>

    <section class="chat-content">
        <!-- <div><span style="color:greenyellow;">도미임</span>TEST</div> -->
    </section>

    <div class="chat-tail">
        <div class="chat-in">
            <input id="chat-input" placeholder="채팅에 참여하려면 로그인 해주세요" type="text" <?php if ($is_guest) echo 'disabled'; ?>>
            <!-- <button></button> -->
        </div>
        <button class="chat-send" disabled>채팅</button>
    </div>
</div>