<?php

require('model/connectdb.php');

$profilebutton='yes';



// On vérifie si le compte existe pas déjà
if ($stmt = $con->prepare('SELECT idaccount, password FROM accounts WHERE email = ?')) {
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $stmt->store_result();
    // On enregistre le résultat pour vérifier si le compte existe pas déjà dans la DB
    if ($stmt->num_rows > 0) {
        // Un compte avec ce mail existe déjà
        $messagedisp = 'Un compte avec ce mail existe déjà, veuillez en saisir une autre.';
    } else {
        // Le compte n'exite pas déjà, on envoi l'invit, on va créer un token dans la table admintokens
        
        //delete previous tokens if there are some
        $stmtd = $con->prepare('DELETE FROM admintokens WHERE email=?');
        $stmtd->bind_param('s', $_POST['email']);
        $stmtd->execute();

        if ($stmt = $con->prepare('INSERT INTO admintokens (email, token) VALUES (?,?)')) {
            // On hash et verifiy le mot de passe pour pas le stocket en clair dans la DB
            $token = uniqid();
            
            $stmt->bind_param('ss', $_POST['email'], $token);
            $stmt->execute();
            
            //$from    = 'Infinite Measures <user13557950@us-imm-node4c.000webhost.io>';
            $subject = 'Création d\'un compte administrateur';
            //$headers = 'From: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $register_link = 'https://infinite-measures-quirky.000webhostapp.com/index.php?action=adminregister&email=' . $_POST['email'] . '&token=' . $token;
            //$message = '<p>Veuillez cliquer sur ce lien pour créer votre compte: <a href="' . $register_link . '">' . $register_link . '</a></p>';
            //mail($_POST['email'], $subject, $message, $headers);
            
            $long_url = $register_link;
            $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
            $genericAccessToken = '752c66f1eb381bbf4e2bf596513221424894937a';
            
            $data = array(
                'long_url' => $long_url
            );
            $payload = json_encode($data);
            
            $header = array(
                'Authorization: Bearer ' . $genericAccessToken,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            );
            
            $ch = curl_init($apiv4);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            $resultToJson = json_decode($result);
            
            if (isset($resultToJson->link)) {
                //echo $resultToJson->link;
                $customlink = $resultToJson->link;
            }
            else {
                $messagedisp = 'error';
            }
            
            $uni=uniqid();
            $filename='public/'.$uni.'temp.html';
            
            $myfile = fopen($filename, "w");
            $themessage = '<html><p>Veuillez cliquer sur ce lien pour créer votre compte: <a href="' . $customlink . '">' . $customlink . '</a></p></html>';
            fwrite($myfile, $themessage);
            fclose($myfile);
            
            require('mail/sendthemail.php');
            
            unlink($filename);
            
            $messagedisp = 'Invitation envoyée';

        } else {
            // Problème avec le SQl, il faut verifier si la table existe
            $messagedisp = 'Erreur';
        }
    }
    $stmt->close();
} else {
    // Problème avec le SQl, il faut verifier si la table existe
    $messagedisp = 'Erreur';
}
$con->close();