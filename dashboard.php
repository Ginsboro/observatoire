<?php
include('config.php');

// Vérification de l'authentification
session_start();
// Suppression de la redirection vers login.php pour l'instant
//if (!isset($_SESSION['admin'])) {
//    header('Location: login.php');
//    exit();
//}

// Récupérer tous les signalements
$sql = "SELECT id, nom, email, titre, description, date_observation, adresse, photo, statut FROM signalements ORDER BY date_creation DESC";
$result = $mysqli->query($sql);

// Gestion de la modification du statut
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['statut']) && isset($_POST['id'])) {
        $statut = $_POST['statut'];
        $id = $_POST['id'];

        // Mettre à jour le statut
        $updateSql = "UPDATE signalements SET statut = ? WHERE id = ?";
        $stmt = $mysqli->prepare($updateSql);
        $stmt->bind_param('si', $statut, $id);
        $stmt->execute();
        $stmt->close();

        // Rediriger pour éviter la soumission multiple
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Signalements</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<body class="dashboard-page">
    <div class="container">
        <h1>Gestion des signalements</h1>

        <!-- Tableau des signalements -->
        <table class="table-dashboard">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Adresse</th>
                    <th>Photo</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['titre']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['date_observation'])); ?></td>
                        <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                        <td>
                            <?php if ($row['photo']) { ?>
                                <a href="<?php echo htmlspecialchars($row['photo']); ?>" target="_blank">Voir la photo</a>
                            <?php } else { ?>
                                Pas de photo
                            <?php } ?>
                        </td>
                        <td>
                            <form method="POST" action="dashboard.php">
                                <select name="statut">
                                    <option value="non traité" <?php echo $row['statut'] == 'non traité' ? 'selected' : ''; ?>>Non traité</option>
                                    <option value="confirmé" <?php echo $row['statut'] == 'confirmé' ? 'selected' : ''; ?>>Confirmé</option>
                                    <option value="infirmé" <?php echo $row['statut'] == 'infirmé' ? 'selected' : ''; ?>>Infirmé</option>
                                    <option value="publié" <?php echo $row['statut'] == 'publié' ? 'selected' : ''; ?>>Publié</option>
                                </select>
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
