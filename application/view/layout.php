<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 18:49
 */

$rec = \Library\Application::instance()->getRequest();
$navPage = null;
if ($rec->getController() == 'index') {
    if ($rec->getAction() == 'index') {
        $navPage = 'index';
    } elseif ($rec->getAction() == 'results') {
        $navPage = 'results';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timeweb test application</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
</head>

<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Timeweb test application</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?= $navPage == 'index' ? 'class="active"' : '' ?>><a href="/">Home</a></li>
                <li <?= $navPage == 'results' ? 'class="active"' : '' ?>><a href="/index/results">Results</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">

    <?= $content; ?>

</div>

</body>
</html>