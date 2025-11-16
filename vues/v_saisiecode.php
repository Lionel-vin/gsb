
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire simple</title>
</head>
<body>
    <form method="post" action="index.php?uc=connexion&action=verifierCode">
        <label for="monInput">Entrer votre code:</label>
        <input type="number" id="monInput" name="code" required>
        <button type="submit">Valider</button>
        <a href="index.php?uc=connexion&action=newCode" 
            style="display:inline-block; padding:10px 20px; background-color:#007BFF; color:white; text-decoration:none; border-radius:5px; font-family:sans-serif; font-size:16px;">
            Renvoyer le code
        </a>
        <br>
        <br>
        <p>
            <?php
                echo $_POST['codeMessage'];
            ?>
        </p>
    </form>
</body>
</html>