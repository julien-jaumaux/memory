<?php

function showAllProfiles()
{
    $mysqli = new mysqli('localhost', 'root', '', 'memory');
    $query = "SELECT DISTINCT name FROM utilisateurs";
    $result = $mysqli->query($query);
    $result = $result->fetch_all();
    return $result;
}

function showProfileRecords($req) {
    $mysqli = new mysqli('localhost', 'root', '', 'memory');
    $query = "SELECT * FROM utilisateurs ORDER BY score DESC";
    $result = $mysqli->query($query);
    $result = $result->fetch_all();
    return $result;
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
    <div class="profiles">
        <div class="profiles-container">
            <div class="profiles-container__btns-block">
                <ul>
                    <?php foreach(showAllProfiles() as $key => $val): ?>
                    <li>
                        <form action="" method="post">
                            <input type="submit" name="user" value="<?=$val[0]?>">
                        </form>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="profiles-container__right-block">
                <div class="profiles-container__content-block">
                        <div class="content-block__container">
                            <h2>Classement général <u><?php $_SESSION['user'] = ['name'];?></u>:</h2>
                            <?php foreach (showProfileRecords($_SESSION['user']) as $val): ?>                                
                                <p><?=$val[1]?> score</p>
                                <p class="date"><i><?=$val[2]?></i></p>
                            <?php endforeach; ?>
                        </div>
                </div>
                <div class="profiles-container__backwards-block">
                    <form action="./index.php" method="post">
                        <input type="submit" value="Revenir au menu principal">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>