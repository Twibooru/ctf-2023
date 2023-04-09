<?php
    session_start();

    if (!isset($_SESSION['logged_in'])) {
        header('Location: login.php');
        die();
    }

    $mares = require('mare_data.inc.php');
    $mare_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $mare = null;
    $error = null;

    if ($mare_id < 0 || $mare_id > (count($mares) - 1)) {
        $error = 'Mare not found D:';
    } else {
        $mare = $mares[$mare_id];
    }

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/mares.css" />
    <meta charset="utf-8" />
    <title>MARES MARES MARES MARES MARES I LOVE THEM I LOVE THEIR KIND SPIRITS I LOVE THEIR SNOWPITYS I LOVE THEIR SCRITCHABLE EARS MARES MARES MARES MARES</title>

    <style>
        img {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div id="container">
<main class="layout--wide" id="content">
    <?php if ($error !== null): ?>
        <p><?= $error ?></p>
    <?php else: ?>
        <h1><?= htmlentities($mare['title']) ?>
        <h2>Mare description:</h2>
        <p><?= htmlentities($mare['description']) ?></p>
        <h2>Mare</h2>
        <a href="<?= $mare['full'] ?>" target="_blank">
            <img alt="<?= $mare['title'] ?>" src="<?= $mare['full'] ?>" />
        </a>
    <?php endif; ?>
</main>
</div>
</body>
<!-- this maresite made by jimm, please don't hack me again -->
</html>
