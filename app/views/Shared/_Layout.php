<?php
/**
 * @var array $viewBag
 * @var string $renderBody
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewBag['title'] ?></title>
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/site.css" rel="stylesheet">
    <script src="/js/lib/jquery.js"></script>
    <script src="/js/lib/jquery-ui.js"></script>
    <script src="/js/lib/angular.js"></script>
    <script src="/js/lib/angular-messages.js"></script>
    <script src="/js/lib/angular-animate.js"></script>
    <script src="/js/lib/angular-sanitize.js"></script>
    <script src="/js/lib/ui-bootstrap-tpls-2.5.0.js"></script>

</head>
<body>
<div style="width: 1024px; margin: auto; background-color: gold;">
    <div id="header">
        <div class="title">Books</div>
    </div>
    <div id="content">
        <?= $renderBody ?>
    </div>
</div>
</body>
</html>