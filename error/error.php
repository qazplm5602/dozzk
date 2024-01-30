<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    include DOZZK_ROOT.'/header/head.php';
    include DOZZK_ROOT.'/toolbar/toolbar.php';

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/error/index.css">';
?>

<section class="screen_container">
    <div class="error-container">
        <img src="<?php DOZZK_URL ?>/assets/warning.svg">
        <div class="title"><?php echo $error_title; ?></div>
        <div class="why"><?php echo $error_why; ?></div>
        <a href="/">다른 방송 보러가기</a>
    </div>
</section>