<?php
session_start();


if (isset($_POST['deletescoreconfirm'])) {
    if ($_POST['deletescoreconfirm'] == 'yes') {
        unset($_SESSION['victoire']);
        unset($_SESSION['defaite']);
    }
    else if ($_POST['deletescoreconfirm'] == 'no') {
        header("index.php");
    } 
}

if (isset($_POST['replayornot'])) {
    if ($_POST['replayornot'] == 'yes') {
        $replay = false;
        header("index.php");
    }
}

if (isset($_POST['boutonreset'])) {
    if ($_POST['boutonreset'] == 'reset') {
        unset($_SESSION['mysteryNumber']);
        unset($_SESSION['guesscount']);
        unset($_POST);
        header("index.php");
    }
}

if (!isset($_SESSION['guesscount'])) {
    $_SESSION['guesscount'] = 0;
}

if (!isset($_SESSION['nombredecoupsmax'])) {
    $_SESSION['nombredecoupsmax'] = 5;
}

if (!isset($_SESSION['borneinf'])) {
    $_SESSION['borneinf'] = 1;
}

if (!isset($_SESSION['bornesup'])) {
    $_SESSION['bornesup'] = 100;
}

if (!isset($_SESSION['victoire'])) {
    $_SESSION['victoire'] = 0;
}

if (!isset($_SESSION['defaite'])) {
    $_SESSION['defaite'] = 0;
}

if (!isset($_SESSION['mysteryNumber'])) {
    // Si le nombre mystère n'est pas déjà initialisé, générer un nouveau nombre entre 1 et 100
    $_SESSION['mysteryNumber'] = rand($_SESSION['borneinf'], $_SESSION['bornesup']);
    // echo "Le nombre mystère est : " . $_SESSION['mysteryNumber'] . "\n";
}

$message = 'Entrer un nombre en bas a gauche';
$playTheGame = true;
$nombreCoupsMax = $_SESSION['nombredecoupsmax'];
$nombreCoups = $_SESSION['guesscount'];
$borneInf = $_SESSION['borneinf'];
$borneSup = $_SESSION['bornesup'];
$nombreMagique = $_SESSION['mysteryNumber'];
$victoire = $_SESSION['victoire'];
$defaite = $_SESSION['defaite'];
// $ecranFinDePartie = false;
$replay = false;




if (isset($_POST["guess"]) && !empty($_POST["guess"])) {

    $userGuess = $_POST["guess"];

    if ($userGuess < $borneInf || $userGuess > $borneSup) {

        $message = "Veuillez saisir un nombre entre " . $borneInf . " et " . $borneSup;

    } else {

        $_SESSION['guesscount']++;
        $nombreCoups = $_SESSION['guesscount'];

        if ($_SESSION['guesscount'] > $nombreCoupsMax - 1 && $userGuess != $_SESSION['mysteryNumber']) {
            
            $_SESSION['defaite']++;
            $defaite = $_SESSION['defaite'];
            $message = "Vous avez dépassé les " . $nombreCoupsMax. " essais autorisés. Vous avez perdu.";
            unset($_SESSION['mysteryNumber']);
            unset($_SESSION['guesscount']);
            unset($_POST);
            $replay = true;
            // $ecranFinDePartie = true;
        

        } else if ($userGuess == $_SESSION['mysteryNumber']) {

            $_SESSION['victoire']++;
            $victoire = $_SESSION['victoire'];
            $message = "Bravo ! Vous avez trouvé le nombre mystère en " . $_SESSION['guesscount'] . " essais.";
            
            unset($_SESSION['mysteryNumber']);
            unset($_SESSION['guesscount']);
            unset($_POST);
            $replay = true;
            // $ecranFinDePartie = true;

        } elseif ($userGuess < $_SESSION['mysteryNumber']) {
            $message = "Le nombre que vous avez proposé est trop bas, essayez encore !";
        } else {
            $message = "Le nombre que vous avez proposé est trop haut, essayez encore !";
        }

    }

} else {
    $message = "Entrer un nombre en bas à gauche";
}

if (!empty($_POST['nombredecoupsmax'])) {
    $_SESSION['nombredecoupsmax'] = $_POST['nombredecoupsmax'];
    $nombreCoupsMax = $_SESSION['nombredecoupsmax'];
    unset($_SESSION['mysteryNumber']);
    unset($_SESSION['guesscount']);
    unset($_POST);
}

