<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'font/font.css">';
    echo '<link rel="stylesheet" href="'.DOZZK_URL.'index.css">';

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/header/header.css">';

    include_once DOZZK_ROOT.'/config.php';
?>
<title>도지직 - DOZZK</title>

<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<script>
    // 전역 변수 선언
    var DOZZK_API = "<?php echo DOZZK_API; ?>";
    var DOZZK_LIVE_URL = "<?php echo DOZZK_LIVE_URL; ?>";
</script>

<header class="header-main">
    <section class="left-menu">
        <a href="https://dozzk.domi.kr">
            <img src="<?php echo DOZZK_URL; ?>/assets/logo.png" alt="">
        </a>
    </section>

    <div class="search-main">
        <input placeholder="스트리머, 게임 영상 검색" type="text">
        <img src="<?php echo DOZZK_URL; ?>/assets/search.svg">
    </div>

    <section class="right-menu">
        <a href="https://domi.kr/bbs/login.php"><button class="text-button">로그인</button></a>
        <button class="user-avater">
            <img src="https://nng-phinf.pstatic.net/MjAyMjA2MTdfNzcg/MDAxNjU1NDYwOTk4MzIx.2GtboKl1AANbxW8mwf7_-3rl1joA5z70GdLSuhVzWssg.ubvmA6JPVkX2fRl0DLLBKY9eBbL2Gh3cN03_MMAwnuAg.PNG/1.png?type=f120_120_na">
            <span class="guide-box">프로필</span>
        </button>
    </section>

    <div class="profile-context">
        <a href="#">
            <div class="myprofile-container">
                <div class="myprofile-image"><img src="https://nng-phinf.pstatic.net/MjAyMjA2MTdfNzcg/MDAxNjU1NDYwOTk4MzIx.2GtboKl1AANbxW8mwf7_-3rl1joA5z70GdLSuhVzWssg.ubvmA6JPVkX2fRl0DLLBKY9eBbL2Gh3cN03_MMAwnuAg.PNG/1.png?type=f120_120_na"></div>
            
                <div class="myprofile-texts">
                    <span class="sub">도미WEB 프로필</span>
                    <span class="name">도미임</span>
                </div>
            </div>
        </a>

        <div class="proile-line"></div>

        <a href="#"><div class="profile-button">
            <img src="<?php echo DOZZK_URL; ?>/assets/logout.svg">
            로그아웃
        </div></a>


    </div>
</header>