<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'quirky';
// Connexion à la DB
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// Si erreur
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// On vérifie si toutes les infos nécessaire à la connexion sont saisis
if ( !isset($_POST['email'], $_POST['password']) ) {
	die ('Veuillez remplir tous les chmaps');
}

// on prépare le SQL pour éviter les injections SQL
if ($stmt = $con->prepare('SELECT idaccount,firstn,lastn, password FROM accounts WHERE email = ?')) {
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	// On vérifie si le compte est dans la DB
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id,$firstn,$lastn,$password);
        $stmt->fetch();
        // Le compte existe on vérifie le mot de passe
        if (password_verify($_POST['password'], $password)) {
            // Tout est okay on créé la sessions
            session_regenerate_id();

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $firstn . " " . $lastn;
            $_SESSION['id'] = $id;
            
            $stmt->close();
            
            $stmt2 = $con->prepare('SELECT * FROM admins WHERE idadmin = ?');
            $stmt2->bind_param('i', $_SESSION['id']);
            $stmt2->execute();
            $stmt2->store_result();

            if ($stmt2->num_rows > 0) {
                //si dans la table admin c'est un admin
                $_SESSION['status'] = "admin";
                header('Location: admin/profile.php');
            } else {
                //sinon user
                $_SESSION['status'] = "user";
                header('Location: profile.php');

            }
            //TODO gestionnaire aussi
            exit;
        } else {
            header('Location: index.php?error=Mot%20de%20passe%20incorrect.');
            $stmt->close();
        }
    } else {
        header('Location: index.php?error=Ce%20compte%20n\'existe%20pas.');
        $stmt->close();
    }
}
?>