<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" href="public/style/style.css">
    <link rel="icon" type="image/png" href="public/images/infinitelogo.png" />
</head>

<?php
    session_start();
?>

<header>
    <div>
        <div class="logo">
            <a href="index.php"><img src="public/images/infinitelogo.png" width=100% height=100%>
        </div>
        <p class="name">Infinite Measures</p>
        </a>
    </div>
    <div class="connection">
        <ul>
            <li class="button"><a class="whitelink" href='<?php
                if($_SESSION['status']=="user"){
                    echo 'index.php?action=userprofile';
                }else if ($_SESSION['status']=="admin"){
                    echo 'index.php?action=adminprofile';
                }
            ?>'>Mon compte</a></li>
            <li class="button"><a class="whitelink" href="index.php?action=chat">Messagerie</a></li>
        </ul>
    </div>
</header>

<body>
    <div class="wrapper">
        <div>
            <div class="barre">
            <h2>Recherche Gestionnaire</h2>
                <form action="index.php?action=searchmanager" method="POST">
                
                    <input type="search" name="prenom" placeholder="Prénom" />
                    <input type="search" name="nom" placeholder="Nom" />
                    <input type="search" name="compagnie" placeholder="Compagnie" />
                    
                    <input type="submit" value="Chercher" />
                    <input type="reset" value="Supprimer" />
                </form>

                <div>
                <?php if(isset($thesearchwasdone)){
                        while($stmt->fetch()) {?>
                            <div class="barre">
                                <ul class="usercard">
                                    <li class="usercard_title"><?=$firstn?> <?=$lastn?></li>
                                    <li class="usercard_data">Email : <?=$email?></li>
                                    <li class="usercard_data">Compagnie : <?=$company_code?></li>
                                </ul>
                            </a>
                            </div>
                                
                        <?php }
                    }
                    ?>
                    </div>
                <div class="push"></div>

                
            </div>
        </div>
    </div>
</body>
