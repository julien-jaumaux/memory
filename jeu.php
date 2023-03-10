<?php
error_reporting(E_ERROR | E_PARSE);
//---appelle de la class card et des fonction qui peuvebt lui être appliquées----//
include('Card.php');
session_start();
//-------attribue un score à la session en fonction du nombre de clic----//
if(!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
} else {
    $_SESSION['count']++; 
}

// mélange et redistribue de façon aléatoire le nombre de carte définie par $_SESSION['pairNmbr]//
if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];

    for ($i = 0; $i < $_SESSION['pairNmbr']*2; $i++) {
        array_push($_SESSION['order'], $i);
    }
    
    shuffle($_SESSION['order']);    
}
//----ici on récupère le nom de l'utilisateur en fin de partie et son score pour le placer en base de
//données avec la méthode post------//
if (isset($_POST['adduser'])) {
    adduser($_POST['username'], $_COOKIE['score']);
    showTopScorers();    
}

$cards = [];
// on fait appel à la classe "card" pour appliquer la méthode de cette class : assigner une carte et
//son double ($i + $f), appliquer l'image qui lui correspond ainsi que le dos de la carte sur chacune d'elles (new Card), l'id de chaques cartes correspond à sa place sur le tableau.
for ($i = 0; $i < $_SESSION['pairNmbr']*2; $i++) {
    $f = $i + 1;
    $cards[$i] = new Card($i, './img/img'.$i.'.jpg', './img/back.jpg');
    $cards[$f] = new Card($f, './img/img'.$i.'.jpg', './img/back.jpg');
    $i++;     
}

function verify($key, $pairsNumber)
{
    //--(ici on compare la correspondance des [clé] qui une valeur de chaine de caractère qui valent 5 (img0->img8) )
    if (strlen($key) == 5) {
        $charToVerify = (int) $key[-1];
        
        if ($charToVerify % 2 != 0) {
            $lastChar = (int) $key[-1] + 1;
            $val = substr($key, 0, -1);
            $cellUp = $val . $lastChar;
            //---condition de clic (post) sur les carte choisies et maintien de la position retournée---//
            if (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                (!isset($_SESSION['verify']) || $_SESSION['verify'] != 'stanby')
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = 'stanby';

            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] == 'on'
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = '';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                ((isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] != 'on') || !isset($_SESSION[$cellUp]))
            ) {
// applique le verify sur les cartes cliquées choisies//
                for ($i = 1; $i <= $pairsNumber * 2; $i++) {
                    $_SESSION['cell' . $i] = '';
                }

                $_SESSION['verify'] = '';
            }
// condition de correspondance entre les deux cartes: elles restent en vue---//
            if (
                isset($_SESSION[$key]) && $_SESSION[$key] == 'on' &&
                isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] == 'on'
            ) {
                $_SESSION['corr' . $charToVerify . $lastChar] = 'yes';
            }
//si condition non vérifiées les cartes sont replacées face retournée----///
        } else {
            
            $lastChar = (int) $key[-1] - 1;
            $val = substr($key, 0, -1);
            $cellDown = $val . $lastChar;
//vérification de correspondanve des cartes retournées avec celles choisies dans le clic précedent//
            if (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                (!isset($_SESSION['verify']) || $_SESSION['verify'] != 'stanby')
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = 'stanby';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] == 'on'
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = '';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                ((isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] != 'on') || !isset($_SESSION[$cellDown]))
            ) {

                for ($i = 1; $i <= $pairsNumber * 2; $i++) {
                    $_SESSION['cell' . $i] = '';
                }

                $_SESSION['verify'] = '';
            }

            if (
                isset($_SESSION[$key]) && $_SESSION[$key] == 'on' &&
                isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] == 'on'
            ) {
                $_SESSION['corr' . $lastChar . $charToVerify] = 'yes';
            }
        }
        //--- même comportement que si dessus mais avec des [clé] de 6 caractères (img de 10->22)
    } elseif (strlen($key) == 6) {
        $charToVerify = (int) $key[-2]. (int) $key[-1];
        if ($charToVerify % 2 != 0) { 
            $lastChar = $charToVerify + 1;
            $val = substr($key, 0, -2);
            $cellUp = $val . $lastChar;

            if (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                (!isset($_SESSION['verify']) || $_SESSION['verify'] != 'stanby')
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = 'stanby';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] == 'on'
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = '';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                ((isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] != 'on') || !isset($_SESSION[$cellUp]))
            ) {

                for ($i = 1; $i <= $pairsNumber * 2; $i++) {
                    $_SESSION['cell' . $i] = '';
                }

                $_SESSION['verify'] = '';
            }

            if (
                isset($_SESSION[$key]) && $_SESSION[$key] == 'on' &&
                isset($_SESSION[$cellUp]) && $_SESSION[$cellUp] == 'on'
            ) {
                $_SESSION['corr' . $charToVerify . $lastChar] = 'yes';
            }

        } else {
            $lastChar = $charToVerify - 1;
            $val = substr($key, 0, -2);
            $cellDown = $val . $lastChar;

            if (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                (!isset($_SESSION['verify']) || $_SESSION['verify'] != 'stanby')
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = 'stanby';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] == 'on'
            ) {
                $_SESSION[$key] = 'on';
                $_SESSION['verify'] = '';
            } elseif (
                isset($_POST[$key]) && $_POST[$key] == $key &&
                isset($_SESSION['verify']) && $_SESSION['verify'] == 'stanby' &&
                ((isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] != 'on') || !isset($_SESSION[$cellDown]))
            ) {

                for ($i = 1; $i <= $pairsNumber * 2; $i++) {
                    $_SESSION['cell' . $i] = '';
                }

                $_SESSION['verify'] = '';
            }

            if (
                isset($_SESSION[$key]) && $_SESSION[$key] == 'on' &&
                isset($_SESSION[$cellDown]) && $_SESSION[$cellDown] == 'on'
            ) {
                $_SESSION['corr' . $lastChar . $charToVerify] = 'yes';
            }
        }
    }
}