if (!empty($_POST['borneinf'])) {
    $_SESSION['borneinf'] = $_POST['borneinf'];
    $borneInf = $_SESSION['borneinf'];
    unset($_SESSION['mysteryNumber']);
    unset($_SESSION['guesscount']);
    unset($_POST);
    
}

if (!empty($_POST['bornesup'])) {
    $_SESSION['bornesup'] = $_POST['bornesup'];
    $borneSup = $_SESSION['bornesup'];
    unset($_SESSION['mysteryNumber']);
    unset($_SESSION['guesscount']);
    unset($_POST);
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./dist/css/index.css">
    <script src="https://kit.fontawesome.com/4aa59fef57.js" crossorigin="anonymous"></script>
    <script src="./scripts/index.js" defer></script>
    <title>Document</title>
</head>
<body>

    <div class="App">

        <div class="guess-container">
            <div class="guess-container__menu-wrapper">
    
                <div class="guess-container__game-buttons">
                    <form action="" method="post">

                        <button name="boutonreset" class="guess-container__reset-button" value="reset">Reset</button>
                    </form>
                    <button class="guess-container__options-button">Options</button>
                </div>
            
                <div class="guess-container__game-options">
                    <p>Entre <?php echo "$borneInf et $borneSup" ?></p>
                    <p>Nombre de coups : <?php echo $nombreCoupsMax ?> </p>
                </div>
                
                
            </div>
            <h2>Le juste prix !! (sans Vincent Lagaf') </h2>
            <p class="guess-container__mystery-number"><?php echo (isset($userGuess) && ( ($userGuess == $nombreMagique) || ($nombreCoups > $nombreCoupsMax - 1) ) ) ? $nombreMagique : '<i class="fa-solid fa-gift"></i>' ?> </p>
            <p class="guess-container__game-statut-message"><?php echo $message?></p>
    
            <div class="guess-container__game-data">
                <form method="POST" action="">
                    <input type="text" placeholder="Entrez un nombre" name="guess" class="guess-container__input-number">
                    <button type="submit" class="guess-container__check-button">Entrer</button>
                </form>
                <?php
                if ($replay) {

                    echo '
                    <form method="POST" action="">
                        <input type="submit" name="replayornot" value="rejouer" class="guess-container__replay-button">
                    </form>
                    ';
                }
                ?>
                <div class="guess-container__score-display">
                    <p class="guess-container__win-score">Victoires: <?php echo $victoire?> </p>
                    <p class="guess-container__lose-score">Défaites: <?php echo $defaite?> </p>
    
    
                </div>
            </div>

            <div class="guess-container__sidebar-menu">
                <div class="guess-container__sidebar-max-tries-option">
                    <p>Nombre de coups autorisés : </p>
                    <form method="POST" action="">
                        <input type="text"  name="nombredecoupsmax" class="guess-container__set-max-tries">
                    </form>
                </div>
                <div class="guess-container__sidebar-limits-option">
                    <p>Definir la fourchette des nombres à deviner : </p>
                    <p>borne inf : </p>
                    <form method="POST" action="">
                        <input type="text"  name="borneinf" class="guess-container__set-limits-option">
                    </form>
                    <p>borne sup : </p>
                    <form method="POST" action="">
                        <input type="text"  name="bornesup" class="guess-container__set-limits-option">
                    </form>
                </div>

                <div class="guess-container__sidebar-reset-option">
                    <p>Remettre à zéro les scores de victoire et défaite ?</p>
                    <form method="POST" action="">
                        <input type="submit" name="deletescoreconfirm" value="yes" class="guess-container__reset-button">
                        <input type="submit" name="deletescoreconfirm" value="no" class="guess-container__reset-button">
                    </form>
                </div>
            </div>

            <!-- <?php

            if ($ecranFinDePartie) {
                echo 
                '
                <div class="guess-container__endscreen">
                    <h1>Voulez-vous rejouer ?</h1>
                    <form method="POST" action="">
                        <input type="submit" name="replayornot" value="yes" class="guess-container__replay-button">
                        <input type="submit" name="replayornot" value="no" class="guess-container__replay-button">
                    </form>
                </div>
                ';
            }
            ?> -->
        </div>
    </div>
</body>
</html>