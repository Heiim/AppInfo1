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
if (!isset($_POST['name'],$_POST['min'],$_POST['max'],$_POST['unit'],$_POST['description'],$_POST['idsensor'],$_POST['idtest'])) {
    $messagedisp ='Erreur: Veuillez remplir tous les champs.';
    $validation=false;
}

if ( empty($_POST['name']) || empty($_POST['max']) || empty($_POST['unit']) || empty($_POST['description']) || empty($_POST['idsensor']) || empty($_POST['idtest'])) {
    $messagedisp ='Erreur: Veuillez remplir tous les champs.';
    $validation=false;
}

if (strlen($_POST['name']) > 30 || strlen($_POST['name']) < 1) {
    $messagedisp ='Erreur: Le nom doit faire entre 1 et 30 caractères.';
    $validation=false;
}

if (strlen($_POST['description']) > 150 || strlen($_POST['description']) < 1) {
    $messagedisp ='Erreur: La description doit faire entre 1 et 250 caractères.';
    $validation=false;
}

//si pas d'erreur
if ($validation){
    // On vérifie si un test avec le même nom existe pas déjà
    if ($stmt = $con->prepare('SELECT idtest FROM tests WHERE (name = ? or description=?) and idtest != ?')) {
        $stmt->bind_param('ssi', $_POST['name'], $_POST['description'], $_POST['idtest']);
        $stmt->execute();
        $stmt->store_result();
        // On enregistre le résultat pour vérifier si le compte existe pas déjà dans la DB
        if ($stmt->num_rows > 0) {
            // Un compte avec ce mail existe déjà
            $messagedisp = 'Un test avec ce nom ou cette description existe déjà, veuillez en saisir d\'autres.';
            $stmt->close();
        } else {
            $stmt->close();
            $sql = "UPDATE tests SET idsensor=?, name=?, description=?, min=?, max=?, unit=? WHERE idtest=?";

            // Prepare statement
            $stmt2 = $con->prepare($sql);
            $stmt2->bind_param('issiisi',$_POST['idsensor'], $_POST['name'],$_POST['description'],$_POST['min'],$_POST['max'],$_POST['unit'],$_POST['idtest']);
            $stmt2->execute();
            $stmt2->close();

            $url='Location: gettest.php?idtest='.$_POST['idtest'];
            
            header($url);
            exit;
        }
    }
}

include 'infoedit.php';
exit;
?>