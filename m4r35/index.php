<?php
    session_start();

    if (!isset($_SESSION['logged_in'])) {
        header('Location: login.php');
        die();
    }

    $mares = require('mare_data.inc.php');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/mares.css" />
    <meta charset="utf-8" />
    <title>MARES MARES MARES MARES MARES I LOVE THEM I LOVE THEIR KIND SPIRITS I LOVE THEIR SNOWPITYS I LOVE THEIR SCRITCHABLE EARS MARES MARES MARES MARES</title>
</head>
<body>
<div id="container">
<main class="layout--wide" id="content">
    <div class="column-layout">
        <div class="column-layout__main">
            <h1>My mare collection!</h1>
            <p>Welcome to my private MARE collection! I don't have very many of them online right now because my hosting company doesn't give me very much disk space :-( But please enjoy my mares MARES MARES MARES MARES MARES I LOVE THEM I LOVE THEIR KIND SPIRITS I LOVE THEIR SNOWPITYS I LOVE THEIR SCRITCHABLE EARS MARES MARES MARES MARES!!!</p>
        
            <div class="post-grid post-grid--whatever">
                <?php foreach ($mares as $id => $mare): ?>
                <div class="media-box js-post-root">
                    <div class="media-box__header media-box__header--link-row">
                        <span><?= htmlentities($mare['title']) ?></span>
                    </div>
                    <div class="media-box__content flex flex--centered flex--center-distributed">
                        <div class="image-container thumb">
                            <div class="media-box__overlay hidden"></div>
                            <a href="mare.php?id=<?= htmlentities($id) ?>" title=""><img alt="<?= htmlentities($mare['title']) ?>" src="<?= htmlentities($mare['thumb']) ?>"></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
</div>
</body>
<!-- this maresite made by jimm, please don't hack me again -->
<!-- flag{[snip]} (2/5) -->
</html>
