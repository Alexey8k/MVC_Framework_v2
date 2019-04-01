<?php
$layout = 'app/views/Shared/_Layout.php';
$viewBag['title'] = 'Books';
?>

<div id="book-catalog-app"></div>

<script>
    <?= file_get_contents("./js/dist/build.js", FILE_USE_INCLUDE_PATH) ?>
</script>