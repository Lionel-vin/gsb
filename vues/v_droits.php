<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Droit à la portabilité</title>
</head>
<body>
    <a href="dossier/dossierDe_<?php echo $id; ?>.JSON" download='mesinfo.JSON'>Telecharger</a>
    <H1>HASH: sha256</H1>
    <p><?php
    $hash='telecharger votre fichier';
    if(isset($_SESSION['hash']))
        $hash=$_SESSION['hash'];
    echo $hash;
    ?></p>
</body>
</html>