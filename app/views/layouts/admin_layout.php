<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo !empty($page_title) ? $page_title : "Trang chủ"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo __WEB_ROOT__; ?>/public/assets/admin/css/main.css">
    <link rel="stylesheet" href="<?php echo __WEB_ROOT__; ?>/public/assets/admin/css/reponsive.css">
</head>
<body>
<div id="toast">
</div>
<div id="modal" >
</div>
<!-- Tải navbar-->
<?php
    $this->renderView('blocks/admin_navbar');
?>
<!--Tải trang sidebar-->

<?php
$this->renderView('blocks/admin_sidebar');
?>
<main>
    <?php
    $this->renderView($content, $data);
    ?>
</main>
<footer>
    <H3>Phần cuối trang</H3>
</footer>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.6/src/table2csv.min.js"></script>
<script src="<?php echo __WEB_ROOT__; ?>/public/assets/admin/js/validator.js"></script>
<script src="<?php echo __WEB_ROOT__; ?>/public/assets/admin/js/toast.js"></script>
<script src="<?php echo __WEB_ROOT__; ?>/public/assets/admin/js/main.js"></script>
<?php if (!empty($js_file)): ?>
    <script src="<?php echo __WEB_ROOT__; ?>/public/assets/admin/js/<?php echo $js_file; ?>"></script>
<?php endif; ?>
</body>
</html>