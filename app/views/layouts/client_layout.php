
<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php
            if(!empty($page_title)){
                echo $page_title;
            }else {
                "Trang chủ";
            }
        ?></title>
    <link>
    <link rel="stylesheet" href="<?php echo __WEB_ROOT__?>/public/assets/client/css/main.css">
</head>
<body>
    <header>
        <?php
            $this->renderView("blocks/header");
        ?>

    </header>
<main>

</main>
<footer>
    <?php
    $this->renderView("blocks/footer");
    ?>
</footer>
    <php

</body>
</html>
