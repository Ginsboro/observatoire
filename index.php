<?php
error_reporting(E_ALL);  // Affiche toutes les erreurs
ini_set('display_errors', 1);  // Affiche les erreurs à l'écran

// Inclure le fichier de configuration de la base de données
include('config.php');

// Initialiser les variables
$nom = $email = $titre = $description = $adresse = $date_observation = $photo = "";
$nom_err = $titre_err = $description_err = $adresse_err = $date_observation_err = "";
$message = "";  // Variable pour le message de succès ou d'erreur

// Vérification et traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validation des champs du formulaire
    if (empty($_POST["nom"])) {
        $nom_err = "Nom est requis";
    } else {
        $nom = htmlspecialchars($_POST["nom"]);
    }

    if (empty($_POST["titre"])) {
        $titre_err = "Le titre est requis";
    } else {
        $titre = htmlspecialchars($_POST["titre"]);
    }

    if (empty($_POST["description"])) {
        $description_err = "La description est requise";
    } else {
        $description = htmlspecialchars($_POST["description"]);
    }

    if (empty($_POST["adresse"])) {
        $adresse_err = "L'adresse est requise";
    } else {
        $adresse = htmlspecialchars($_POST["adresse"]);
    }

    if (empty($_POST["date_observation"])) {
        $date_observation_err = "La date de l'observation est requise";
    } else {
        $date_observation = $_POST["date_observation"];
    }

    // Traitement de l'upload de la photo (si présent)
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $photo = 'uploads/' . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    // Si tout est valide, insérer dans la base de données
    if (empty($nom_err) && empty($titre_err) && empty($description_err) && empty($adresse_err) && empty($date_observation_err)) {

        // Définir le statut par défaut
        $statut = 'non traité';

        // Préparer la requête d'insertion
        $sql = "INSERT INTO signalements (nom, email, titre, description, adresse, date_observation, photo, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Préparer la requête
        $stmt = $mysqli->prepare($sql);

        // Lier les paramètres
        $stmt->bind_param("ssssssss", $nom, $email, $titre, $description, $adresse, $date_observation, $photo, $statut);

        // Exécuter la requête
        if ($stmt->execute()) {
            $message = "Signalement soumis avec succès."; // Message de succès
        } else {
            $message = "Erreur lors de la soumission du signalement : " . $stmt->error; // Message d'erreur
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observatoire des Aménagements cyclables de Saint-Ouen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Soumettre une observation</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom ou Pseudo</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse Email (facultatif)</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse à Saint-Ouen</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>
            <div class="form-group">
                <label for="date_observation">Date de l'observation</label>
                <input type="date" id="date_observation" name="date_observation" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo (facultatif)</label>
                <input type="file" id="photo" name="photo">
            </div>
            <div class="form-group">
                <input type="submit" value="Envoyer">
            </div>
        </form>

        <!-- Affichage du message de succès ou d'erreur -->
        <?php if (isset($message)): ?>
            <div class="<?= $message_class ?>"><?= $message ?></div>
        <?php endif; ?>

    </div>
</body>
</html>

<?php if (!empty($message)) : ?>
    <div class="<?= (strpos($message, 'succès') !== false) ? 'success' : 'error' ?>">
        <?= $message ?>
    </div>
<?php endif; ?>
