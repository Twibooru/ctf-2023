<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

$questions = [
    [
        'question' => 'Who do I love?',
        'answers' => ['mare', 'mares']
    ],
    [
        'question' => 'What do I love most about mares?',
        'answers' => [
            'snowpity', 'their snowpity', 'their snowpities', 'snowpities', 'their snowpitys', 'snowpitys'
        ]
    ]
];

$step = isset($_SESSION['step']) ? (int) $_SESSION['step'] : 0;
$error = null;
$selected_question = null;

function change_step(int $newStep) {
    global $step;
    $step = $newStep;
    $_SESSION['step'] = $newStep;
}

if ($step === 0) {
    if (!empty($_POST['username'])) {
        if (strtolower(trim($_POST['username'])) !== 'jimm') {
            $error = 'Unknown username!';
        } else {
            change_step(1);
            $selected_question = $questions[array_rand($questions)];
            $_SESSION['question'] = json_encode($selected_question);
        }
    }
} else if ($step === 1) {
    $selected_question = json_decode($_SESSION['question'], true);
    if (!empty($_POST['answer'])) {
        foreach ($selected_question['answers'] as $answer_candidate) {
            if ($answer_candidate === strtolower(trim($_POST['answer']))) {
                change_step(2);
                unset($_SESSION['question']);
                goto output; // skip over the error assignment below
            }
        }

        $error = 'Invalid security question answer provided!';
    }
} else if ($step === 2) {
    if (!empty($_POST['password'])) {
        if (empty($_POST['confirm_password']) || $_POST['confirm_password'] !== $_POST['password']) {
            $error = 'The password confirmation must equal the password!';
        } else {
            // do the reset, you are winner
            change_step(3);
            $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
    }
}


output:
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
    <?php if ($error !== null): ?>
        <div class="flash flash--warning"><?= $error ?></div>
    <?php endif; ?>
    <main id="content" class="layout--narrow">
        <h1>mares</h1>
        <h2>i forgot about them?! impossible</h2>

        <form action="" method="POST">
            <?php if ($step === 0): ?>
                <div class="field">
                    <input class="input" type="text" name="username" id="username" placeholder="Username" />
                </div>
            <?php elseif ($step === 1): ?>
                <div class="field">
                    <label for="question">Security Question</p>
                    <input type="hidden" name="question" value="<?= htmlentities($selected_question['question']) ?>" />
                    <p id="question"><?= htmlentities($selected_question['question']) ?></p>
                </div>
                <div class="field">
                    <input class="input" type="text" name="answer" id="answer" placeholder="Answer" />
                </div>
            <?php elseif($step === 2): ?>
                <p>Please choose a secure password.</p>
                <div class="field">
                    <input class="input" type="password" name="password" id="password" placeholder="New Password" />
                </div>
                <div class="field">
                    <input class="input" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" />
                </div>
            <?php elseif ($step === 3): ?>
                <p>Your password has been reset successfully.</p>
                <p>Perhaps you would like to <a href="login.php">log in</a>?</p>
            <?php endif; ?>

            <input class="button" type="submit" value="Reset Password" />
        </form>
    </main>
</div>


</body>
<!-- this maresite made by jimm, please don't hack me again -->
</html>