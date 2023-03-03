<?php
error_reporting(E_ERROR | E_PARSE);

//---------on s'assure que la session precendente est bien détruite--------------//

if(isset($_SESSION)) {
    session_unset();
    session_destroy();
    session_abort();
    session_register_shutdown();    
} else {
    //------initialisation du nombre de paire compris entre 2 et 12 (entier seulement)-----------//
    if (isset($_POST['start']) && (int) $_POST['start'] >= 2 && (int) $_POST['start'] <= 12) {
        session_start();
        $_SESSION['pairNmbr'] = (int) $_POST['start'];
        header('Location: jeu.php');    
    } 
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <div class="homepage">
        <div class="homepage-container">
            <!----verifie si le le nombre de paires est bien un entier---->
            <h1>Memory Game</h1>
            <?php if (isset($_POST['start']) && (gettype($_POST['start']) != 'integer' || $_POST['start'] > 12 || $_POST['start'] < 2)): ?>
            <p><i>Minimum 2 / maximum 12 </i></p>
            <?php endif;?>
            <h2>Selectionner un nombre de paire entre 2 et 12</h2>
<!----on récupère les valeur $_SESSION['pairNmbr] et on demarre la partie-->           
            <form action="" method="post">
                <input type="text" name="start" class="restart">
                <input type="submit" value="START GAME" class="restart">
            </form>
        </div>
    </div>
</body>
</html>
