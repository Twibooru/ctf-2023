<?php
session_start();
$error = null;

if (!empty($_POST['username'])) {
    if ($_POST['username'] !== 'jimm') {
        $error = 'Invalid username provided :(';
    } else if (empty($_POST['password'])) {
        $error = 'No password provided :(';
    } else if (empty($_SESSION['password']) || !password_verify($_POST['password'], $_SESSION['password'])) {
        $error = 'Invalid password provided :(';
    } else {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        die();
    }
}
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
        <pre><?= var_dump($_SESSION); ?></pre>

    <?php if ($error !== null): ?>
        <div class="flash flash--warning"><?= $error ?></div>
    <?php endif; ?>
    <main id="content" class="layout--narrow">
        <h1>mares</h1>
        <h2>i love them</h2>
        <p>
            <a href="forgot.php">Forgot Password</a>
        </p>
        <form action="" method="POST">
            <div class="field">
                <input class="input" type="text" name="username" id="username" placeholder="Username" />
            </div>

            <div class="field">
                <input class="input" type="password" name="password" id="password" placeholder="Password" />
            </div>
            <input class="button" type="submit" value="Log In" />
        </form>
    </main>
</div>
</body>
<!-- this maresite made by jimm, please don't hack me again -->
</html>
