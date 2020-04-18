<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" href="public/style/style.css">
    <link rel="icon" type="image/png" href="public/images/infinitelogo.png" />
</head>

<header>
    <div>
        <div class="logo">
            <a href="index.php"><img src="public/images/infinitelogo.png" width=100%x height=100%>
        </div>
        <p class="name">Infinite Measures</p>
        </a>
    </div>
    <div class="connection">
        <ul>
            <li class="button"><a class="whitelink" href="index.php?action=chat">Messagerie</a></li>
            <li class="button"><a class="whitelink" href="index.php?action=logout">Déconnexion</a></li>
        </ul>
    </div>
</header>

<body>
    <div class="wrapper">
        <div>
            <div class="barre">
            <h2>Recherche Gestionnaire</h2>
                <form action="index.php?action=searchmanager" method="GET">
                
                    <input type="search" name="prenom" placeholder="Prénom" />
                    <input type="search" name="nom" placeholder="Nom" />
                    <input type="search" name="compagnie" placeholder="Compagnie" />
                    
                    <input type="submit" value="Chercher" />
                    <input type="reset" value="Supprimer" />
                </form>

                <div>

                    <?php include 'model/searchmanager.php';?>
                </div>
                <div class="push"></div>

                
            </div>
        </div>
    </div>
</body>
