<?php
    include_once '_common.php';
    include_once DOZZK_ROOT.'/config.php';
?>

<?php
    // api 호출
    $url = DOZZK_API_HOST."channel/lives"; 

    $ch = curl_init();                                 //CURL 세션 초기화
    curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정    
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);       //connection timeout 3초
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
    $response = curl_exec($ch);                        //쿼리 실행
    curl_close($ch);

    if ($response === false) {
        http_response_code(502);

        $error_title = "서버 오류";
        $error_why = "서버 내부에서 오류가 발생하였습니다.</br>나중에 다시 시도하세요.";
        include DOZZK_ROOT.'/error/error.php';
        exit;
    }

    $liveStreams = json_decode($response, true);

    if ($liveStreams === false) {
        http_response_code(502);

        $error_title = "서버 오류";
        $error_why = "서버 내부에서 오류가 발생하였습니다.</br>나중에 다시 시도하세요.";
        include DOZZK_ROOT.'/error/error.php';
        exit;
    }

    $channelData = array();

    if (count($liveStreams) > 0) {
        $sql = sql_query("SELECT id, title, game FROM dozzk_channel WHERE id IN ('". implode( "','", array_map_deep( G5_ESCAPE_FUNCTION, array_keys( (array)$liveStreams) ) ) ."')");
        while($array = sql_fetch_array($sql)){
            $channelData[$array["id"]] = $array;
        }
    }
?>

<?php
    include DOZZK_ROOT.'/header/head.php';
    include DOZZK_ROOT.'/toolbar/toolbar.php';
?>

<link rel="stylesheet" href="<?php echo DOZZK_URL; ?>/lives/video_card.css">
<link rel="stylesheet" href="<?php echo DOZZK_URL; ?>/lives/index.css">

<section class="screen_container">
    <div class="lives-title">라이브 영상</div>
    <section class="lives-contents">
        <?php foreach ($liveStreams as $channel => $data) {
            $streamer = get_member($channel);
            if (is_null($streamer)) continue;
        ?>
            <div class="video_card-container">
                <a href="/live/<?php echo $channel; ?>">
                    <div class="video_card-thumbnail">
                        <img src="<?php echo DOZZK_API."channel/thumbnail/".$channel; ?>">
                        <div class="info">
                            <div class="live">LIVE</div>
                            <div class="count"><?php echo number_format($data["clients"]); ?>명 시청</div>
                        </div>
                    </div>
                </a>

                <div class="video_card-channel">
                    <a href="#" class="icon"><?php echo get_member_profile_img($channel); ?></a>    
                    <div class="info">
                        <a href="/live/<?php echo $channel; ?>" class="channel-title"><?php echo $channelData[$channel]["title"]; ?></a>
                        <a href="#" class="channel-name"><?php echo $streamer["mb_nick"]; ?></a>
                        <a href="#" class="channel-game"><?php
                            $game = $channelData[$channel]["game"];
                            echo (is_null($game) ? "talk" : $game);
                        ?></a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>

    <?php include DOZZK_ROOT.'/tail/tail.php'; ?>
</section>
