<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    echo '<link rel="stylesheet" href="'.DOZZK_URL.'/toolbar/index.css">';
?>

<div class="toolbar-main">
    <div class="category-text category-button">
        <span>추천 채널</span>
        <div class="container"><img src="<?php echo DOZZK_URL; ?>/assets/arrow_down.svg"></div>
    </div>

    <div class="category-line"></div>

    <div class="category-text"><span>탐색</span></div>
    <a class="link-button" href="/lives">
        <button>
            <img src="https://domi.kr/img/domi_logo.png">
            <div>전체 라이브</div>
        </button>
    </a>
</div>