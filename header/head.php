<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'font/font.css">';
    echo '<link rel="stylesheet" href="'.DOZZK_URL.'index.css">';

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/header/header.css">';
?>

<header class="header-main">
    <section class="left-menu">
        <a href="https://dozzk.domi.kr">
            <img src="https://domi.kr/img/logo.png" alt="">
        </a>
    </section>

    <div class="search-main">
        <input type="text">
        <img src="<?php echo DOZZK_URL; ?>/assets/search.svg">
    </div>

    <section class="right-menu">
        <a href="https://domi.kr/bbs/login.php"><button class="text-button">로그인</button></a>
    </section>
</header>