function isEnd() {
    $corrNumber = 0;
// la fonction verifie si le nombre de correspondance est égal au nombre de paire choisies au départ. Si la condition est remplie la partieest terminée
    for ($i = 1; $i <= $_SESSION['pairNmbr']*2; $i = $i + 2) {
        $f = $i + 1;
        $verifVar = 'corr' . $i . $f;
        if ($_SESSION[$verifVar] == 'yes') {
            $corrNumber++;
        }        
    }
    
    if ($corrNumber == $_SESSION['pairNmbr']) {
        $_SESSION['end'] = 'yes';
        setcookie('score', round($_SESSION['pairNmbr'] / ($_SESSION['count'] != 0 ? $_SESSION['count'] : 1)  * 100));
    }
}
//fonction qui permet d'ajouter le score des joueurs dans la base de données
function adduser($username, $final) {    
    $mysqli = new mysqli('localhost', 'root', '', 'memory');
    $query = "INSERT INTO utilisateurs (id, name, score) VALUES (null, '$username', '$final')";
    $mysqli->query($query);    
}
//fonction qui trie le classement par ordre de score décroissant
function showTopScorers() {
    $mysqli = new mysqli('localhost', 'root', '', 'memory');
    $query = "SELECT name, score FROM utilisateurs ORDER BY score DESC LIMIT 10";
    $result = $mysqli->query($query);
    $result = $result->fetch_all();
    return $result;
}


foreach ($_POST as $key => $val) {
    verify($val, $_SESSION['pairNmbr']); 
}

isEnd();

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
<!--fin de partie, on affiche le classement, l'input qui permet de s'enregister en base de données et on peut lancer une nouvelle partie-->
<body>       
    <?php if ($_SESSION['end'] == 'yes'):
        $finalCount = (int) round($_SESSION['pairNmbr'] / ($_SESSION['count'] != 0 ? $_SESSION['count'] : 1)  * 100); 
        session_unset();
        session_destroy();
        session_abort();
        session_register_shutdown(); ?>
        <div class="end-game__container">
            <div class="end-ann_container">
                <h1>Félicitations!!!</h1>
                <?php if ($finalCount != 0): ?>
                <h3>Votre score est de : <?=$finalCount?></h3>
                <div class="end-ann_container__add-block">
                    <form action="" method="post">
                        <h4>entrez votre nom: </h4>
                        <input type="text" name="username">
                        <input type="submit" class="restart" name="adduser" value="Poster">
                    </form>
                </div>                
                <?php endif;?>                 
                <div class="end-ann_container__btn-block">
                    <form action="index.php" method="post">
                        <input type="submit" class="restart" name="reset" value="Nouvelle partie">
                    </form>
                </div>
                <div class="topscorers">
                    <h4>Classement:</h4>
                    <ul>
                        <?php foreach (showTopScorers() as $val): ?>
                            <li>                                
                                <span><?=$val[0]?></span><span><?=$val[1]?></span>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="end-ann_container profiles">
                <form action="./profiles.php">
                    <input type="submit" class="restart" value="Voir le classement">
                </form>
            </div>
        </div>
</body>
    <?php else: ?>
    <div class="game-container">                
        <div class="game">            
            <?php foreach ($cards as $key=>$val): ?>
<!-- ci dessous on génère l'affichage du tableau de jeu en utilisant les méthodes apliquer à la classe card-->
            <div class="game-cell" style="order: <?=$_SESSION['order'][$key]?>;">
                <form action="" method="post" style="<?= isset($_SESSION[$val->generateNum()]) && $_SESSION[$val->generateNum()] == 'yes' ? 'display: none' : ''?>">
                    <input type="submit" name=<?=$val->cellNumber()?> value=<?=$val->cellNumber()?>>
                </form>

                <?php if(!isset($_SESSION[$val->cellNumber()]) || ( $_SESSION[$val->cellNumber()] == '' && $_SESSION[$val->generateNum()] != 'yes')):?>
                <div class="game-cell__img1">
                    <img src=<?=$val->revSideImg?> alt="">
                </div>

                <?php elseif((isset($_SESSION[$val->generateNum()]) && $_SESSION[$val->generateNum()] == 'yes') || $_SESSION[$val->cellNumber()] == 'on'): ?>
                <div class="game-cell__img2">
                    <img src=<?=$val->img?> alt="">
                </div>
                
                <?php endif; ?> 
            </div>

            <?php endforeach;?>
        </div>        
    </div>
    <?php endif; ?>
</body>
</html>