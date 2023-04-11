<?php

// Je vérifie si le formulaire est soumis comme d'habitude
if($_SERVER['REQUEST_METHOD'] === "POST"){
    // Je sécurise et effectue mes tests
   $card = array_map('trim', $_POST);
    if(!isset($card["name"])){
        $errors[]="name require";
    }if(!isset($card["adress"])){
        $errors[]="address require";
    }

    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'uploads/';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $authorizedExtensions = ['jpg','jpeg','png'];
    // Le poids max géré par PHP par défaut est de 1M
    $maxFileSize = 1000000;

    $tablExtention = explode( '.',$uploadFile);
    $newName =  uniqid($tablExtention[0]). "." .$tablExtention[1];



    /****** Si l'extension est autorisée *************/
    if( (!in_array($extension, $authorizedExtensions))){
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if( file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize)
    {
    $errors[] = "Votre fichier doit faire moins de 2M !";
    }
$cheminFile = $_FILES['avatar']['tmp_name'] . $newName;

    move_uploaded_file($_FILES['avatar']['tmp_name'], $newName);

}  ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="src/asset/style.css">
</head>
<body>
<?php if(isset($errors)) :
foreach ( $errors as $error): ?>
<ul>
    <li><?= $error; ?></li>
</ul>

<?php endforeach;
endif; ?>
<form method="post" enctype="multipart/form-data">
    <label for="name">name : </label>
    <input type="text" name="name" id="name" />
    <label for="adress">adress : </label>
    <input type="text" name="adress" id="adress" />
    <label for="imageUpload">Upload an profile image</label>
    <input type="file" name="avatar" id="imageUpload" />
    <button name="send">Send</button>
</form>

<?php if($_SERVER['REQUEST_METHOD'] === "POST") : ?>
<section><div>
    <img src="<?= $newName ?>" alt="">
</div>
<div>
    <p><a href=" <?php
    if(file_exists ($newName)):
        unlink($cheminFile );
    endif; ?>">
     Delete the file : </a></p>
    <p> your name is <?= $card["name"] ?></p>
    <p> your adress is <?= $card["adress"] ?></p>
</div>
</section>
<?php endif; ?>
</body>
</html>