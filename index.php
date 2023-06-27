<?php
    //déclarer une variable pour chaque champs du formulaire
    $rue = null;
    $codePostal = null;
    $ville = null;
    $erreurs = []; //Contient les eventuelles message d'erreurs de validation

    //Est ce que le formulaire a été soumis
    // Regarder la methode HTTP utilisé
    // GET --> Affichage du formulaire
    // POST -->  Soumission du formula
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Le formulaire a été fournis
        // Tester si tout les champs obligatoires sont saisis

        // Tester la RUE
        if (empty(trim($_POST["rue"]))) {
            $erreurs["rue"] = "La rue est obligatoire";
        } else {
            $rue = trim($_POST["rue"]);
        }
        // Tester le Code postal
        if (empty(trim($_POST["code-postal"]))) {
            $erreurs["code-postal"] = "Le code postal est obligatoire";
        } else {
            $codePostal = trim($_POST["code-postal"]);
        }
        // Tester la Ville
        if (empty(trim($_POST["ville"]))) {
            $erreurs["ville"] = "La ville est obligatoire";
        } else {
            $ville = trim($_POST["ville"]);
        }
        // Tester si le fichier est present
        if (empty($_FILES["photo"]["name"])) {
            $erreurs["photo"] = "La photo est obligatoire";
        } else {
            // Récupere les infos liées a la photo
            $nomFichier = $_FILES["photo"]["name"];
            $typeFichier = $_FILES["photo"]["type"];
            $tmpFichier = $_FILES["photo"]["tmp_name"];
            $tailleFichier = $_FILES["photo"]["size"];
            $extensionFichier = pathinfo($nomFichier, PATHINFO_EXTENSION);

            // Test si le fichier est une image
            if (!str_contains($typeFichier, "image")) {
                $erreurs["photo"] = "Seules les images sont accepter";
            } else {
                //Tester la taille du fichier (limite a 500ko)
                if ($tailleFichier > 500 * 1024) {
                    $erreurs["photo"] = "L'image ne dois pas dépasser 500 Ko";
                } else {
                    // Générer un nom de fichier unique
                    $nomFichier = uniqid();
                    // Déplacer le fichier dans le dossier image
                    if (!move_uploaded_file($tmpFichier, "../image/$nomFichier.$extensionFichier")) {
                        $erreurs["photo"] = "Un probleme interne est survenu .";
                    }
                }
            }
        }


        // Tester si il n'y a pas d'erreur
        if (empty($erreurs)) {
            // Traitement des données saisies

            // Renvoyer une réponse http au
            //navigateur lui demandant de lancer
            // une vouvelle requete HTTP vers accueil.php
            header("Location: accueil.php");
        }
    }

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Formulaire</title>
    <script type="text/javascript">
        /* Voici la fonction javascript qui change la propriété "display"
        pour afficher ou non le div selon que ce soit "none" ou "block". */

        function AfficherMasquer()
        {
            divInfo = document.getElementById('divacacher');

            if (divInfo.style.display == 'none')
                divInfo.style.display = 'block';
            else
                divInfo.style.display = 'none';

        }
    </script>
</head>
<body>
    <p>Hhjehnge</p>
    <h1>sglsgklmesgjk</h1>
    <div id="divacacher" style="display:none;">
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="rue">Rue*</label>
                <input type="text" id="rue" name="rue" value="<?= $rue ?>">
                <?php
                    // Tester si il y a une erreur pour le champ RUE
                 if (isset($erreurs["rue"])) { ?>
                     <p class="erreur-validation"><?= $erreurs["rue"] ?></p>
                     <?php } ?>


                <label for="code-postal">Code postal*</label>
                <input type="text" id="code-postal" name="code-postal" value="<?= $codePostal ?>">
                <?php
                // Tester si il y a une erreur pour le champ code-postal
                if (isset($erreurs["code-postal"])) { ?>
                    <p class="erreur-validation"><?= $erreurs["code-postal"] ?></p>
                <?php } ?>

                <label for="ville">Ville*</label>
                <input type="text" id="ville" name="ville" value="<?= $ville ?>">
                <?php
                // Tester si il y a une erreur pour le champ ville
                if (isset($erreurs["ville"])) { ?>
                    <p class="erreur-validation"><?= $erreurs["ville"] ?></p>
                <?php } ?>

                <label for="photo">Photo*</label>
                <input type="file" name="photo" id="photo" accept="image/png,image/jpg,image/jpeg">
                <?php
                // Tester si il y a une erreur pour le champ ville
                if (isset($erreurs["photo"])) { ?>
                    <p class="erreur-validation"><?= $erreurs["photo"] ?></p>
                <?php } ?>

                <p>* : Champs obligatoires</p>

                <input type="submit" value="Envoyer">
            </form>
        </div>
    </div>
    <input type="button" value="Afficher ou Masquer" onClick="AfficherMasquer()" />
</body>
</html>