<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'quirky';

$validation=true;

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}


// On vérifie que tout est bien récupéré par le serveur
if (!isset($_POST['id'],$_POST['email'],$_POST['firstn'],$_POST['lastn'],$_POST['birthday'],$_POST['height'],$_POST['weight'])) {
    $messagedisp ='Erreur: Veuillez remplir tous les champs.';
    $validation=false;
}
// On vérifie que tout est rempli
if ( empty($_POST['email']) || empty($_POST['firstn']) || empty($_POST['lastn']) || empty($_POST['birthday']) || empty($_POST['height']) || empty($_POST['weight'])) {
    $messagedisp ='Erreur: Veuillez remplir tous les champs.';
    $validation=false;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $messagedisp ='Erreur: Email invalide.';
    $validation=false;
}


if (strlen($_POST['lastn']) > 30 || strlen($_POST['lastn']) < 1) {
    $messagedisp ='Erreur: Le nom doit faire entre 1 et 30 caractères.';
    $validation=false;
}

if (strlen($_POST['firstn']) > 30 || strlen($_POST['firstn']) < 1) {
    $messagedisp ='Erreur: Le prénom doit faire entre 1 et 30 caractères.';
    $validation=false;
}

//si pas d'erreur
if ($validation){
    //
    if ($stmt = $con->prepare('SELECT email FROM accounts WHERE idaccount = ?')) {
        
        $stmt->bind_param('i', $_POST['id']);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        if($email==$_POST['email']) {
            $sql = "UPDATE accounts SET email=?, firstn=?, lastn=? WHERE idaccount=?";

            // Prepare statement
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sssi', $_POST['email'],$_POST['firstn'],$_POST['lastn'], $_POST['id']);
            $stmt->execute();
            $stmt->close();


            $sql2 = "UPDATE users SET birthday=?, height=?, weight=? WHERE iduser=?";
            // Prepare statement
            $stmt2 = $con->prepare($sql2);
            $stmt2->bind_param('sssi', $_POST['birthday'],$_POST['height'],$_POST['weight'],$_POST['id']);
            $stmt2->execute();
            $stmt2->close();

            header('Location:../profile.php');
            exit;
        } else {
            //TODO: check if email is change we must check if an athoer account in the DB has it and if not update it with email confirmation
            $stmt = $con->prepare('SELECT idaccount, password FROM accounts WHERE email = ?');
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            // On enregistre le résultat pour vérifier si le compte existe pas déjà dans la DB
            if ($stmt->num_rows > 0) {
                // Un compte avec ce mail existe déjà
                $messagedisp = 'Un compte avec ce mail existe déjà, veuillez en saisir une autre.';
                $emailchanged=false;
            }else {
                $sql = "UPDATE accounts SET email=?, activation_code=?, firstn=?, lastn=? WHERE idaccount=?";

                // Prepare statement
                $stmt = $con->prepare($sql);
                $uniqid = uniqid();
                $stmt->bind_param('ssssi', $_POST['email'], $uniqid, $_POST['firstn'], $_POST['lastn'], $_POST['id']);
                $stmt->execute();
                $stmt->close();


                $sql2 = "UPDATE users SET birthday=?, height=?, weight=? WHERE iduser=?";

                // Prepare statement
                $stmt2 = $con->prepare($sql2);
                $stmt2->bind_param('sssi', $_POST['birthday'],$_POST['height'],$_POST['weight'], $_POST['id']);
                $stmt2->execute();
                $stmt2->close();

                $emailchanged=true;

                $from    = 'quirkylimited@gmail.com';
                $subject = 'Changement d\'addresse mail';
                $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                $activate_link = 'http://localhost/login/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
                $message = '<p>Veuillez cliquer sur ce lien pour confirmer le changement d\'email: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
                mail($_POST['email'], $subject, $message, $headers);
                $messagedisp = 'Consultez votre boite mail pour confirmer le changement d\'email.';

            }
        }
    }
}

include 'infoedit.php';
exit;
?>